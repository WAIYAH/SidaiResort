<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Contact Us';
$pageDescription = 'Get in touch with Sidai Safari Dreams';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
</head>
<body>
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="min-h-screen bg-cream pt-20 pb-10">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-playfair font-bold text-forest-green mb-4">Get In Touch</h1>
                <p class="text-lg text-gray-600">We'd love to hear from you. Drop us a message anytime.</p>
            </div>

            <form id="contactForm" method="POST" action="<?php echo WEB_ROOT; ?>/api/contact-submit.php" class="bg-white rounded-lg shadow-lg p-8">
                <?php echo csrf_token_field(); ?>

                <div class="mb-6">
                    <label for="full_name" class="block text-sm font-semibold text-forest-green mb-2">Full Name</label>
                    <input type="text" id="full_name" name="full_name" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-forest-green mb-2">Email Address</label>
                    <input type="email" id="email" name="email" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div class="mb-6">
                    <label for="phone" class="block text-sm font-semibold text-forest-green mb-2">Phone Number</label>
                    <input type="tel" id="phone" name="phone" placeholder="+254..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div class="mb-6">
                    <label for="subject" class="block text-sm font-semibold text-forest-green mb-2">Subject</label>
                    <input type="text" id="subject" name="subject" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold">
                </div>

                <div class="mb-6">
                    <label for="message" class="block text-sm font-semibold text-forest-green mb-2">Message</label>
                    <textarea id="message" name="message" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold"></textarea>
                </div>

                <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-white font-semibold py-3 rounded-lg transition">
                    Send Message
                </button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="text-center">
                    <div class="text-3xl text-gold mb-2">📞</div>
                    <h3 class="font-semibold text-forest-green mb-1">Phone</h3>
                    <p class="text-gray-600"><?php echo RESORT_PHONE; ?></p>
                </div>
                <div class="text-center">
                    <div class="text-3xl text-gold mb-2">✉️</div>
                    <h3 class="font-semibold text-forest-green mb-1">Email</h3>
                    <p class="text-gray-600"><?php echo RESORT_EMAIL; ?></p>
                </div>
                <div class="text-center">
                    <div class="text-3xl text-gold mb-2">📍</div>
                    <h3 class="font-semibold text-forest-green mb-1">Location</h3>
                    <p class="text-gray-600"><?php echo RESORT_ADDRESS; ?></p>
                </div>
            </div>
        </div>
    </main>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
