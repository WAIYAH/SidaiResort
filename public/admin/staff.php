<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

// Only super_admin and manager can manage staff
$auth = new \App\Core\Auth();
if (!$auth->hasRole(['super_admin', 'manager'])) {
    http_response_code(403);
    echo 'Access denied. Insufficient permissions.';
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Staff';

$staffModel = new \App\Models\Staff();
$staffMembers = $staffModel->getAll();

$roleLabels = [
    'super_admin' => 'Super Admin',
    'manager' => 'Manager',
    'receptionist' => 'Receptionist',
    'finance' => 'Finance',
    'kitchen' => 'Kitchen',
    'housekeeping' => 'Housekeeping',
];

// Handle actions
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST[CSRF_TOKEN_NAME] ?? null;
    if (!\App\Core\CSRF::verify(is_string($token) ? $token : null)) {
        $message = ['type' => 'error', 'text' => 'Security validation failed. Please refresh and retry.'];
    } else {
        $action = $_POST['action'] ?? '';

        if ($action === 'create') {
            $data = [
                'full_name' => trim($_POST['full_name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'role' => $_POST['role'] ?? 'receptionist',
                'password' => $_POST['password'] ?? '',
            ];

            if ($data['full_name'] && $data['email'] && $data['password']) {
                $id = $staffModel->create($data);
                if ($id) {
                    $message = ['type' => 'success', 'text' => 'Staff member created successfully.'];
                    log_audit_action('staff.create', 'staff', $id);
                    $staffMembers = $staffModel->getAll();
                } else {
                    $message = ['type' => 'error', 'text' => 'Failed to create staff member. Email may already exist.'];
                }
            } else {
                $message = ['type' => 'error', 'text' => 'Name, email, and password are required.'];
            }
        }

        if ($action === 'deactivate') {
            $id = (int)($_POST['staff_id'] ?? 0);
            if ($id > 0 && $id !== $staff['id']) {
                $staffModel->deactivate($id);
                $message = ['type' => 'success', 'text' => 'Staff member deactivated.'];
                log_audit_action('staff.deactivate', 'staff', $id);
                $staffMembers = $staffModel->getAll();
            }
        }
    }
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Staff Management</h2>
            <button class="btn btn-gold" data-modal-open="add-staff-modal">+ Add Staff</button>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo $message['type']; ?>" data-auto-dismiss="4000">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Staff Members</h3>
                <span style="font-size:0.8rem;color:#6B7280;"><?php echo count($staffMembers); ?> total</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table" id="staff-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($staffMembers as $member): ?>
                            <tr>
                                <td><strong><?php echo safe_html($member['full_name']); ?></strong></td>
                                <td><?php echo safe_html($member['email']); ?></td>
                                <td><span class="badge badge-active"><?php echo $roleLabels[$member['role']] ?? ucfirst($member['role']); ?></span></td>
                                <td>
                                    <span class="badge <?php echo (int)$member['is_active'] === 1 ? 'badge-active' : 'badge-inactive'; ?>">
                                        <?php echo (int)$member['is_active'] === 1 ? 'Active' : 'Inactive'; ?>
                                    </span>
                                </td>
                                <td><?php echo $member['last_login'] ? format_eat($member['last_login']) : 'Never'; ?></td>
                                <td>
                                    <?php if ((int)$member['id'] !== $staff['id']): ?>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Deactivate this staff member?');">
                                            <?php echo \App\Core\CSRF::field(); ?>
                                            <input type="hidden" name="action" value="deactivate">
                                            <input type="hidden" name="staff_id" value="<?php echo (int)$member['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                                        </form>
                                    <?php else: ?>
                                        <span style="color:#9CA3AF;font-size:0.8rem;">You</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Add Staff Modal -->
<div class="admin-modal-backdrop" id="add-staff-modal">
    <div class="admin-modal">
        <div class="admin-modal-header">
            <h3 class="admin-modal-title">Add Staff Member</h3>
            <button class="admin-modal-close" data-modal-close>&times;</button>
        </div>
        <form method="post" class="admin-form">
            <?php echo \App\Core\CSRF::field(); ?>
            <input type="hidden" name="action" value="create">
            <div class="admin-modal-body">
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" id="phone" name="phone">
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select id="role" name="role" required>
                            <?php foreach ($roleLabels as $value => $label): ?>
                                <option value="<?php echo $value; ?>"><?php echo $label; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required minlength="8">
                    </div>
                </div>
            </div>
            <div class="admin-modal-footer">
                <button type="button" class="btn btn-outline" data-modal-close>Cancel</button>
                <button type="submit" class="btn btn-gold">Create Staff</button>
            </div>
        </form>
    </div>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
