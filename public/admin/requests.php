<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Requests';

$contactModel = new \App\Models\ContactMessage();
$messages = $contactModel->getAll(200);
$unreadCount = $contactModel->getUnreadCount();

$notice = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $token = $_POST[CSRF_TOKEN_NAME] ?? null;
    if (!\App\Core\CSRF::verify(is_string($token) ? $token : null)) {
        $notice = ['type' => 'error', 'text' => 'Security validation failed. Please refresh and retry.'];
    } else {
        $action = (string)($_POST['action'] ?? '');
        $messageId = (int)($_POST['message_id'] ?? 0);

        if ($action === 'mark_read' && $messageId > 0) {
            if ($contactModel->markRead($messageId)) {
                $notice = ['type' => 'success', 'text' => 'Request marked as read.'];
                log_audit_action('contact.mark_read', 'contact_message', $messageId);
            } else {
                $notice = ['type' => 'error', 'text' => 'Could not update request status.'];
            }
        }
    }

    $messages = $contactModel->getAll(200);
    $unreadCount = $contactModel->getUnreadCount();
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>
<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">User Requests</h2>
        </div>

        <?php if ($notice): ?>
            <div class="admin-alert admin-alert-<?php echo safe_html($notice['type']); ?>" data-auto-dismiss="3500">
                <?php echo safe_html($notice['text']); ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">✉️</div>
                <div class="stat-label">Total Requests</div>
                <div class="stat-value"><?php echo count($messages); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🟡</div>
                <div class="stat-label">Unread</div>
                <div class="stat-value"><?php echo (int)$unreadCount; ?></div>
            </div>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search requests..." data-table-search="requests-table">
            <select data-status-filter="requests-table">
                <option value="">All</option>
                <option value="unread">Unread</option>
                <option value="read">Read</option>
            </select>
        </div>

        <div class="admin-card">
            <div style="overflow-x:auto;">
                <table class="admin-table" id="requests-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Name</th>
                            <th>Contact</th>
                            <th>Subject</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messages)): ?>
                            <tr><td colspan="7" class="admin-empty"><div class="empty-icon">📩</div><div class="empty-text">No user requests found</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($messages as $entry): ?>
                                <?php $isRead = (int)($entry['is_read'] ?? 0) === 1; ?>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo safe_html(format_eat((string)$entry['created_at'], 'd M Y H:i')); ?></td>
                                    <td><strong><?php echo safe_html((string)$entry['full_name']); ?></strong></td>
                                    <td>
                                        <div style="display:grid;gap:0.1rem;">
                                            <a href="mailto:<?php echo safe_html((string)$entry['email']); ?>"><?php echo safe_html((string)$entry['email']); ?></a>
                                            <?php if (!empty($entry['phone'])): ?>
                                                <a href="tel:<?php echo safe_html((string)$entry['phone']); ?>"><?php echo safe_html((string)$entry['phone']); ?></a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo safe_html((string)($entry['subject'] ?? 'General')); ?></td>
                                    <td style="max-width:320px;white-space:normal;"><?php echo safe_html((string)$entry['message']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $isRead ? 'badge-active' : 'badge-pending'; ?>">
                                            <?php echo $isRead ? 'Read' : 'Unread'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if (!$isRead): ?>
                                            <form method="post">
                                                <?php echo \App\Core\CSRF::field(); ?>
                                                <input type="hidden" name="action" value="mark_read">
                                                <input type="hidden" name="message_id" value="<?php echo (int)$entry['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline">Mark Read</button>
                                            </form>
                                        <?php else: ?>
                                            <span style="color:#9CA3AF;font-size:0.8rem;">—</span>
                                        <?php endif; ?>
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
