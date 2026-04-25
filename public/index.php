<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\CSRF;
use App\Core\Database;

$pageTitle = 'Sidai Resort | Where Good Meets Luxury';
$pageDescription = 'Sidai Resort. Luxury safari stays, event halls, pool sessions, fine dining, and unforgettable celebrations.';
$pageImage = APP_URL . '/assets/images/hero-sunset.jpg';

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

$dbNotice = null;

$galleryRows = [];
$testimonialRows = [];

$galleryFallback = [
    [
        'title' => 'Golden Sunset Over Sidai Resort',
        'description' => 'A signature evening view across the resort grounds.',
        'image_path' => '/assets/images/hero-sunset.jpg',
        'category' => 'nature',
    ],
    [
        'title' => 'Executive Conference Experience',
        'description' => 'Host high-impact meetings in refined spaces.',
        'image_path' => '/assets/images/conferencing.jpg',
        'category' => 'events',
    ],
    [
        'title' => 'Poolside Luxury Moments',
        'description' => 'Refresh in our serene swimming pool retreat.',
        'image_path' => '/assets/images/swimming-pool.jpg',
        'category' => 'pool',
    ],
    [
        'title' => 'Fine Dining Ambiance',
        'description' => 'Maasai-inspired and international cuisine prepared with care.',
        'image_path' => '/assets/images/dining.jpg',
        'category' => 'dining',
    ],
    [
        'title' => 'Serene Enkima Fire',
        'description' => 'Experience our signature bonfire under the stars.',
        'image_path' => '/assets/images/hero-section.jpg',
        'category' => 'nature',
    ],
    [
        'title' => 'Celebration-Ready Spaces',
        'description' => 'Elegant venues for weddings and milestone moments.',
        'image_path' => '/assets/images/conferencing.jpg',
        'category' => 'weddings',
    ],
];

$testimonialFallback = [
    [
        'guest_name' => 'Achieng Owuor',
        'rating' => 5,
        'message' => 'Sidai Resort made our family celebration feel effortless and luxurious from start to finish.',
    ],
    [
        'guest_name' => 'Daniel Kimani',
        'rating' => 5,
        'message' => 'The conference setup, hospitality, and dining exceeded expectations for our leadership retreat.',
    ],
    [
        'guest_name' => 'Grace Njeri',
        'rating' => 5,
        'message' => 'From the pool to the spa and evening bonfire, every moment felt premium and warm.',
    ],
];

try {
    $database = Database::getInstance();
    $database->beginTransaction();

    $galleryRows = $database->queryAll(
        'SELECT title, description, image_path, category
         FROM gallery_items
         WHERE is_active = 1
         ORDER BY is_featured DESC, sort_order ASC, created_at DESC
         LIMIT 12'
    );

    $testimonialRows = $database->queryAll(
        'SELECT guest_name, rating, message
         FROM testimonials
         WHERE is_approved = 1
         ORDER BY is_featured DESC, created_at DESC
         LIMIT 10'
    );

    $database->commit();
} catch (Throwable $exception) {
    if (isset($database)) {
        try {
            $database->rollback();
        } catch (Throwable $rollbackException) {
            log_error('Failed to rollback homepage data transaction.', $rollbackException);
        }
    }

    log_error('Homepage data retrieval failed.', $exception);
    $dbNotice = 'Some live content is temporarily unavailable. You can still explore Sidai Resort experiences below.';
}

$galleryItems = $galleryRows !== [] ? $galleryRows : $galleryFallback;
$testimonials = $testimonialRows !== [] ? $testimonialRows : $testimonialFallback;

$galleryPreview = array_slice($galleryItems, 0, 6);

$eventHighlights = [
    [
        'title' => 'Weddings & Receptions',
        'description' => 'Breathtaking ceremony and reception setups with custom decor support.',
        'image' => '/assets/images/conferencing.jpg',
    ],
    [
        'title' => 'Birthday Celebrations',
        'description' => 'Private and shared packages for unforgettable birthdays.',
        'image' => '/assets/images/dining.jpg',
    ],
    [
        'title' => 'Corporate Events',
        'description' => 'Professional conferencing with full hospitality and catering.',
        'image' => '/assets/images/conferencing.jpg',
    ],
    [
        'title' => 'Music Video Shoots',
        'description' => 'Cinematic indoor and outdoor backdrops for creators and production teams.',
        'image' => '/assets/images/hero-sunset.jpg',
    ],
    [
        'title' => 'Pool Parties',
        'description' => 'Lively poolside celebrations with dining and lounge service.',
        'image' => '/assets/images/swimming-pool.jpg',
    ],
    [
        'title' => 'Graduations & Milestones',
        'description' => "Elegant event styling for life's big achievements.",
        'image' => '/assets/images/hero-section.jpg',
    ],
];

$signatureMenuItems = [
    [
        'name' => 'Nyama Choma Signature Platter',
        'description' => 'Premium grilled cuts with seasonal accompaniments.',
        'price' => 3500,
        'image' => '/assets/images/dining.jpg',
    ],
    [
        'name' => 'Savanna Sunset Mocktail',
        'description' => 'A refreshing tropical blend inspired by Maasai sunset tones.',
        'price' => 850,
        'image' => '/assets/images/hero-sunset.jpg',
    ],
    [
        'name' => 'Sidai Breakfast Experience',
        'description' => 'A curated breakfast board with local and international flavors.',
        'price' => 2200,
        'image' => '/assets/images/swimming-pool.jpg',
    ],
];

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>
<main class="pt-28 lg:pt-32">
    <section id="hero" class="relative isolate flex min-h-[100dvh] items-center overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="/assets/images/hero-section.jpg"
                alt="Sidai Resort view"
                class="h-full w-full object-cover"
                fetchpriority="high"
            >
            <div class="absolute inset-0 bg-gradient-to-br from-night/80 via-night/45 to-earth/50"></div>
            <div id="hero-particles" class="particles-canvas" aria-hidden="true"></div>
        </div>

        <div class="relative z-10 mx-auto grid w-full max-w-7xl gap-8 px-4 pb-10 pt-20 sm:px-6 lg:grid-cols-[1fr_auto] lg:px-8">
            <div class="max-w-3xl">
                <p class="mb-4 inline-flex items-center rounded-full border border-gold/40 bg-night/50 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-gold">Sidai Resort</p>
                <h1 class="font-display text-5xl leading-[0.92] text-white sm:text-6xl lg:text-8xl" data-aos="fade-up">
                    Where Good Meets Luxury
                </h1>
                <p class="mt-5 max-w-2xl text-base text-cream sm:text-lg" data-aos="fade-up" data-aos-delay="120">
                    Experience warm Maasai hospitality, premium event halls, serene pool sessions, and timeless safari elegance in the heart of Narok County.
                </p>

                <div class="mt-6 flex flex-wrap items-center gap-3 text-sm text-cream" data-aos="fade-up" data-aos-delay="180">
                    <span class="font-medium text-gold">Now Featuring:</span>
                    <span class="relative inline-flex min-h-[1.8rem] items-center rounded-full border border-gold/40 bg-night/60 px-4 py-1 font-semibold text-gold">
                        <span id="hero-typewriter">Weddings</span>
                    </span>
                </div>

                <div class="mt-10 flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="240">
                    <a href="/booking" class="inline-flex items-center rounded-full bg-gold px-7 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-night transition hover:bg-gold-light">Book Your Stay</a>
                    <a href="#about" class="inline-flex items-center rounded-full border border-gold/60 bg-transparent px-7 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-gold transition hover:bg-gold hover:text-night">Explore Resort</a>
                </div>
            </div>

            <a href="#stats" class="group mt-auto hidden items-center gap-2 self-end justify-self-end rounded-full border border-gold/40 bg-night/40 px-4 py-2 text-xs uppercase tracking-[0.25em] text-gold lg:inline-flex" aria-label="Scroll to statistics">
                Scroll
                <span class="inline-block animate-bounce">&darr;</span>
            </a>
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

    <section id="stats" class="bg-night py-10">
        <div class="mx-auto grid max-w-7xl gap-4 px-4 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center" data-aos="fade-up">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="2" data-counter-suffix="+">0</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Event Halls</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center" data-aos="fade-up" data-aos-delay="80">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="100" data-counter-format="infinity">0</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Memories</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center" data-aos="fade-up" data-aos-delay="140">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="5" data-counter-suffix="&#9733;">0</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Experiences</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="100" data-counter-suffix="%">0</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Maasai Heritage</p>
            </article>
        </div>
    </section>

    <section id="about" class="bg-cream py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div class="relative overflow-hidden rounded-3xl shadow-2xl" data-aos="fade-right">
                <img
                    src="/assets/images/swimming-pool.jpg"
                    alt="Poolside view at Sidai Resort"
                    class="h-full w-full object-cover"
                    loading="lazy"
                    data-parallax-speed="0.18"
                >
            </div>
            <div data-aos="fade-left">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-earth">The Sidai Story</p>
                <h2 class="mt-3 font-display text-4xl text-brown sm:text-5xl">Luxury rooted in culture, crafted for celebration.</h2>
                <p class="mt-5 text-base leading-8 text-brown/90 sm:text-lg">
                    Sidai Resort is a destination where guests can relax, connect, and celebrate in style. From sunrise nature walks to evening bonfires, every experience reflects the warmth of Narok and the richness of Maasai identity.
                </p>
                <p class="mt-4 text-base leading-8 text-brown/90 sm:text-lg">
                    Whether you are planning a quiet escape, a high-profile corporate event, or a cinematic music shoot, Sidai welcomes you with premium spaces, thoughtful service, and unforgettable atmosphere.
                </p>
                <div class="mt-8 rounded-2xl border border-gold/40 bg-white p-6 shadow-sm">
                    <p class="text-sm uppercase tracking-[0.2em] text-earth">Maasai Meaning</p>
                    <p class="mt-2 font-display text-3xl text-forest">"Sidai" = Good</p>
                    <p class="mt-2 text-sm text-brown/80">Good service. Good moments. Good memories that last.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-earth">Experiences</p>
                    <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">Signature Services</h2>
                </div>
                <a href="/services" class="text-sm font-semibold uppercase tracking-[0.2em] text-forest hover:text-gold">Explore all services &rarr;</a>
            </div>

            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <a href="/services#pool" class="group overflow-hidden rounded-2xl border border-brown/10 bg-cream/50 transition hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up">
                    <img src="/assets/images/swimming-pool.jpg" alt="Swimming pool sessions at Sidai Resort" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="p-5">
                        <h3 class="font-display text-3xl text-brown">Swimming Pool</h3>
                        <p class="mt-2 text-sm text-brown/80">Book serene pool sessions with optional lounge and beverage service.</p>
                    </div>
                </a>
                <a href="/services#halls" class="group overflow-hidden rounded-2xl border border-brown/10 bg-cream/50 transition hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="60">
                    <img src="/assets/images/conferencing.jpg" alt="Event hall setup at Sidai Resort" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="p-5">
                        <h3 class="font-display text-3xl text-brown">Event Halls</h3>
                        <p class="mt-2 text-sm text-brown/80">Two spacious halls for weddings, graduations, parties, and corporate functions.</p>
                    </div>
                </a>
                <a href="/services#dining" class="group overflow-hidden rounded-2xl border border-brown/10 bg-cream/50 transition hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="120">
                    <img src="/assets/images/dining.jpg" alt="Fine dining setup at Sidai Resort" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="p-5">
                        <h3 class="font-display text-3xl text-brown">Fine Dining</h3>
                        <p class="mt-2 text-sm text-brown/80">Pre-order curated Kenyan, Maasai-inspired, and international dishes.</p>
                    </div>
                </a>
                <a href="/services#activities" class="group overflow-hidden rounded-2xl border border-brown/10 bg-cream/50 transition hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="180">
                    <img src="/assets/images/hero-section.jpg" alt="Playground and activities" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="p-5">
                        <h3 class="font-display text-3xl text-brown">Playground</h3>
                        <p class="mt-2 text-sm text-brown/80">Engage in thrilling outdoor adventures and serene nature walks.</p>
                    </div>
                </a>
                <a href="/services#music-shoots" class="group overflow-hidden rounded-2xl border border-brown/10 bg-cream/50 transition hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="240">
                    <img src="/assets/images/hero-sunset.jpg" alt="Music and film shoot location" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="p-5">
                        <h3 class="font-display text-3xl text-brown">Music Shoots</h3>
                        <p class="mt-2 text-sm text-brown/80">Premium indoor and outdoor locations for creative productions.</p>
                    </div>
                </a>
                <a href="/services#conference" class="group overflow-hidden rounded-2xl border border-brown/10 bg-cream/50 transition hover:-translate-y-1 hover:shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <img src="/assets/images/conferencing.jpg" alt="Conference and business facilities" class="h-52 w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                    <div class="p-5">
                        <h3 class="font-display text-3xl text-brown">Conference</h3>
                        <p class="mt-2 text-sm text-brown/80">Professional packages for meetings, launches, and strategy sessions.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <section id="quick-book" class="bg-gradient-to-br from-gold to-gold-light py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl bg-night/90 p-8 text-cream shadow-2xl" x-data="{ type: 'room' }">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <h2 class="font-display text-4xl">Quick Booking</h2>
                    <p class="text-sm uppercase tracking-[0.2em] text-gold">Instant Availability Check</p>
                </div>

                <form action="/booking" method="get" class="mt-8 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <div>
                        <label for="quick-type" class="mb-2 block text-xs uppercase tracking-[0.15em] text-gold">Type</label>
                        <select id="quick-type" name="type" x-model="type" class="w-full rounded-xl border border-gold/40 bg-white/10 px-3 py-3 text-sm text-cream focus:outline-none focus:ring-2 focus:ring-gold">
                            <option value="room">Room Stay</option>
                            <option value="pool">Pool Day Pass</option>
                            <option value="hall">Hall Hire</option>
                            <option value="dining">Dining Reservation</option>
                            <option value="spa">Spa</option>
                            <option value="music_shoot">Music Shoot</option>
                        </select>
                    </div>

                    <div>
                        <label for="quick-date" class="mb-2 block text-xs uppercase tracking-[0.15em] text-gold">Date</label>
                        <input id="quick-date" name="date" type="text" class="js-flatpickr w-full rounded-xl border border-gold/40 bg-white/10 px-3 py-3 text-sm text-cream placeholder:text-cream/60 focus:outline-none focus:ring-2 focus:ring-gold" placeholder="Select date" autocomplete="off" required>
                    </div>

                    <div>
                        <label for="quick-guests" class="mb-2 block text-xs uppercase tracking-[0.15em] text-gold">Guests</label>
                        <input id="quick-guests" name="guests" type="number" min="1" max="500" value="2" class="w-full rounded-xl border border-gold/40 bg-white/10 px-3 py-3 text-sm text-cream focus:outline-none focus:ring-2 focus:ring-gold" required>
                    </div>

                    <div x-show="type === 'hall'" x-transition>
                        <label for="quick-event" class="mb-2 block text-xs uppercase tracking-[0.15em] text-gold">Event Type</label>
                        <input id="quick-event" name="event_type" type="text" class="w-full rounded-xl border border-gold/40 bg-white/10 px-3 py-3 text-sm text-cream placeholder:text-cream/60 focus:outline-none focus:ring-2 focus:ring-gold" placeholder="Wedding, conference...">
                    </div>

                    <div class="self-end">
                        <button type="submit" class="w-full rounded-xl bg-gold px-4 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-night transition hover:bg-gold-light">Check Availability</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <section id="gallery-preview" class="bg-cream py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-earth">Gallery</p>
                    <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">A glimpse of Sidai</h2>
                </div>
                <a href="/about#gallery" class="text-sm font-semibold uppercase tracking-[0.2em] text-forest hover:text-gold">View Full Gallery &rarr;</a>
            </div>

            <div class="columns-1 gap-4 sm:columns-2 lg:columns-3">
                <?php foreach ($galleryPreview as $item): ?>
                    <?php
                        $title = trim((string)($item['title'] ?? 'Sidai Resort Moment'));
                        $description = trim((string)($item['description'] ?? 'Luxury hospitality at Sidai Resort.'));
                        $category = trim((string)($item['category'] ?? 'resort'));
                        $imageUrl = $resolveImagePath((string)($item['image_path'] ?? ''));
                    ?>
                    <a
                        href="<?php echo safe_html($imageUrl); ?>"
                        class="gallery-lightbox mb-4 block overflow-hidden rounded-2xl border border-brown/10 bg-white shadow-sm"
                        data-gallery="home-gallery"
                        data-title="<?php echo safe_html($title); ?>"
                        data-description="<?php echo safe_html($description); ?>"
                    >
                        <img
                            src="<?php echo safe_html($imageUrl); ?>"
                            alt="<?php echo safe_html($title . ' - ' . ucfirst($category)); ?>"
                            class="w-full object-cover transition duration-500 hover:scale-[1.03]"
                            loading="lazy"
                            data-reveal-image
                        >
                        <span class="block px-4 py-3 text-xs uppercase tracking-[0.2em] text-earth"><?php echo safe_html($title); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="events-highlight" class="bg-night py-20 text-cream">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-gold">Events</p>
                    <h2 class="mt-2 font-display text-4xl sm:text-5xl">Celebrate without limits</h2>
                </div>
                <a href="/events" class="text-sm font-semibold uppercase tracking-[0.2em] text-gold hover:text-gold-light">Plan Your Event &rarr;</a>
            </div>

            <div class="swiper events-swiper" data-swiper-events>
                <div class="swiper-wrapper">
                    <?php foreach ($eventHighlights as $event): ?>
                        <article class="swiper-slide overflow-hidden rounded-2xl border border-gold/20 bg-white/5">
                            <img src="<?php echo safe_html($event['image']); ?>" alt="<?php echo safe_html($event['title']); ?> at Sidai Resort" class="h-52 w-full object-cover" loading="lazy">
                            <div class="p-5">
                                <h3 class="font-display text-3xl text-gold"><?php echo safe_html($event['title']); ?></h3>
                                <p class="mt-3 text-sm leading-7 text-cream/80"><?php echo safe_html($event['description']); ?></p>
                                <a href="/about#contact" class="mt-5 inline-flex text-sm font-semibold uppercase tracking-[0.16em] text-gold hover:text-gold-light">Enquire &rarr;</a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 flex justify-center gap-2">
                    <div class="events-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="menu-teaser" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 flex flex-wrap items-end justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-earth">Dining</p>
                    <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">Taste the Savanna</h2>
                </div>
                <a href="/menu" class="text-sm font-semibold uppercase tracking-[0.2em] text-forest hover:text-gold">View Full Menu & Order &rarr;</a>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <?php foreach ($signatureMenuItems as $item): ?>
                    <article class="overflow-hidden rounded-2xl border border-brown/10 bg-cream/60" data-aos="fade-up">
                        <img src="<?php echo safe_html($item['image']); ?>" alt="<?php echo safe_html($item['name']); ?>" class="h-52 w-full object-cover" loading="lazy">
                        <div class="p-5">
                            <h3 class="font-display text-3xl text-brown"><?php echo safe_html($item['name']); ?></h3>
                            <p class="mt-2 text-sm leading-7 text-brown/80"><?php echo safe_html($item['description']); ?></p>
                            <p class="mt-4 text-sm font-semibold uppercase tracking-[0.15em] text-earth"><?php echo safe_html(format_kes((float)$item['price'])); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php include APP_PATH . '/includes/elements.php'; ?>

    <section id="testimonials" class="bg-gradient-to-br from-forest to-night py-20 text-cream">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-gold">Guest Stories</p>
                <h2 class="mt-2 font-display text-4xl sm:text-5xl">Voices from Sidai</h2>
            </div>

            <div class="swiper testimonials-swiper" data-swiper-testimonials>
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $entry): ?>
                        <?php
                            $guestName = trim((string)($entry['guest_name'] ?? 'Sidai Guest'));
                            $rating = max(1, min(5, (int)($entry['rating'] ?? 5)));
                            $message = trim((string)($entry['message'] ?? 'We had an unforgettable experience at Sidai Resort.'));
                        ?>
                        <article class="swiper-slide h-auto rounded-2xl border border-gold/30 bg-white/10 p-8 backdrop-blur-sm">
                            <div class="mb-4 flex items-center gap-1 text-gold" aria-label="<?php echo safe_html((string)$rating); ?> star rating">
                                <?php for ($i = 0; $i < $rating; $i++): ?>
                                    <span>&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <p class="text-base leading-8 text-cream/90">"<?php echo safe_html($message); ?>"</p>
                            <p class="mt-6 text-sm font-semibold uppercase tracking-[0.2em] text-gold"><?php echo safe_html($guestName); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 flex justify-center">
                    <div class="testimonials-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <section id="location" class="bg-cream py-20">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div data-aos="fade-right">
                <p class="text-sm font-semibold uppercase tracking-[0.25em] text-earth">Find Us</p>
                <h2 class="mt-2 font-display text-4xl text-brown sm:text-5xl">Naroosura, Narok County</h2>
                <p class="mt-4 text-base leading-8 text-brown/85">
                    Sidai Resort is positioned for both business and leisure, with scenic access routes, tranquil surroundings, and premium on-site experiences.
                </p>

                <div class="mt-6 space-y-2 text-sm text-brown/90">
                    <p><strong>Email:</strong> <a href="mailto:<?php echo safe_html(APP_EMAIL); ?>" class="text-forest hover:text-gold"><?php echo safe_html(APP_EMAIL); ?></a></p>
                    <p><strong>Phone:</strong> <a href="tel:<?php echo safe_html(APP_PHONE); ?>" class="text-forest hover:text-gold"><?php echo safe_html(APP_PHONE); ?></a></p>
                    <p><strong>Address:</strong> <?php echo safe_html(APP_ADDRESS); ?></p>
                </div>

                <?php
                    $whatsAppMessage = rawurlencode('Hello Sidai Resort, I would like to make a booking enquiry.');
                    $whatsAppUrl = SOCIAL_WHATSAPP . '?text=' . $whatsAppMessage;
                ?>
                <a href="<?php echo safe_html($whatsAppUrl); ?>" target="_blank" rel="noopener noreferrer" class="mt-8 inline-flex rounded-full bg-forest px-6 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-cream transition hover:bg-forest-light">
                    Chat on WhatsApp
                </a>
            </div>

            <div class="overflow-hidden rounded-3xl border border-brown/10 shadow-xl" data-aos="fade-left">
                <iframe
                    title="Sidai Resort location map"
                    src="https://www.google.com/maps?q=Naroosura%2C%20Narok%20County%2C%20Kenya&output=embed"
                    class="h-[420px] w-full border-0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    </section>

    <section id="newsletter-cta" class="bg-gradient-to-r from-gold to-gold-light py-16">
        <div class="mx-auto max-w-5xl px-4 text-center sm:px-6 lg:px-8">
            <h2 class="font-display text-4xl text-night sm:text-5xl">Get exclusive offers straight to your inbox</h2>
            <p class="mx-auto mt-4 max-w-2xl text-sm leading-7 text-night/85 sm:text-base">
                Join the Sidai circle for curated packages, event offers, and seasonal experiences.
            </p>

            <form id="home-newsletter-form" action="/api/newsletter-subscribe" method="post" class="mx-auto mt-8 grid max-w-2xl gap-3 sm:grid-cols-[1fr_auto]">
                <input type="hidden" name="home_newsletter_form" value="1">
                <?php echo CSRF::field(); ?>
                <input
                    id="home-newsletter-email"
                    name="email"
                    type="email"
                    required
                    class="w-full rounded-2xl border border-night/20 bg-white px-4 py-3 text-sm text-night focus:outline-none focus:ring-2 focus:ring-night"
                    placeholder="Enter your email"
                    aria-label="Email address"
                >
                <button type="submit" class="rounded-2xl bg-night px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-gold transition hover:bg-brown">Subscribe</button>
            </form>
            <p id="home-newsletter-feedback" class="mt-3 text-sm text-night/80" aria-live="polite"></p>
            <noscript>
                <p class="mt-4 text-sm text-night/80">JavaScript is disabled. You can still subscribe via the footer newsletter form.</p>
            </noscript>
        </div>
    </section>
</main>

<script>
(() => {
    const form = document.getElementById('home-newsletter-form');
    const feedback = document.getElementById('home-newsletter-feedback');

    if (!form || !feedback) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        feedback.textContent = 'Submitting...';
        feedback.className = 'mt-3 text-sm text-night/80';

        try {
            const formData = new FormData(form);

            const response = await fetch('/api/newsletter-subscribe', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            const data = await response.json();

            if (!response.ok || data.success !== true) {
                throw new Error(data.message || 'Subscription failed.');
            }

            feedback.textContent = data.message || 'Subscription successful.';
            feedback.className = 'mt-3 text-sm text-green-700';
            form.reset();
        } catch (error) {
            feedback.textContent = error instanceof Error ? error.message : 'Something went wrong. Please try again.';
            feedback.className = 'mt-3 text-sm text-red-700';
        }
    });
})();
</script>

<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>

