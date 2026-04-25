<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\Database;

$pageTitle = 'Sidai Resort Gallery | Luxury Moments in Naroosura';
$pageDescription = 'Explore Sidai Resort gallery: pool, dining, events, rooms, nature, spa, weddings, and parties in a luxury safari setting.';
$pageImage = APP_URL . '/assets/images/hero-sunset.jpg';

$categories = [
    'all' => 'All',
    'pool' => 'Pool',
    'dining' => 'Dining',
    'events' => 'Events',
    'rooms' => 'Rooms',
    'nature' => 'Nature',
    'spa' => 'Spa',
    'weddings' => 'Weddings',
    'parties' => 'Parties',
];

$allowedFilterCategories = array_keys($categories);
$requestedCategory = strtolower(trim((string)($_GET['category'] ?? 'all')));
$initialCategory = in_array($requestedCategory, $allowedFilterCategories, true) ? $requestedCategory : 'all';

$resolveImagePath = static function (?string $rawPath): string {
    $path = trim((string)$rawPath);

    if ($path === '') {
        return '/assets/images/hero-sunset.jpg';
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }

    return '/' . ltrim($path, '/');
};

$toEmbedUrl = static function (string $url): ?string {
    $trimmed = trim($url);
    if ($trimmed === '') {
        return null;
    }

    if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/)([a-zA-Z0-9_-]{6,})~', $trimmed, $matches) === 1) {
        return 'https://www.youtube.com/embed/' . $matches[1];
    }

    if (preg_match('~vimeo\.com/(?:video/)?([0-9]{6,})~', $trimmed, $matches) === 1) {
        return 'https://player.vimeo.com/video/' . $matches[1];
    }

    if (preg_match('~^https://(?:www\.)?(youtube\.com/embed/|player\.vimeo\.com/video/)~', $trimmed) === 1) {
        return $trimmed;
    }

    return null;
};

$defaultItems = [
    ['title' => 'Infinity Pool Retreat', 'description' => 'Poolside luxury with panoramic views.', 'image_path' => '/assets/images/swimming-pool.jpg', 'category' => 'pool', 'is_featured' => 1],
    ['title' => 'Fine Dining Evenings', 'description' => 'Curated culinary moments at Sidai.', 'image_path' => '/assets/images/dining.jpg', 'category' => 'dining', 'is_featured' => 0],
    ['title' => 'Celebration Hall Setup', 'description' => 'Elegant space ready for premium events.', 'image_path' => '/assets/images/conferencing.jpg', 'category' => 'events', 'is_featured' => 1],
    ['title' => 'Luxury Room Ambience', 'description' => 'Comfort-focused spaces for restful stays.', 'image_path' => '/assets/images/conference-suite.jpg', 'category' => 'rooms', 'is_featured' => 0],
    ['title' => 'Golden Hour Landscape', 'description' => 'Naroosura skies and warm savanna tones.', 'image_path' => '/assets/images/hero-sunset.jpg', 'category' => 'nature', 'is_featured' => 1],
    ['title' => 'Wellness Sanctuary', 'description' => 'Spa rituals designed for calm and recovery.', 'image_path' => '/assets/images/spa-wellness.jpg', 'category' => 'spa', 'is_featured' => 0],
    ['title' => 'Wedding Reception Decor', 'description' => 'Refined wedding spaces with tailored styling.', 'image_path' => '/assets/images/hall-enkaji.jpg', 'category' => 'weddings', 'is_featured' => 0],
    ['title' => 'Evening Party Atmosphere', 'description' => 'Festive ambience for birthdays and milestones.', 'image_path' => '/assets/images/pool-lounge.jpg', 'category' => 'parties', 'is_featured' => 0],
];

$galleryItems = [];
$featuredItems = [];
$videoEmbeds = [];
$dbNotice = null;

try {
    $database = Database::getInstance();
    $database->beginTransaction();

    $galleryRows = $database->queryAll(
        'SELECT title, description, image_path, category, sort_order, is_featured
         FROM gallery_items
         WHERE is_active = 1
         ORDER BY sort_order ASC, created_at DESC'
    );

    $featuredRows = $database->queryAll(
        'SELECT title, description, image_path, category, sort_order, is_featured
         FROM gallery_items
         WHERE is_active = 1 AND is_featured = 1
         ORDER BY sort_order ASC, created_at DESC
         LIMIT 3'
    );

    $videoRows = $database->queryAll(
        "SELECT setting_key, setting_value
         FROM site_settings
         WHERE setting_key IN ('gallery_video_1', 'gallery_video_2')"
    );

    $database->commit();

    $galleryItems = $galleryRows;
    $featuredItems = $featuredRows;

    foreach ($videoRows as $videoRow) {
        $embed = $toEmbedUrl((string)($videoRow['setting_value'] ?? ''));
        if ($embed !== null) {
            $videoEmbeds[] = $embed;
        }
    }
} catch (Throwable $exception) {
    if (isset($database)) {
        try {
            $database->rollback();
        } catch (Throwable $rollbackException) {
            log_error('Failed to rollback gallery transaction.', $rollbackException);
        }
    }

    log_error('Failed to load gallery data.', $exception);
    $dbNotice = 'Some gallery records are temporarily unavailable. Curated showcase content is displayed below.';
}

if ($galleryItems === []) {
    $galleryItems = $defaultItems;
}

if ($featuredItems === []) {
    $featuredItems = array_slice(array_values(array_filter($galleryItems, static fn (array $item): bool => (int)($item['is_featured'] ?? 0) === 1)), 0, 3);
}

if ($featuredItems === []) {
    $featuredItems = array_slice($galleryItems, 0, 3);
}

$normalizeItem = static function (array $item) use ($resolveImagePath): array {
    $category = strtolower(trim((string)($item['category'] ?? 'nature')));
    $title = trim((string)($item['title'] ?? 'Sidai Resort Gallery Moment'));
    $description = trim((string)($item['description'] ?? 'Luxury hospitality and memorable experiences at Sidai Resort.'));

    return [
        'title' => $title !== '' ? $title : 'Sidai Resort Gallery Moment',
        'description' => $description !== '' ? $description : 'Luxury hospitality and memorable experiences at Sidai Resort.',
        'image_path' => $resolveImagePath((string)($item['image_path'] ?? '')),
        'category' => $category,
        'is_featured' => (int)($item['is_featured'] ?? 0),
    ];
};

$galleryItems = array_map($normalizeItem, $galleryItems);
$featuredItems = array_map($normalizeItem, $featuredItems);

$heroMosaic = array_slice($galleryItems, 0, 3);
if (count($heroMosaic) < 3) {
    $heroMosaic = array_slice(array_merge($heroMosaic, $defaultItems), 0, 3);
    $heroMosaic = array_map($normalizeItem, $heroMosaic);
}

$galleryPayload = json_encode($galleryItems, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
$initialCategoryPayload = json_encode($initialCategory, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

$cardHeights = ['h-56', 'h-64', 'h-72', 'h-80', 'h-60'];

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>
<main class="pt-28 lg:pt-32">
    <section class="bg-night py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-4 md:grid-cols-3" id="gallery-hero-mosaic">
                <?php foreach ($heroMosaic as $index => $heroItem): ?>
                    <article class="overflow-hidden rounded-2xl border border-gold/20 <?php echo $index === 0 ? 'md:col-span-2 md:row-span-2' : ''; ?>">
                        <img
                            src="<?php echo safe_html($heroItem['image_path']); ?>"
                            alt="<?php echo safe_html($heroItem['title']); ?> in Sidai Resort gallery"
                            class="h-full min-h-[220px] w-full object-cover"
                            <?php echo $index === 0 ? 'fetchpriority="high"' : 'loading="lazy"'; ?>
                        >
                    </article>
                <?php endforeach; ?>
            </div>
            <div class="mt-8 max-w-3xl">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-gold">Sidai Gallery</p>
                <h1 class="mt-3 font-display text-5xl text-cream sm:text-6xl">Moments Worth Revisiting</h1>
                <p class="mt-4 text-base leading-8 text-cream/80 sm:text-lg">Explore visuals from poolside leisure, luxury dining, celebrations, and serene nature experiences at Sidai Resort.</p>
            </div>
        </div>
    </section>

    <?php if ($dbNotice !== null): ?>
        <section class="bg-amber-100/80 py-4">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <p class="rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    <?php echo safe_html($dbNotice); ?>
                </p>
            </div>
        </section>
    <?php endif; ?>

    <section class="bg-cream py-14" x-data="galleryPage()" x-init="init()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 overflow-x-auto">
                <div class="inline-flex min-w-full gap-2 rounded-2xl border border-gold/30 bg-white/70 p-2">
                    <?php foreach ($categories as $key => $label): ?>
                        <?php $href = $key === 'all' ? '/gallery' : '/gallery?category=' . rawurlencode($key); ?>
                        <a
                            href="<?php echo safe_html($href); ?>"
                            @click.prevent="setFilter('<?php echo safe_html($key); ?>')"
                            class="rounded-full px-4 py-2 text-sm font-semibold uppercase tracking-[0.12em] transition"
                            :class="activeFilter === '<?php echo safe_html($key); ?>' ? 'bg-gold text-night shadow' : 'bg-night/80 text-gold hover:bg-night'"
                        >
                            <?php echo safe_html($label); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="columns-2 gap-4 md:columns-3 xl:columns-4" id="gallery-grid">
                <?php foreach ($galleryItems as $index => $item): ?>
                    <?php
                        $heightClass = $cardHeights[$index % count($cardHeights)];
                        $isInitiallyVisible = $initialCategory === 'all' || $item['category'] === $initialCategory;
                    ?>
                    <article
                        class="js-gallery-item group relative mb-4 block break-inside-avoid overflow-hidden rounded-2xl border border-brown/10 bg-white shadow-sm"
                        data-category="<?php echo safe_html($item['category']); ?>"
                        <?php echo $isInitiallyVisible ? '' : 'style="display:none;"'; ?>
                    >
                        <a
                            href="<?php echo safe_html($item['image_path']); ?>"
                            class="gallery-lightbox block"
                            data-gallery="sidai-gallery"
                            data-title="<?php echo safe_html($item['title']); ?>"
                            data-description="<?php echo safe_html($item['description']); ?>"
                        >
                            <img
                                src="/placeholder.svg"
                                data-src="<?php echo safe_html($item['image_path']); ?>"
                                alt="<?php echo safe_html($item['title']); ?> - <?php echo safe_html(ucfirst($item['category'])); ?> at Sidai Resort"
                                class="lazy-gallery-image <?php echo safe_html($heightClass); ?> w-full object-cover transition duration-500 group-hover:scale-[1.03]"
                                loading="lazy"
                            >
                            <noscript>
                                <img
                                    src="<?php echo safe_html($item['image_path']); ?>"
                                    alt="<?php echo safe_html($item['title']); ?> - <?php echo safe_html(ucfirst($item['category'])); ?> at Sidai Resort"
                                    class="<?php echo safe_html($heightClass); ?> w-full object-cover"
                                >
                            </noscript>
                            <span class="absolute inset-0 bg-gradient-to-t from-night/75 via-night/20 to-transparent opacity-0 transition duration-300 group-hover:opacity-100"></span>
                            <span class="absolute bottom-0 left-0 right-0 p-4 opacity-0 transition duration-300 group-hover:opacity-100">
                                <span class="block text-sm font-semibold text-cream"><?php echo safe_html($item['title']); ?></span>
                                <span class="mt-1 inline-flex rounded-full bg-gold/90 px-3 py-1 text-xs font-semibold uppercase tracking-[0.12em] text-night"><?php echo safe_html($categories[$item['category']] ?? ucfirst($item['category'])); ?></span>
                            </span>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>

            <p class="mt-6 hidden rounded-xl border border-brown/20 bg-white/80 px-4 py-3 text-sm text-brown/80" x-show="visibleCount === 0" x-cloak>
                No gallery items found for this category.
            </p>
        </div>
    </section>

    <section class="bg-white py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-earth">Featured</p>
                    <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">Sidai Highlights</h2>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-[1fr_1.35fr_1fr]" id="featured-gallery">
                <?php foreach (array_slice($featuredItems, 0, 3) as $index => $featuredItem): ?>
                    <?php $direction = $index === 1 ? 'center' : ($index === 0 ? 'left' : 'right'); ?>
                    <article class="js-featured-item overflow-hidden rounded-2xl border border-gold/25 bg-night/95 text-cream shadow-lg" data-direction="<?php echo safe_html($direction); ?>">
                        <a
                            href="<?php echo safe_html($featuredItem['image_path']); ?>"
                            class="gallery-lightbox block"
                            data-gallery="featured-gallery"
                            data-title="<?php echo safe_html($featuredItem['title']); ?>"
                            data-description="<?php echo safe_html($featuredItem['description']); ?>"
                        >
                            <img
                                src="<?php echo safe_html($featuredItem['image_path']); ?>"
                                alt="<?php echo safe_html($featuredItem['title']); ?> featured at Sidai Resort"
                                class="h-72 w-full object-cover"
                                loading="lazy"
                            >
                            <div class="p-5">
                                <p class="text-xs uppercase tracking-[0.18em] text-gold"><?php echo safe_html($categories[$featuredItem['category']] ?? ucfirst($featuredItem['category'])); ?></p>
                                <h3 class="mt-2 font-display text-3xl"><?php echo safe_html($featuredItem['title']); ?></h3>
                                <p class="mt-2 text-sm text-cream/80"><?php echo safe_html($featuredItem['description']); ?></p>
                            </div>
                        </a>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if ($videoEmbeds !== []): ?>
        <section class="bg-cream py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mb-8">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-earth">Video Tour</p>
                    <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">Sidai in Motion</h2>
                </div>
                <div class="grid gap-6 <?php echo count($videoEmbeds) > 1 ? 'lg:grid-cols-2' : ''; ?>">
                    <?php foreach (array_slice($videoEmbeds, 0, 2) as $videoIndex => $embedUrl): ?>
                        <article class="overflow-hidden rounded-2xl border border-brown/10 bg-white shadow-sm">
                            <div class="relative pb-[56.25%]">
                                <iframe
                                    src="<?php echo safe_html($embedUrl); ?>"
                                    class="absolute inset-0 h-full w-full border-0"
                                    title="Sidai Resort video showcase <?php echo safe_html((string)($videoIndex + 1)); ?>"
                                    loading="lazy"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    <?php endif; ?>
</main>

<script>
const SIDAI_GALLERY_DATA = <?php echo $galleryPayload ?: '[]'; ?>;
const SIDAI_GALLERY_INITIAL_CATEGORY = <?php echo $initialCategoryPayload ?: '"all"'; ?>;

function galleryPage() {
    return {
        activeFilter: SIDAI_GALLERY_INITIAL_CATEGORY,
        visibleCount: 0,

        init() {
            this.applyFilter(false);
        },

        setFilter(filter) {
            this.activeFilter = filter;
            this.applyFilter(true);

            const url = new URL(window.location.href);
            if (filter === 'all') {
                url.searchParams.delete('category');
            } else {
                url.searchParams.set('category', filter);
            }

            history.replaceState({}, '', url);
        },

        applyFilter(animate) {
            const items = Array.from(document.querySelectorAll('.js-gallery-item'));
            const visibleItems = [];
            const hiddenItems = [];

            items.forEach((item) => {
                const category = item.dataset.category || '';
                const match = this.activeFilter === 'all' || category === this.activeFilter;

                if (match) {
                    item.style.display = 'block';
                    visibleItems.push(item);
                } else {
                    hiddenItems.push(item);
                }
            });

            this.visibleCount = visibleItems.length;

            if (animate && window.gsap) {
                if (hiddenItems.length > 0) {
                    window.gsap.to(hiddenItems, {
                        opacity: 0,
                        scale: 0.94,
                        y: 10,
                        duration: 0.2,
                        stagger: 0.02,
                        onComplete: () => {
                            hiddenItems.forEach((item) => {
                                item.style.display = 'none';
                            });
                        },
                    });
                }

                if (visibleItems.length > 0) {
                    window.gsap.fromTo(
                        visibleItems,
                        { opacity: 0, scale: 0.94, y: 12 },
                        { opacity: 1, scale: 1, y: 0, duration: 0.3, stagger: 0.04, ease: 'power2.out' }
                    );
                }
            } else {
                hiddenItems.forEach((item) => {
                    item.style.display = 'none';
                });
            }

            if (!animate && window.gsap && visibleItems.length > 0) {
                window.gsap.fromTo(
                    visibleItems,
                    { opacity: 0.98, scale: 0.99 },
                    { opacity: 1, scale: 1, duration: 0.12, stagger: 0.01, ease: 'power1.out' }
                );
            }
        },
    };
}

document.addEventListener('DOMContentLoaded', () => {
    const lazyImages = document.querySelectorAll('img[data-src]');

    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) {
                return;
            }

            const image = entry.target;
            const realSource = image.getAttribute('data-src');
            if (realSource) {
                image.setAttribute('src', realSource);
                image.removeAttribute('data-src');
            }

            observer.unobserve(image);
        });
    }, { rootMargin: '120px 0px' });

    lazyImages.forEach((image) => imageObserver.observe(image));

    if (window.gsap && window.ScrollTrigger) {
        window.gsap.utils.toArray('.js-gallery-item').forEach((card) => {
            window.gsap.fromTo(
                card,
                { opacity: 0, scale: 0.96, y: 16 },
                {
                    opacity: 1,
                    scale: 1,
                    y: 0,
                    duration: 0.35,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 90%',
                    },
                }
            );
        });

        window.gsap.fromTo(
            '#gallery-hero-mosaic article',
            { opacity: 0, y: 20, scale: 0.97 },
            { opacity: 1, y: 0, scale: 1, duration: 0.45, stagger: 0.1, ease: 'power2.out' }
        );

        const featuredCards = document.querySelectorAll('.js-featured-item');
        featuredCards.forEach((card) => {
            const direction = card.getAttribute('data-direction');
            const fromX = direction === 'left' ? -36 : direction === 'right' ? 36 : 0;

            window.gsap.fromTo(
                card,
                { opacity: 0, x: fromX, y: 18 },
                {
                    opacity: 1,
                    x: 0,
                    y: 0,
                    duration: 0.5,
                    ease: 'power2.out',
                    scrollTrigger: {
                        trigger: card,
                        start: 'top 88%',
                    },
                }
            );
        });
    }
});
</script>

<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>
