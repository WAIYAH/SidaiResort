import re
from pathlib import Path

file_path = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static\index.html")

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Update Stats Section
stats_pattern = re.compile(r'<section id="stats" class="bg-night py-10">.*?</section>', re.DOTALL)
new_stats = r'''
    <section id="stats" class="bg-night py-10">
        <div class="mx-auto grid max-w-7xl gap-4 px-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 sm:px-6 lg:px-8">
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center cursor-pointer transition hover:bg-gold/10" data-aos="fade-up" onmouseenter="if(window.animateCounter) window.animateCounter(this.querySelector('[data-counter]'))">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="2" data-counter-suffix="+">2+</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Event Halls</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center cursor-pointer transition hover:bg-gold/10" data-aos="fade-up" data-aos-delay="50" onmouseenter="if(window.animateCounter) window.animateCounter(this.querySelector('[data-counter]'))">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="100" data-counter-suffix="k+">100k+</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Memories</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center cursor-pointer transition hover:bg-gold/10" data-aos="fade-up" data-aos-delay="100" onmouseenter="if(window.animateCounter) window.animateCounter(this.querySelector('[data-counter]'))">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="5" data-counter-suffix="&#9733;">5&#9733;</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Experiences</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center cursor-pointer transition hover:bg-gold/10" data-aos="fade-up" data-aos-delay="150" onmouseenter="if(window.animateCounter) window.animateCounter(this.querySelector('[data-counter]'))">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="100" data-counter-suffix="%">100%</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Heritage</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center cursor-pointer transition hover:bg-gold/10" data-aos="fade-up" data-aos-delay="200" onmouseenter="if(window.animateCounter) window.animateCounter(this.querySelector('[data-counter]'))">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="50" data-counter-suffix="+">50+</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Luxury Rooms</p>
            </article>
            <article class="rounded-2xl border border-gold/20 bg-night/50 p-6 text-center cursor-pointer transition hover:bg-gold/10" data-aos="fade-up" data-aos-delay="250" onmouseenter="if(window.animateCounter) window.animateCounter(this.querySelector('[data-counter]'))">
                <p class="text-4xl font-display text-gold" data-counter data-counter-target="300" data-counter-suffix="+">300+</p>
                <p class="mt-2 text-sm uppercase tracking-[0.2em] text-cream">Bird Species</p>
            </article>
        </div>
        <script>
            window.animateCounter = function(el) {
                if(!el || !window.gsap) return;
                const target = parseInt(el.getAttribute('data-counter-target')) || 0;
                const suffix = el.getAttribute('data-counter-suffix') || '';
                window.gsap.fromTo(el, 
                    { innerText: 0 }, 
                    { 
                        innerText: target, 
                        duration: 1.5, 
                        ease: 'power2.out',
                        snap: { innerText: 1 }, 
                        onUpdate: function() { 
                            el.innerText = Math.round(this.targets()[0].innerText) + suffix; 
                        }
                    }
                );
            }
        </script>
    </section>'''
content = stats_pattern.sub(new_stats, content)

# 2. Reduce Outdoor Events (Eoshet & Enkiu)
# We will match from <div class="grid lg:grid-cols-2 gap-12 items-center mb-24"> (Eoshet) down to </a>\s*</div>\s*<div class="order-1 lg:order-2 rounded-2xl overflow-hidden shadow-xl" data-aos="fade-left"> (End of Enkiu)
# Actually, let's just do text replacements for Eoshet and Enkiu.
eoshet_pattern = re.compile(r'<div class="space-y-4 text-base leading-8 text-brown/85 mb-8">.*?</div>\s*<div class="grid sm:grid-cols-2', re.DOTALL)
short_eoshet = r'''<div class="space-y-4 text-base leading-8 text-brown/85 mb-8">
                    <p>
                        In Maasai tradition, the shade is sacred. Our <span class="italic text-gold">Eoshet</span> terraces and pavilions offer serene, shaded retreats where you can drift between reading, relaxing, and enjoying our in-situ refreshment service. Find your quiet corner scattered across the resort's lush grounds.
                    </p>
                </div>
                <div class="grid sm:grid-cols-2'''
content = eoshet_pattern.sub(short_eoshet, content, count=1)

enkiu_pattern = re.compile(r'<h3 class="font-display text-4xl text-brown mb-6"><span class="italic text-gold text-5xl">Enkiu</span> — The Living Room the Forest Always Had</h3>\s*<div class="space-y-4 text-base leading-8 text-brown/85 mb-8">.*?</div>\s*<div class="flex flex-wrap gap-3', re.DOTALL)
short_enkiu = r'''<h3 class="font-display text-4xl text-brown mb-6"><span class="italic text-gold text-5xl">Enkiu</span> — The Premier Outdoor Lounge</h3>
                <div class="space-y-4 text-base leading-8 text-brown/85 mb-8">
                    <p>
                        <span class="italic text-gold">Enkiu</span> is where Sidai comes alive. Drenched in filtered forest light by day and string lights by night, our premier outdoor lounge offers full bar service, deep comfortable seating, and an effortlessly vibrant atmosphere. Eat, drink, and socialize under the canopy.
                    </p>
                </div>
                <div class="flex flex-wrap gap-3'''
content = enkiu_pattern.sub(short_enkiu, content, count=1)

# 3. Reduce Enkima Story & Add 2 Images
enkima_pattern = re.compile(r'<div class="space-y-6 text-lg sm:text-xl leading-relaxed text-cream/90 max-w-4xl mx-auto text-left enkima-prose">.*?</div>\s*<div class="mt-16', re.DOTALL)
short_enkima = r'''<div class="space-y-6 text-lg sm:text-xl leading-relaxed text-cream/90 max-w-4xl mx-auto text-left enkima-prose">
            <p>
                <span class="italic text-gold">Enkima</span> — our signature bonfire experience. Gather in a circle under an immense, unpolluted star canopy where stories emerge and friendships form over warming drinks and Maasai-inspired snacks.
            </p>
            <p>
                It is the irreducible pleasure of being alive in the right place at exactly the right time.
            </p>
            
            <!-- Added Bonfire Images -->
            <div class="grid grid-cols-2 gap-4 mt-8 mb-4">
                <img src="assets/images/bonfire-2.jpeg" alt="Enkima Bonfire gathering" class="w-full h-48 sm:h-64 object-cover rounded-2xl shadow-xl hover:scale-[1.02] transition-transform duration-500">
                <img src="assets/images/bornfire.jpeg" alt="Enkima Night Fire" class="w-full h-48 sm:h-64 object-cover rounded-2xl shadow-xl hover:scale-[1.02] transition-transform duration-500">
            </div>
        </div>

        <div class="mt-16'''
content = enkima_pattern.sub(short_enkima, content, count=1)


with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("Updated index.html: Interactive hover stats, shortened Eoshet/Enkiu/Enkima stories, added 2 bonfire images.")
