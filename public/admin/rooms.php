<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Rooms';

$roomModel = new \App\Models\Room();
$rooms = $roomModel->getAll();

$roomTypes = [
    'standard' => 'Standard',
    'deluxe' => 'Deluxe',
    'suite' => 'Suite',
    'pool_villa' => 'Pool Villa',
    'honeymoon' => 'Honeymoon',
];
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Room Management</h2>
        </div>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">ðŸ›ï¸</div>
                <div class="stat-label">Total Rooms</div>
                <div class="stat-value"><?php echo count($rooms); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">âœ…</div>
                <div class="stat-label">Available</div>
                <div class="stat-value"><?php echo count(array_filter($rooms, fn($r) => (int)($r['is_available'] ?? 0) === 1)); ?></div>
            </div>
        </div>

        <div class="admin-card">
            <div style="overflow-x:auto;">
                <table class="admin-table" id="rooms-table">
                    <thead>
                        <tr>
                            <th>Room #</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Price/Night</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rooms)): ?>
                            <tr><td colspan="6" class="admin-empty"><div class="empty-icon">ðŸ›ï¸</div><div class="empty-text">No rooms configured</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($rooms as $room): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($room['room_number']); ?></strong></td>
                                    <td><?php echo safe_html($room['name']); ?></td>
                                    <td><span class="badge badge-active"><?php echo $roomTypes[$room['type']] ?? ucfirst($room['type']); ?></span></td>
                                    <td><?php echo (int)$room['capacity']; ?> guests</td>
                                    <td><?php echo format_kes($room['price_per_night']); ?></td>
                                    <td>
                                        <span class="badge <?php echo (int)$room['is_available'] === 1 ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo (int)$room['is_available'] === 1 ? 'Available' : 'Unavailable'; ?>
                                        </span>
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
