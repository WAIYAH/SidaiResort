import re

with open("d:/MY PROJECTS/6. Financial & Business Systems/sidai-safari-dreams/public/index.php", "r", encoding="utf-8") as f:
    content = f.read()

# Replace Page Title, Description, Image
content = re.sub(r"\$pageTitle = '.*?';", "$pageTitle = 'Sidai Resort — Where Your Needs Are Our Goals | Naroosura, Narok Kenya';", content)
content = re.sub(r"\$pageDescription = '.*?';", "$pageDescription = 'Sidai Resort in Naroosura, Loita Hills, Narok County Kenya. Luxury resort for picnics, weddings, conferences, birdwatching, bonfires, accommodation & sundowners. Excellence is our minimum.';", content)
content = re.sub(r"\$pageImage = .*?;", "$pageImage = APP_URL . '/assets/images/sidai-logo.png';", content)

# Add structured data before include head.php
structured_data = """$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'LodgingBusiness',
    'name' => 'Sidai Resort',
    'description' => 'Premium nature resort in Naroosura, Loita Hills, Narok County, Kenya',
    'url' => APP_URL,
    'telephone' => ['+254703761951', '+254721940823'],
    'email' => 'sidairesort21@gmail.com',
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Naroosura, Loita Hills',
        'addressLocality' => 'Narok',
        'postalCode' => '20500',
        'addressCountry' => 'KE'
    ],
    'sameAs' => [
        'https://facebook.com/SidaiResort',
        'https://instagram.com/SidaiResort',
        'https://tiktok.com/@SidaiResort'
    ]
];

include dirname(__DIR__) . '/app/includes/head.php';"""

content = re.sub(r"include dirname\(__DIR__\) \. '/app/includes/head.php';", structured_data, content)

html_part = """<main class="pt-28 lg:pt-32">
    <!-- HERO SECTION 1: THE GRAND WELCOME -->
    <section id="hero-1" class="relative isolate flex min-h-[100dvh] items-center overflow-hidden snap-start">
        <div class="absolute inset-0">
            <img src="/assets/images/hero-sunset.jpg" alt="Forest Resort" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-night/55"></div>
            <div id="particles-js" class="absolute inset-0" aria-hidden="true"></div>
        </div>
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 text-center sm:px-6 lg:px-8">
            <p id="hero1-badge" class="mb-6 inline-block rounded-full bg-gold px-4 py-1.5 text-xs font-semibold uppercase tracking-[0.2em] text-night opacity-0">
                Naroosura &middot; Loita Hills &middot; Narok County
            </p>
            <h1 id="hero1-title" class="font-display text-5xl leading-tight text-white sm:text-7xl lg:text-8xl">
                <span class="block">SIDAI RESORT</span>
                <span id="hero1-subtitle" class="block text-3xl sm:text-5xl lg:text-6xl text-gold italic mt-4 opacity-0 -translate-x-10">Where Excellence Meets the Wild</span>
            </h1>
            <p id="hero1-desc" class="mx-auto mt-8 max-w-3xl font-serif text-xl sm:text-2xl text-cream italic opacity-0 translate-y-10">
                "Sidai — a Maasai word meaning Excellence."<br>
                "Here, it is not a promise. It is the irreducible minimum."
            </p>
            <div id="hero1-buttons" class="mt-10 flex flex-wrap justify-center gap-4 opacity-0 scale-90">
                <a href="/booking" class="rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Book Your Stay</a>
                <a href="/services" class="rounded-full border-2 border-cream text-cream px-8 py-3.5 text-sm font-bold uppercase tracking-wider transition hover:bg-cream hover:text-night">Explore the Resort</a>
            </div>
            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 text-center">
                <p class="text-xs uppercase tracking-[0.2em] text-cream mb-2">Scroll to discover</p>
                <div class="animate-bounce text-gold text-2xl">&darr;</div>
            </div>
        </div>
    </section>

    <!-- HERO SECTION 2: THE EXPERIENCE -->
    <section id="hero-2" class="relative isolate flex min-h-[100dvh] items-center overflow-hidden snap-start bg-forest">
        <div class="absolute inset-0 lg:w-1/2">
            <img src="/assets/images/swimming-pool.jpg" alt="Resort Lifestyle" class="h-full w-full object-cover" id="hero2-img">
        </div>
        <div class="relative z-10 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8 flex justify-end">
            <div class="lg:w-1/2 lg:pl-16 py-20" id="hero2-content">
                <p class="font-serif text-2xl text-gold italic mb-4">The Sidai Experience</p>
                <h2 class="font-display text-5xl sm:text-6xl text-white mb-6 leading-tight">
                    Breathtaking Experiences.<br>Bliss at Its Best.
                </h2>
                <p class="text-lg text-cream/90 leading-relaxed mb-10">
                    Perched on the slopes of the Loita Hills, Sidai Resort is where the Maasai heartland opens its arms. Whether you are here to celebrate, to rest, to work, or simply to breathe — the forest, the hills, and our people will take care of the rest.
                </p>
                <div class="grid grid-cols-2 gap-6 mb-10">
                    <div class="flex items-center gap-3 text-cream">
                        <span class="text-2xl">🏊</span>
                        <span class="font-medium">Swimming Pool</span>
                    </div>
                    <div class="flex items-center gap-3 text-cream">
                        <span class="text-2xl">🔥</span>
                        <span class="font-medium">Bonfires & Sundowners</span>
                    </div>
                    <div class="flex items-center gap-3 text-cream">
                        <span class="text-2xl">🎂</span>
                        <span class="font-medium">Celebrations</span>
                    </div>
                    <div class="flex items-center gap-3 text-cream">
                        <span class="text-2xl">🐦</span>
                        <span class="font-medium">Birdwatching & Nature</span>
                    </div>
                </div>
                <a href="/services" class="inline-flex rounded-full bg-gold px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-gold-light">Discover All Experiences</a>
            </div>
        </div>
    </section>

    <!-- HERO SECTION 3: THE INVITATION -->
    <section id="hero-3" class="relative isolate flex min-h-[100dvh] items-center overflow-hidden snap-start text-center">
        <div class="absolute inset-0">
            <img src="/assets/images/hero-section.jpg" alt="Loita Hills Landscape" class="h-full w-full object-cover object-bottom">
            <div class="absolute inset-0 bg-gradient-to-t from-gold/40 via-night/40 to-night/20"></div>
        </div>
        <div class="relative z-10 mx-auto w-full max-w-5xl px-4 sm:px-6 lg:px-8 mt-auto mb-32">
            <h2 class="font-display text-4xl sm:text-6xl lg:text-7xl text-white italic leading-tight" id="hero3-quote">
                <span class="block">"Come for the views."</span>
                <span class="block mt-2">"Stay for the feeling."</span>
                <span class="block mt-2">"Leave with a story."</span>
            </h2>
            <div id="hero3-divider" class="mx-auto mt-10 h-[2px] w-0 bg-gold"></div>
            <p id="hero3-subtext" class="mt-8 text-sm font-medium uppercase tracking-[0.3em] text-cream">
                NAROOSURA &middot; LOITA HILLS &middot; NAROK COUNTY &middot; KENYA
            </p>
            <div class="mt-12">
                <a href="/booking" class="inline-flex rounded-full bg-white px-10 py-4 text-sm font-bold uppercase tracking-wider text-night transition hover:bg-cream">Begin Your Journey</a>
            </div>
        </div>
    </section>

    <!-- SECTION A: QUICK STATS BAR -->
    <section id="stats" class="bg-night py-16">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 sm:grid-cols-2 lg:grid-cols-4 lg:px-8">
            <article class="text-center" data-aos="fade-up">
                <p class="text-5xl font-display text-gold" data-counter data-counter-target="11">0</p>
                <p class="mt-3 text-sm font-medium uppercase tracking-[0.2em] text-cream">Unique Sanctuaries</p>
            </article>
            <article class="text-center" data-aos="fade-up" data-aos-delay="100">
                <p class="text-5xl font-display text-gold" data-counter data-counter-target="2">0</p>
                <p class="mt-3 text-sm font-medium uppercase tracking-[0.2em] text-cream">Grand Event Halls</p>
            </article>
            <article class="text-center" data-aos="fade-up" data-aos-delay="200">
                <p class="text-5xl font-display text-gold" data-counter data-counter-target="300" data-counter-suffix="+">0</p>
                <p class="mt-3 text-sm font-medium uppercase tracking-[0.2em] text-cream">Bird Species</p>
            </article>
            <article class="text-center" data-aos="fade-up" data-aos-delay="300">
                <p class="text-5xl font-display text-gold">&infin;</p>
                <p class="mt-3 text-sm font-medium uppercase tracking-[0.2em] text-cream">Memories Made</p>
            </article>
        </div>
    </section>

    <!-- SECTION B: ABOUT SNIPPET -->
    <section id="about" class="bg-cream py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div data-aos="fade-right">
                <h2 class="font-display text-4xl text-brown sm:text-5xl leading-tight">Where the Loita Hills Begin and Luxury Never Ends</h2>
                <p class="mt-6 text-lg leading-relaxed text-brown/90">
                    Sidai Resort sits on the slopes of the magnificent Loita Hills in Naroosura, Narok County — a cool, forested sanctuary where nature's grandeur and genuine Maasai hospitality combine into something truly extraordinary.
                </p>
                <p class="mt-4 text-lg leading-relaxed text-brown/90">
                    We are the place for picnics, weddings, conferences, birthday parties, sherehe, bonfires, goat eating, birdwatching, farm visits, and sundowners from the balcony. We are Sidai — and Sidai means Excellence.
                </p>
                <p class="mt-6 text-xl font-serif italic text-forest">
                    "Your needs are our goals. Your memories are our mission."
                </p>
                <div class="mt-10">
                    <a href="/about" class="inline-flex rounded-full bg-forest px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-cream transition hover:bg-forest-light">Our Full Story</a>
                </div>
            </div>
            <div class="relative overflow-hidden rounded-3xl shadow-2xl" data-aos="fade-left">
                <img src="/assets/images/hero-section.jpg" alt="Resort grounds and Loita Hills view" class="h-[600px] w-full object-cover" data-parallax-speed="0.15">
            </div>
        </div>
    </section>

    <!-- SECTION C: WHAT WE OFFER -->
    <section id="services-teaser" class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl text-brown sm:text-5xl">What We Offer</h2>
                <p class="mt-4 text-lg text-brown/70">From thrilling celebrations to quiet natural escapes, everything you need is right here.</p>
            </div>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <a href="/services#pool" class="group block rounded-2xl border border-brown/10 bg-cream/30 p-8 transition hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-4xl mb-4">🏊</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Swimming Pool</h3>
                    <p class="text-brown/80">Dive into pure resort luxury</p>
                </a>
                <a href="/services#events" class="group block rounded-2xl border border-brown/10 bg-cream/30 p-8 transition hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl mb-4">🎉</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Events & Celebrations</h3>
                    <p class="text-brown/80">Weddings, parties, sherehe & more</p>
                </a>
                <a href="/services#conference" class="group block rounded-2xl border border-brown/10 bg-cream/30 p-8 transition hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl mb-4">🏢</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Conferences & Meetings</h3>
                    <p class="text-brown/80">Indoor and outdoor professional spaces</p>
                </a>
                <a href="/rooms" class="group block rounded-2xl border border-brown/10 bg-cream/30 p-8 transition hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl mb-4">🛏️</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Accommodation</h3>
                    <p class="text-brown/80">11 uniquely named Maasai sanctuaries</p>
                </a>
                <a href="/services#bonfires" class="group block rounded-2xl border border-brown/10 bg-cream/30 p-8 transition hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-4xl mb-4">🔥</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Bonfires & Sundowners</h3>
                    <p class="text-brown/80">Evenings under Loita Hills stars</p>
                </a>
                <a href="/services#nature" class="group block rounded-2xl border border-brown/10 bg-cream/30 p-8 transition hover:-translate-y-2 hover:shadow-xl" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-4xl mb-4">🐦</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Nature Experiences</h3>
                    <p class="text-brown/80">Birdwatching, farm visits & forest walks</p>
                </a>
            </div>
            <div class="mt-12 text-center" data-aos="fade-up">
                <a href="/services" class="inline-flex text-sm font-bold uppercase tracking-[0.2em] text-forest hover:text-gold transition-colors">View All Services &rarr;</a>
            </div>
        </div>
    </section>

    <!-- SECTION D: WHY CHOOSE SIDAI -->
    <section id="why-choose-us" class="bg-forest py-24 text-cream">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl sm:text-5xl text-gold">Why Choose Sidai</h2>
            </div>
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-3">
                <div class="flex gap-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full bg-gold/20 text-gold text-2xl border border-gold/40">🌿</div>
                    <div>
                        <h3 class="font-display text-2xl mb-2 text-white">Breathtaking Loita Hills Setting</h3>
                        <p class="text-cream/80 leading-relaxed">Perched on the slopes of the Loita Hills, Naroosura — our landscape is our first gift to every guest who arrives.</p>
                    </div>
                </div>
                <div class="flex gap-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full bg-gold/20 text-gold text-2xl border border-gold/40">🎯</div>
                    <div>
                        <h3 class="font-display text-2xl mb-2 text-white">Excellence Is Our Minimum Standard</h3>
                        <p class="text-cream/80 leading-relaxed">Sidai means excellence in Maasai. We do not aim for it — we guarantee it. Every guest, every visit, every moment.</p>
                    </div>
                </div>
                <div class="flex gap-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full bg-gold/20 text-gold text-2xl border border-gold/40">🔥</div>
                    <div>
                        <h3 class="font-display text-2xl mb-2 text-white">Experiences for Every Occasion</h3>
                        <p class="text-cream/80 leading-relaxed">Picnics, weddings, goat eating, sherehe, bonfires, conferences, sundowners — Sidai is built for every mood and every milestone.</p>
                    </div>
                </div>
                <div class="flex gap-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full bg-gold/20 text-gold text-2xl border border-gold/40">🐦</div>
                    <div>
                        <h3 class="font-display text-2xl mb-2 text-white">Nature, Wildlife & Birdwatching</h3>
                        <p class="text-cream/80 leading-relaxed">Over 300 bird species call the Loita Hills home. Farm visits, forest walks, and wildlife encounters await at every turn.</p>
                    </div>
                </div>
                <div class="flex gap-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full bg-gold/20 text-gold text-2xl border border-gold/40">🌅</div>
                    <div>
                        <h3 class="font-display text-2xl mb-2 text-white">Sundowners Like Nowhere Else</h3>
                        <p class="text-cream/80 leading-relaxed">Watch the sun sink behind the hills from our balcony, drink in hand. The kind of moment you will describe for years.</p>
                    </div>
                </div>
                <div class="flex gap-4" data-aos="fade-up" data-aos-delay="500">
                    <div class="flex-shrink-0 flex items-center justify-center w-14 h-14 rounded-full bg-gold/20 text-gold text-2xl border border-gold/40">📍</div>
                    <div>
                        <h3 class="font-display text-2xl mb-2 text-white">Genuinely Maasai, Genuinely Excellent</h3>
                        <p class="text-cream/80 leading-relaxed">Rooted in Maasai culture, delivered with world-class hospitality. Your needs are our goals — always.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION E: TESTIMONIALS CAROUSEL -->
    <section id="testimonials" class="bg-cream py-24 text-brown">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mb-12 text-center" data-aos="fade-up">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-earth">Guest Stories</p>
                <h2 class="mt-2 font-display text-4xl sm:text-5xl text-brown">Voices from Sidai</h2>
            </div>
            <div class="swiper testimonials-swiper" data-swiper-testimonials>
                <div class="swiper-wrapper">
                    <?php foreach ($testimonials as $entry): ?>
                        <?php
                            $guestName = trim((string)($entry['guest_name'] ?? 'Sidai Guest'));
                            $rating = max(1, min(5, (int)($entry['rating'] ?? 5)));
                            $message = trim((string)($entry['message'] ?? 'We had an unforgettable experience at Sidai Resort.'));
                        ?>
                        <article class="swiper-slide h-auto rounded-3xl border border-brown/10 bg-white p-10 shadow-lg">
                            <div class="mb-6 flex items-center gap-1 text-gold text-xl" aria-label="<?php echo safe_html((string)$rating); ?> star rating">
                                <?php for ($i = 0; $i < $rating; $i++): ?>
                                    <span>&#9733;</span>
                                <?php endfor; ?>
                            </div>
                            <p class="text-lg leading-relaxed text-brown/90 font-serif italic">"<?php echo safe_html($message); ?>"</p>
                            <p class="mt-8 text-sm font-bold uppercase tracking-[0.2em] text-earth"><?php echo safe_html($guestName); ?></p>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="mt-10 flex justify-center">
                    <div class="testimonials-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION F: FIND US ONLINE -->
    <section id="social-media" class="bg-white py-24 border-t border-brown/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-4xl sm:text-5xl text-brown mb-4" data-aos="fade-up">We Are Everywhere You Are</h2>
            <p class="text-lg text-brown/70 mb-16" data-aos="fade-up" data-aos-delay="100">Follow us, message us, tag us — search Sidai Resort on all platforms</p>
            
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <a href="https://facebook.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-[#1877F2]/10 text-[#1877F2] text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('facebook', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-brown mb-1">Sidai Resort</h3>
                    <p class="text-sm text-brown/60 mb-6">Facebook</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-[#1877F2] text-[#1877F2] text-sm font-bold group-hover:bg-[#1877F2] group-hover:text-white transition-colors">Follow Us</span>
                </a>
                
                <a href="https://instagram.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-gradient-to-tr from-[#fd1d1d]/10 via-[#fcb045]/10 to-[#833ab4]/10 text-[#e1306c] text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('instagram', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-brown mb-1">@SidaiResort</h3>
                    <p class="text-sm text-brown/60 mb-6">Instagram</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-[#e1306c] text-[#e1306c] text-sm font-bold group-hover:bg-gradient-to-tr group-hover:from-[#fd1d1d] group-hover:via-[#fcb045] group-hover:to-[#833ab4] group-hover:text-white transition-colors">Follow Us</span>
                </a>
                
                <a href="https://tiktok.com/@SidaiResort" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-black/5 text-black text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('tiktok', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-brown mb-1">@SidaiResort</h3>
                    <p class="text-sm text-brown/60 mb-6">TikTok</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-black text-black text-sm font-bold group-hover:bg-black group-hover:text-white transition-colors">Follow Us</span>
                </a>
                
                <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-gray-100 shadow-sm hover:shadow-xl transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-[#25D366]/10 text-[#25D366] text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('whatsapp', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-brown mb-1">0703 761 951</h3>
                    <p class="text-sm text-brown/60 mb-6">WhatsApp</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-[#25D366] text-[#25D366] text-sm font-bold group-hover:bg-[#25D366] group-hover:text-white transition-colors">Message Us</span>
                </a>
            </div>
        </div>
    </section>

    <!-- SECTION G: LOCATION TEASER -->
    <section id="location" class="bg-cream py-24">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8">
            <div data-aos="fade-right">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-earth mb-2">Find Us</p>
                <h2 class="font-display text-4xl text-brown sm:text-5xl leading-tight mb-6">Find Us in the Heart of the Maasai Highlands</h2>
                <div class="space-y-4 text-lg text-brown/90 mb-10">
                    <p class="flex items-center gap-3">
                        <span class="text-2xl">📍</span>
                        <span>Naroosura, Loita Hills, Narok County, Kenya</span>
                    </p>
                    <p class="flex items-center gap-3">
                        <span class="text-2xl">📞</span>
                        <a href="tel:0703761951" class="hover:text-gold transition-colors">0703 761 951</a> / <a href="tel:0721940823" class="hover:text-gold transition-colors">0721 940 823</a>
                    </p>
                    <p class="flex items-center gap-3">
                        <span class="text-2xl">✉️</span>
                        <a href="mailto:sidairesort21@gmail.com" class="hover:text-gold transition-colors">sidairesort21@gmail.com</a>
                    </p>
                </div>
                <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="inline-flex rounded-full bg-[#25D366] px-8 py-3.5 text-sm font-bold uppercase tracking-wider text-white shadow-lg transition hover:bg-[#1DA851] items-center gap-2">
                    <?php echo social_icon('whatsapp', 18); ?> Chat on WhatsApp
                </a>
            </div>
            <div class="overflow-hidden rounded-3xl border border-brown/10 shadow-xl" data-aos="fade-left">
                <iframe
                    title="Sidai Resort location map"
                    src="https://www.google.com/maps?q=Naroosura%2C%20Narok%20County%2C%20Kenya&output=embed"
                    class="h-[450px] w-full border-0"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                ></iframe>
            </div>
        </div>
    </section>

    <!-- GSAP Custom Animations for Heroes -->
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof gsap !== 'undefined' && typeof ScrollTrigger !== 'undefined') {
            gsap.registerPlugin(ScrollTrigger);

            // Particles.js initialization for Hero 1
            if(typeof particlesJS !== 'undefined') {
                particlesJS('particles-js', {
                    particles: {
                        number: { value: 50 },
                        color: { value: '#D4AF37' },
                        shape: { type: 'circle' },
                        opacity: { value: 0.5 },
                        size: { value: 3 },
                        move: { enable: true, speed: 1, direction: 'top', out_mode: 'out' }
                    }
                });
            }

            // Hero 1 Animation Sequence
            const tl1 = gsap.timeline({delay: 0.2});
            tl1.to('#hero1-badge', { y: -20, opacity: 1, duration: 0.8, ease: "power3.out" }, 0.3)
               .fromTo('#hero1-title span:first-child', { opacity: 0, y: 30 }, { opacity: 1, y: 0, duration: 1, ease: "power3.out" }, 0.6)
               .to('#hero1-subtitle', { x: 0, opacity: 1, duration: 1, ease: "power3.out" }, 1.0)
               .to('#hero1-desc', { y: 0, opacity: 1, duration: 1, ease: "power3.out" }, 1.3)
               .to('#hero1-buttons', { scale: 1, opacity: 1, duration: 0.8, ease: "back.out(1.7)" }, 1.6);

            // Hero 2 Animation
            gsap.to('#hero2-img', {
                yPercent: 20,
                ease: "none",
                scrollTrigger: {
                    trigger: "#hero-2",
                    start: "top bottom",
                    end: "bottom top",
                    scrub: true
                }
            });
            
            gsap.from('#hero2-content > *', {
                x: 50,
                opacity: 0,
                stagger: 0.15,
                duration: 1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#hero-2",
                    start: "top center+=100",
                }
            });

            // Hero 3 Animation
            const quoteLines = document.querySelectorAll('#hero3-quote span');
            gsap.from(quoteLines, {
                y: 30,
                opacity: 0,
                stagger: 0.4,
                duration: 1,
                ease: "power2.out",
                scrollTrigger: {
                    trigger: "#hero-3",
                    start: "top center",
                }
            });
            gsap.to('#hero3-divider', {
                width: "80px",
                duration: 1,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: "#hero-3",
                    start: "top center",
                }
            });
            gsap.from('#hero3-subtext', {
                y: 20,
                opacity: 0,
                duration: 1,
                delay: 1.2,
                scrollTrigger: {
                    trigger: "#hero-3",
                    start: "top center",
                }
            });
        }
    });
    </script>
</main>
<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>"""

content = re.sub(r"<main class=\"pt-28 lg:pt-32\">.*", html_part, content, flags=re.DOTALL)

with open("d:/MY PROJECTS/6. Financial & Business Systems/sidai-safari-dreams/public/index.php", "w", encoding="utf-8") as f:
    f.write(content)
