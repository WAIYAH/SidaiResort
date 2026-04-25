<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

http_response_code(404);
$pageTitle = 'Page Not Found';
$pageDescription = 'The page you\'re looking for doesn\'t exist';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
</head>
<body>
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="min-h-screen bg-cream pt-20 pb-10 flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <div class="mb-8">
                <h1 class="text-9xl font-playfair font-bold text-gold mb-4">404</h1>
                <h2 class="text-4xl font-bold text-forest-green mb-4">Page Not Found</h2>
                <p class="text-lg text-gray-600 mb-8">
                    We're sorry, but the page you're looking for doesn't exist. It might have been moved or deleted.
                </p>
            </div>

            <div class="flex gap-4 justify-center">
                <a href="<?php echo WEB_ROOT; ?>" class="bg-gold hover:bg-gold-dark text-white font-semibold py-3 px-8 rounded-lg transition">
                    Go to Home
                </a>
                <a href="<?php echo WEB_ROOT; ?>/booking.php" class="bg-forest-green hover:bg-forest-green-dark text-white font-semibold py-3 px-8 rounded-lg transition">
                    Make a Booking
                </a>
            </div>

            <div class="mt-16">
                <p class="text-gray-600 mb-4">Need help? Contact us:</p>
                <p class="text-forest-green font-semibold">
                    <?php echo RESORT_EMAIL; ?> | <?php echo RESORT_PHONE; ?>
                </p>
            </div>
        </div>
    </main>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
