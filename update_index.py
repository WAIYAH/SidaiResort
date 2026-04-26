import re
from pathlib import Path

base_dir = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static")

why_choose_us_html = """
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
    </section>
    
    <section id="quick-book" class="bg-gradient-to-br from-gold to-gold-light py-16">
"""

def replace_in_file(filepath, pattern, replacement):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = re.sub(pattern, replacement, content, flags=re.DOTALL)
    
    if content != new_content:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(new_content)
            print(f"Updated {filepath}")

# Update index.html to insert the new green grid
index_file = base_dir / "index.html"
replace_in_file(index_file, r'<section id="quick-book" class="bg-gradient-to-br from-gold to-gold-light py-16">', why_choose_us_html)
