<?php declare(strict_types=1);

ob_start();
require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Privacy Policy | Sidai Resort';
$pageDescription = 'How we protect your privacy at Sidai Resort.';

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>

<main class="pt-28 lg:pt-32 min-h-screen bg-cream pb-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 pt-10">
        <h1 class="font-display text-4xl sm:text-5xl text-brown mb-8 text-center">Privacy Policy</h1>

        <div class="bg-white rounded-2xl shadow-sm border border-brown/10 p-8 sm:p-12 text-brown/80 leading-relaxed space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">1. Introduction</h2>
                <p>Sidai Resort ("we" or "our") operates the Sidai Resort website. This page informs you of our policies regarding the collection, use, and disclosure of personal data when you use our Service. Your privacy is paramount to us, ensuring you receive "Nothing But the Best" in both hospitality and data security.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">2. Information Collection and Use</h2>
                <p class="mb-4">We collect several different types of information for various purposes to provide and improve our Service to you.</p>
                <ul class="list-disc list-inside space-y-2 ml-4">
                    <li><strong>Personal Data:</strong> Email address, first name, last name, phone number, address</li>
                    <li><strong>Booking Information:</strong> Dates, room preferences, special requests</li>
                    <li><strong>Payment Information:</strong> Processed securely through M-Pesa or other accepted payment methods</li>
                    <li><strong>Usage Data:</strong> Browser type, pages visited, time and date of visit</li>
                </ul>
            </section>
            
            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">3. Cookies and Tracking Data</h2>
                <p>We use cookies and similar tracking technologies to track the activity on our Service and hold certain information. You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. For more details, please view our <a href="/cookie-policy" class="text-gold underline hover:text-gold-dark">Cookie Policy</a>.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">4. Security of Data</h2>
                <p>The security of your data is important to us but remember that no method of transmission over the Internet or method of electronic storage is 100% secure. While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee its absolute security.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">5. Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us at:</p>
                <div class="mt-4 p-4 bg-cream/50 rounded-xl border border-brown/5 inline-block">
                    <p class="mb-2"><strong>Email:</strong> <?php echo APP_EMAIL; ?></p>
                    <p><strong>Phone:</strong> <?php echo APP_PHONE; ?></p>
                </div>
            </section>
        </div>
    </div>
</main>

<?php include APP_PATH . '/includes/footer.php'; ?>
