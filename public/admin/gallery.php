<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Gallery';

$galleryModel = new \App\Models\GalleryItem();
$items = $galleryModel->getAll(200);
$categories = $galleryModel->getCategories();

// Handle delete
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    $itemId = (int)($_POST['item_id'] ?? 0);
    if ($itemId > 0) {
        $galleryModel->delete($itemId);
        $message = ['type' => 'success', 'text' => 'Gallery item removed.'];
        $items = $galleryModel->getAll(200);
    }
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Gallery Management</h2>
            <a href="#" class="btn btn-gold">+ Upload Image</a>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo $message['type']; ?>" data-auto-dismiss="4000">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <div class="stat-grid">
            <div class="stat-card">
                <div class="stat-icon">🖼️</div>
                <div class="stat-label">Total Images</div>
                <div class="stat-value"><?php echo count($items); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">📂</div>
                <div class="stat-label">Categories</div>
                <div class="stat-value"><?php echo count($categories); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">⭐</div>
                <div class="stat-label">Featured</div>
                <div class="stat-value"><?php echo count(array_filter($items, fn($i) => (int)($i['is_featured'] ?? 0) === 1)); ?></div>
            </div>
        </div>

        <div class="admin-filters">
            <input type="text" placeholder="Search gallery..." data-table-search="gallery-table">
        </div>

        <div class="admin-card">
            <div style="overflow-x:auto;">
                <table class="admin-table" id="gallery-table">
                    <thead>
                        <tr>
                            <th>Preview</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Featured</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($items)): ?>
                            <tr><td colspan="6" class="admin-empty"><div class="empty-icon">🖼️</div><div class="empty-text">No gallery items yet</div></td></tr>
                        <?php else: ?>
                            <?php foreach ($items as $item): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo safe_html($item['image_thumb'] ?? $item['image_path']); ?>"
                                             alt="<?php echo safe_html($item['title'] ?? ''); ?>"
                                             style="width:60px;height:40px;object-fit:cover;border-radius:0.25rem;">
                                    </td>
                                    <td><?php echo safe_html($item['title'] ?? '(untitled)'); ?></td>
                                    <td><span class="badge badge-active"><?php echo ucfirst($item['category']); ?></span></td>
                                    <td><?php echo (int)($item['is_featured'] ?? 0) ? '⭐' : '—'; ?></td>
                                    <td>
                                        <span class="badge <?php echo (int)($item['is_active'] ?? 1) ? 'badge-active' : 'badge-inactive'; ?>">
                                            <?php echo (int)($item['is_active'] ?? 1) ? 'Active' : 'Hidden'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('Remove this image?');">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger">Remove</button>
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
