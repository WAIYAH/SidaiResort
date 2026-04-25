<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Events';

$eventModel = new \App\Models\Event();
$events = $eventModel->getAll(100);

$hallModel = new \App\Models\Hall();
$halls = $hallModel->getAll();
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Events & Conferences</h2>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">🎉</div>
                <div class="stat-label">Total Events</div>
                <div class="stat-value"><?php echo count($events); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📅</div>
                <div class="stat-label">Upcoming</div>
                <div class="stat-value"><?php echo count(array_filter($events, fn($e) => ($e['event_date'] ?? '') >= date('Y-m-d') && $e['status'] !== 'cancelled')); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🏛️</div>
                <div class="stat-label">Available Halls</div>
                <div class="stat-value"><?php echo count(array_filter($halls, fn($h) => (int)($h['is_available'] ?? 0) === 1)); ?></div>
            </div>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search events..." data-table-search="events-table">
            <select data-status-filter="events-table">
                <option value="">All Statuses</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">Event Bookings</h3>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table" id="events-table">
                    <thead>
                        <tr>
                            <th>Ref</th>
                            <th>Guest</th>
                            <th>Type</th>
                            <th>Hall</th>
                            <th>Event Date</th>
                            <th>Guests</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($events)): ?>
                            <tr><td colspan="8" class="admin-empty"><div class="empty-icon">📅</div><div class="empty-text">No events found</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($events as $event): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($event['booking_ref']); ?></strong></td>
                                    <td><?php echo safe_html($event['full_name']); ?></td>
                                    <td><?php echo ucfirst(str_replace('_', ' ', $event['booking_type'])); ?></td>
                                    <td><?php echo safe_html($event['hall_name'] ?? '—'); ?></td>
                                    <td><?php echo $event['event_date'] ? format_eat($event['event_date'], 'd M Y') : '—'; ?></td>
                                    <td><?php echo (int)($event['num_guests'] ?? 0); ?></td>
                                    <td><?php echo format_kes($event['total_amount'] ?? 0); ?></td>
                                    <td><span class="badge badge-<?php echo safe_html($event['status']); ?>"><?php echo ucfirst(str_replace('_', ' ', $event['status'])); ?></span></td>
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
