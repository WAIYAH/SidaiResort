<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Guests';

$guestModel = new \App\Models\Guest();
$guests = $guestModel->getAll(100);
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Guest Directory</h2>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search guests..." data-table-search="guests-table">
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h3 class="admin-card-title">All Guests</h3>
                <span style="font-size:0.8rem;color:#6B7280;"><?php echo count($guests); ?> total</span>
            </div>
            <div style="overflow-x:auto;">
                <table class="admin-table" id="guests-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>ID Number</th>
                            <th>Nationality</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($guests)): ?>
                            <tr><td colspan="6" class="admin-empty"><div class="empty-icon">👥</div><div class="empty-text">No guests found</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($guests as $guest): ?>
                                <tr>
                                    <td><strong><?php echo safe_html($guest['full_name']); ?></strong></td>
                                    <td><?php echo safe_html($guest['email']); ?></td>
                                    <td><?php echo safe_html($guest['phone']); ?></td>
                                    <td><?php echo safe_html($guest['id_number'] ?? '—'); ?></td>
                                    <td><?php echo safe_html($guest['nationality'] ?? '—'); ?></td>
                                    <td><?php echo format_eat($guest['created_at'], 'd M Y'); ?></td>
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
