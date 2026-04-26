import re
from pathlib import Path

file_path = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static\index.html")

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Replace Hero Section with a 3-Slide Swiper
hero_pattern = re.compile(r'<section id="hero".*?</section>', re.DOTALL)

new_hero = """
    <section id="hero" class="relative overflow-hidden min-h-[100dvh]">
        <div class="swiper hero-swiper h-full w-full absolute inset-0">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide relative flex items-center justify-center">
                    <img src="assets/images/african-sunset.avif" alt="African Sunset" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-br from-night/80 via-night/45 to-earth/50"></div>
                    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 text-center sm:px-6 lg:px-8 pt-20">
                        <p class="mb-4 inline-flex items-center rounded-full border border-gold/40 bg-night/50 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-gold" data-swiper-parallax="-100">Sidai Resort</p>
                        <h1 class="font-display text-5xl leading-tight text-white sm:text-6xl lg:text-7xl drop-shadow-lg" data-swiper-parallax="-300">Where Good Meets Luxury</h1>
                        <p class="mt-4 max-w-2xl mx-auto text-base text-cream sm:text-lg" data-swiper-parallax="-200">Experience timeless safari elegance in the heart of Narok County.</p>
                        <div class="mt-8 flex justify-center gap-4" data-swiper-parallax="-100">
                            <a href="booking.html" class="inline-flex items-center rounded-full bg-gold px-7 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-night transition hover:bg-gold-light">Book Stay</a>
                            <a href="#about" class="inline-flex items-center rounded-full border border-gold/60 bg-night/30 px-7 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-gold transition hover:bg-gold hover:text-night backdrop-blur-sm">Explore</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide relative flex items-center justify-center">
                    <img src="assets/images/swimming-pool.jpeg" alt="Swimming Pool" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-br from-night/80 via-night/45 to-safari/50"></div>
                    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 text-center sm:px-6 lg:px-8 pt-20">
                        <p class="mb-4 inline-flex items-center rounded-full border border-gold/40 bg-night/50 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-gold" data-swiper-parallax="-100">Relax & Unwind</p>
                        <h1 class="font-display text-5xl leading-tight text-white sm:text-6xl lg:text-7xl drop-shadow-lg" data-swiper-parallax="-300">Dive Into Serenity</h1>
                        <p class="mt-4 max-w-2xl mx-auto text-base text-cream sm:text-lg" data-swiper-parallax="-200">Relax by our pristine pool surrounded by the lush Loita Hills forest.</p>
                        <div class="mt-8 flex justify-center gap-4" data-swiper-parallax="-100">
                            <a href="services.html#swimming" class="inline-flex items-center rounded-full bg-gold px-7 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-night transition hover:bg-gold-light">Discover More</a>
                        </div>
                    </div>
                </div>

                <!-- Slide 3 -->
                <div class="swiper-slide relative flex items-center justify-center">
                    <img src="assets/images/symboloflove-photo-area.jpeg" alt="Weddings and Events" class="absolute inset-0 h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-br from-night/80 via-night/45 to-forest/50"></div>
                    <div class="relative z-10 mx-auto w-full max-w-7xl px-4 text-center sm:px-6 lg:px-8 pt-20">
                        <p class="mb-4 inline-flex items-center rounded-full border border-gold/40 bg-night/50 px-4 py-1 text-xs font-semibold uppercase tracking-[0.25em] text-gold" data-swiper-parallax="-100">Celebrate With Us</p>
                        <h1 class="font-display text-5xl leading-tight text-white sm:text-6xl lg:text-7xl drop-shadow-lg" data-swiper-parallax="-300">Unforgettable Moments</h1>
                        <p class="mt-4 max-w-2xl mx-auto text-base text-cream sm:text-lg" data-swiper-parallax="-200">From grand weddings to intimate bonfires, we bring your dreams to life.</p>
                        <div class="mt-8 flex justify-center gap-4" data-swiper-parallax="-100">
                            <a href="services.html" class="inline-flex items-center rounded-full bg-gold px-7 py-3 text-sm font-semibold uppercase tracking-[0.15em] text-night transition hover:bg-gold-light">View Experiences</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="swiper-button-next text-gold after:text-2xl hidden md:flex"></div>
            <div class="swiper-button-prev text-gold after:text-2xl hidden md:flex"></div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        
        <a href="#stats" class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 hidden flex-col items-center gap-2 rounded-full px-4 py-2 text-xs uppercase tracking-[0.25em] text-gold lg:flex transition hover:text-white" aria-label="Scroll to statistics">
            Scroll
            <span class="inline-block animate-bounce">&darr;</span>
        </a>
    </section>

    <!-- Swiper Initialization Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if(window.Swiper) {
                new Swiper('.hero-swiper', {
                    speed: 1200,
                    parallax: true,
                    loop: true,
                    autoplay: {
                        delay: 6000,
                        disableOnInteraction: false,
                    },
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true,
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                    effect: 'fade',
                    fadeEffect: {
                        crossFade: true
                    }
                });
            }
        });
    </script>
"""
content = hero_pattern.sub(new_hero, content)

# 2. Make the long stories short in #about
about_pattern = re.compile(r'(<h2 class="mt-3 font-display text-4xl text-brown sm:text-5xl">Luxury rooted in culture, crafted for celebration\.</h2>).*?(<div class="mt-8 rounded-2xl)', re.DOTALL)
short_about = r'''\1
                <p class="mt-5 text-base leading-8 text-brown/90 sm:text-lg">
                    Relax, connect, and celebrate in style. We blend premium spaces with the warmth of Maasai hospitality to create unforgettable moments—whether for a quiet escape, a grand wedding, or a corporate retreat.
                </p>
                \2'''
content = about_pattern.sub(short_about, content)

# 3. Make long stories short in #why-choose-us and replace the grid logic entirely
why_pattern = re.compile(r'(<h2 class="font-display text-4xl sm:text-5xl text-white">The Sidai Standard</h2>\s*</div>).*?(<div class="mt-16 text-center text-sm text-cream/70)', re.DOTALL)
short_why = r'''\1
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-6 rounded-3xl shadow-lg hover:bg-gold/10 transition" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-3xl mb-3">🌿</div>
                    <h3 class="font-display text-xl text-gold mb-2 font-bold">1. Breathtaking Setting</h3>
                    <p class="text-cream/80 text-sm">Perched on the stunning slopes of the Loita Hills with unbelievable sunsets.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-6 rounded-3xl shadow-lg hover:bg-gold/10 transition" data-aos="fade-up" data-aos-delay="50">
                    <div class="text-3xl mb-3">🎯</div>
                    <h3 class="font-display text-xl text-gold mb-2 font-bold">2. Absolute Excellence</h3>
                    <p class="text-cream/80 text-sm">"Sidai" means excellence. We guarantee nothing but the absolute best.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-6 rounded-3xl shadow-lg hover:bg-gold/10 transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-3xl mb-3">🔥</div>
                    <h3 class="font-display text-xl text-gold mb-2 font-bold">3. Every Occasion</h3>
                    <p class="text-cream/80 text-sm">Built for every mood: grand weddings, large conferences, or intimate bonfires.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-6 rounded-3xl shadow-lg hover:bg-gold/10 transition" data-aos="fade-up" data-aos-delay="150">
                    <div class="text-3xl mb-3">🐦</div>
                    <h3 class="font-display text-xl text-gold mb-2 font-bold">4. Living Nature</h3>
                    <p class="text-cream/80 text-sm">A natural paradise teeming with over 300 bird species and ancient forests.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-6 rounded-3xl shadow-lg hover:bg-gold/10 transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-3xl mb-3">🌅</div>
                    <h3 class="font-display text-xl text-gold mb-2 font-bold">5. Golden Sundowners</h3>
                    <p class="text-cream/80 text-sm">Unmatched evening views and cocktails from our elevated outdoor balcony.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-6 rounded-3xl shadow-lg hover:bg-gold/10 transition" data-aos="fade-up" data-aos-delay="250">
                    <div class="text-3xl mb-3">📍</div>
                    <h3 class="font-display text-xl text-gold mb-2 font-bold">6. Home Away From Home</h3>
                    <p class="text-cream/80 text-sm">Authentic Maasai-inspired hospitality that treats every guest like family.</p>
                </div>
            </div>
            \2'''
content = why_pattern.sub(short_why, content)

# 4. Update Images used in Signature Services
content = content.replace('src="assets/images/serene-places.jpeg" alt="Swimming pool sessions at Sidai Resort"', 'src="assets/images/cool-swimimng.jpeg" alt="Swimming pool sessions at Sidai Resort"')
content = content.replace('src="assets/images/nightview-swimmingpool.jpeg" alt="Event hall setup at Sidai Resort"', 'src="assets/images/conference-setting.jpeg" alt="Event hall setup at Sidai Resort"')
content = content.replace('src="assets/images/outdoor-congreagation.jpeg" alt="Fine dining setup at Sidai Resort"', 'src="assets/images/goat-soup.jpeg" alt="Fine dining setup at Sidai Resort"')
content = content.replace('src="assets/images/hero-section.jpg" alt="Playground and activities"', 'src="assets/images/farm-eden-entrance.jpeg" alt="Nature and activities"')
content = content.replace('src="assets/images/outdoor-meetings.jpeg" alt="Music and film shoot location"', 'src="assets/images/symboloflove-photo-area.jpeg" alt="Music and film shoot location"')
content = content.replace('src="assets/images/nightview-swimmingpool.jpeg" alt="Conference and business facilities"', 'src="assets/images/conference-hall.jpeg" alt="Conference and business facilities"')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated index.html: 3 Hero Slides, shortened text, updated images.")
