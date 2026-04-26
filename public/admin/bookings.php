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

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $token = $_POST[CSRF_TOKEN_NAME] ?? null;
    if (!\App\Core\CSRF::verify(is_string($token) ? $token : null)) {
        $message = ['type' => 'error', 'text' => 'Security validation failed. Please refresh and retry.'];
    } else {
        $action = (string)($_POST['action'] ?? '');
        if ($action === 'update_status') {
            $bookingId = (int)($_POST['booking_id'] ?? 0);
            $newStatus = (string)($_POST['new_status'] ?? '');
            $validStatuses = ['inquiry', 'pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'];

            if ($bookingId > 0 && in_array($newStatus, $validStatuses, true)) {
                $updated = $bookingModel->update($bookingId, ['status' => $newStatus]);
                if ($updated) {
                    $message = ['type' => 'success', 'text' => 'Booking status updated.'];
                    log_audit_action('booking.status_update', 'booking', $bookingId, ['status' => $newStatus]);
                } else {
                    $message = ['type' => 'error', 'text' => 'Could not update booking status.'];
                }
            } else {
                $message = ['type' => 'error', 'text' => 'Invalid booking status update request.'];
            }
        }
    }

    $bookings = $bookingModel->getAll(50);
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Bookings</h2>
            <a href="<?php echo WEB_ROOT; ?>/booking" class="btn btn-gold" target="_blank" rel="noopener noreferrer">+ New Booking</a>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo safe_html($message['type']); ?>" data-auto-dismiss="3500">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

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
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.5rem;justify-content:flex-end;">
                                            <a href="<?php echo WEB_ROOT; ?>/receipt?ref=<?php echo rawurlencode((string)$booking['booking_ref']); ?>" class="btn btn-sm btn-outline" target="_blank" rel="noopener noreferrer">Receipt</a>
                                            <form method="post" style="display:inline-flex;align-items:center;gap:0.35rem;">
                                                <?php echo \App\Core\CSRF::field(); ?>
                                                <input type="hidden" name="action" value="update_status">
                                                <input type="hidden" name="booking_id" value="<?php echo (int)$booking['id']; ?>">
                                                <select name="new_status" class="btn btn-sm btn-outline" style="padding:0.28rem 0.5rem;" aria-label="Update booking status">
                                                    <?php foreach (['pending','confirmed','checked_in','checked_out','cancelled'] as $status): ?>
                                                        <option value="<?php echo $status; ?>" <?php echo ($booking['status'] ?? '') === $status ? 'selected' : ''; ?>>
                                                            <?php echo ucfirst(str_replace('_', ' ', $status)); ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-gold">Save</button>
                                            </form>
                                        </div>
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
