<?php declare(strict_types=1);

$currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$navItems = [
    ['label' => 'Home', 'href' => WEB_ROOT . '/'],
    ['label' => 'Services', 'href' => WEB_ROOT . '/services'],
    ['label' => 'Accommodation', 'href' => WEB_ROOT . '/rooms'],
    ['label' => 'Menus', 'href' => WEB_ROOT . '/menu'],
    ['label' => 'About Us', 'href' => WEB_ROOT . '/about'],
];

$isActive = static function (string $href) use ($currentPath): bool {
    if ($href === '/') {
        return $currentPath === '/';
    }

    $normalizedHref = preg_replace('/\.php$/', '', $href) ?: $href;

    return $currentPath === $normalizedHref || $currentPath === $href;
};
?>
<div id="page-loader" class="page-loader" role="status" aria-live="polite">
    <div class="loader-mark text-center">
        <p class="loader-logo font-display text-5xl text-gold">SIDAI</p>
        <p class="loader-copy font-light uppercase tracking-[0.5em] text-brown mt-3">Where Good Meets Luxury</p>
    </div>
</div>

<header id="site-header" x-data="{ mobileOpen: false }" class="fixed top-0 inset-x-0 z-50 transition-all duration-300">
    <div class="mx-auto max-w-7xl px-3 sm:px-6 lg:px-8">
        <nav class="mt-3 sm:mt-4 rounded-2xl border border-gold/30 bg-cream/70 px-3 sm:px-4 py-2.5 sm:py-3 backdrop-blur-lg shadow-lg" aria-label="Primary">
            <div class="flex items-center justify-between gap-2 sm:gap-4">
                <a href="<?php echo WEB_ROOT; ?>/" class="flex items-center gap-2 sm:gap-3 min-w-0" aria-label="Sidai Resort Home">
                    <span class="inline-flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-gradient-to-br from-gold to-gold-dark text-night font-bold text-lg sm:text-xl flex-shrink-0">S</span>
                        <span class="block font-display text-xl sm:text-2xl text-brown truncate">Sidai Resort</span>
                        <span class="block text-[8px] sm:text-[10px] uppercase tracking-[0.2em] sm:tracking-[0.3em] text-forest truncate mt-1">Nothing But the Best</span>
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
                .from('#site-header nav', { y: -30, opacity: 0, duration: 0.7 })
                .from('.header-link', { y: -12, opacity: 0, stagger: 0.05, duration: 0.35 }, '-=0.35');
        }
    });

    window.addEventListener('scroll', updateHeaderState, { passive: true });
})();
</script>
