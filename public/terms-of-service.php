<?php declare(strict_types=1);

ob_start();
require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Terms of Service | Sidai Resort';
$pageDescription = 'Our terms and conditions at Sidai Resort.';

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>

<main class="pt-28 lg:pt-32 min-h-screen bg-cream pb-20">
    <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 pt-10">
        <h1 class="font-display text-4xl sm:text-5xl text-brown mb-8 text-center">Terms of Service</h1>

        <div class="bg-white rounded-2xl shadow-sm border border-brown/10 p-8 sm:p-12 text-brown/80 leading-relaxed space-y-8">
            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">1. Use License</h2>
                <p class="mb-4">Permission is granted to temporarily download one copy of the materials (information or software) on Sidai Resort's website for personal, non-commercial transitory viewing only. This is the grant of a license, not a transfer of title, and under this license you may not:</p>
                <ul class="list-disc list-inside space-y-2 ml-4">
                    <li>Modify or copy the materials</li>
                    <li>Use the materials for any commercial purpose or for any public display</li>
                    <li>Attempt to decompile or reverse engineer any software contained on the website</li>
                    <li>Remove any copyright or other proprietary notations from the materials</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">2. Booking Terms</h2>
                <p class="mb-4">To ensure you receive "Nothing But the Best", the following booking terms apply:</p>
                <ul class="list-disc list-inside space-y-2 ml-4">
                    <li>A deposit is required to confirm your booking and secure your room or event hall.</li>
                    <li>Cancellations must be made at least 48 hours before the scheduled arrival to be eligible for a refund.</li>
                    <li>Full payment is due before or upon arrival depending on the service booked.</li>
                    <li>Prices are subject to change, but confirmed bookings will be honored at the booked rate.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">3. Code of Conduct</h2>
                <p>Guests are expected to behave in a manner that respects the peace and tranquility of the resort and other guests. Sidai Resort reserves the right to ask any guest to leave without a refund if their behavior is deemed unacceptable.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">4. Disclaimer</h2>
                <p>The materials on Sidai Resort's website are provided on an 'as is' basis. Sidai Resort makes no warranties, expressed or implied, and hereby disclaims and negates all other warranties including, without limitation, implied warranties or conditions of merchantability, fitness for a particular purpose, or non-infringement of intellectual property or other violation of rights.</p>
            </section>

            <section>
                <h2 class="text-2xl font-bold text-brown mb-4 font-display">5. Contact Us</h2>
                <p>For questions about these Terms, please contact us at:</p>
                <div class="mt-4 p-4 bg-cream/50 rounded-xl border border-brown/5 inline-block">
                    <p class="mb-2"><strong>Email:</strong> <?php echo APP_EMAIL; ?></p>
                    <p><strong>Phone:</strong> <?php echo APP_PHONE; ?></p>
                </div>
            </section>
        </div>
    </div>
</main>

<?php include APP_PATH . '/includes/footer.php'; ?>
