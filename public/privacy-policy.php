<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Privacy Policy';
$pageDescription = 'How we protect your privacy';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
</head>
<body>
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="min-h-screen bg-cream pt-20 pb-10">
        <div class="container mx-auto px-4 max-w-3xl">
            <h1 class="text-4xl font-playfair font-bold text-forest-green mb-8">Privacy Policy</h1>

            <div class="bg-white rounded-lg shadow-lg p-8 text-gray-700 leading-relaxed">
                <h2 class="text-2xl font-bold text-forest-green mb-4">1. Introduction</h2>
                <p class="mb-6">Sidai Safari Dreams ("we" or "our") operates the Sidai Safari Dreams website. This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service.</p>

                <h2 class="text-2xl font-bold text-forest-green mb-4">2. Information Collection and Use</h2>
                <p class="mb-4">We collect several different types of information for various purposes to provide and improve our Service to you.</p>
                <ul class="list-disc list-inside mb-6 space-y-2">
                    <li>Personal Data: Email address, first name, last name, phone number, address</li>
                    <li>Booking Information: Dates, room preferences, special requests</li>
                    <li>Payment Information: Processed through M-Pesa or other secure payment methods</li>
                    <li>Usage Data: Browser type, pages visited, time and date of visit</li>
                </ul>

                <h2 class="text-2xl font-bold text-forest-green mb-4">3. Security of Data</h2>
                <p class="mb-6">The security of your data is important to us but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee its absolute security.</p>

                <h2 class="text-2xl font-bold text-forest-green mb-4">4. Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                <p class="mt-2">
                    Email: <?php echo RESORT_EMAIL; ?><br>
                    Phone: <?php echo RESORT_PHONE; ?>
                </p>
            </div>
        </div>
    </main>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
