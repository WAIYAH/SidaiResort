<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Our Menu | Sidai Resort';
$pageDescription = 'Nothing but the Best - from our kitchen to your table. Explore breakfast, local dishes, nyama choma, desserts, drinks, and more.';
$pageImage = APP_URL . '/assets/images/menu-hero.png';
$pageKeywords = 'Sidai Resort menu, Nyama Choma Narok, Kenyan breakfast, local dishes, drinks';

$menuSections = [
    [
        'id' => 'breakfast',
        'emoji' => '🌅',
        'title' => 'Breakfast',
        'image' => '/assets/images/menu-breakfast.png',
        'items' => [
            ['name' => 'Full English', 'description' => 'Eggs, sausages, bacon, beans, toast and grilled tomato.', 'price' => 750],
            ['name' => 'Mandazi & Chai', 'description' => 'Freshly made mandazi served with hot Kenyan chai.', 'price' => 200],
            ['name' => 'Ugali & Eggs', 'description' => 'Traditional ugali with pan-fried eggs.', 'price' => 300],
            ['name' => 'Pancakes & Honey', 'description' => 'Soft pancakes finished with natural honey.', 'price' => 450],
            ['name' => 'Avocado Toast', 'description' => 'Toasted bread layered with seasoned avocado.', 'price' => 400],
            ['name' => 'Boiled Eggs & Bread', 'description' => 'Simple, hearty and filling morning option.', 'price' => 250],
            ['name' => 'Sidai Platter (2 pax)', 'description' => 'A generous breakfast board for two guests.', 'price' => 1200],
            ['name' => 'Uji wa Wimbi', 'description' => 'Traditional finger millet porridge.', 'price' => 150],
        ],
    ],
    [
        'id' => 'local',
        'emoji' => '🍛',
        'title' => 'Local Dishes',
        'image' => '/assets/images/menu-main-course.png',
        'items' => [
            ['name' => 'Nyama Stew & Ugali', 'description' => 'Slow-cooked meat stew with classic ugali.', 'price' => 650],
            ['name' => 'Sukuma & Ugali', 'description' => 'Seasoned sukuma wiki served with ugali.', 'price' => 350],
            ['name' => 'Pilau Rice', 'description' => 'Aromatic Kenyan pilau with warm spices.', 'price' => 500],
            ['name' => 'Githeri', 'description' => 'Traditional maize and beans slow-cooked to perfection.', 'price' => 300],
            ['name' => 'Matoke', 'description' => 'Plantain stew with flavorful herbs and vegetables.', 'price' => 350],
            ['name' => 'Chapati & Beans', 'description' => 'Soft chapati with rich bean stew.', 'price' => 300],
            ['name' => 'Mukimo', 'description' => 'Mashed potatoes, greens and maize.', 'price' => 400],
            ['name' => 'Veg Stew & Rice', 'description' => 'Garden vegetable stew served with rice.', 'price' => 400],
        ],
    ],
    [
        'id' => 'nyama-choma',
        'emoji' => '🔥',
        'title' => 'Nyama Choma',
        'image' => '/assets/images/dining.jpg',
        'items' => [
            ['name' => 'Goat Choma 500g', 'description' => 'Tender goat cuts roasted over open flame.', 'price' => 900],
            ['name' => 'Beef Choma 500g', 'description' => 'Juicy flame-grilled beef with house seasoning.', 'price' => 850],
            ['name' => 'Chicken Choma Half', 'description' => 'Half chicken marinated and charcoal-grilled.', 'price' => 750],
            ['name' => 'Pork Ribs 400g', 'description' => 'Sticky, smoky ribs with rich glaze.', 'price' => 950],
            ['name' => 'Mixed Grill Platter (2 pax)', 'description' => 'A shared platter with signature grilled selections.', 'price' => 1400],
            ['name' => 'Tilapia Grilled', 'description' => 'Whole tilapia grilled with herbs and lemon.', 'price' => 800],
            ['name' => 'Sausages & Chips', 'description' => 'Classic combo with crisp fries.', 'price' => 550],
        ],
    ],
    [
        'id' => 'snacks',
        'emoji' => '🥪',
        'title' => 'Snacks',
        'image' => '/assets/images/dining-signature.jpg',
        'items' => [
            ['name' => 'Samosa (3 pc)', 'description' => 'Golden crispy samosas with savory filling.', 'price' => 150],
            ['name' => 'Chips Large', 'description' => 'Large serving of freshly fried chips.', 'price' => 250],
            ['name' => 'Bhajia', 'description' => 'Spiced potato fritters with dip.', 'price' => 200],
            ['name' => 'Smokies', 'description' => 'Served hot with kachumbari and sauces.', 'price' => 200],
            ['name' => 'Chicken Sandwich', 'description' => 'Toasted sandwich with seasoned chicken filling.', 'price' => 450],
            ['name' => 'Boiled Maize', 'description' => 'Simple and satisfying local snack.', 'price' => 100],
            ['name' => 'Spring Rolls (4 pc)', 'description' => 'Crunchy rolls with vegetable filling.', 'price' => 300],
            ['name' => 'Groundnuts', 'description' => 'Roasted local groundnuts.', 'price' => 100],
        ],
    ],
    [
        'id' => 'desserts',
        'emoji' => '🍰',
        'title' => 'Desserts',
        'image' => '/assets/images/menu-desserts.png',
        'items' => [
            ['name' => 'Mahamri & Coconut', 'description' => 'Soft mahamri paired with coconut cream.', 'price' => 250],
            ['name' => 'Fruit Salad', 'description' => 'Seasonal fruits served chilled.', 'price' => 300],
            ['name' => 'Chocolate Cake', 'description' => 'Rich cake slice with cocoa finish.', 'price' => 350],
            ['name' => 'Yoghurt & Honey', 'description' => 'Creamy yoghurt with natural honey drizzle.', 'price' => 250],
            ['name' => 'Banana Fritters', 'description' => 'Warm banana fritters with light cinnamon notes.', 'price' => 200],
            ['name' => 'Ice Cream 2 Scoops', 'description' => 'Two scoops of your favorite flavor.', 'price' => 300],
        ],
    ],
    [
        'id' => 'drinks',
        'emoji' => '🥤',
        'title' => 'Soft Drinks',
        'image' => '/assets/images/menu-beverages.png',
        'items' => [
            ['name' => 'Soda (300ml)', 'description' => 'Assorted soda flavors served chilled.', 'price' => 100],
            ['name' => 'Mango Juice', 'description' => 'Fresh mango juice.', 'price' => 250],
            ['name' => 'Passion Juice', 'description' => 'Tangy passion fruit juice.', 'price' => 250],
            ['name' => 'Orange Juice', 'description' => 'Freshly squeezed orange juice.', 'price' => 250],
            ['name' => 'Watermelon Juice', 'description' => 'Refreshing watermelon blend.', 'price' => 200],
            ['name' => 'Alvaro', 'description' => 'Premium malt soft drink.', 'price' => 150],
            ['name' => 'Water', 'description' => 'Still bottled water.', 'price' => 100],
            ['name' => 'Delmonte', 'description' => 'Packaged fruit juice options.', 'price' => 150],
        ],
    ],
    [
        'id' => 'hot',
        'emoji' => '☕',
        'title' => 'Hot Drinks',
        'image' => '/assets/images/menu-beverages.png',
        'items' => [
            ['name' => 'Kenyan Chai', 'description' => 'Authentic Kenyan tea blend.', 'price' => 100],
            ['name' => 'Black Coffee', 'description' => 'Freshly brewed black coffee.', 'price' => 150],
            ['name' => 'White Coffee', 'description' => 'Coffee served with milk.', 'price' => 200],
            ['name' => 'Hot Chocolate', 'description' => 'Creamy hot cocoa.', 'price' => 200],
            ['name' => 'Ginger Lemon Tea', 'description' => 'Soothing ginger and lemon infusion.', 'price' => 150],
            ['name' => 'Milo', 'description' => 'Warm malt chocolate drink.', 'price' => 150],
        ],
    ],
    [
        'id' => 'traditional',
        'emoji' => '🌿',
        'title' => 'Traditional',
        'image' => '/assets/images/hero-section.jpg',
        'items' => [
            ['name' => 'Muratina', 'description' => 'Traditional fermented beverage.', 'price' => 300],
            ['name' => 'Uji wa Wimbi', 'description' => 'Nutritious millet porridge.', 'price' => 150],
            ['name' => 'Sugarcane Juice', 'description' => 'Fresh sugarcane press.', 'price' => 150],
            ['name' => 'Tamarind Water', 'description' => 'Tangy tamarind refreshment.', 'price' => 150],
            ['name' => 'Hibiscus Tea', 'description' => 'Warm hibiscus infusion.', 'price' => 200],
        ],
    ],
    [
        'id' => 'beers',
        'emoji' => '🍺',
        'title' => 'Beers',
        'image' => '/assets/images/menu-beverages.png',
        'items' => [
            ['name' => 'Tusker Lager', 'description' => 'Classic Kenyan lager.', 'price' => 350],
            ['name' => 'Tusker Malt', 'description' => 'Smooth malt lager.', 'price' => 350],
            ['name' => 'White Cap', 'description' => 'Crisp and refreshing.', 'price' => 350],
            ['name' => 'Pilsner', 'description' => 'Bold pilsner profile.', 'price' => 350],
            ['name' => 'Savanna Cider', 'description' => 'Premium cider selection.', 'price' => 400],
        ],
    ],
];

$forkFallbackSvg = rawurlencode(
    '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 200"><rect width="320" height="200" fill="#0f3320"/><path d="M115 45v45m15-45v45m15-45v45m0 0c0 8-6 14-15 14v45h-15v-45c-9 0-15-6-15-14m65-45v104m0-58c16 0 16-22 0-22h-6v22z" stroke="#d4af37" stroke-width="10" fill="none" stroke-linecap="round"/><text x="160" y="172" text-anchor="middle" fill="#d4af37" font-family="Montserrat,Arial,sans-serif" font-size="14" letter-spacing="2">SIDAI MENU</text></svg>'
);
$fallbackImage = "data:image/svg+xml;utf8,{$forkFallbackSvg}";

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>
<main class="pt-28 lg:pt-32 bg-cream">
    <section class="relative overflow-hidden bg-forest py-16 sm:py-20">
        <div class="absolute inset-0 opacity-25 pointer-events-none" style="background-image: repeating-linear-gradient(45deg, rgba(212,175,55,0.22) 0, rgba(212,175,55,0.22) 2px, transparent 2px, transparent 20px), repeating-linear-gradient(-45deg, rgba(212,175,55,0.18) 0, rgba(212,175,55,0.18) 2px, transparent 2px, transparent 24px);"></div>
        <div class="relative mx-auto max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <p class="text-sm uppercase tracking-[0.24em] text-gold">Sidai Kitchen</p>
            <h1 class="mt-3 font-display text-5xl text-gold sm:text-6xl">Our Menu</h1>
            <p class="mx-auto mt-4 max-w-3xl font-serif text-lg italic text-cream sm:text-xl">Nothing but the Best - from our kitchen to your table.</p>
        </div>
    </section>

    <section class="sticky top-[92px] z-40 border-y border-gold/25 bg-night/95 backdrop-blur-sm lg:top-[122px]">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <nav class="menu-tabs flex gap-2 overflow-x-auto py-3 no-scrollbar" aria-label="Menu Sections">
                <?php foreach ($menuSections as $index => $section): ?>
                    <button type="button" data-tab-target="<?php echo safe_html($section['id']); ?>" class="menu-tab whitespace-nowrap rounded-full border border-gold/30 px-4 py-2 text-xs font-semibold uppercase tracking-[0.1em] transition-colors <?php echo $index === 0 ? 'bg-gold text-night' : 'bg-night text-gold'; ?>">
                        <?php echo safe_html($section['emoji'] . ' ' . $section['title']); ?>
                    </button>
                <?php endforeach; ?>
            </nav>
        </div>
    </section>

    <section class="py-12 sm:py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-16 sm:space-y-20">
            <?php foreach ($menuSections as $section): ?>
                <article id="<?php echo safe_html($section['id']); ?>" class="menu-section scroll-mt-48">
                    <div class="mb-6 flex items-end justify-between gap-4">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-earth">Category</p>
                            <h2 class="mt-1 font-display text-4xl text-brown"><?php echo safe_html($section['emoji'] . ' ' . $section['title']); ?></h2>
                        </div>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <?php foreach ($section['items'] as $item): ?>
                            <article class="menu-card overflow-hidden rounded-2xl border border-brown/12 bg-white shadow-sm">
                                <div class="h-44 overflow-hidden bg-forest">
                                    <img
                                        src="<?php echo WEB_ROOT . safe_html($section['image']); ?>"
                                        alt="<?php echo safe_html($item['name']); ?>"
                                        loading="lazy"
                                        class="h-full w-full object-cover"
                                        data-fallback-src="<?php echo safe_html($fallbackImage); ?>"
                                    >
                                </div>
                                <div class="flex min-h-[190px] flex-col p-5">
                                    <h3 class="font-display text-3xl leading-tight text-gold"><?php echo safe_html($item['name']); ?></h3>
                                    <p class="mt-2 flex-grow font-serif text-sm leading-6 text-brown/85"><?php echo safe_html($item['description']); ?></p>
                                    <p class="mt-4 font-sans text-base font-bold tracking-wide text-gold">KSh <?php echo number_format((float)$item['price'], 0); ?></p>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="menu-preorder" class="bg-night py-14 sm:py-16">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-gold/25 bg-white p-6 sm:p-8">
                <p class="text-xs font-semibold uppercase tracking-[0.24em] text-earth">Pre-Order</p>
                <h2 class="mt-2 font-display text-4xl text-brown">Send Your Menu Request</h2>
                <p class="mt-3 text-sm leading-6 text-brown/80">Submit your order details and our team will confirm availability and timing via WhatsApp.</p>

                <form id="menuOrderForm" action="<?php echo WEB_ROOT; ?>/api/menu-order.php" method="post" class="mt-6 space-y-5">
                    <?php echo csrf_token_field(); ?>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="name" class="mb-2 block text-sm font-semibold text-brown">Name</label>
                            <input id="name" name="name" type="text" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        </div>
                        <div>
                            <label for="phone" class="mb-2 block text-sm font-semibold text-brown">Phone</label>
                            <input id="phone" name="phone" type="tel" required placeholder="07xxxxxxxx or 2547xxxxxxxx" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="room_table" class="mb-2 block text-sm font-semibold text-brown">Room / Table</label>
                            <input id="room_table" name="room_table" type="text" placeholder="Room 107 / Table 4 / Pickup" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        </div>
                        <div>
                            <label for="date_time" class="mb-2 block text-sm font-semibold text-brown">Date & Time</label>
                            <input id="date_time" name="date_time" type="datetime-local" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        </div>
                    </div>

                    <div>
                        <label for="items" class="mb-2 block text-sm font-semibold text-brown">Items</label>
                        <textarea id="items" name="items" rows="4" required placeholder="Example: 2x Goat Choma 500g, 1x Mango Juice, 1x Fruit Salad" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40"></textarea>
                    </div>
                    <div>
                        <label for="special_instructions" class="mb-2 block text-sm font-semibold text-brown">Special Instructions</label>
                        <textarea id="special_instructions" name="special_instructions" rows="3" placeholder="Dietary needs, no onions, less chili, serving notes..." class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40"></textarea>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gold px-8 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-night transition hover:bg-gold-light">
                            Submit Pre-Order
                        </button>
                        <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center rounded-full border border-[#25D366] px-8 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-[#25D366] transition hover:bg-[#25D366] hover:text-white">
                            WhatsApp Fallback
                        </a>
                    </div>
                    <p id="menuOrderFeedback" class="hidden rounded-xl border px-4 py-3 text-sm font-medium"></p>
                </form>
            </div>
        </div>
    </section>
</main>

<style>
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
</style>

<script>
(() => {
    const tabs = Array.from(document.querySelectorAll('.menu-tab'));
    const sections = Array.from(document.querySelectorAll('.menu-section'));
    const cards = Array.from(document.querySelectorAll('.menu-card'));
    const tabOffset = () => (window.innerWidth >= 1024 ? 190 : 150);

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const targetId = tab.getAttribute('data-tab-target');
            const section = document.getElementById(targetId);
            if (!section) {
                return;
            }
            window.scrollTo({
                top: section.getBoundingClientRect().top + window.scrollY - tabOffset(),
                behavior: 'smooth',
            });
        });
    });

    const setActiveTab = (id) => {
        tabs.forEach((tab) => {
            const active = tab.getAttribute('data-tab-target') === id;
            tab.classList.toggle('bg-gold', active);
            tab.classList.toggle('text-night', active);
            tab.classList.toggle('bg-night', !active);
            tab.classList.toggle('text-gold', !active);
        });
    };

    if (window.gsap && window.ScrollTrigger) {
        window.gsap.registerPlugin(window.ScrollTrigger);

        sections.forEach((section) => {
            const sectionCards = section.querySelectorAll('.menu-card');
            window.gsap.from(section.querySelector('h2'), {
                opacity: 0,
                y: 18,
                duration: 0.45,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 84%',
                },
            });

            window.gsap.from(sectionCards, {
                opacity: 0,
                y: 22,
                duration: 0.4,
                stagger: 0.08,
                ease: 'power2.out',
                scrollTrigger: {
                    trigger: section,
                    start: 'top 80%',
                },
            });

            window.ScrollTrigger.create({
                trigger: section,
                start: 'top center',
                end: 'bottom center',
                onEnter: () => setActiveTab(section.id),
                onEnterBack: () => setActiveTab(section.id),
            });
        });

        cards.forEach((card) => {
            card.addEventListener('mouseenter', () => {
                window.gsap.to(card, {
                    y: -8,
                    boxShadow: '0 14px 32px rgba(212, 175, 55, 0.28)',
                    borderColor: 'rgba(212, 175, 55, 0.6)',
                    duration: 0.25,
                    ease: 'power2.out',
                });
            });
            card.addEventListener('mouseleave', () => {
                window.gsap.to(card, {
                    y: 0,
                    boxShadow: '0 1px 2px rgba(17, 24, 39, 0.06)',
                    borderColor: 'rgba(61, 28, 2, 0.12)',
                    duration: 0.22,
                    ease: 'power2.inOut',
                });
            });
        });
    } else if (sections[0]) {
        setActiveTab(sections[0].id);
    }

    document.querySelectorAll('img[data-fallback-src]').forEach((img) => {
        img.addEventListener('error', () => {
            const fallback = img.getAttribute('data-fallback-src');
            if (fallback && img.src !== fallback) {
                img.src = fallback;
            }
        }, { once: true });
    });

    const form = document.getElementById('menuOrderForm');
    const feedback = document.getElementById('menuOrderFeedback');
    if (!form || !feedback) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        feedback.className = 'rounded-xl border px-4 py-3 text-sm font-medium block border-brown/20 bg-cream text-brown';
        feedback.textContent = 'Submitting your pre-order...';

        try {
            const payload = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: payload,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Unable to submit pre-order right now.');
            }

            feedback.className = 'rounded-xl border px-4 py-3 text-sm font-medium block border-green-300 bg-green-50 text-green-800';
            feedback.textContent = "We'll confirm via WhatsApp.";
            form.reset();
        } catch (error) {
            feedback.className = 'rounded-xl border px-4 py-3 text-sm font-medium block border-red-300 bg-red-50 text-red-700';
            feedback.textContent = (error && error.message) ? error.message : 'Something went wrong. Please use WhatsApp fallback.';
        }
    });
})();
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>
