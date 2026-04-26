import re
from pathlib import Path

file_path = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static\services.html")

new_section = """
    <!-- 12 Core Experiences Overview -->
    <section class="bg-forest py-24 text-cream relative overflow-hidden border-b border-gold/20" id="all-experiences">
        <!-- Background texture -->
        <div class="absolute inset-0 z-0 opacity-10 mix-blend-overlay">
            <img src="assets/images/african-sunset.avif" alt="Background Texture" class="w-full h-full object-cover">
        </div>
        
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-sm font-bold uppercase tracking-[0.2em] text-gold mb-3 block">Discover Your Perfect Moment</span>
                <h2 class="font-display text-4xl sm:text-5xl lg:text-6xl text-white">The Sidai Experiences</h2>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-cream/80">From intimate forested picnics to grand savanna weddings, every service we offer is executed with the irreducible minimum of absolute excellence.</p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="0">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🏊</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🏊</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Swimming</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Dive into our pristine pool surrounded by forest.</p>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="50">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🎂</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🎂</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Birthday Parties</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Celebrate in style with full event support for your sherehe.</p>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">💍</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">💍</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Weddings</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Your perfect day mapped out in a perfect landscape setting.</p>
                    </div>
                </div>

                <!-- Card 4 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="150">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🏢</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🏢</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Conferences</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Indoor and outdoor professional spaces for strategic meetings.</p>
                    </div>
                </div>

                <!-- Card 5 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🍖</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🍖</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Goat Eating</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Authentic Maasai-style nyama choma experiences by the fire.</p>
                    </div>
                </div>

                <!-- Card 6 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="250">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🔥</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🔥</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Bonfires</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Magical evenings under a canopy of unpolluted stars.</p>
                    </div>
                </div>

                <!-- Card 7 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🛏️</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🛏️</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Accommodation</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Comfortable, elegantly appointed rooms for restful stays.</p>
                    </div>
                </div>

                <!-- Card 8 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="350">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🐦</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🐦</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Birdwatching</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Over 300 bird species thriving in the Loita Hills ecosystem.</p>
                    </div>
                </div>

                <!-- Card 9 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="400">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🌾</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🌾</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Farm Visits</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Connect with the land and experience local agricultural life.</p>
                    </div>
                </div>

                <!-- Card 10 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="450">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🌅</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🌅</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Sundowners</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Golden hour drinks and views from our stunning balcony.</p>
                    </div>
                </div>

                <!-- Card 11 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="500">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🌳</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🌳</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Outdoor Meetings</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Fresh air, clear minds, and better decisions in nature.</p>
                    </div>
                </div>

                <!-- Card 12 -->
                <div class="group relative bg-night/40 backdrop-blur-md border border-gold/20 rounded-3xl p-8 hover:bg-gold hover:border-gold transition-all duration-500 cursor-pointer overflow-hidden transform hover:-translate-y-2 shadow-lg" data-aos="fade-up" data-aos-delay="550">
                    <div class="absolute -right-8 -top-8 text-9xl opacity-5 group-hover:opacity-10 transition-opacity duration-500">🧺</div>
                    <div class="relative z-10">
                        <span class="text-4xl block mb-5 group-hover:scale-125 transition-transform duration-500 origin-left">🧺</span>
                        <h3 class="font-display text-2xl text-gold group-hover:text-night mb-3 transition-colors font-bold">Picnics</h3>
                        <p class="text-sm text-cream/70 group-hover:text-night/80 transition-colors leading-relaxed">Curated picnic experiences deep within our forested grounds.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-16 text-center">
                <a href="#detailed-services" class="inline-flex items-center gap-2 rounded-full border border-gold/50 bg-transparent px-8 py-3 text-sm font-semibold uppercase tracking-wider text-gold hover:bg-gold hover:text-night transition-colors" onclick="document.getElementById('detailed-services').scrollIntoView({behavior: 'smooth'})">
                    View Package Pricing &darr;
                </a>
            </div>
        </div>
    </section>

    <!-- Anchor for the tabs section -->
    <div id="detailed-services"></div>
"""

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Find the end of the hero section
pattern = r'(</section>)\s*(<section class="bg-cream py-14">)'
new_content = re.sub(pattern, r'\1' + '\n' + new_section + r'\2', content)

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Updated services.html with the new 12-item cool grid.")
