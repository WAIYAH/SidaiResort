<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Bookings';

$bookingModel = new \App\Models\Booking();
$bookings = $bookingModel->getAll(50);
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Bookings</h2>
            <a href="#" class="btn btn-gold">+ New Booking</a>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search bookings..." data-table-search="bookings-table">
            <select data-status-filter="bookings-table">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="checked in">Checked In</option>
                <option value="checked out">Checked Out</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="admin-card">
            <div style="overflow-x:auto;">
                <table class="admin-table" id="bookings-table">
                    <thead>
                        <tr>
                            <th>Booking Ref</th>
                            <th>Guest</th>
                            <th>Type</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($bookings)): ?>
                            <tr><td colspan="6" class="admin-empty"><div class="empty-icon">📅</div><div class="empty-text">No bookings found</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($bookings as $booking): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($booking['booking_ref']); ?></strong></td>
                                    <td><?php echo safe_html($booking['full_name']); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $booking['booking_type'])); ?></td>
                                    <td><?php echo format_kes($booking['total_amount']); ?></td>
                                    <td><span class="badge badge-<?php echo safe_html($booking['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?></span></td>
                                    <td><a href="#" class="btn btn-sm btn-outline">View</a></td>
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
