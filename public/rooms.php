<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Luxury Rooms | Emirishoi, Elalai & More | Sidai Resort';
$pageDescription = 'Discover our 11 uniquely named Maasai sanctuaries at Sidai Resort Ã¢â‚¬â€ from Emirishoi to Elalai. Nothing but the Best in luxury forest accommodation.';

$db = \App\Core\Database::getInstance();
$dbRooms = $db->query("SELECT * FROM rooms WHERE is_available = 1 ORDER BY price_per_night DESC");

$premiumRoom = null;
$rooms = [];
$index = 1;

foreach ($dbRooms as $r) {
    $formattedRoom = [
        'id' => strtolower(str_replace(' ', '_', $r['name'])),
        'name' => $r['name'],
        'desc' => $r['description'],
        'amenities' => json_decode($r['amenities'] ?? '[]', true) ?: [],
        'image' => 'https://loremflickr.com/800/600/luxury,bedroom?random=' . $index++,
        'price' => (float)$r['price_per_night'],
        'capacity' => (int)$r['capacity'],
        'isPremium' => ($r['name'] === 'Elalai')
    ];

    if ($r['name'] === 'Elalai') {
        $formattedRoom['image'] = 'https://loremflickr.com/1200/800/luxury,suite?random=100';
        $premiumRoom = $formattedRoom;
    } else {
        $rooms[] = $formattedRoom;
    }
}

// Fallback if Elalai is not found for some reason
if (!$premiumRoom && count($rooms) > 0) {
    $premiumRoom = array_shift($rooms);
    $premiumRoom['isPremium'] = true;
}

$allRoomsJson = json_encode(array_merge([$premiumRoom], $rooms));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
    <style>
        .sanctuary-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sanctuary-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 25px -5px rgba(212, 175, 55, 0.3), 0 8px 10px -6px rgba(212, 175, 55, 0.1);
            border-color: rgba(212, 175, 55, 0.5);
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-cream">
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="pt-28 lg:pt-32 pb-20 overflow-hidden" x-data="roomsECommerce()" x-init="init()">
        
        <section class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center mb-12">
            <h1 class="font-display text-5xl text-brown sm:text-6xl" data-aos="fade-up">The Sanctuaries</h1>
            <p class="mt-3 text-lg font-playfair italic text-gold" data-aos="fade-up" data-aos-delay="100">Where Comfort Meets Tradition</p>
        </section>

        <!-- Filters Section for Room Price and Capacity -->
        <!-- Controlled by Alpine.js x-model directives binding to maxPrice and minCapacity -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 mb-10" data-aos="fade-up" data-aos-delay="200">
            <div class="bg-white rounded-2xl border border-gold/30 p-6 shadow-sm flex flex-col sm:flex-row gap-6 items-center justify-between">
                
                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold uppercase tracking-widest text-brown mb-2">Max Price per Night: Ksh <span x-text="maxPrice"></span></label>
                    <input type="range" min="1000" max="3500" step="100" x-model="maxPrice" class="w-full accent-gold h-2 bg-cream rounded-lg appearance-none cursor-pointer">
                </div>

                <div class="flex-1 w-full">
                    <label class="block text-xs font-bold uppercase tracking-widest text-brown mb-2">Minimum Capacity</label>
                    <select x-model="minCapacity" class="w-full bg-cream border border-brown/20 text-brown rounded-xl px-4 py-2 focus:outline-none focus:border-gold">
                        <option value="1">Any Capacity</option>
                        <option value="2">2+ People</option>
                        <option value="3">3+ People</option>
                        <option value="4">4+ People</option>
                    </select>
                </div>
                
                <div class="w-full sm:w-auto text-right text-sm font-semibold text-brown/80 mt-4 sm:mt-0">
                    Showing <span x-text="filteredRooms.length" class="text-gold font-bold"></span> of 11 Rooms
                </div>

            </div>
        </section>

        <!-- Room Grid: Dynamically renders items from the filteredRooms array -->
        <section class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" id="sanctuaries-grid">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                
                <template x-for="room in filteredRooms" :key="room.id">
                    <article 
                        class="sanctuary-card relative flex flex-col overflow-hidden rounded-2xl border bg-white group"
                        :class="room.isPremium ? 'border-gold/50 sm:col-span-2 lg:col-span-2' : 'border-brown/10'"
                    >
                        <div x-show="room.isPremium" class="absolute top-4 right-4 z-10 rounded-full bg-gradient-to-r from-gold to-gold-dark px-4 py-1.5 text-xs font-bold uppercase tracking-widest text-night shadow-lg">
                            Our Finest
                        </div>
                        
                        <div class="relative w-full overflow-hidden" :class="room.isPremium ? 'h-72 sm:h-96' : 'h-56'">
                            <img :src="room.image" :alt="room.name" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-night/90 via-night/30 to-transparent"></div>
                            
                            <h2 class="absolute bottom-6 left-6 font-display italic text-gold group-hover:scale-105 transition-transform duration-500 origin-left"
                                :class="room.isPremium ? 'text-4xl' : 'text-3xl'">
                                <span x-text="room.name"></span>
                            </h2>
                            <div class="absolute bottom-6 right-6 text-right">
                                <span class="block text-sm text-cream/80">From</span>
                                <span class="block text-xl font-bold text-white">Ksh <span x-text="room.price.toLocaleString()"></span></span>
                            </div>
                        </div>
                        
                        <div class="flex flex-col justify-between flex-grow p-6">
                            <div>
                                <p class="text-sm leading-relaxed text-brown/85 mb-5 line-clamp-3">
                                    <span x-text="room.desc"></span>
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mb-6">
                                    <span class="inline-flex items-center gap-1.5 rounded-md border border-gold/30 bg-gold/5 px-2 py-1 text-[11px] font-bold uppercase tracking-wider text-brown">
                                        <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        Sleeps <span x-text="room.capacity"></span>
                                    </span>
                                    <template x-for="amenity in room.amenities">
                                        <span class="inline-flex items-center gap-1 rounded bg-cream/60 px-2 py-1 text-[11px] font-medium text-brown/80" x-text="amenity"></span>
                                    </template>
                                </div>
                            </div>
                            
                            <div class="mt-auto flex items-center justify-between gap-4">
                                <a :href="'<?php echo WEB_ROOT; ?>/booking?type=room&room=' + encodeURIComponent(room.name)" 
                                   class="inline-flex w-full items-center justify-center rounded-xl px-4 py-2.5 text-xs font-semibold uppercase tracking-wider transition-colors"
                                   :class="room.isPremium ? 'bg-gold text-night hover:bg-gold-light' : 'border border-gold/50 bg-transparent text-brown hover:bg-gold hover:text-night hover:border-gold'">
                                    Reserve <span x-text="room.name" class="ml-1"></span> &rarr;
                                </a>
                            </div>
                        </div>
                    </article>
                </template>

                <div x-show="filteredRooms.length === 0" class="col-span-full py-20 text-center" x-cloak>
                    <svg class="mx-auto h-12 w-12 text-gold/50 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3 class="text-xl font-display text-brown mb-2">No Sanctuaries Available</h3>
                    <p class="text-brown/70">Try adjusting your filters to see more rooms.</p>
                </div>
            </div>
        </section>

    </main>

    <script>
        /**
         * Alpine.js Component Logic for E-commerce Room Filtering
         * Dynamically filters the list of rooms based on user-selected max price and min capacity.
         */
        function roomsECommerce() {
            return {
                allRooms: <?php echo $allRoomsJson; ?>,
                maxPrice: 3500,
                minCapacity: 1,
                
                get filteredRooms() {
                    return this.allRooms.filter(room => {
                        return room.price <= this.maxPrice && room.capacity >= this.minCapacity;
                    });
                },
                
                init() {
                    // Initialization logic if necessary
                }
            }
        }
    </script>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>

