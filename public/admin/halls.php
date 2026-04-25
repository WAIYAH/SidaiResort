<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Halls';

$hallModel = new \App\Models\Hall();
$halls = $hallModel->getAll();

// Handle form submissions
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];
    $hallId = (int)($_POST['hall_id'] ?? 0);

    if ($action === 'toggle_availability' && $hallId > 0) {
        $hall = $hallModel->getById($hallId);
        if ($hall) {
            $newStatus = (int)$hall['is_available'] === 1 ? 0 : 1;
            $hallModel->updateAvailability($hallId, $newStatus);
            $message = ['type' => 'success', 'text' => 'Hall availability updated.'];
            $halls = $hallModel->getAll(); // re-fetch
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
            <h2 class="admin-page-title">Hall Management</h2>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo $message['type']; ?>" data-auto-dismiss="4000">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">🏛️</div>
                <div class="stat-label">Total Halls</div>
                <div class="stat-value"><?php echo count($halls); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-label">Available</div>
                <div class="stat-value"><?php echo count(array_filter($halls, fn($h) => (int)($h['is_available'] ?? 0) === 1)); ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Halls</h3>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table" id="halls-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Capacity</th>
                            <th>Full Day</th>
                            <th>Half Day</th>
                            <th>Evening</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($halls)): ?>
                            <tr><td colspan="8" class="admin-empty"><div class="empty-icon">🏛️</div><div class="empty-text">No halls configured</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($halls as $hall): ?>
                                <tr>
                                    <td><?php echo (int)$hall['hall_number']; ?></td>
                                    <td><strong><?php echo safe_html($hall['name']); ?></strong></td>
                                    <td><?php echo (int)$hall['capacity']; ?> guests</td>
                                    <td><?php echo format_kes($hall['price_full_day']); ?></td>
                                    <td><?php echo $hall['price_half_day'] ? format_kes($hall['price_half_day']) : '—'; ?></td>
                                    <td><?php echo $hall['price_evening'] ? format_kes($hall['price_evening']) : '—'; ?></td>
                                    <td>
                                        <span class="badge <?php echo (int)$hall['is_available'] === 1 ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo (int)$hall['is_available'] === 1 ? 'Available' : 'Unavailable'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="action" value="toggle_availability">
                                            <input type="hidden" name="hall_id" value="<?php echo (int)$hall['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline">
                                                <?php echo (int)$hall['is_available'] === 1 ? 'Disable' : 'Enable'; ?>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
