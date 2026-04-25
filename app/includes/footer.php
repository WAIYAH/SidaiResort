<?php declare(strict_types=1);

use App\Core\CSRF;
use App\Core\Database;

if (ob_get_level() === 0) {
    ob_start();
}

$newsletterResponse = null;

if (
    ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST'
    && isset($_POST['newsletter_form'])
) {
    $submittedToken = $_POST[CSRF_TOKEN_NAME] ?? null;

    if (!CSRF::verify(is_string($submittedToken) ? $submittedToken : null)) {
        $newsletterResponse = ['type' => 'error', 'message' => 'We could not verify your request. Please try again.'];
    } else {
        $rateKey = 'newsletter_attempts';
        $window = 3600;
        $maxAttempts = 6;

        $_SESSION[$rateKey] = array_values(array_filter(
            $_SESSION[$rateKey] ?? [],
            static fn (int $timestamp): bool => (time() - $timestamp) < $window
        ));

        if (count($_SESSION[$rateKey]) >= $maxAttempts) {
            $newsletterResponse = ['type' => 'error', 'message' => 'Too many attempts. Please try again later.'];
        } else {
            $_SESSION[$rateKey][] = time();
            $email = filter_var(trim((string)($_POST['newsletter_email'] ?? '')), FILTER_VALIDATE_EMAIL);

            if ($email === false) {
                $newsletterResponse = ['type' => 'error', 'message' => 'Please enter a valid email address.'];
            } else {
                try {
                    $database = Database::getInstance();
                    $database->query(
                        'INSERT INTO newsletter_subscribers (email, is_active, verified_at, created_at)
                         VALUES (:email, 1, NULL, NOW())
                         ON DUPLICATE KEY UPDATE is_active = 1, unsubscribed_at = NULL',
                        [':email' => $email]
                    );

                    $subject = APP_NAME . ' Newsletter Subscription';
                    $body = "Thank you for subscribing to Sidai Resort updates.\n\nWe will share exclusive offers and experiences from Naroosura.";
                    $headers = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';

                    @mail($email, $subject, $body, $headers);

                    $newsletterResponse = ['type' => 'success', 'message' => 'Subscription successful. Thank you for joining us.'];
                } catch (Throwable $exception) {
                    log_error('Newsletter subscription failed.', $exception);
                    $newsletterResponse = ['type' => 'error', 'message' => 'We could not complete your subscription right now.'];
                }
            }
        }
    }
}
?>
<footer class="mt-12 sm:mt-20 bg-night text-cream">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:py-16 sm:px-6 lg:px-8">
        <div class="grid gap-10 sm:gap-12 grid-cols-1 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Brand Column -->
            <div class="sm:col-span-2 lg:col-span-1">
                <a href="/" class="flex items-center gap-3">
                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gold text-night font-bold">S</span>
                    <span class="font-display text-2xl text-gold">Sidai Resort</span>
                </a>
                <p class="mt-4 text-sm leading-7 text-cream/80">
                    Warm luxury rooted in Maasai heritage. Sidai means good, and every stay is crafted to feel exceptional.
                </p>
                <div class="mt-5 flex items-center gap-3">
                    <a href="<?php echo safe_html(SOCIAL_INSTAGRAM); ?>" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="Instagram"><?php echo social_icon('instagram', 18); ?></a>
                    <a href="<?php echo safe_html(SOCIAL_FACEBOOK); ?>" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="Facebook"><?php echo social_icon('facebook', 18); ?></a>
                    <a href="<?php echo safe_html(SOCIAL_WHATSAPP); ?>" target="_blank" rel="noopener noreferrer" class="social-pill social-pill--whatsapp" aria-label="WhatsApp"><?php echo social_icon('whatsapp', 18); ?></a>
                    <a href="https://twitter.com/sidairesort" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="Twitter / X"><?php echo social_icon('twitter', 16); ?></a>
                    <a href="https://tiktok.com/@sidairesort" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="TikTok"><?php echo social_icon('tiktok', 16); ?></a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div>
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-list">
                    <li><a href="/">Home</a></li>
                    <li><a href="/services">Services</a></li>
                    <li><a href="/gallery">Gallery</a></li>
                    <li><a href="/events">Events</a></li>
                    <li><a href="/menu">Menu</a></li>
                    <li><a href="/booking">Book Now</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </div>

            <!-- Experiences Column -->
            <div>
                <h3 class="footer-title">Experiences</h3>
                <ul class="footer-list">
                    <li>Swimming Pool Sessions</li>
                    <li>Event Hall Celebrations</li>
                    <li>Fine Dining</li>
                    <li>Spa and Wellness</li>
                    <li>Music and Film Shoots</li>
                    <li>Conference Facilities</li>
                </ul>
            </div>

            <!-- Contact + Newsletter Column -->
            <div>
                <h3 class="footer-title">Contact</h3>
                <div class="space-y-3 text-sm text-cream/85">
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70"><?php echo social_icon('email', 16); ?></span>
                        <a href="mailto:<?php echo safe_html(APP_EMAIL); ?>"><?php echo safe_html(APP_EMAIL); ?></a>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70"><?php echo social_icon('phone', 16); ?></span>
                        <a href="tel:<?php echo safe_html(APP_PHONE); ?>"><?php echo safe_html(APP_PHONE); ?></a>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70"><?php echo social_icon('map', 16); ?></span>
                        <span><?php echo safe_html(APP_ADDRESS); ?></span>
                    </p>
                </div>

                <form class="mt-5 space-y-3" method="post" action="">
                    <input type="hidden" name="newsletter_form" value="1">
                    <?php echo CSRF::field(); ?>
                    <label for="newsletter_email" class="text-sm font-medium text-cream">Newsletter Signup</label>
                    <div class="flex gap-2">
                        <input
                            id="newsletter_email"
                            name="newsletter_email"
                            type="email"
                            required
                            class="flex-1 min-w-0 rounded-xl border border-gold/40 bg-white/10 px-3 py-2 text-sm text-cream placeholder:text-cream/50 focus:outline-none focus:ring-2 focus:ring-gold"
                            placeholder="you@example.com"
                        >
                        <button type="submit" class="rounded-xl bg-gold px-4 py-2 text-sm font-semibold text-night hover:bg-gold-light transition-colors whitespace-nowrap">Subscribe</button>
                    </div>
                    <?php if ($newsletterResponse !== null): ?>
                        <p class="text-xs <?php echo $newsletterResponse['type'] === 'success' ? 'text-green-300' : 'text-red-300'; ?>">
                            <?php echo safe_html($newsletterResponse['message']); ?>
                        </p>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="mt-10 sm:mt-12 border-t border-gold/20 pt-6">
            <div class="flex flex-col gap-4 text-sm text-cream/70 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; <?php echo date('Y'); ?> <?php echo safe_html(APP_NAME); ?>. All rights reserved.</p>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <a href="/privacy-policy" class="hover:text-gold transition-colors">Privacy Policy</a>
                    <a href="/terms-of-service" class="hover:text-gold transition-colors">Terms of Service</a>
                    <a href="/cookie-policy" class="hover:text-gold transition-colors">Cookie Policy</a>
                </div>
                <button id="back-to-top" type="button" class="hidden sm:inline-flex h-10 w-10 items-center justify-center rounded-full border border-gold/50 text-gold hover:bg-gold hover:text-night transition-colors" aria-label="Back to top">
                    <span aria-hidden="true">&uarr;</span>
                </button>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Chat Button -->
<a href="<?php echo safe_html(SOCIAL_WHATSAPP); ?>" target="_blank" rel="noopener noreferrer" class="whatsapp-float" aria-label="Chat on WhatsApp">
    <?php echo social_icon('whatsapp', 28); ?>
</a>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/TextPlugin.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollToPlugin.min.js" defer></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/@studio-freight/lenis@1.0.42/bundled/lenis.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr" defer></script>
<script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.160.0/build/three.min.js" defer></script>
<script src="/assets/js/app.js" defer></script>
</body>
</html>
