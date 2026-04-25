<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Models\MenuItem;
use App\Models\MenuCategory;

$pageTitle = 'Our Menu';
$pageDescription = 'Discover our delicious dining options';

$categoryModel = new MenuCategory();
$itemModel = new MenuItem();

$categories = $categoryModel->getAll();
$allItems = $itemModel->getAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
</head>
<body>
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="min-h-screen bg-cream pt-20 pb-10">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-playfair font-bold text-forest-green mb-4">Our Menu</h1>
                <p class="text-lg text-gray-600">Exquisite dining experiences crafted for you</p>
            </div>

            <?php if (!empty($categories)): ?>
                <div class="grid grid-cols-1 gap-12">
                    <?php foreach ($categories as $category): ?>
                        <section class="bg-white rounded-lg shadow-lg p-8">
                            <h2 class="text-2xl font-playfair font-bold text-forest-green mb-6">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </h2>

                            <?php if ($category['description']): ?>
                                <p class="text-gray-600 mb-6"><?php echo htmlspecialchars($category['description']); ?></p>
                            <?php endif; ?>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <?php 
                                $categoryItems = array_filter($allItems, fn($item) => $item['category_id'] == $category['id']);
                                foreach ($categoryItems as $item): 
                                ?>
                                    <div class="border-b-2 border-gold-light pb-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h3 class="text-lg font-semibold text-forest-green">
                                                    <?php echo htmlspecialchars($item['name']); ?>
                                                </h3>
                                                <?php if ($item['is_vegetarian']): ?>
                                                    <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded">🥗 Vegetarian</span>
                                                <?php endif; ?>
                                                <?php if ($item['is_spicy']): ?>
                                                    <span class="text-xs bg-red-100 text-red-800 px-2 py-1 rounded ml-1">🌶️ Spicy</span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-lg font-bold text-gold">KES <?php echo format_kes($item['price']); ?></span>
                                        </div>
                                        <?php if ($item['description']): ?>
                                            <p class="text-sm text-gray-600"><?php echo htmlspecialchars($item['description']); ?></p>
                                        <?php endif; ?>
                                        <?php if ($item['allergen_info']): ?>
                                            <p class="text-xs text-red-600 mt-2">⚠️ Contains: <?php echo htmlspecialchars($item['allergen_info']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </section>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <p class="text-gray-600">Menu coming soon!</p>
                </div>
            <?php endif; ?>

            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">Interested in our special dining packages?</p>
                <a href="<?php echo WEB_ROOT; ?>/contact.php" class="inline-block bg-gold hover:bg-gold-dark text-white font-semibold py-3 px-8 rounded-lg transition">
                    Contact Us for Reservations
                </a>
            </div>
        </div>
    </main>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
