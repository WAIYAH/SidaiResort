<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Menu';

$categoryModel = new \App\Models\MenuCategory();
$menuItemModel = new \App\Models\MenuItem();

$categories = $categoryModel->getAll();
$allItems = [];
foreach ($categories as $cat) {
    $allItems[$cat['id']] = $menuItemModel->getByCategory((int)$cat['id']);
}

// Handle actions
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'toggle_item') {
        $itemId = (int)($_POST['item_id'] ?? 0);
        if ($itemId > 0) {
            $item = $menuItemModel->getById($itemId);
            if ($item) {
                $menuItemModel->toggleAvailability($itemId);
                $message = ['type' => 'success', 'text' => 'Item availability updated.'];
                // Re-fetch
                $allItems = [];
                foreach ($categories as $cat) {
                    $allItems[$cat['id']] = $menuItemModel->getByCategory((int)$cat['id']);
                }
            }
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
            <h2 class="admin-page-title">Menu Management</h2>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo $message['type']; ?>" data-auto-dismiss="4000">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">📂</div>
                <div class="stat-label">Categories</div>
                <div class="stat-value"><?php echo count($categories); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">🍽️</div>
                <div class="stat-label">Total Items</div>
                <div class="stat-value"><?php echo array_sum(array_map('count', $allItems)); ?></div>
            </div>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search menu items..." data-table-search="menu-table">
        </div>

        <?php foreach ($categories as $category): ?>
            <div class="admin-card" style="margin-bottom:1.5rem;">
                <div class="admin-card-header">
                    <h3 class="admin-card-title"><?php echo safe_html($category['name']); ?></h3>
                    <span class="badge badge-active"><?php echo count($allItems[$category['id']] ?? []); ?> items</span>
                </div>
                <div style="overflow-x:auto;">
                    <table class="admin-table" id="menu-table">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Dietary</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($allItems[$category['id']])): ?>
                                <tr><td colspan="6" style="text-align:center;color:#9CA3AF;padding:1rem;">No items in this category</td></tr>
                            <?php else: ?>
                                <?php foreach ($allItems[$category['id']] as $item): ?>
                                    <tr>
                                        <td><strong><?php echo safe_html($item['name']); ?></strong></td>
                                        <td style="max-width:250px;"><?php echo safe_html(mb_substr($item['description'] ?? '', 0, 80)); ?></td>
                                        <td><?php echo format_kes($item['price']); ?></td>
                                        <td>
                                            <?php if ((int)($item['is_vegetarian'] ?? 0)): ?><span class="badge badge-active" style="margin-right:0.25rem;">🌿 Veg</span><?php endif; ?>
                                            <?php if ((int)($item['is_spicy'] ?? 0)): ?><span class="badge badge-cancelled">🌶️ Spicy</span><?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo (int)($item['is_available'] ?? 1) ? 'badge-active' : 'badge-inactive'; ?>">
                                                <?php echo (int)($item['is_available'] ?? 1) ? 'Available' : 'Unavailable'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form method="post" style="display:inline;">
                                                <input type="hidden" name="action" value="toggle_item">
                                                <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                                <button type="submit" class="btn btn-sm btn-outline">Toggle</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>
    </main>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
