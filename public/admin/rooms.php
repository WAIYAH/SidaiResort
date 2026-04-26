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

$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $token = $_POST[CSRF_TOKEN_NAME] ?? null;
    if (!\App\Core\CSRF::verify(is_string($token) ? $token : null)) {
        $message = ['type' => 'error', 'text' => 'Security validation failed. Please refresh and retry.'];
    } else {
        $action = (string)($_POST['action'] ?? '');
        if ($action === 'toggle_availability') {
            $roomId = (int)($_POST['room_id'] ?? 0);
            if ($roomId > 0 && $roomModel->toggleAvailability($roomId)) {
                $message = ['type' => 'success', 'text' => 'Room availability updated.'];
                log_audit_action('room.toggle_availability', 'room', $roomId);
            } else {
                $message = ['type' => 'error', 'text' => 'Could not update room availability.'];
            }
        }
    }

    $rooms = $roomModel->getAll();
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Room Management</h2>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo safe_html($message['type']); ?>" data-auto-dismiss="3500">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">🛏️</div>
                <div class="stat-label">Total Rooms</div>
                <div class="stat-value"><?php echo count($rooms); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">✅</div>
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
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($rooms)): ?>
                            <tr><td colspan="7" class="admin-empty"><div class="empty-icon">🛏️</div><div class="empty-text">No rooms configured</div></td></tr>
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
                                    <td>
                                        <form method="post" style="display:inline;">
                                            <?php echo \App\Core\CSRF::field(); ?>
                                            <input type="hidden" name="action" value="toggle_availability">
                                            <input type="hidden" name="room_id" value="<?php echo (int)$room['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-outline">
                                                <?php echo (int)$room['is_available'] === 1 ? 'Disable' : 'Enable'; ?>
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
