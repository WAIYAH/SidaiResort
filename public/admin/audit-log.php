<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

// Only super_admin and manager can view audit logs
$auth = new \App\Core\Auth();
if (!$auth->hasRole(['super_admin', 'manager'])) {
    http_response_code(403);
    echo 'Access denied. Insufficient permissions.';
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Audit Log';

$auditModel = new \App\Models\AuditLog();
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 50;
$offset = ($page - 1) * $perPage;

$logs = $auditModel->getAll($perPage, $offset);
$totalCount = $auditModel->getTotalCount();
$totalPages = max(1, (int)ceil($totalCount / $perPage));
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Audit Log</h2>
            <span style="font-size:0.85rem;color:#6B7280;"><?php echo number_format($totalCount); ?> entries</span>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search actions..." data-table-search="audit-table">
        </div>

        <div class="admin-card">
            <div style="overflow-x:auto;">
                <table class="admin-table" id="audit-table">
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Staff</th>
                            <th>Action</th>
                            <th>Entity</th>
                            <th>Entity ID</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)): ?>
                            <tr><td colspan="6" class="admin-empty"><div class="empty-icon">📋</div><div class="empty-text">No audit entries found</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td style="white-space:nowrap;"><?php echo format_eat($log['created_at'], 'd M Y H:i:s'); ?></td>
                                    <td><?php echo safe_html($log['staff_name'] ?? 'System'); ?></td>
                                    <td>
                                        <span class="badge badge-<?php
                                            echo match(true) {
                                                str_contains($log['action'], 'login') => 'active',
                                                str_contains($log['action'], 'create') => 'confirmed',
                                                str_contains($log['action'], 'update') => 'pending',
                                                str_contains($log['action'], 'delete') || str_contains($log['action'], 'deactivate') => 'cancelled',
                                                default => 'inactive',
                                            };
                                        ?>">
                                            <?php echo safe_html($log['action']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo safe_html($log['entity_type']); ?></td>
                                    <td><?php echo $log['entity_id'] ? (int)$log['entity_id'] : '—'; ?></td>
                                    <td style="font-family:monospace;font-size:0.8rem;"><?php echo safe_html($log['ip_address'] ?? '—'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="admin-pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?php echo $page - 1; ?>">← Prev</a>
                    <?php endif; ?>

                    <?php for ($i = max(1, $page - 3); $i <= min($totalPages, $page + 3); $i++): ?>
                        <?php if ($i === $page): ?>
                            <span class="active"><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page + 1; ?>">Next →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
