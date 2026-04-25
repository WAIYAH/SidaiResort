<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\Database;

$pageTitle = 'Sidai Resort Services | World-Class Experiences';
$pageDescription = 'Explore Sidai Resort services: swimming pool sessions, event halls, fine dining, spa and wellness, music shoot locations, and conference packages.';
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

$poolDetails = [
    'hours' => $settingText('pool_hours', '6:00 AM - 8:00 PM daily'),
    'capacity' => (int)$settingAmount('pool_capacity', 120),
    'adult_day_pass' => $settingAmount('pool_day_pass_adult', 2500),
    'child_day_pass' => $settingAmount('pool_day_pass_child', 1500),
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
            'price' => $settingAmount('dining_signature_flame_platter', 4200),
            'image' => '/assets/images/dining-signature.jpg',
        ],
        [
            'name' => 'Maasai Herb Tilapia',
            'description' => 'Lake fish infused with local herbs and citrus glaze.',
            'price' => $settingAmount('dining_signature_tilapia', 3600),
            'image' => '/assets/images/dining.jpg',
        ],
        [
            'name' => 'Sidai Royal Dessert Trio',
            'description' => 'Chef-crafted tasting board for premium celebrations.',
            'price' => $settingAmount('dining_signature_dessert_trio', 1800),
            'image' => '/assets/images/hero-sunset.jpg',
        ],
    ],
];

$spaServices = [
    ['name' => 'Deep Tissue Massage (60 min)', 'price' => $settingAmount('spa_deep_tissue_60', 6500)],
    ['name' => 'Aromatherapy Ritual (75 min)', 'price' => $settingAmount('spa_aromatherapy_75', 8000)],
    ['name' => 'Couples Rejuvenation Package', 'price' => $settingAmount('spa_couples_package', 14500)],
    ['name' => 'Express Recovery Therapy (30 min)', 'price' => $settingAmount('spa_express_recovery_30', 3800)],
];

$musicPackages = [
    ['name' => 'Half Day Location Package', 'price' => $settingAmount('music_shoot_half_day', 85000)],
    ['name' => 'Full Day Location Package', 'price' => $settingAmount('music_shoot_full_day', 150000)],
    ['name' => 'Overnight Production Package', 'price' => $settingAmount('music_shoot_overnight', 220000)],
];

$conferencePackages = [
    ['name' => 'Executive Day Package', 'price' => $settingAmount('conference_executive_day', 5500)],
    ['name' => 'Strategy Retreat Package', 'price' => $settingAmount('conference_strategy_retreat', 8900)],
    ['name' => 'Full Service Corporate Summit', 'price' => $settingAmount('conference_corporate_summit', 12500)],
];

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>
<main class="pt-28 lg:pt-32">
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

    <section class="bg-cream py-14" x-data="servicesTabs()" x-init="init()">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-x-auto">
                <nav class="inline-flex min-w-full gap-2 rounded-2xl border border-gold/30 bg-white/80 p-2">
                    <button type="button" @click="setTab('pool')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'pool' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Swimming Pool
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'pool' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('halls')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'halls' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Event Halls
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'halls' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('dining')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'dining' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Fine Dining
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'dining' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('spa')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'spa' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Spa
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'spa' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('music-shoots')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'music-shoots' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Music Shoots
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'music-shoots' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                    <button type="button" @click="setTab('conference')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="activeTab === 'conference' ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
                        Conference
                        <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="activeTab === 'conference' ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
                    </button>
                </nav>
            </div>

            <section id="pool" data-tab-panel="pool" x-show="activeTab === 'pool'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
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

            <section id="halls" data-tab-panel="halls" x-show="activeTab === 'halls'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-6 lg:grid-cols-2">
                    <?php foreach ($hallPackages as $hall): ?>
                        <article class="overflow-hidden rounded-3xl border border-brown/10 bg-white shadow-sm">
                            <img src="<?php echo safe_html($hall['image']); ?>" alt="<?php echo safe_html($hall['name']); ?> event hall interior" class="h-56 w-full object-cover" loading="lazy">
                            <div class="p-7">
                                <h2 class="font-display text-4xl text-brown"><?php echo safe_html($hall['name']); ?></h2>
                                <p class="mt-3 text-sm text-brown/80">Capacity: <?php echo safe_html((string)$hall['capacity']); ?> guests</p>
                                <p class="mt-1 text-sm text-brown/80">Dimensions: <?php echo safe_html($hall['dimensions']); ?></p>
                                <p class="mt-4 text-xs font-semibold uppercase tracking-[0.18em] text-earth">Available Setups</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <?php foreach ($hall['setups'] as $setup): ?>
                                        <span class="rounded-full border border-gold/30 px-3 py-1 text-xs text-brown"><?php echo safe_html($setup); ?></span>
                                    <?php endforeach; ?>
                                </div>

                                <div class="mt-5 space-y-2 text-sm">
                                    <p class="flex items-center justify-between rounded-xl border border-brown/10 bg-cream/50 px-4 py-3"><span>Full Day</span><strong><?php echo safe_html(format_kes($hall['full_day'])); ?></strong></p>
                                    <p class="flex items-center justify-between rounded-xl border border-brown/10 bg-cream/50 px-4 py-3"><span>Half Day</span><strong><?php echo safe_html(format_kes($hall['half_day'])); ?></strong></p>
                                    <p class="flex items-center justify-between rounded-xl border border-brown/10 bg-cream/50 px-4 py-3"><span>Evening Session</span><strong><?php echo safe_html(format_kes($hall['evening'])); ?></strong></p>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <article class="rounded-3xl border border-gold/30 bg-night p-8 text-cream">
                    <h3 class="font-display text-3xl text-gold">Hall Inclusions</h3>
                    <ul class="services-bullets mt-5 grid gap-3 sm:grid-cols-2">
                        <?php foreach ($hallInclusions as $inclusion): ?>
                            <li class="rounded-xl border border-gold/25 bg-white/10 px-4 py-3 text-sm"><?php echo safe_html($inclusion); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <a href="/contact?subject=Hall+Hire" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Request Quote</a>
                </article>
            </section>

            <section id="dining" data-tab-panel="dining" x-show="activeTab === 'dining'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
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

            <section id="spa" data-tab-panel="spa" x-show="activeTab === 'spa'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-4xl text-brown">Spa and Wellness Sanctuary</h2>
                        <p class="mt-4 text-base leading-8 text-brown/85">
                            Rebalance with restorative treatments crafted for relaxation, recovery, and holistic well-being. Advance booking is recommended for all therapies.
                        </p>
                        <ul class="mt-6 space-y-3 text-sm">
                            <?php foreach ($spaServices as $service): ?>
                                <li class="flex items-center justify-between rounded-xl border border-brown/10 bg-cream/50 px-4 py-3 text-brown/85">
                                    <span><?php echo safe_html($service['name']); ?></span>
                                    <strong><?php echo safe_html(format_kes($service['price'])); ?></strong>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </article>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <a href="/assets/images/spa-wellness.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="spa-gallery" data-title="Spa relaxation suite">
                            <img src="/assets/images/spa-wellness.jpg" alt="Spa relaxation suite at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                        </a>
                        <a href="/assets/images/spa-ritual.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="spa-gallery" data-title="Wellness treatment room">
                            <img src="/assets/images/spa-ritual.jpg" alt="Wellness treatment room at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                        </a>
                        <a href="/assets/images/hero-sunset.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10 sm:col-span-2" data-gallery="spa-gallery" data-title="Tranquil evening wellness atmosphere">
                            <img src="/assets/images/hero-sunset.jpg" alt="Tranquil evening wellness atmosphere at the resort" class="h-56 w-full object-cover" loading="lazy">
                        </a>
                    </div>
                </div>

                <a href="/booking?type=spa" class="inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Book Spa Experience</a>
            </section>

            <section id="music-shoots" data-tab-panel="music-shoots" x-show="activeTab === 'music-shoots'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
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
                        <a href="/contact?subject=Location+Shoot" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Book Location</a>
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

            <section id="conference" data-tab-panel="conference" x-show="activeTab === 'conference'" x-transition.opacity.duration.250ms class="mt-10 space-y-8">
                <div class="grid gap-8 lg:grid-cols-[1fr_0.95fr]">
                    <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                        <h2 class="font-display text-4xl text-brown">Conference and Business Facilities</h2>
                        <p class="mt-4 text-base leading-8 text-brown/85">
                            Run productive offsites, board meetings, and corporate summits in settings designed for focus, hospitality, and seamless event delivery.
                        </p>
                        <p class="mt-5 text-xs font-semibold uppercase tracking-[0.2em] text-earth">AV Equipment</p>
                        <ul class="services-bullets mt-3 space-y-3 text-sm text-brown/85">
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">4K projection systems and presentation clickers</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Wireless microphones and distributed room audio</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">High-speed Wi-Fi and dedicated support desk</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Stage lighting and custom branding display points</li>
                        </ul>

                        <p class="mt-6 text-xs font-semibold uppercase tracking-[0.2em] text-earth">Catering Options</p>
                        <ul class="mt-3 space-y-2 text-sm text-brown/85">
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Tea and coffee breaks</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Executive working lunches</li>
                            <li class="rounded-xl border border-brown/10 bg-cream/50 px-4 py-3">Buffet and plated dinner service</li>
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
                        <p class="mt-5 rounded-xl bg-white/10 px-4 py-3 text-sm text-cream/85">Pricing shown per delegate where applicable. Full package quotes are tailored to group size and duration.</p>
                        <a href="/contact?subject=Conference+Package" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Plan Conference</a>
                    </aside>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <a href="/assets/images/conference-suite.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="conference-gallery" data-title="Conference suite">
                        <img src="/assets/images/conference-suite.jpg" alt="Conference suite setup at Sidai Resort" class="h-56 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/conferencing.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="conference-gallery" data-title="Main conference hall">
                        <img src="/assets/images/conferencing.jpg" alt="Main conference hall with theatre setup" class="h-56 w-full object-cover" loading="lazy">
                    </a>
                    <a href="/assets/images/dining.jpg" class="gallery-lightbox overflow-hidden rounded-2xl border border-brown/10" data-gallery="conference-gallery" data-title="Conference catering service">
                        <img src="/assets/images/dining.jpg" alt="Conference catering service display" class="h-56 w-full object-cover" loading="lazy">
                    </a>
                </div>
            </section>
        </div>
    </section>
</main>

<script>
function servicesTabs() {
    return {
        validTabs: ['pool', 'halls', 'dining', 'spa', 'music-shoots', 'conference'],
        activeTab: 'pool',

        init() {
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
        history.replaceState(null, '', '#pool');
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
