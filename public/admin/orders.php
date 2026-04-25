<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Orders';

$orderModel = new \App\Models\Order();
$orders = $orderModel->getAll(100);

// Handle status update
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'update_status') {
        $orderId = (int)($_POST['order_id'] ?? 0);
        $newStatus = $_POST['new_status'] ?? '';

        $validStatuses = ['pending', 'preparing', 'ready', 'delivered', 'cancelled'];
        if ($orderId > 0 && in_array($newStatus, $validStatuses, true)) {
            $orderModel->updateStatus($orderId, $newStatus);
            $message = ['type' => 'success', 'text' => 'Order status updated to ' . ucfirst($newStatus) . '.'];
            $orders = $orderModel->getAll(100); // re-fetch
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
            <h2 class="admin-page-title">Order Management</h2>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo $message['type']; ?>" data-auto-dismiss="4000">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">🧾</div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-value"><?php echo count($orders); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">Pending</div>
                <div class="stat-value"><?php echo count(array_filter($orders, fn($o) => $o['status'] === 'pending')); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👨‍🍳</div>
                <div class="stat-label">Preparing</div>
                <div class="stat-value"><?php echo count(array_filter($orders, fn($o) => $o['status'] === 'preparing')); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
                <div class="stat-label">Delivered Today</div>
                <div class="stat-value"><?php echo count(array_filter($orders, fn($o) => $o['status'] === 'delivered' && ($o['delivered_at'] ?? '') >= date('Y-m-d'))); ?></div>
            </div>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search orders..." data-table-search="orders-table">
            <select data-status-filter="orders-table">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="preparing">Preparing</option>
                <option value="ready">Ready</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Orders</h3>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table" id="orders-table">
                    <thead>
                        <tr>
                            <th>Order Ref</th>
                            <th>Delivery</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr><td colspan="7" class="admin-empty"><div class="empty-icon">🧾</div><div class="empty-text">No orders found</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($order['order_ref']); ?></strong></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $order['delivery_type'] ?? 'dine_in')); ?></td>
                                    <td>
                                        <?php
                                        $items = json_decode($order['items'] ?? '[]', true);
                                        echo count($items) . ' item' . (count($items) !== 1 ? 's' : '');
                                        ?>
                                    </td>
                                    <td><?php echo format_kes($order['total'] ?? 0); ?></td>
                                    <td><span class="badge badge-<?php echo safe_html($order['status']); ?>"><?php echo ucfirst($order['status']); ?></span></td>
                                    <td><?php echo format_eat($order['created_at'], 'd M H:i'); ?></td>
                                    <td>
                                        <?php if ($order['status'] !== 'delivered' && $order['status'] !== 'cancelled'): ?>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="order_id" value="<?php echo (int)$order['id']; ?>">
                                                <?php
                                                $nextStatus = match($order['status']) {
                                                    'pending' => 'preparing',
                                                    'preparing' => 'ready',
                                                    'ready' => 'delivered',
                                                    default => null,
                                                };
                                                ?>
                                                <?php if ($nextStatus): ?>
                                                    <input type="hidden" name="new_status" value="<?php echo $nextStatus; ?>">
                                                    <button type="submit" class="btn btn-sm btn-gold">→ <?php echo ucfirst($nextStatus); ?></button>
                                                <?php endif; ?>
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
