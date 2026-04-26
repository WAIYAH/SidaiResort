import re

with open("d:/MY PROJECTS/6. Financial & Business Systems/sidai-safari-dreams/public/services.php", "r", encoding="utf-8") as f:
    content = f.read()

# Replace Page Title, Image
content = re.sub(r"\$pageTitle = '.*?';", "$pageTitle = 'Our Services | Swimming, Events, Conferences, Birdwatching & More | Sidai Resort';", content)
content = re.sub(r"\$pageImage = .*?;", "$pageImage = APP_URL . '/assets/images/sidai-logo.png';", content)

html_part = """<main class="pt-28 lg:pt-32">
    <!-- Hero Section -->
    <section class="relative overflow-hidden h-[50vh] min-h-[400px]">
        <div class="absolute inset-0">
            <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg" alt="Sidai Resort Services" class="h-full w-full object-cover" data-parallax-speed="0.15">
            <div class="absolute inset-0 bg-gradient-to-t from-night via-night/60 to-transparent"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 h-full flex flex-col justify-center sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-gold mb-4" data-aos="fade-down">What We Offer</p>
            <h1 class="font-display text-5xl text-white sm:text-6xl lg:text-7xl mb-6" data-aos="fade-up" data-aos-delay="100">Everything You Could Want.<br>All in One Place.</h1>
            <p class="text-lg text-cream/90 max-w-2xl mx-auto italic font-serif" data-aos="fade-up" data-aos-delay="200">From sunrise birdwatching to bonfire nights — Sidai does it all.</p>
        </div>
    </section>

    <!-- Services Sticky Navigation -->
    <div class="sticky top-[72px] sm:top-[88px] z-40 bg-white/90 backdrop-blur-md border-b border-brown/10 shadow-sm overflow-x-auto">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex gap-3 whitespace-nowrap min-w-max pb-2 md:pb-0 md:min-w-0 md:flex-wrap md:justify-center">
                <a href="#pool" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🏊 Pool</a>
                <a href="#events" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🎉 Events</a>
                <a href="#conference" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🏢 Conferences</a>
                <a href="#accommodation" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🛏️ Rooms</a>
                <a href="#goat-eating" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🍖 Goat Eating</a>
                <a href="#bonfires" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🔥 Bonfires</a>
                <a href="#birdwatching" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🐦 Birding</a>
                <a href="#farm" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🌾 Farm Visits</a>
                <a href="#picnics" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">🧺 Picnics</a>
                <a href="#more" class="rounded-full border border-brown/20 bg-cream/50 px-4 py-2 text-sm font-medium text-brown hover:bg-gold hover:text-night transition-colors">✨ More</a>
            </div>
        </div>
    </div>

    <!-- SERVICE 1: POOL -->
    <section id="pool" class="service-block bg-cream py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/swimming-pool.jpg" alt="Swimming Pool" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🏊</div>
                    <h2 class="font-display text-4xl text-brown mb-4">The Pool</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        Dive into our pristine swimming pool surrounded by the cool forest air of the Loita Hills. Whether you are splashing with family, swimming laps at dawn, or simply floating with a cold drink in hand — our pool is a world of its own. Pool sessions available for day guests and resort guests alike.
                    </p>
                    <ul class="space-y-2 mb-8 text-brown/90 font-medium">
                        <li>✓ Day passes available</li>
                        <li>✓ Safe for families</li>
                        <li>✓ Poolside refreshments</li>
                        <li>✓ Forest views from the water</li>
                    </ul>
                    <a href="<?php echo WEB_ROOT; ?>/booking?type=pool" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Book a Pool Day</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 2: EVENTS -->
    <section id="events" class="service-block bg-white py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row-reverse gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/conferencing.jpg" alt="Events" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🎉</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Celebrations, Sherehe & Milestones</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        At Sidai, every celebration becomes legendary. Our two magnificent event halls — Enchula and Entumo — are ready for your wedding reception, birthday party, sherehe, graduation, fundraiser, or any occasion that deserves to be remembered. With full catering, AV equipment, décor support, and a dedicated events team — you bring the people, we build the memory.
                    </p>
                    <ul class="space-y-2 mb-8 text-brown/90 font-medium">
                        <li>✓ Weddings & Receptions</li>
                        <li>✓ Birthday Parties</li>
                        <li>✓ Sherehe & Cultural Events</li>
                        <li>✓ Graduations & Fundraisers</li>
                    </ul>
                    <a href="<?php echo WEB_ROOT; ?>/events" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Plan Your Event</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 3: CONFERENCES -->
    <section id="conference" class="service-block bg-cream py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/conferencing.jpg" alt="Conferences" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🏢</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Conferences, Meetings & Outdoor Sessions</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        Ideas grow differently when surrounded by hills instead of walls. Sidai Resort offers professional indoor conference facilities in Enchula and Entumo — fully equipped with AV, high-speed Wi-Fi, flexible seating, and dedicated catering. For those who think best in the open air, we also offer outdoor meeting spaces where the Loita Hills become your backdrop and fresh air fuels fresh thinking. Nothing but the best for your team.
                    </p>
                    <ul class="space-y-2 mb-8 text-brown/90 font-medium">
                        <li>✓ Indoor halls & Outdoor meeting areas</li>
                        <li>✓ AV & projector</li>
                        <li>✓ Wi-Fi & Custom catering</li>
                        <li>✓ Event coordinator</li>
                    </ul>
                    <a href="<?php echo WEB_ROOT; ?>/booking?type=conference" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Book a Conference</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 4: ACCOMMODATION -->
    <section id="accommodation" class="service-block bg-white py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row-reverse gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg" alt="Accommodation" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🛏️</div>
                    <h2 class="font-display text-4xl text-brown mb-4">The Sanctuaries — 11 Maasai-Named Rooms</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        Rest in one of our eleven uniquely named sanctuaries — each bearing a Maasai name, each designed so that comfort and tradition speak the same language. From Emirishoi to Elalai, every room is a story. Every morning is the forest. Every evening is the hills. We offer nothing but the best — because you deserve nothing less.
                    </p>
                    <div class="flex flex-wrap gap-2 mb-8 text-sm font-medium text-forest">
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Emirishoi</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Enchipai</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Eserian</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Empiris</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Ewangan</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Enkanasa</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Esipil</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Enkipai</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Eripoto</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full">Ereto</span>
                        <span class="bg-forest/10 px-3 py-1 rounded-full text-gold bg-night font-bold">Elalai ⭐</span>
                    </div>
                    <a href="<?php echo WEB_ROOT; ?>/rooms" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">View All Rooms</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 5: GOAT EATING -->
    <section id="goat-eating" class="service-block bg-cream py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/dining.jpg" alt="Goat Eating" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🍖</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Nyama Choma — The Maasai Way</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        There are few pleasures on earth as honest and as joyful as a Maasai-style goat roast. At Sidai, we do nyama choma the proper way — slow, smoky, and surrounded by the sounds of the Loita Hills forest. Whether it is a family gathering, a group celebration, or simply your idea of the perfect afternoon — our goat eating experience is a Sidai signature that guests return for again and again.
                    </p>
                    <ul class="space-y-2 mb-8 text-brown/90 font-medium">
                        <li>✓ Whole goat packages</li>
                        <li>✓ Group bookings welcome</li>
                        <li>✓ Traditional preparation</li>
                        <li>✓ Paired with local sides & drinks</li>
                    </ul>
                    <a href="<?php echo WEB_ROOT; ?>/contact?subject=Goat+Eating" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Enquire About Nyama Choma</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 6: BONFIRES -->
    <section id="bonfires" class="service-block bg-white py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row-reverse gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="Bonfires" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🔥🌅</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Enkima Bonfires & Balcony Sundowners</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        As the Loita Hills turn gold and the equatorial sky begins its nightly performance, Sidai offers two of Kenya's finest evening rituals.
                    </p>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        Enkima — our signature bonfire experience — gathers guests around an open fire under a canopy of unpolluted stars. Stories emerge. Friendships form. The Milky Way arrives uninvited and stays until dawn.
                    </p>
                    <p class="text-lg text-brown/80 mb-8 leading-relaxed">
                        From the balcony, sundowners are a different kind of magic — the hills glowing amber, a cold drink in hand, the day dissolving beautifully into evening. Both experiences are available nightly for all resort guests.
                    </p>
                    <a href="<?php echo WEB_ROOT; ?>/booking?type=bonfire" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Book Your Evening</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 7: BIRDWATCHING -->
    <section id="birdwatching" class="service-block bg-cream py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg" alt="Birdwatching" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🐦</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Birdwatching in the Loita Hills</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        The Loita Hills ecosystem is one of Kenya's most biodiverse birding destinations — home to over 300 species across forest, grassland, and wetland habitats. At Sidai, expert-guided birdwatching walks are available at dawn (the golden hour for birders) and throughout the day. From rare forest endemics to spectacular raptors riding the hill thermals — every walk is a discovery.
                    </p>
                    <ul class="space-y-2 mb-8 text-brown/90 font-medium">
                        <li>✓ Expert-guided walks</li>
                        <li>✓ Dawn & daytime sessions</li>
                        <li>✓ All skill levels welcome</li>
                        <li>✓ Checklist provided</li>
                    </ul>
                    <a href="<?php echo WEB_ROOT; ?>/booking?type=birdwatching" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Book a Birdwatching Walk</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 8: FARM VISITS -->
    <section id="farm" class="service-block bg-white py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row-reverse gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="Farm Visits" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🌾</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Farm Visits & Agricultural Experiences</h2>
                    <p class="text-lg text-brown/80 mb-8 leading-relaxed">
                        Connect with the land. Sidai Resort's farm visit experience takes guests into the living, working agricultural world of Naroosura — where traditional Maasai farming practices meet sustainable modern methods. Walk the farm, meet the animals, understand the soil. Whether for curious children or reflective adults, a farm visit at Sidai reconnects you with something essential and deeply satisfying.
                    </p>
                    <a href="<?php echo WEB_ROOT; ?>/contact?subject=Farm+Visit" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Enquire About Farm Visits</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 9: PICNICS -->
    <section id="picnics" class="service-block bg-cream py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/swimming-pool.jpg" alt="Picnics" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">🧺</div>
                    <h2 class="font-display text-4xl text-brown mb-4">Curated Picnic Experiences</h2>
                    <p class="text-lg text-brown/80 mb-8 leading-relaxed">
                        Pack a memory instead of a bag. Sidai Resort's curated picnic experiences take you into the most beautiful corners of our forested grounds — shaded by ancient trees, cooled by the Loita Hills breeze, and served with freshly prepared food from our kitchen. Perfect for couples, families, and friend groups who understand that the best meals are eaten outside.
                    </p>
                    <a href="<?php echo WEB_ROOT; ?>/booking?type=picnic" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Book a Picnic</a>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICE 10: MORE -->
    <section id="more" class="service-block bg-white py-20 overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row-reverse gap-12 items-center">
                <div class="w-full md:w-1/2 service-img">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="And So Much More" class="rounded-3xl shadow-xl w-full h-[400px] object-cover">
                </div>
                <div class="w-full md:w-1/2 service-content">
                    <div class="text-4xl mb-4">✨</div>
                    <h2 class="font-display text-4xl text-brown mb-4">And So Much More</h2>
                    <p class="text-lg text-brown/80 mb-8 leading-relaxed">
                        Nature walks through the forest. Cultural Maasai experiences. Outdoor yoga in the morning mist. Group games on the grounds. Photography walks through the hills. At Sidai, there is always something to discover, something to do, and something to feel. Ask our team — we will make it happen.
                    </p>
                    <a href="<?php echo WEB_ROOT; ?>/contact" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Contact Us</a>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            const blocks = document.querySelectorAll('.service-block');
            
            blocks.forEach((block, index) => {
                const img = block.querySelector('.service-img');
                const content = block.querySelector('.service-content');
                const isEven = index % 2 === 0;

                gsap.fromTo(img, 
                    { x: isEven ? -100 : 100, opacity: 0 },
                    { x: 0, opacity: 1, duration: 1, ease: 'power3.out',
                      scrollTrigger: { trigger: block, start: 'top 80%' } }
                );

                gsap.fromTo(content, 
                    { x: isEven ? 100 : -100, opacity: 0 },
                    { x: 0, opacity: 1, duration: 1, ease: 'power3.out', delay: 0.2,
                      scrollTrigger: { trigger: block, start: 'top 80%' } }
                );
            });
        }
    });
    </script>
</main>
<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>"""

content = re.sub(r"<main class=\"pt-28 lg:pt-32\">.*", html_part, content, flags=re.DOTALL)

with open("d:/MY PROJECTS/6. Financial & Business Systems/sidai-safari-dreams/public/services.php", "w", encoding="utf-8") as f:
    f.write(content)
