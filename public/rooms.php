<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\Database;

$pageTitle = 'Luxury Rooms | Sidai Resort';
$pageDescription = 'Discover 11 uniquely named Maasai-inspired sanctuaries at Sidai Resort, with premium comfort and scenic Loita Hills ambience.';
$pageImage = APP_URL . '/assets/images/hero-section.jpg';
$pageKeywords = 'Sidai Resort rooms, Narok accommodation, Loita Hills stay, luxury room booking';

$roomImageMap = [
    'elalai' => '/assets/images/hero-sunset.jpg',
    'emirishoi' => '/assets/images/hero-section.jpg',
    'enchipai' => '/assets/images/swimming-pool.jpg',
    'eserian' => '/assets/images/dining.jpg',
    'empiris' => '/assets/images/conferencing.jpg',
    'ewangan' => '/assets/images/pool-lounge.jpg',
    'enkanasa' => '/assets/images/conference-suite.jpg',
    'esipil' => '/assets/images/hero-section.jpg',
    'enkipai' => '/assets/images/hero-sunset.jpg',
    'eripoto' => '/assets/images/dining-signature.jpg',
    'ereto' => '/assets/images/hero-sunset.jpg',
];

$fallbackRooms = [
    ['name' => 'Elalai', 'description' => 'Our signature sanctuary with elevated comfort and premium views.', 'capacity' => 4, 'price_per_night' => 3200, 'type' => 'suite'],
    ['name' => 'Emirishoi', 'description' => 'Bright and calming room with warm forest character.', 'capacity' => 2, 'price_per_night' => 2000, 'type' => 'standard'],
    ['name' => 'Enchipai', 'description' => 'Earthy tones and cozy textures for restful nights.', 'capacity' => 2, 'price_per_night' => 1200, 'type' => 'standard'],
];

$rooms = [];
$loadNotice = null;

try {
    $database = Database::getInstance();
    $rooms = $database->queryAll(
        'SELECT id, name, description, capacity, price_per_night, type, amenities
         FROM rooms
         WHERE is_available = 1
         ORDER BY price_per_night DESC, name ASC'
    );
} catch (Throwable $exception) {
    log_error('Failed loading rooms list.', $exception);
    $loadNotice = 'Live room inventory is currently unavailable. Showing curated room highlights.';
}

if ($rooms === []) {
    $rooms = $fallbackRooms;
}

$normalizedRooms = [];
$maxPrice = 0.0;
$maxCapacity = 1;

foreach ($rooms as $index => $room) {
    $name = trim((string)($room['name'] ?? 'Sidai Room'));
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name) ?? 'room-' . $index);
    $imageKey = strtolower(preg_replace('/[^a-z0-9]+/i', '', $name) ?? '');
    $image = $roomImageMap[$imageKey] ?? '/assets/images/hero-section.jpg';
    $price = (float)($room['price_per_night'] ?? 0);
    $capacity = max(1, (int)($room['capacity'] ?? 1));
    $amenitiesRaw = $room['amenities'] ?? '[]';
    $amenities = is_string($amenitiesRaw) ? (json_decode($amenitiesRaw, true) ?: []) : [];
    if (!is_array($amenities)) {
        $amenities = [];
    }

    $maxPrice = max($maxPrice, $price);
    $maxCapacity = max($maxCapacity, $capacity);

    $normalizedRooms[] = [
        'id' => $slug,
        'name' => $name,
        'description' => trim((string)($room['description'] ?? 'Comfortable, elegant and designed for restful stays.')),
        'capacity' => $capacity,
        'price' => $price,
        'type' => (string)($room['type'] ?? 'standard'),
        'image' => $image,
        'amenities' => array_values(array_slice($amenities, 0, 4)),
    ];
}

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>
<main class="pt-28 lg:pt-32 bg-cream pb-20">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="Sidai Resort rooms hero" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-night/60"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 text-center sm:px-6 lg:px-8 lg:py-20">
            <p class="text-sm font-semibold uppercase tracking-[0.22em] text-gold">Accommodation</p>
            <h1 class="mt-3 font-display text-5xl text-white sm:text-6xl">Our Sanctuaries</h1>
            <p class="mx-auto mt-4 max-w-3xl text-base leading-8 text-cream/90">Where comfort meets Maasai-inspired elegance. Choose your ideal room and reserve instantly.</p>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="rounded-2xl border border-gold/30 bg-white p-5 shadow-lg sm:p-6">
            <?php if ($loadNotice !== null): ?>
                <p class="mb-5 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800"><?php echo safe_html($loadNotice); ?></p>
            <?php endif; ?>

            <div class="grid gap-6 md:grid-cols-3">
                <div class="md:col-span-2">
                    <label for="maxPrice" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-brown">Max Price Per Night: <span id="maxPriceLabel"><?php echo number_format($maxPrice, 0); ?></span></label>
                    <input id="maxPrice" type="range" min="1000" max="<?php echo (int)max(3500, ceil($maxPrice / 100) * 100); ?>" step="100" value="<?php echo (int)max(3500, ceil($maxPrice / 100) * 100); ?>" class="h-2 w-full cursor-pointer appearance-none rounded-lg bg-cream accent-gold">
                </div>
                <div>
                    <label for="minCapacity" class="mb-2 block text-xs font-bold uppercase tracking-[0.16em] text-brown">Minimum Capacity</label>
                    <select id="minCapacity" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-2.5 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        <option value="1">Any capacity</option>
                        <?php for ($i = 2; $i <= max(4, $maxCapacity); $i++): ?>
                            <option value="<?php echo $i; ?>"><?php echo $i; ?>+ guests</option>
                        <?php endfor; ?>
                    </select>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto mt-8 max-w-7xl px-4 sm:px-6 lg:px-8">
        <div id="roomsGrid" class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($normalizedRooms as $room): ?>
                <article class="room-card overflow-hidden rounded-3xl border border-brown/10 bg-white shadow-sm" data-price="<?php echo (int)$room['price']; ?>" data-capacity="<?php echo (int)$room['capacity']; ?>">
                    <div class="relative h-56 overflow-hidden">
                        <img src="<?php echo WEB_ROOT . safe_html($room['image']); ?>" alt="<?php echo safe_html($room['name']); ?>" class="h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-night/75 via-night/20 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                            <h2 class="font-display text-3xl text-gold"><?php echo safe_html($room['name']); ?></h2>
                            <p class="rounded-full bg-night/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.08em] text-cream"><?php echo safe_html(ucfirst(str_replace('_', ' ', $room['type']))); ?></p>
                        </div>
                    </div>
                    <div class="flex min-h-[190px] flex-col p-5">
                        <p class="text-sm leading-6 text-brown/85"><?php echo safe_html($room['description']); ?></p>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-md border border-gold/30 bg-gold/10 px-2.5 py-1 text-[11px] font-semibold uppercase tracking-[0.08em] text-brown">Sleeps <?php echo (int)$room['capacity']; ?></span>
                            <?php foreach ($room['amenities'] as $amenity): ?>
                                <span class="rounded-md bg-cream px-2.5 py-1 text-[11px] text-brown/80"><?php echo safe_html((string)$amenity); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-5 flex items-center justify-between gap-3">
                            <p class="text-base font-bold text-gold">KSh <?php echo number_format((float)$room['price'], 0); ?>/night</p>
                            <a href="<?php echo WEB_ROOT; ?>/booking?type=room&room=<?php echo rawurlencode($room['name']); ?>" class="inline-flex items-center rounded-full border border-gold/45 px-4 py-2 text-xs font-semibold uppercase tracking-[0.12em] text-brown transition hover:bg-gold hover:text-night">Reserve</a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div id="emptyState" class="hidden py-16 text-center">
            <h3 class="font-display text-3xl text-brown">No Rooms Match This Filter</h3>
            <p class="mt-2 text-sm text-brown/70">Adjust price or capacity filters to view available options.</p>
        </div>
    </section>
</main>

<script>
(() => {
    const maxPriceInput = document.getElementById('maxPrice');
    const minCapacityInput = document.getElementById('minCapacity');
    const maxPriceLabel = document.getElementById('maxPriceLabel');
    const cards = Array.from(document.querySelectorAll('.room-card'));
    const emptyState = document.getElementById('emptyState');

    const applyFilters = () => {
        const maxPrice = Number(maxPriceInput ? maxPriceInput.value : 0);
        const minCapacity = Number(minCapacityInput ? minCapacityInput.value : 1);

        if (maxPriceLabel) {
            maxPriceLabel.textContent = maxPrice.toLocaleString();
        }

        let visibleCount = 0;
        cards.forEach((card) => {
            const price = Number(card.dataset.price || 0);
            const capacity = Number(card.dataset.capacity || 1);
            const visible = price <= maxPrice && capacity >= minCapacity;
            card.classList.toggle('hidden', !visible);
            if (visible) {
                visibleCount += 1;
            }
        });

        if (emptyState) {
            emptyState.classList.toggle('hidden', visibleCount !== 0);
        }
    };

    if (maxPriceInput) {
        maxPriceInput.addEventListener('input', applyFilters);
    }
    if (minCapacityInput) {
        minCapacityInput.addEventListener('change', applyFilters);
    }

    applyFilters();
})();
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>
