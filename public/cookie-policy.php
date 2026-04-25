<?php declare(strict_types=1);

ob_start();
require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Cookie Policy | Sidai Resort';
$pageDescription = 'Our cookie policy at Sidai Resort.';

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>

<main class="pt-28 lg:pt-32 min-h-screen bg-cream pb-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 pt-10">
        <h1 class="font-display text-4xl sm:text-5xl text-brown mb-8 text-center">Cookie Policy</h1>

        <div class="bg-white rounded-2xl shadow-sm border border-brown/10 p-8 sm:p-12 text-brown/80 leading-relaxed space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">1. What Are Cookies?</h2>
                <p>Cookies are small text files that are placed on your computer or mobile device when you visit a website. They are widely used to make websites work, or work more efficiently, as well as to provide information to the owners of the site.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">2. How We Use Cookies</h2>
                <p class="mb-4">Sidai Resort uses cookies to:</p>
                <ul class="list-disc list-inside space-y-2 ml-4">
                    <li>Understand and save your preferences for future visits (such as cookie consent).</li>
                    <li>Compile aggregate data about site traffic and site interactions in order to offer better site experiences and tools in the future.</li>
                    <li>Process bookings efficiently.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">3. Types of Cookies We Use</h2>
                <ul class="list-disc list-inside space-y-2 ml-4">
                    <li><strong>Essential Cookies:</strong> Necessary for the website to function properly. They enable core functionalities like security, network management, and accessibility.</li>
                    <li><strong>Analytical/Performance Cookies:</strong> Allow us to recognize and count the number of visitors and to see how visitors move around our website when they are using it.</li>
                    <li><strong>Functionality Cookies:</strong> Used to recognize you when you return to our website. This enables us to personalize our content for you.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">4. Managing Cookies</h2>
                <p>You can choose to have your computer warn you each time a cookie is being sent, or you can choose to turn off all cookies. You do this through your browser settings. Since every browser is a little different, look at your browser's Help Menu to learn the correct way to modify your cookies.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">5. Contact Us</h2>
                <p>If you have any questions about this Cookie Policy, please contact us at:</p>
                <div class="mt-4 p-4 bg-cream/50 rounded-xl border border-brown/5 inline-block">
                    <p class="mb-2"><strong>Email:</strong> <?php echo APP_EMAIL; ?></p>
                    <p><strong>Phone:</strong> <?php echo APP_PHONE; ?></p>
                </div>
            </section>
        </div>
    </div>
</main>

<?php include APP_PATH . '/includes/footer.php'; ?>
