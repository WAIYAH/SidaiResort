<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\Database;

$pageTitle = 'Services & Outdoor Experiences | Enkiu Lounge & Enkima Bonfire | Sidai Resort';
$pageDescription = "Explore Sidai Resort services: swimming pool, fine dining, and outdoor experiences like Eoshet retreats, Enkiu lounge, and our signature Enkima bonfire under the stars. Nothing but the Best.";
$pageImage = APP_URL . '/assets/images/conference-suite.jpg';

$settings = [];
$dbNotice = null;

try {
    $database = Database::getInstance();
    $database->beginTransaction();

    $rows = $database->queryAll(
        'SELECT setting_key, setting_value
         FROM site_settings'
    );

    $database->commit();

    foreach ($rows as $row) {
        $key = trim((string)($row['setting_key'] ?? ''));
        if ($key === '') {
            continue;
        }

        $settings[$key] = (string)($row['setting_value'] ?? '');
    }
} catch (Throwable $exception) {
    if (isset($database)) {
        try {
            $database->rollback();
        } catch (Throwable $rollbackException) {
            log_error('Failed to rollback services settings transaction.', $rollbackException);
        }
    }

    log_error('Failed to load services settings.', $exception);
    $dbNotice = 'Live pricing settings are temporarily unavailable. Displayed prices use fallback defaults.';
}

$settingText = static function (string $key, string $default) use ($settings): string {
    $value = trim($settings[$key] ?? '');
    return $value !== '' ? $value : $default;
};

$settingAmount = static function (string $key, float $default) use ($settings): float {
    $value = trim($settings[$key] ?? '');
    if ($value === '' || !is_numeric($value)) {
        return $default;
    }

    return (float)$value;
};

/**
 * Pool access details and rules.
 * Handled via global DB setting or defaults.
 */
$poolDetails = [
    'hours' => $settingText('pool_hours', '8:00 AM - 6:00 PM daily'),
    'capacity' => (int)$settingAmount('pool_capacity', 30),
    'adult_day_pass' => $settingAmount('pool_day_pass_adult', 300),
    'child_day_pass' => $settingAmount('pool_day_pass_child', 150),
    'rules' => [
        'Appropriate swimwear is required for all pool users.',
        'Children under 12 must be accompanied by a guardian.',
        'Outside food and beverages are not permitted in pool areas.',
        'Poolside lounge access is subject to booking package tier.',
    ],
];

$hallPackages = [
    [
        'key' => 'hall_1',
        'name' => $settingText('hall_1_name', 'Enkaji Grand Hall'),
        'capacity' => (int)$settingAmount('hall_1_capacity', 320),
        'dimensions' => $settingText('hall_1_dimensions', '24m x 18m'),
        'setups' => ['Theatre', 'Classroom', 'Banquet', 'Cocktail'],
        'full_day' => $settingAmount('hall_1_full_day', 180000),
        'half_day' => $settingAmount('hall_1_half_day', 110000),
        'evening' => $settingAmount('hall_1_evening', 95000),
        'image' => '/assets/images/hall-enkaji.jpg',
    ],
    [
        'key' => 'hall_2',
        'name' => $settingText('hall_2_name', 'Olkeri Signature Hall'),
        'capacity' => (int)$settingAmount('hall_2_capacity', 220),
        'dimensions' => $settingText('hall_2_dimensions', '18m x 14m'),
        'setups' => ['Theatre', 'Classroom', 'Banquet', 'Cocktail'],
        'full_day' => $settingAmount('hall_2_full_day', 130000),
        'half_day' => $settingAmount('hall_2_half_day', 80000),
        'evening' => $settingAmount('hall_2_evening', 70000),
        'image' => '/assets/images/hall-olkeri.jpg',
    ],
];

$hallInclusions = [
    'High-lumen projector and motorized projection screen',
    'Professional public address system and wireless microphones',
    'Dedicated event coordinator and setup team',
    'Complimentary guest parking and reception desk support',
];

$diningDetails = [
    'hours_breakfast' => $settingText('dining_hours_breakfast', '6:30 AM - 10:30 AM'),
    'hours_lunch' => $settingText('dining_hours_lunch', '12:00 PM - 3:30 PM'),
    'hours_dinner' => $settingText('dining_hours_dinner', '6:30 PM - 10:30 PM'),
    'signatures' => [
        [
            'name' => 'Savanna Flame Platter',
            'description' => 'Grilled meats with seasonal vegetables and house sauces.',
            'price' => $settingAmount('dining_signature_flame_platter', 1400),
            'image' => '/assets/images/dining-signature.jpg',
        ],
        [
            'name' => 'Maasai Herb Tilapia',
            'description' => 'Lake fish infused with local herbs and citrus glaze.',
            'price' => $settingAmount('dining_signature_tilapia', 800),
            'image' => '/assets/images/dining.jpg',
        ],
        [
            'name' => 'Sidai Royal Dessert Trio',
            'description' => 'Chef-crafted tasting board for premium celebrations.',
            'price' => $settingAmount('dining_signature_dessert_trio', 200),
            'image' => '/assets/images/hero-sunset.jpg',
        ],
    ],
];

/**
 * Playground activity options and day pass access.
 * Ensured to be within the 8000 max price rule.
 */
$playgroundActivities = [
    ['name' => 'Bouncing Castle Access', 'price' => $settingAmount('playground_bouncing_castle', 100)],
    ['name' => 'Swinging & Slides', 'price' => $settingAmount('playground_swinging', 100)],
    ['name' => 'Full Playground Access (Day Pass)', 'price' => $settingAmount('playground_full_access', 100)],
    ['name' => 'Face Painting & Guided Activities', 'price' => $settingAmount('playground_activities', 100)],
];

/**
 * Location shooting packages for music videos or film production.
 */
$musicPackages = [
    ['name' => 'Half Day Location Package', 'price' => $settingAmount('music_shoot_half_day', 1500)],
    ['name' => 'Full Day Location Package', 'price' => $settingAmount('music_shoot_full_day', 4500)],
    ['name' => 'Overnight Production Package', 'price' => $settingAmount('music_shoot_overnight', 6500)],
];

$conferencePackages = [
    ['name' => 'Executive Day Package', 'price' => $settingAmount('conference_executive_day', 5500)],
    ['name' => 'Strategy Retreat Package', 'price' => $settingAmount('conference_strategy_retreat', 8900)],
    ['name' => 'Full Service Corporate Summit', 'price' => $settingAmount('conference_corporate_summit', 12500)],
];

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>
<main class="pt-28 lg:pt-32 pb-0" x-data="servicesTabs()" x-init="init()">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img
                src="/assets/images/conference-suite.jpg"
                alt="Sidai Resort conference and events service hero image"
                class="h-full w-full object-cover"
                fetchpriority="high"
                data-parallax-speed="0.14"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-night/85 via-night/65 to-forest/55"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-28 sm:px-6 lg:px-8 lg:py-36">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-gold">Sidai Resort Services</p>
            <h1 class="mt-4 max-w-4xl font-display text-5xl text-white sm:text-6xl lg:text-7xl">Our World-Class Services</h1>
            <p class="mt-6 max-w-3xl text-base leading-8 text-cream sm:text-lg">
                Discover refined experiences designed for unforgettable leisure stays, premium celebrations, business gatherings, and creative productions.
            </p>
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

    <section class="bg-cream py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <nav class="inline-flex min-w-full gap-2 rounded-2xl border border-gold/30 bg-white/80 p-2 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    <button type="button" @click="setTab('conference-events')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'conference-events' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Conference & Events
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'conference-events' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('pool')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'pool' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Swimming Pool
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'pool' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('outdoor')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'outdoor' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Outdoor & Bonfire
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'outdoor' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('dining')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'dining' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Fine Dining
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'dining' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('activities')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'activities' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Playground
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'activities' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('music-shoots')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'music-shoots' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Music Shoots
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'music-shoots' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                </nav>
            </div>

            <section  data-tab-panel="pool" x-show="activeTab === 'pool'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-4xl text-brown">Swimming Pool Experience</h2>
                        <p class="mt-4 text-base leading-8 text-brown/85">
                            Enjoy a luxury pool environment designed for daytime relaxation, social moments, and premium leisure sessions for families and private guests.
                        </p>
                        <ul class="services-bullets mt-6 space-y-3 text-sm text-brown/85">
                            <li class="rounded-xl border border-gold/20 bg-cream/50 px-4 py-3">Pool Hours: <?php echo safe_html($poolDetails['hours']); ?></li>
                            <li class="rounded-xl border border-gold/20 bg-cream/50 px-4 py-3">Maximum Capacity: <?php echo safe_html((string)$poolDetails['capacity']); ?> guests</li>
                            <?php foreach ($poolDetails['rules'] as $rule): ?>
                                <li class="rounded-xl border border-gold/20 bg-cream/50 px-4 py-3"><?php echo safe_html($rule); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </article>

                    <aside class="rounded-3xl border border-gold/30 bg-night p-8 text-cream">
                        <h3 class="font-display text-3xl text-gold">Pool Access Pricing</h3>
                        <div class="mt-5 space-y-3 text-sm">
                            <p class="flex items-center justify-between rounded-xl border border-gold/20 px-4 py-3"><span>Adults Day Pass</span><strong><?php echo safe_html(format_kes($poolDetails['adult_day_pass'])); ?></strong></p>
                            <p class="flex items-center justify-between rounded-xl border border-gold/20 px-4 py-3"><span>Children Day Pass</span><strong><?php echo safe_html(format_kes($poolDetails['child_day_pass'])); ?></strong></p>
                            <p class="rounded-xl bg-white/10 px-4 py-3 text-cream/85">Pool bar and light menu service available all day with curated beverages and snacks.</p>
                        </div>
                        <a href="/booking?type=pool" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Book Pool Session</a>
                    </aside>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <a href="/assets/images/swimming-pool.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="pool-gallery" data-title="Main pool deck">
                        <img src="/assets/images/swimming-pool.jpg" alt="Main swimming pool deck at Sidai Resort" class="h-52 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/pool-lounge.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="pool-gallery" data-title="Pool lounge">
                        <img src="/assets/images/pool-lounge.jpg" alt="Poolside lounge seating at Sidai Resort" class="h-52 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/hero-sunset.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="pool-gallery" data-title="Sunset ambience">
                        <img src="/assets/images/hero-sunset.jpg" alt="Sunset ambience around the pool area" class="h-52 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/dining.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="pool-gallery" data-title="Pool refreshment service">
                        <img src="/assets/images/dining.jpg" alt="Poolside refreshment service setup" class="h-52 w-full object-cover" loading="lazy">
                    </a>
                </div>
            </section>

                        <section  data-tab-panel="conference-events" x-show="activeTab === 'conference-events'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                
                <div class="relative bg-forest text-cream py-16 rounded-3xl overflow-hidden shadow-xl mb-12">
                    <div class="absolute bottom-0 left-0 right-0 opacity-10 pointer-events-none">
                        <svg viewBox="0 0 1440 320" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                            <path d="M0 320L48 298.7C96 277 192 235 288 213.3C384 192 480 192 576 208C672 224 768 256 864 256C960 256 1056 224 1152 213.3C1248 203 1344 213 1392 218.7L1440 224V320H1392C1344 320 1248 320 1152 320C1056 320 960 320 864 320C768 320 672 320 576 320C480 320 384 320 288 320C192 320 96 320 48 320H0Z" fill="#D4AF37"/>
                            <path d="M0 320L60 288C120 256 240 192 360 176C480 160 600 192 720 208C840 224 960 224 1080 197.3C1200 171 1320 117 1380 90.7L1440 64V320H1380C1320 320 1200 320 1080 320C960 320 840 320 720 320C600 320 480 320 360 320C240 320 120 320 60 320H0Z" fill="#F5ECD7" fill-opacity="0.5"/>
                        </svg>
                    </div>

                    <div class="relative z-10 mx-auto px-6 lg:px-12">
                        <div class="text-center max-w-3xl mx-auto mb-16">
                            <h1 class="font-display text-4xl text-gold sm:text-5xl mb-3">The Gathering</h1>
                            <p class="font-playfair text-xl italic text-cream/90 mb-6">Where Big Ideas Meet Tranquility</p>
                            <p class="text-sm leading-7 text-cream/80">
                                The forest has always been the place where important things are decided — 
                                where councils are held, where futures are shaped. Sidai Resort's 
                                conference and social spaces carry that ancient wisdom into the modern era. 
                                We offer <span class="font-playfair italic text-gold">Nothing but the Best</span> — for your people, your ideas, 
                                and your most important gatherings.
                            </p>
                        </div>

                        <div class="grid gap-8 lg:grid-cols-2">
                            <article class="bg-cream rounded-2xl overflow-hidden shadow-2xl relative text-brown">
                                <div class="h-48 relative overflow-hidden">
                                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hall-enkaji.jpg" alt="Enchula Conference Hall" class="w-full h-full object-cover">
                                </div>
                                <div class="p-6 relative">
                                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <h2 class="font-display text-3xl italic text-gold">Enchula</h2>
                                        <span class="inline-flex items-center rounded-full bg-gold px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-night">
                                            Up to 80 Guests
                                        </span>
                                    </div>
                                    <p class="text-xs leading-6 text-forest/90 mb-6">
                                        Enchula is the room where important conversations happen. Intimate enough to 
                                        foster genuine connection, sophisticated enough to impress the most discerning delegates.
                                    </p>
                                    <a href="<?php echo WEB_ROOT; ?>/about#contact" class="inline-flex items-center justify-center rounded-xl border-2 border-forest bg-transparent px-4 py-2 text-xs font-semibold uppercase tracking-wider text-forest hover:bg-forest hover:text-cream transition-colors">
                                        Enquire About Enchula &rarr;
                                    </a>
                                </div>
                            </article>

                            <article class="bg-cream rounded-2xl overflow-hidden shadow-2xl relative text-brown">
                                <div class="h-48 relative overflow-hidden">
                                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hall-olkeri.jpg" alt="Entumo Events Hall" class="w-full h-full object-cover">
                                </div>
                                <div class="p-6 relative">
                                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <h2 class="font-display text-3xl italic text-gold">Entumo</h2>
                                        <span class="inline-flex items-center rounded-full bg-gold px-3 py-1 text-[10px] font-bold uppercase tracking-wider text-night">
                                            Larger Gatherings Welcome
                                        </span>
                                    </div>
                                    <p class="text-xs leading-6 text-forest/90 mb-6">
                                        Where Enchula whispers, Entumo speaks with authority. A space that carries the dual gift 
                                        of scale and warmth, built for the gatherings that matter most.
                                    </p>
                                    <a href="<?php echo WEB_ROOT; ?>/about#contact" class="inline-flex items-center justify-center rounded-xl border-2 border-forest bg-transparent px-4 py-2 text-xs font-semibold uppercase tracking-wider text-forest hover:bg-forest hover:text-cream transition-colors">
                                        Enquire About Entumo &rarr;
                                    </a>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>

                <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-3xl text-brown mb-4">Conference and Business Facilities</h2>
                        <ul class="services-bullets mt-3 space-y-3 text-sm text-brown/85">
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">4K projection systems and presentation clickers</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Wireless microphones and distributed room audio</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">High-speed Wi-Fi and dedicated support desk</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Stage lighting and custom branding display points</li>
                        </ul>
                    </article>

                    <aside class="rounded-3xl border border-gold/30 bg-night p-8 text-cream">
                        <h3 class="font-display text-3xl text-gold">Corporate Packages</h3>
                        <div class="mt-5 space-y-3 text-sm">
                            <?php foreach ($conferencePackages as $package): ?>
                                <p class="flex items-center justify-between rounded-xl border border-gold/20 px-4 py-3">
                                    <span><?php echo safe_html($package['name']); ?></span>
                                    <strong><?php echo safe_html(format_kes($package['price'])); ?></strong>
                                </p>
                            <?php endforeach; ?>
                        </div>
                        <a href="/about#contact" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Plan Conference</a>
                    </aside>
                </div>
            </section>


            <section  data-tab-panel="dining" x-show="activeTab === 'dining'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-4xl text-brown">Fine Dining at Sidai</h2>
                        <p class="mt-4 text-base leading-8 text-brown/85">
                            Enjoy Kenyan classics, international cuisine, and Maasai-inspired signatures in an elegant setting designed for both intimate dinners and premium group reservations.
                        </p>
                        <ul class="mt-6 space-y-3 text-sm text-brown/85">
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Breakfast: <?php echo safe_html($diningDetails['hours_breakfast']); ?></li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Lunch: <?php echo safe_html($diningDetails['hours_lunch']); ?></li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Dinner: <?php echo safe_html($diningDetails['hours_dinner']); ?></li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Private dining experiences available by advance reservation.</li>
                        </ul>
                    </article>

                    <div class="grid gap-4">
                        <?php foreach ($diningDetails['signatures'] as $dish): ?>
                            <article class="overflow-hidden rounded-2xl border border-brown/10 bg-white shadow-sm">
                                <div class="grid sm:grid-cols-[170px_1fr]">
                                    <img src="<?php echo safe_html($dish['image']); ?>" alt="<?php echo safe_html($dish['name']); ?> signature dish" class="h-full w-full object-cover" loading="lazy">
                                    <div class="p-5">
                                        <h3 class="font-display text-3xl text-brown"><?php echo safe_html($dish['name']); ?></h3>
                                        <p class="mt-2 text-sm text-brown/80"><?php echo safe_html($dish['description']); ?></p>
                                        <p class="mt-3 text-sm font-semibold uppercase tracking-[0.15em] text-earth"><?php echo safe_html(format_kes($dish['price'])); ?></p>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="/menu" class="inline-flex rounded-full bg-forest px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-cream transition hover:bg-forest-light">View Full Menu</a>
                    <a href="/booking?type=dining" class="inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Reserve Dining</a>
                </div>
            </section>

            <section  data-tab-panel="activities" x-show="activeTab === 'activities'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-4xl text-brown">Kids Playground & Activities</h2>
                        <p class="mt-4 text-base leading-8 text-brown/85">
                            Keep the little ones entertained with our fully-equipped children's playground featuring safe bouncing castles, swinging sets, face painting, and guided activities.
                        </p>
                        <ul class="mt-6 space-y-3 text-sm">
                            <?php foreach ($playgroundActivities as $activity): ?>
                                <li class="flex items-center justify-between rounded-xl border border-brown/10 bg-cream/50 px-4 py-3 text-brown/85">
                                    <span><?php echo safe_html($activity['name']); ?></span>
                                    <strong><?php echo safe_html(format_kes($activity['price'])); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </article>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <a href="https://loremflickr.com/800/600/playground,kids?random=1" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="playground-gallery" data-title="Bouncing Castle">
                            <img src="https://loremflickr.com/800/600/playground,kids?random=1" alt="Kids bouncing castle at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                        </a>
                        <a href="https://loremflickr.com/800/600/swings,kids?random=2" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="playground-gallery" data-title="Swings and slides">
                            <img src="https://loremflickr.com/800/600/swings,kids?random=2" alt="Kids swings and slides at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                        </a>
                        <a href="https://loremflickr.com/800/600/facepainting,kids?random=3" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10 sm:col-span-2" data-gallery="playground-gallery" data-title="Face painting and activities">
                            <img src="https://loremflickr.com/800/600/facepainting,kids?random=3" alt="Kids face painting activities at the resort" class="h-56 w-full object-cover" loading="lazy">
                        </a>
                    </div>
                </div>

                <a href="/booking?type=activities" class="inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Book Activities Pass</a>
            </section>

            <section  data-tab-panel="music-shoots" x-show="activeTab === 'music-shoots'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-4xl text-brown">Music Video and Film Location</h2>
                        <p class="mt-4 text-base leading-8 text-brown/85">
                            Sidai offers cinematic indoor and outdoor scenes, from savanna-inspired landscapes to polished interiors for premium music and film production.
                        </p>
                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-earth">Location Highlights</p>
                        <ul class="services-bullets mt-3 space-y-3 text-sm text-brown/85">
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Open-air savanna-inspired backgrounds and sunset horizon viewpoints</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Poolside scenes with lounge furniture and premium ambiance lighting</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Event halls and reception interiors suitable for branded productions</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Dedicated power points and basic setup support included</li>
                        </ul>
                    </article>

                    <aside class="rounded-3xl border border-gold/30 bg-night p-8 text-cream">
                        <h3 class="font-display text-3xl text-gold">Production Packages</h3>
                        <div class="mt-5 space-y-3 text-sm">
                            <?php foreach ($musicPackages as $package): ?>
                                <p class="flex items-center justify-between rounded-xl border border-gold/20 px-4 py-3">
                                    <span><?php echo safe_html($package['name']); ?></span>
                                    <strong><?php echo safe_html(format_kes($package['price'])); ?></strong>
                                </p>
                            <?php endforeach; ?>
                        </div>
                        <p class="mt-5 rounded-xl bg-white/10 px-4 py-3 text-sm text-cream/85">
                            Packages include location access, standard security coordination, and basic utility support.
                        </p>
                        <a href="/about#contact" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Book Location</a>
                    </aside>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <a href="/assets/images/music-shoot.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="music-gallery" data-title="Outdoor film scene">
                        <img src="/assets/images/music-shoot.jpg" alt="Outdoor film scene setup at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/swimming-pool.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="music-gallery" data-title="Poolside production scene">
                        <img src="/assets/images/swimming-pool.jpg" alt="Poolside production scene at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/conferencing.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="music-gallery" data-title="Indoor cinematic setup">
                        <img src="/assets/images/conferencing.jpg" alt="Indoor cinematic production setup in event hall" class="h-56 w-full object-cover" loading="lazy">
                    </a>
                </div>
            </section>

            
        </div>
    </section>


<script>
function servicesTabs() {
    return {
        validTabs: ['conference-events', 'pool', 'outdoor', 'dining', 'activities', 'music-shoots'],
        activeTab: 'conference-events',

        init() {
            window.scrollTo(0, 0);

            const hash = window.location.hash.replace('#', '');
            if (this.validTabs.includes(hash)) {
                this.activeTab = hash;
            }

            this.$watch('activeTab', (value) => {
                const nextHash = `#${value}`;
                if (window.location.hash !== nextHash) {
                    history.replaceState(null, '', nextHash);
                }
                this.animateCurrentTab();
            });

            window.addEventListener('hashchange', () => {
                const next = window.location.hash.replace('#', '');
                if (this.validTabs.includes(next)) {
                    this.activeTab = next;
                }
            });

            this.animateCurrentTab();
        },

        setTab(tab) {
            if (!this.validTabs.includes(tab)) {
                return;
            }

            this.activeTab = tab;
        },

        animateCurrentTab() {
            if (!window.gsap) {
                return;
            }

            const panel = document.querySelector(`[data-tab-panel="${this.activeTab}"]`);
            if (!panel) {
                return;
            }

            window.gsap.fromTo(
                panel,
                { opacity: 0, y: 16 },
                { opacity: 1, y: 0, duration: 0.35, ease: 'power2.out' }
            );
        },
    };
}

document.addEventListener('DOMContentLoaded', () => {
    if (!window.location.hash) {
        history.replaceState(null, '', '#conference-events');
    }

    if (window.gsap && window.ScrollTrigger) {
        window.gsap.utils.toArray('.services-bullets').forEach((list) => {
            const items = list.querySelectorAll('li');
            window.gsap.from(items, {
                x: -20,
                opacity: 0,
                stagger: 0.07,
                duration: 0.35,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: list,
                    start: 'top 88%',
                },
            });
        });
    }
});
</script>

<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>






