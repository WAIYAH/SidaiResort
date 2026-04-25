<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Cookie Policy';
$pageDescription = 'How we use cookies';

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
            <h1 class="text-4xl font-playfair font-bold text-forest-green mb-8">Cookie Policy</h1>

            <div class="bg-white rounded-lg shadow-lg p-8 text-gray-700 leading-relaxed">
                <h2 class="text-2xl font-bold text-forest-green mb-4">1. What Are Cookies?</h2>
                <p class="mb-6">Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used in order to make websites work, or work more efficiently, as well as to provide information to the owners of the site.</p>

                <h2 class="text-2xl font-bold text-forest-green mb-4">2. How We Use Cookies</h2>
                <p class="mb-6">Sidai Safari Dreams uses cookies for the following purposes:</p>
                <ul class="list-disc list-inside mb-6 space-y-2">
                    <li><strong>Session Cookies:</strong> To keep you logged in during your visit</li>
                    <li><strong>Preference Cookies:</strong> To remember your preferences</li>
                    <li><strong>Analytics Cookies:</strong> To understand how our website is used</li>
                    <li><strong>Security Cookies:</strong> To protect against malicious activities</li>
                </ul>

                <h2 class="text-2xl font-bold text-forest-green mb-4">3. Managing Cookies</h2>
                <p class="mb-6">Most web browsers allow some control of cookies through the browser settings. To find out more about cookies, including how to see what cookies have been set and how to manage and delete them, visit www.allaboutcookies.org.</p>

                <h2 class="text-2xl font-bold text-forest-green mb-4">4. Contact Us</h2>
                <p>If you have questions about our cookie policy, please contact us at:</p>
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
