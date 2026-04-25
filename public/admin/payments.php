<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Payments';

$db = \App\Core\Database::getInstance();
$payments = $db->queryAll(
    'SELECT p.*, b.booking_ref
     FROM payments p
     LEFT JOIN bookings b ON p.booking_id = b.id
     ORDER BY p.created_at DESC
     LIMIT 100'
);

$totalCollected = $db->queryOne("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'completed'")['total'] ?? 0;
$pendingPayments = $db->queryOne("SELECT COUNT(*) as count FROM payments WHERE status = 'pending'")['count'] ?? 0;
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Payment Records</h2>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Total Collected</div>
                <div class="stat-value"><?php echo format_kes($totalCollected); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">Pending</div>
                <div class="stat-value"><?php echo (int)$pendingPayments; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🧾</div>
                <div class="stat-label">Total Transactions</div>
                <div class="stat-value"><?php echo count($payments); ?></div>
            </div>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search payments..." data-table-search="payments-table">
            <select data-status-filter="payments-table">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>

        <div class="admin-card">
            <div style="overflow-x:auto;">
                <table class="admin-table" id="payments-table">
                    <thead>
                        <tr>
                            <th>Payment Ref</th>
                            <th>Booking</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($payments)): ?>
                            <tr><td colspan="6" class="admin-empty"><div class="empty-icon">💳</div><div class="empty-text">No payments recorded</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($payments as $payment): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($payment['payment_ref']); ?></strong></td>
                                    <td><?php echo safe_html($payment['booking_ref'] ?? '—'); ?></td>
                                    <td><?php echo format_kes($payment['amount']); ?></td>
                                    <td><?php echo ucfirst($payment['method']); ?></td>
                                    <td><span class="badge badge-<?php echo safe_html($payment['status']); ?>"><?php echo ucfirst($payment['status']); ?></span></td>
                                    <td><?php echo format_eat($payment['created_at']); ?></td>
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
