<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php?bypass_cache=1');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Dashboard';

$db = \App\Core\Database::getInstance();

// Dashboard statistics
$totalBookings = $db->queryOne('SELECT COUNT(*) as count FROM bookings WHERE deleted_at IS NULL')['count'] ?? 0;
$totalGuests = $db->queryOne('SELECT COUNT(*) as count FROM guests WHERE deleted_at IS NULL')['count'] ?? 0;
$totalRevenue = $db->queryOne('SELECT COALESCE(SUM(total_amount), 0) as total FROM bookings WHERE status NOT IN ("cancelled", "pending")')['total'] ?? 0;
$pendingBookings = $db->queryOne('SELECT COUNT(*) as count FROM bookings WHERE status = "pending"')['count'] ?? 0;
$todayCheckIns = $db->queryOne('SELECT COUNT(*) as count FROM bookings WHERE check_in = CURDATE() AND status = "confirmed"')['count'] ?? 0;
$occupiedRooms = $db->queryOne('SELECT COUNT(*) as count FROM bookings WHERE status = "checked_in" AND check_out >= CURDATE()')['count'] ?? 0;

// Recent bookings
$recentBookings = $db->queryAll(
    'SELECT b.booking_ref, b.booking_type, b.status, b.total_amount, b.created_at, g.full_name
     FROM bookings b
     JOIN guests g ON b.guest_id = g.id
     WHERE b.deleted_at IS NULL
     ORDER BY b.created_at DESC
     LIMIT 8'
);

// Recent payments
$recentPayments = $db->queryAll(
    'SELECT payment_ref, amount, method, status, paid_at, created_at
     FROM payments
     ORDER BY created_at DESC
     LIMIT 5'
);
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <h2 class="admin-page-title">Welcome back, <?php echo safe_html($staff['full_name']); ?> 👋</h2>

        <!-- Stats Grid -->
        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-label">Total Bookings</div>
                <div class="stat-value"><?php echo number_format((int)$totalBookings); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">👥</div>
                <div class="stat-label">Total Guests</div>
                <div class="stat-value"><?php echo number_format((int)$totalGuests); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">💰</div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value"><?php echo format_kes($totalRevenue); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⏳</div>
                <div class="stat-label">Pending Bookings</div>
                <div class="stat-value"><?php echo number_format((int)$pendingBookings); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏨</div>
                <div class="stat-label">Today's Check-ins</div>
                <div class="stat-value"><?php echo (int)$todayCheckIns; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🛏️</div>
                <div class="stat-label">Occupied Rooms</div>
                <div class="stat-value"><?php echo (int)$occupiedRooms; ?></div>
            </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
            <!-- Recent Bookings -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Recent Bookings</h3>
                    <a href="<?php echo WEB_ROOT; ?>/admin/bookings.php" class="btn btn-sm btn-outline">View All</a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ref</th>
                                <th>Guest</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentBookings as $booking): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($booking['booking_ref']); ?></strong></td>
                                    <td><?php echo safe_html($booking['full_name']); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $booking['booking_type'])); ?></td>
                                    <td><?php echo format_kes($booking['total_amount']); ?></td>
                                    <td><span class="badge badge-<?php echo safe_html($booking['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentBookings)): ?>
                                <tr><td colspan="5" style="text-align:center;color:#9CA3AF;padding:1.5rem;">No bookings yet</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Payments -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Recent Payments</h3>
                    <a href="<?php echo WEB_ROOT; ?>/admin/payments.php" class="btn btn-sm btn-outline">View All</a>
                </div>
                <div style="overflow-x:auto;">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Ref</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentPayments as $payment): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($payment['payment_ref']); ?></strong></td>
                                    <td><?php echo format_kes($payment['amount']); ?></td>
                                    <td><?php echo ucfirst($payment['method']); ?></td>
                                    <td><span class="badge badge-<?php echo safe_html($payment['status']); ?>"><?php echo ucfirst($payment['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($recentPayments)): ?>
                                <tr><td colspan="4" style="text-align:center;color:#9CA3AF;padding:1.5rem;">No payments yet</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="admin-card" style="margin-top:1.5rem;">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Quick Actions</h3>
            </div>
            <div class="admin-card-body" style="display:flex;gap:1rem;flex-wrap:wrap;">
                <a href="<?php echo WEB_ROOT; ?>/admin/bookings.php" class="btn btn-gold">📅 New Booking</a>
                <a href="<?php echo WEB_ROOT; ?>/admin/guests.php" class="btn btn-gold">👥 Add Guest</a>
                <a href="<?php echo WEB_ROOT; ?>/admin/payments.php" class="btn btn-forest">💳 View Payments</a>
                <a href="<?php echo WEB_ROOT; ?>/admin/reports.php" class="btn btn-forest">📈 Reports</a>
                <a href="<?php echo WEB_ROOT; ?>/" class="btn btn-outline" target="_blank">🌐 View Website</a>
            </div>
        </div>
    </main>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
