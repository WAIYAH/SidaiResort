<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Terms of Service';
$pageDescription = 'Our terms and conditions';

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
            <h1 class="text-4xl font-playfair font-bold text-forest-green mb-8">Terms of Service</h1>

            <div class="bg-white rounded-lg shadow-lg p-8 text-gray-700 leading-relaxed">
                <h2 class="text-2xl font-bold text-forest-green mb-4">1. Use License</h2>
                <p class="mb-6">Permission is granted to temporarily download one copy of the materials (information or software) on Sidai Safari Dreams' website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                <ul class="list-disc list-inside mb-6 space-y-2">
                    <li>Modify or copy the materials</li>
                    <li>Use the materials for any commercial purpose or for any public display</li>
                    <li>Attempt to decompile or reverse engineer any software contained on the website</li>
                    <li>Remove any copyright or other proprietary notations from the materials</li>
                </ul>

                <h2 class="text-2xl font-bold text-forest-green mb-4">2. Booking Terms</h2>
                <ul class="list-disc list-inside mb-6 space-y-2">
                    <li>A deposit is required to confirm your booking</li>
                    <li>Cancellations must be made 48 hours before arrival</li>
                    <li>Full payment is due 7 days before your arrival date</li>
                </ul>

                <h2 class="text-2xl font-bold text-forest-green mb-4">3. Disclaimer</h2>
                <p class="mb-6">The materials on Sidai Safari Dreams' website are provided on an 'as is' basis. Sidai Safari Dreams makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>

                <h2 class="text-2xl font-bold text-forest-green mb-4">4. Contact Us</h2>
                <p>For questions about these Terms, please contact us at:</p>
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
