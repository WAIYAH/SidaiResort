import re
from pathlib import Path

base_dir = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static")

logo_html = """<img src="/assets/images/Sidai-Logo.png" alt="Sidai Resort Logo" class="h-10 sm:h-12 w-auto object-contain flex-shrink-0">"""

about_story_html = """<!-- Our Story Section -->
    <section id="our-story" class="bg-cream py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center mb-20">
                <div data-aos="fade-right">
                    <h2 class="font-display text-4xl sm:text-5xl text-brown mb-6">🌿 About Sidai Resort</h2>
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-gold mb-6">"Where your needs are our goals"</p>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed font-serif">
                        Nestled on the breathtaking slopes of the <strong>Loita Hills in Naroosura, Narok County</strong>, Sidai Resort is nature's finest address — a sanctuary where the wild beauty of the Maasai heartland meets world-class hospitality. Here, ancient forests whisper, birds sing at dawn, and every sunset from the balcony is a masterpiece painted just for you.
                    </p>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed font-serif">
                        <strong>"Sidai"</strong> is a Maasai word meaning <em>excellence</em> — and excellence is not an aspiration here, it is the irreducible minimum. From the moment you arrive, exceptional service is simply the standard. Nothing less. Nothing but the best.
                    </p>
                    <p class="text-lg text-brown/80 leading-relaxed font-serif">
                        Whether you are seeking a tranquil escape from the city, a vibrant celebration with your loved ones, a productive conference away from the noise, or a soul-stirring encounter with raw nature — <strong>Sidai is the place to be.</strong>
                    </p>
                </div>
                <div class="relative" data-aos="fade-left">
                    <div class="absolute -inset-4 rounded-3xl border border-gold/30 transform rotate-3"></div>
                    <img src="/assets/images/Sunset.jpeg" alt="Sidai Resort Sunset" class="relative rounded-2xl shadow-2xl w-full h-[500px] object-cover">
                </div>
            </div>

            <div class="mt-20">
                <div class="text-center mb-12" data-aos="fade-up">
                    <h3 class="font-display text-4xl text-brown mb-4">🎉 What We Offer</h3>
                    <p class="text-lg text-brown/70 max-w-3xl mx-auto">From intimate picnics under ancient trees to grand weddings in our event halls, from sunrise birdwatching walks to unforgettable sundowners on the balcony as the Loita Hills glow golden — Sidai Resort is built for every kind of experience:</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🏊</span><div><h4 class="font-bold text-brown mb-1">Swimming</h4><p class="text-sm text-brown/70">Dive into our pristine pool surrounded by forest</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🎂</span><div><h4 class="font-bold text-brown mb-1">Birthday Parties & Sherehe</h4><p class="text-sm text-brown/70">Celebrate in style with full event support</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">💍</span><div><h4 class="font-bold text-brown mb-1">Weddings & Receptions</h4><p class="text-sm text-brown/70">Your perfect day in a perfect setting</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🏢</span><div><h4 class="font-bold text-brown mb-1">Conferences & Meetings</h4><p class="text-sm text-brown/70">Indoor and outdoor professional spaces</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🍖</span><div><h4 class="font-bold text-brown mb-1">Goat Eating</h4><p class="text-sm text-brown/70">Authentic Maasai-style nyama choma experiences</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🔥</span><div><h4 class="font-bold text-brown mb-1">Bonfires</h4><p class="text-sm text-brown/70">Magical evenings under a canopy of stars</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🛏️</span><div><h4 class="font-bold text-brown mb-1">Accommodation</h4><p class="text-sm text-brown/70">Comfortable, elegantly appointed rooms</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🐦</span><div><h4 class="font-bold text-brown mb-1">Birdwatching</h4><p class="text-sm text-brown/70">Over 300 species in the Loita Hills ecosystem</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🌾</span><div><h4 class="font-bold text-brown mb-1">Farm Visits</h4><p class="text-sm text-brown/70">Connect with the land and local agricultural life</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🌅</span><div><h4 class="font-bold text-brown mb-1">Sundowners</h4><p class="text-sm text-brown/70">Golden hour drinks from our stunning balcony</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🌳</span><div><h4 class="font-bold text-brown mb-1">Outdoor Meetings</h4><p class="text-sm text-brown/70">Fresh air, clear minds, better decisions</p></div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-gold/20 shadow-sm flex items-start gap-4 hover:shadow-md transition">
                        <span class="text-3xl">🧺</span><div><h4 class="font-bold text-brown mb-1">Picnics</h4><p class="text-sm text-brown/70">Curated picnic experiences in our forested grounds</p></div>
                    </div>
                </div>
            </div>
            
            <div class="mt-24 bg-white p-10 rounded-3xl border border-brown/10 shadow-xl text-center" data-aos="fade-up">
                <h3 class="font-display text-4xl text-brown mb-4">📱 Find Us Online</h3>
                <p class="text-brown/70 mb-8 max-w-2xl mx-auto">We are active and available across all major platforms. Follow us, DM us, tag us — we love hearing from you.</p>
                <div class="flex flex-wrap justify-center gap-4 sm:gap-8 mb-8">
                    <a href="https://facebook.com/sidairesort" target="_blank" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 rounded-full bg-[#1877F2]/10 text-[#1877F2] flex items-center justify-center text-2xl group-hover:bg-[#1877F2] group-hover:text-white transition-colors">📘</div>
                        <span class="text-sm font-semibold text-brown/80">Facebook</span>
                    </a>
                    <a href="https://instagram.com/sidairesort" target="_blank" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 rounded-full bg-[#E1306C]/10 text-[#E1306C] flex items-center justify-center text-2xl group-hover:bg-[#E1306C] group-hover:text-white transition-colors">📸</div>
                        <span class="text-sm font-semibold text-brown/80">Instagram</span>
                    </a>
                    <a href="https://wa.me/254703761951" target="_blank" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 rounded-full bg-[#25D366]/10 text-[#25D366] flex items-center justify-center text-2xl group-hover:bg-[#25D366] group-hover:text-white transition-colors">💬</div>
                        <span class="text-sm font-semibold text-brown/80">WhatsApp</span>
                    </a>
                    <a href="https://tiktok.com/@sidairesort" target="_blank" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 rounded-full bg-black/10 text-black flex items-center justify-center text-2xl group-hover:bg-black group-hover:text-white transition-colors">🎵</div>
                        <span class="text-sm font-semibold text-brown/80">TikTok</span>
                    </a>
                    <a href="mailto:sidairesort21@gmail.com" class="flex flex-col items-center gap-2 group">
                        <div class="w-14 h-14 rounded-full bg-forest/10 text-forest flex items-center justify-center text-2xl group-hover:bg-forest group-hover:text-white transition-colors">✉️</div>
                        <span class="text-sm font-semibold text-brown/80">Email</span>
                    </a>
                </div>
                <p class="text-sm font-semibold uppercase tracking-widest text-gold">Search "Sidai Resort" wherever you are.</p>
                <p class="text-sm text-brown/60 mt-4">WhatsApp: 0703 761 951 / 0721 940 823 | sidairesort21@gmail.com</p>
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section (Green Grid Replacement) -->
    <section class="bg-forest py-24 text-cream relative overflow-hidden" id="why-choose-us">
        <div class="absolute inset-0 z-0 opacity-10">
            <img src="/assets/images/hero-sunset.jpg" alt="Background Texture" class="w-full h-full object-cover">
        </div>
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-sm font-bold uppercase tracking-[0.2em] text-gold mb-2 block">✅ Why Choose Sidai Resort?</span>
                <h2 class="font-display text-4xl sm:text-5xl text-white">The Sidai Standard</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-8 rounded-3xl shadow-lg hover:bg-night/60 transition" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-4xl mb-4">🌿</div>
                    <h3 class="font-display text-2xl text-gold mb-3">1. Breathtaking Loita Hills Setting</h3>
                    <p class="text-cream/80 text-sm leading-relaxed">Perched on the slopes of the Loita Hills in Naroosura, Narok County, Sidai Resort offers landscapes so stunning they feel unreal — rolling hills, ancient forests, and skies so wide they humble you.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-8 rounded-3xl shadow-lg hover:bg-night/60 transition" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl mb-4">🎯</div>
                    <h3 class="font-display text-2xl text-gold mb-3">2. Excellence Is Our Minimum Standard</h3>
                    <p class="text-cream/80 text-sm leading-relaxed">"Sidai" means excellence in Maasai. We don't aim for it — we guarantee it. Every guest, every visit, every service is delivered with exceptional care. Nothing but the best, always.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-8 rounded-3xl shadow-lg hover:bg-night/60 transition" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl mb-4">🔥</div>
                    <h3 class="font-display text-2xl text-gold mb-3">3. Experiences for Every Occasion</h3>
                    <p class="text-cream/80 text-sm leading-relaxed">Picnics, weddings, birthday sherehe, goat eating, bonfires, conferences, sundowners — Sidai is built for every mood, every milestone, and every kind of gathering. One resort, infinite memories.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-8 rounded-3xl shadow-lg hover:bg-night/60 transition" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl mb-4">🐦</div>
                    <h3 class="font-display text-2xl text-gold mb-3">4. Nature, Wildlife & Birdwatching</h3>
                    <p class="text-cream/80 text-sm leading-relaxed">The Loita Hills ecosystem is alive with over 300 bird species, native wildlife, and ancient trees. For nature lovers, birdwatchers, and farm visitors, Sidai is a living paradise.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-8 rounded-3xl shadow-lg hover:bg-night/60 transition" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-4xl mb-4">🌅</div>
                    <h3 class="font-display text-2xl text-gold mb-3">5. Sundowners Like Nowhere Else</h3>
                    <p class="text-cream/80 text-sm leading-relaxed">Watch the sun sink behind the hills from our balcony, drink in hand, as the sky turns amber and gold. It is the kind of moment you will describe to people for years.</p>
                </div>
                <div class="bg-night/40 backdrop-blur-sm border border-gold/30 p-8 rounded-3xl shadow-lg hover:bg-night/60 transition" data-aos="fade-up" data-aos-delay="500">
                    <div class="text-4xl mb-4">📍</div>
                    <h3 class="font-display text-2xl text-gold mb-3">6. Your Home Away From Home</h3>
                    <p class="text-cream/80 text-sm leading-relaxed">With warm accommodation, authentic Maasai-inspired hospitality, outdoor and indoor meeting spaces, and a team that treats every guest like family — Sidai Resort is not just a destination. It is a feeling.</p>
                </div>
            </div>
            
            <div class="mt-16 text-center text-sm text-cream/70 border-t border-gold/20 pt-8" data-aos="fade-up">
                <p>P.O Box 617 – 20500, Narok, Kenya | Tel: 0703 761 951 / 0721 940 823 | sidairesort21@gmail.com</p>
                <p class="mt-2">Follow us on Facebook, Instagram, TikTok & WhatsApp — <strong>@Sidai Resort</strong></p>
            </div>
        </div>
    </section>"""

def replace_in_file(filepath, pattern, replacement):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)
    
    with open(filepath, 'w', encoding='utf-8') as f:
        f.write(new_content)

# Update about.html
about_file = base_dir / "about.html"

# Replace the hero image from hero-sunset.jpg to African Sunset.avif or similar
# The user wants to use images in assets/images. Let's make sure the hero image is one of them.
replace_in_file(about_file, r'src="/assets/images/hero-sunset.jpg"\s*alt="Sidai Resort About Us Hero"', r'src="/assets/images/African Sunset.avif" alt="Sidai Resort About Us Hero"')

# Replace logo in header
logo_pattern = r'<span class="inline-flex h-9 w-9 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-gradient-to-br from-gold to-gold-dark text-night font-bold text-lg sm:text-xl flex-shrink-0">S</span>'
replace_in_file(about_file, logo_pattern, logo_html)

# Also in footer
footer_logo_pattern = r'<span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gold text-night font-bold">S</span>'
replace_in_file(about_file, footer_logo_pattern, logo_html)

# Replace Our Story section in about.html
about_story_pattern = r'<!-- Our Story Section -->.*?</section>'
replace_in_file(about_file, about_story_pattern, about_story_html)

# Update index.html
index_file = base_dir / "index.html"
replace_in_file(index_file, logo_pattern, logo_html)
replace_in_file(index_file, footer_logo_pattern, logo_html)

# Update services.html, rooms.html, menu.html, booking.html to include the logo
for page in ["services.html", "rooms.html", "menu.html", "booking.html"]:
    page_file = base_dir / page
    if page_file.exists():
        replace_in_file(page_file, logo_pattern, logo_html)
        replace_in_file(page_file, footer_logo_pattern, logo_html)

print("Updated files successfully.")
