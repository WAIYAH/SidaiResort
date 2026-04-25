<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Models\Hall;

$pageTitle = 'Events & Halls';
$pageDescription = 'Perfect venues for your special events';

$hallModel = new Hall();
$halls = $hallModel->getAll();

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
                <h1 class="text-4xl font-playfair font-bold text-forest-green mb-4">Events & Halls</h1>
                <p class="text-lg text-gray-600">Perfect venues for your memorable occasions</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <?php if (!empty($halls)): ?>
                    <?php foreach ($halls as $hall): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition">
                            <div class="h-64 bg-gray-300 flex items-center justify-center">
                                <span class="text-gray-600">Hall Image</span>
                            </div>

                            <div class="p-6">
                                <h3 class="text-2xl font-bold text-forest-green mb-2">
                                    <?php echo htmlspecialchars($hall['name']); ?>
                                </h3>

                                <div class="flex items-center text-gold mb-4">
                                    <span class="text-lg font-semibold">👥 <?php echo $hall['capacity']; ?> guests</span>
                                </div>

                                <?php if ($hall['description']): ?>
                                    <p class="text-gray-600 mb-4">
                                        <?php echo htmlspecialchars($hall['description']); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="space-y-2 mb-6">
                                    <?php if ($hall['price_full_day']): ?>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Full Day:</span>
                                            <span class="font-semibold">KES <?php echo format_kes($hall['price_full_day']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($hall['price_half_day']): ?>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Half Day:</span>
                                            <span class="font-semibold">KES <?php echo format_kes($hall['price_half_day']); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($hall['price_evening']): ?>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Evening:</span>
                                            <span class="font-semibold">KES <?php echo format_kes($hall['price_evening']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <a href="<?php echo WEB_ROOT; ?>/booking.php?type=hall&hall_id=<?php echo $hall['id']; ?>"
                                   class="inline-block w-full text-center bg-gold hover:bg-gold-dark text-white font-semibold py-2 rounded-lg transition">
                                    Book Now
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-2 bg-white rounded-lg shadow-lg p-8 text-center">
                        <p class="text-gray-600">Hall information coming soon. Please contact us for inquiries.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-2xl font-bold text-forest-green mb-6">Event Services We Offer</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-4xl mb-2">💒</div>
                        <h3 class="font-semibold text-forest-green mb-2">Weddings</h3>
                        <p class="text-sm text-gray-600">Romantic ceremonies and receptions</p>
                    </div>

                    <div class="text-center">
                        <div class="text-4xl mb-2">🎉</div>
                        <h3 class="font-semibold text-forest-green mb-2">Corporate Events</h3>
                        <p class="text-sm text-gray-600">Conferences and product launches</p>
                    </div>

                    <div class="text-center">
                        <div class="text-4xl mb-2">🎂</div>
                        <h3 class="font-semibold text-forest-green mb-2">Celebrations</h3>
                        <p class="text-sm text-gray-600">Birthdays and anniversaries</p>
                    </div>

                    <div class="text-center">
                        <div class="text-4xl mb-2">📊</div>
                        <h3 class="font-semibold text-forest-green mb-2">Conferences</h3>
                        <p class="text-sm text-gray-600">Professional meetings and seminars</p>
                    </div>

                    <div class="text-center">
                        <div class="text-4xl mb-2">🎓</div>
                        <h3 class="font-semibold text-forest-green mb-2">Educational Events</h3>
                        <p class="text-sm text-gray-600">Workshops and training sessions</p>
                    </div>

                    <div class="text-center">
                        <div class="text-4xl mb-2">🍽️</div>
                        <h3 class="font-semibold text-forest-green mb-2">Gala Dinners</h3>
                        <p class="text-sm text-gray-600">Elegant dining experiences</p>
                    </div>
                </div>
            </div>

            <div class="mt-12 text-center">
                <p class="text-gray-600 mb-4">Ready to plan your event?</p>
                <a href="<?php echo WEB_ROOT; ?>/contact.php" class="inline-block bg-forest-green hover:bg-forest-green-dark text-white font-semibold py-3 px-8 rounded-lg transition">
                    Contact Our Events Team
                </a>
            </div>
        </div>
    </main>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
