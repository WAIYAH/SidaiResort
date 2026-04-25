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
            }
        }
    } elseif ($action === 'create_item') {
        $data = [
            'category_id' => (int)($_POST['category_id'] ?? 0),
            'name' => trim($_POST['name'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float)($_POST['price'] ?? 0),
            'is_vegetarian' => isset($_POST['is_vegetarian']) ? 1 : 0,
            'is_spicy' => isset($_POST['is_spicy']) ? 1 : 0,
            'is_available' => 1
        ];
        if ($data['category_id'] > 0 && !empty($data['name']) && $data['price'] >= 0) {
            if ($menuItemModel->create($data)) {
                $message = ['type' => 'success', 'text' => 'Menu item created successfully.'];
            } else {
                $message = ['type' => 'error', 'text' => 'Failed to create menu item.'];
            }
        } else {
            $message = ['type' => 'error', 'text' => 'Please fill all required fields correctly.'];
        }
    } elseif ($action === 'update_item') {
        $itemId = (int)($_POST['item_id'] ?? 0);
        if ($itemId > 0) {
            $data = [
                'category_id' => (int)($_POST['category_id'] ?? 0),
                'name' => trim($_POST['name'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float)($_POST['price'] ?? 0),
                'is_vegetarian' => isset($_POST['is_vegetarian']) ? 1 : 0,
                'is_spicy' => isset($_POST['is_spicy']) ? 1 : 0
            ];
            if ($data['category_id'] > 0 && !empty($data['name']) && $data['price'] >= 0) {
                if ($menuItemModel->update($itemId, $data)) {
                    $message = ['type' => 'success', 'text' => 'Menu item updated successfully.'];
                } else {
                    $message = ['type' => 'error', 'text' => 'Failed to update menu item.'];
                }
            } else {
                $message = ['type' => 'error', 'text' => 'Please fill all required fields correctly.'];
            }
        }
    } elseif ($action === 'delete_item') {
        $itemId = (int)($_POST['item_id'] ?? 0);
        if ($itemId > 0 && $menuItemModel->delete($itemId)) {
            $message = ['type' => 'success', 'text' => 'Menu item deleted successfully.'];
        } else {
            $message = ['type' => 'error', 'text' => 'Failed to delete menu item.'];
        }
    }

    // Re-fetch after any action
    $allItems = [];
    foreach ($categories as $cat) {
        $allItems[$cat['id']] = $menuItemModel->getByCategory((int)$cat['id']);
    }
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="admin-page-title">Menu Management</h2>
            <button type="button" class="btn btn-primary" onclick="openMenuModal()">+ Add New Item</button>
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
                                <th style="text-align: right;">Action</th>
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
                                        <td style="text-align: right;">
                                            <div style="display:flex; gap:0.5rem; justify-content:flex-end;">
                                                <button type="button" class="btn btn-sm btn-outline" onclick='openMenuModal(<?php echo json_encode($item); ?>)'>Edit</button>
                                                <form method="post" style="display:inline;">
                                                    <input type="hidden" name="action" value="toggle_item">
                                                    <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline">Toggle</button>
                                                </form>
                                                <form method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this menu item?');">
                                                    <input type="hidden" name="action" value="delete_item">
                                                    <input type="hidden" name="item_id" value="<?php echo (int)$item['id']; ?>">
                                                    <button type="submit" class="btn btn-sm" style="background:#FEE2E2; color:#DC2626; border-color:#FEE2E2;">Delete</button>
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
        <?php endforeach; ?>
    </main>
</div>

<!-- Modal Dialog -->
<dialog id="menuModal" style="padding: 2rem; border: none; border-radius: 0.5rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1); max-width: 500px; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 id="modalTitle" style="font-size: 1.25rem; font-weight: 600; color: #111827;">Add New Menu Item</h3>
        <button type="button" onclick="document.getElementById('menuModal').close()" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #6B7280;">&times;</button>
    </div>
    <form method="post" id="menuForm">
        <input type="hidden" name="action" id="formAction" value="create_item">
        <input type="hidden" name="item_id" id="formItemId" value="">

        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Category</label>
            <select name="category_id" id="formCategoryId" required style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem;">
                <option value="">Select Category</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?php echo $cat['id']; ?>"><?php echo safe_html($cat['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Item Name</label>
            <input type="text" name="name" id="formName" required style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem;">
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Description</label>
            <textarea name="description" id="formDescription" rows="3" style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem;"></textarea>
        </div>

        <div style="margin-bottom: 1rem;">
            <label style="display: block; font-weight: 500; margin-bottom: 0.5rem; color: #374151;">Price (KES)</label>
            <input type="number" name="price" id="formPrice" required min="0" step="0.01" style="width: 100%; padding: 0.5rem; border: 1px solid #D1D5DB; border-radius: 0.375rem;">
        </div>

        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem;">
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_vegetarian" id="formVegetarian" value="1"> Vegetarian
            </label>
            <label style="display: flex; align-items: center; gap: 0.5rem;">
                <input type="checkbox" name="is_spicy" id="formSpicy" value="1"> Spicy
            </label>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem;">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('menuModal').close()">Cancel</button>
            <button type="submit" class="btn btn-primary" id="formSubmitBtn">Save Item</button>
        </div>
    </form>
</dialog>

<script>
function openMenuModal(item = null) {
    const modal = document.getElementById('menuModal');
    const form = document.getElementById('menuForm');
    
    if (item) {
        document.getElementById('modalTitle').textContent = 'Edit Menu Item';
        document.getElementById('formAction').value = 'update_item';
        document.getElementById('formItemId').value = item.id;
        document.getElementById('formCategoryId').value = item.category_id;
        document.getElementById('formName').value = item.name;
        document.getElementById('formDescription').value = item.description || '';
        document.getElementById('formPrice').value = item.price;
        document.getElementById('formVegetarian').checked = item.is_vegetarian == 1;
        document.getElementById('formSpicy').checked = item.is_spicy == 1;
        document.getElementById('formSubmitBtn').textContent = 'Update Item';
    } else {
        form.reset();
        document.getElementById('modalTitle').textContent = 'Add New Menu Item';
        document.getElementById('formAction').value = 'create_item';
        document.getElementById('formItemId').value = '';
        document.getElementById('formSubmitBtn').textContent = 'Save Item';
    }
    
    modal.showModal();
}
</script>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
