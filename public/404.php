<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

http_response_code(404);

$pageTitle = '404 | Page Not Found | Sidai Resort';
$pageDescription = 'The page you are looking for could not be found.';
$pageImage = APP_URL . '/assets/images/hero-section.jpg';
$pageRobots = 'noindex, nofollow';

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>
<main class="pt-28 lg:pt-32 min-h-screen bg-cream pb-16">
    <section class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center">
        <div class="rounded-3xl border border-brown/10 bg-white p-8 shadow-lg sm:p-12">
            <p class="text-xs font-semibold uppercase tracking-[0.24em] text-earth">Error</p>
            <h1 class="mt-2 font-display text-7xl text-gold sm:text-8xl">404</h1>
            <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">Page Not Found</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-brown/75 sm:text-base">
                The page may have moved or the link may be outdated. Use the buttons below to continue browsing Sidai Resort.
            </p>

            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                <a href="<?php echo WEB_ROOT; ?>/" class="inline-flex items-center justify-center rounded-full bg-gold px-7 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-night transition hover:bg-gold-light">Go Home</a>
                <a href="<?php echo WEB_ROOT; ?>/booking" class="inline-flex items-center justify-center rounded-full border border-brown/30 px-7 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-brown transition hover:border-gold hover:text-gold-dark">Make Booking</a>
            </div>

            <p class="mt-8 text-sm text-brown/70">
                Need help? <a href="mailto:<?php echo safe_html(RESORT_EMAIL); ?>" class="font-semibold text-forest hover:text-gold">Email us</a> or call <a href="tel:<?php echo safe_html(preg_replace('/\s+/', '', RESORT_PHONE)); ?>" class="font-semibold text-forest hover:text-gold"><?php echo safe_html(RESORT_PHONE); ?></a>.
            </p>
        </div>
    </section>
</main>

<?php include APP_PATH . '/includes/footer.php'; ?>
