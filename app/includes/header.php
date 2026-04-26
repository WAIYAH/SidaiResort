<?php declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$navItems = [
    ['label' => 'Home', 'href' => WEB_ROOT . '/'],
    ['label' => 'About', 'href' => WEB_ROOT . '/about'],
    ['label' => 'Rooms', 'href' => WEB_ROOT . '/rooms'],
    ['label' => 'Services', 'href' => WEB_ROOT . '/services'],
    ['label' => 'Gallery', 'href' => WEB_ROOT . '/about#gallery'],
    ['label' => 'Menu', 'href' => WEB_ROOT . '/menu'],
    ['label' => 'Contact', 'href' => WEB_ROOT . '/contact'],
];

$isActive = static function (string $href) use ($currentPath): bool {
    $hrefPath = parse_url($href, PHP_URL_PATH) ?: $href;
    if ($hrefPath === '/') {
        return $currentPath === '/' || $currentPath === WEB_ROOT . '/';
    }

    $normalizedHref = preg_replace('/\.php$/', '', $hrefPath) ?: $hrefPath;

    return $currentPath === $normalizedHref || $currentPath === $hrefPath;
};
?>
<div id="page-loader" class="page-loader" role="status" aria-live="polite">
    <div class="loader-mark text-center">
        <p class="loader-logo font-display text-5xl text-gold">SIDAI</p>
        <p class="loader-copy font-light uppercase tracking-[0.5em] text-brown mt-3">Where Good Meets Luxury</p>
    </div>
</div>

<header id="site-header" x-data="{ mobileOpen: false }" class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
    <!-- Top Contact Bar -->
    <div class="hidden md:block bg-night text-cream py-1.5 px-4 sm:px-6 lg:px-8 text-xs font-medium tracking-wide">
        <div class="mx-auto max-w-7xl flex items-center justify-between">
            <div class="flex items-center gap-4">
                <span>📞 0703 761 951 / 0721 940 823</span>
                <span>✉ sidairesort21@gmail.com</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="https://facebook.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors">Facebook</a>
                <a href="https://instagram.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors">Instagram</a>
                <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors">WhatsApp</a>
                <a href="https://tiktok.com/@SidaiResort" target="_blank" rel="noopener noreferrer" class="hover:text-gold transition-colors">TikTok</a>
            </div>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <nav class="mt-3 sm:mt-4 rounded-2xl border border-gold/30 bg-cream/70 px-3 sm:px-4 py-2.5 sm:py-3 backdrop-blur-lg shadow-lg" aria-label="Primary">
            <div class="flex items-center justify-between gap-2 sm:gap-4">
                <a href="<?php echo WEB_ROOT; ?>/" class="site-logo flex items-center gap-2 sm:gap-3 min-w-0" aria-label="Sidai Resort Home">
                    <picture>
                        <source media="(prefers-color-scheme: dark)" srcset="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo-white.png">
                        <img src="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo.png" alt="Sidai Resort — Where your needs are our goals" class="h-12 md:h-16 w-auto object-contain">
                    </picture>
                </a>

                <div class="hidden lg:flex items-center gap-1" id="desktop-nav-links">
                    <?php foreach ($navItems as $item): ?>
                        <a
                            href="<?php echo safe_html($item['href']); ?>"
                            class="header-link rounded-full px-4 py-2 text-sm font-medium transition-colors <?php echo $isActive($item['href']) ? 'text-gold bg-night/80' : 'text-brown hover:text-gold hover:bg-night/70'; ?>"
                        >
                            <?php echo safe_html($item['label']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <div class="hidden lg:flex items-center gap-2">
                    <a href="<?php echo WEB_ROOT; ?>/admin/login.php" class="inline-flex items-center rounded-full border border-gold/40 bg-night/70 px-4 py-2 text-xs font-semibold uppercase tracking-[0.14em] text-gold hover:bg-night transition-colors">
                        Admin
                    </a>
                    <a href="<?php echo WEB_ROOT; ?>/booking" class="ml-1 inline-flex items-center rounded-full bg-gold px-5 py-2.5 text-sm font-semibold text-night hover:bg-gold-light transition-colors">Book Now</a>
                </div>

                <button
                    type="button"
                    class="lg:hidden inline-flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-full border border-gold/40 bg-night/80 text-gold flex-shrink-0"
                    aria-label="Toggle mobile menu"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                >
                    <span x-show="!mobileOpen" x-cloak>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 sm:h-6 sm:w-6"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </span>
                    <span x-show="mobileOpen" x-cloak>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="h-5 w-5 sm:h-6 sm:w-6"><path stroke-linecap="round" d="m6 6 12 12M6 18 18 6"/></svg>
                    </span>
                </button>
            </div>

            <div x-show="mobileOpen" x-cloak x-transition.origin.top class="lg:hidden mt-3 border-t border-gold/20 pt-3" id="mobile-menu">
                <div class="space-y-1.5">
                    <?php foreach ($navItems as $item): ?>
                        <a
                            href="<?php echo safe_html($item['href']); ?>"
                            class="flex items-center rounded-xl px-4 py-3 text-sm font-medium transition-colors <?php echo $isActive($item['href']) ? 'bg-night text-gold' : 'bg-white/60 text-brown hover:bg-white/80'; ?>"
                            @click="mobileOpen = false"
                        >
                            <?php echo safe_html($item['label']); ?>
                        </a>
                    <?php endforeach; ?>
                    <a href="<?php echo WEB_ROOT; ?>/booking" class="flex items-center justify-center rounded-xl bg-gold px-4 py-3 text-sm font-semibold text-night hover:bg-gold-light transition-colors" @click="mobileOpen = false">
                        Book Now
                    </a>
                    <a href="<?php echo WEB_ROOT; ?>/admin/login.php" class="flex items-center justify-center rounded-xl border border-gold/30 bg-night/90 px-4 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-gold transition-colors" @click="mobileOpen = false">
                        Admin Portal
                    </a>

                </div>
            </div>
        </nav>
    </div>
</header>

<script>
(() => {
    const header = document.getElementById('site-header');
    if (!header) {
        return;
    }

    const updateHeaderState = () => {
        if (window.scrollY > 20) {
            header.classList.add('is-scrolled');
        } else {
            header.classList.remove('is-scrolled');
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        updateHeaderState();

        if (window.gsap) {
            const timeline = window.gsap.timeline({ defaults: { ease: 'power3.out' } });
            timeline
                .from('.site-logo', { x: -50, opacity: 0, duration: 0.8 })
                .from('.header-link', { y: -20, opacity: 0, stagger: 0.1, duration: 0.5 }, '-=0.5');
        }
    });

    window.addEventListener('scroll', updateHeaderState, { passive: true });
})();
</script>
