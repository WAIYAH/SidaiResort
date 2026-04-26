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
                    $body = "Thank you for subscribing to Sidai Resort updates.\n\nWe will share exclusive offers and experiences from our resort.";
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
                <a href="<?php echo WEB_ROOT; ?>/" class="flex items-center gap-3">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo.png" alt="Sidai Resort" class="h-14 w-auto">
                </a>
                <p class="mt-4 text-sm font-semibold text-gold">
                    Where your needs are our goals
                </p>
                <p class="mt-2 text-sm leading-7 text-cream/80">
                    Sidai — Excellence in Maasai
                </p>
                <div class="mt-5 flex items-center gap-3">
                    <a href="https://facebook.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="Facebook"><?php echo social_icon('facebook', 18); ?></a>
                    <a href="https://instagram.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="Instagram"><?php echo social_icon('instagram', 18); ?></a>
                    <a href="https://tiktok.com/@SidaiResort" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="TikTok"><?php echo social_icon('tiktok', 16); ?></a>
                    <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="social-pill social-pill--whatsapp" aria-label="WhatsApp"><?php echo social_icon('whatsapp', 18); ?></a>
                    <a href="#" target="_blank" rel="noopener noreferrer" class="social-pill" aria-label="YouTube"><?php echo social_icon('youtube', 18); ?></a>
                </div>
            </div>

            <!-- Quick Links Column -->
            <div>
                <h3 class="footer-title">Quick Links</h3>
                <ul class="footer-list">
                    <li><a href="<?php echo WEB_ROOT; ?>/">Home</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/about">About</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/rooms">Rooms</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/services">Services</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/about#gallery">Gallery</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/events">Events</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/menu">Menu</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/booking">Booking</a></li>
                    <li><a href="<?php echo WEB_ROOT; ?>/contact">Contact</a></li>
                </ul>
            </div>

            <!-- Experiences Column -->
            <div>
                <h3 class="footer-title">Our Experiences</h3>
                <ul class="footer-list">
                    <li>Swimming Pool</li>
                    <li>Events & Weddings</li>
                    <li>Conferences</li>
                    <li>Accommodation</li>
                    <li>Goat Eating</li>
                    <li>Bonfires & Sundowners</li>
                    <li>Birdwatching</li>
                    <li>Farm Visits</li>
                    <li>Picnics</li>
                </ul>
            </div>

            <!-- Contact Column -->
            <div>
                <h3 class="footer-title">Contact Us</h3>
                <div class="space-y-3 text-sm text-cream/85">
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70">📍</span>
                        <span>Naroosura, Loita Hills, Narok County, Kenya</span>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70">📮</span>
                        <span>P.O Box 617 – 20500, Narok</span>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70">📞</span>
                        <a href="tel:0703761951">0703 761 951</a>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70">📞</span>
                        <a href="tel:0721940823">0721 940 823</a>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70">✉️</span>
                        <a href="mailto:sidairesort21@gmail.com">sidairesort21@gmail.com</a>
                    </p>
                    <p class="flex items-start gap-2">
                        <span class="flex-shrink-0 mt-0.5 text-gold/70">💬</span>
                        <a href="https://wa.me/254703761951">WhatsApp: 0703 761 951</a>
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
                <p>&copy; 2025 Sidai Resort. All rights reserved.</p>
                <p class="text-gold uppercase tracking-[0.2em] font-semibold text-center text-xs">Nothing but the Best — Always</p>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2">
                    <a href="<?php echo WEB_ROOT; ?>/privacy-policy" class="hover:text-gold transition-colors">Privacy Policy</a>
                    <a href="<?php echo WEB_ROOT; ?>/terms-of-service" class="hover:text-gold transition-colors">Terms of Service</a>
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

<!-- Cookie Consent Banner -->
<div x-data="cookieConsent()" x-init="checkConsent()" x-show="showBanner" x-transition.opacity.duration.500ms class="fixed bottom-0 left-0 right-0 z-50 bg-night/95 backdrop-blur-md border-t border-gold/30 p-4 sm:p-6 shadow-2xl" style="display: none;">
    <div class="mx-auto max-w-7xl flex flex-col sm:flex-row items-center justify-between gap-4">
        <div class="text-cream text-sm sm:text-base flex-1">
            <h4 class="text-gold font-display text-lg mb-1">We Value Your Privacy</h4>
            <p>We use cookies to enhance your browsing experience, serve personalized content, and analyze our traffic. By clicking "Accept All", you consent to our use of cookies. <a href="<?php echo WEB_ROOT; ?>/cookie-policy" class="text-gold underline hover:text-gold-light">Read more</a>.</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto shrink-0">
            <button @click="declineCookies()" class="flex-1 sm:flex-none px-5 py-2 rounded-lg border border-cream/30 text-cream hover:bg-cream/10 transition-colors text-sm font-semibold uppercase tracking-wider">Decline</button>
            <button @click="acceptCookies()" class="flex-1 sm:flex-none px-5 py-2 rounded-lg bg-gold text-night hover:bg-gold-dark transition-colors shadow-lg text-sm font-semibold uppercase tracking-wider">Accept All</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('cookieConsent', () => ({
        showBanner: false,
        checkConsent() {
            if (!localStorage.getItem('sidai_cookie_consent')) {
                setTimeout(() => { this.showBanner = true; }, 1000);
            }
        },
        acceptCookies() {
            localStorage.setItem('sidai_cookie_consent', 'accepted');
            this.showBanner = false;
        },
        declineCookies() {
            localStorage.setItem('sidai_cookie_consent', 'declined');
            this.showBanner = false;
        }
    }));
});
</script>

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
<script src="<?php echo WEB_ROOT; ?>/assets/js/app.js" defer></script>
</body>
</html>
