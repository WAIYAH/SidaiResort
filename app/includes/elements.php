<?php declare(strict_types=1); ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TouristAttraction",
  "name": "Enkima Bonfire Experience at Sidai Resort",
  "description": "Experience our signature Enkima bonfire under the stars at Sidai Resort. Fire, stars, and the ancient art of gathering.",
  "url": "<?php echo APP_URL; ?>/services#enkima",
  "availableLanguage": ["English", "Maasai", "Swahili"]
}
</script>
<!-- THE ELEMENTS SECTION -->
<section id="elements" class="bg-cream py-24 relative overflow-hidden" x-data="{}" x-init="
    if (window.gsap && window.ScrollTrigger) {
        window.gsap.from('.elements-text-left', {
            x: -50, opacity: 0, duration: 1, ease: 'power3.out',
            scrollTrigger: { trigger: '.elements-text-left', start: 'top 80%' }
        });
        window.gsap.from('.elements-text-right', {
            x: 50, opacity: 0, duration: 1, ease: 'power3.out',
            scrollTrigger: { trigger: '.elements-text-right', start: 'top 80%' }
        });
        
        const enkimaText = document.querySelectorAll('.enkima-prose p');
        window.gsap.from(enkimaText, {
            y: 30, opacity: 0, duration: 1.5, stagger: 0.3, ease: 'power2.out',
            scrollTrigger: { trigger: '#enkima-text-container', start: 'top 70%' }
        });
    }
">
    <!-- Parallax Background -->
    <div class="absolute inset-0 z-0 opacity-30" data-parallax-speed="0.3">
        <!-- IMAGE: Wide-angle shot of resort grounds through tall indigenous trees -->
        <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg" alt="Sidai Resort forested grounds" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-cream/80"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center max-w-3xl mx-auto mb-20" data-aos="fade-up">
            <p class="text-sm font-semibold uppercase tracking-[0.25em] text-earth">Outdoor Experiences</p>
            <h2 class="mt-3 font-display text-5xl text-brown sm:text-6xl">The Elements</h2>
            <p class="mt-4 font-playfair text-xl italic text-gold">The Outdoors, Perfected</p>
            <p class="mt-6 text-base leading-8 text-brown/90 sm:text-lg">
                There are resorts that happen to have outdoor spaces, and then there is Sidai — 
                a resort that is, at its very soul, an outdoor experience with a roof for the 
                rare occasion you need one. The cool, forested environment is not 
                a backdrop here. It is the main event. Every path, every terrace, every ember 
                has been designed to bring you into closer, more intentional contact with the 
                living world around you. Step outside. The forest is ready.
            </p>
        </div>

        <!-- Sub-section 1: Forest -->
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-24">
            <div class="order-2 lg:order-1 elements-text-left">
                <h3 class="font-display text-4xl text-brown mb-6">A Forest That Breathes With You</h3>
                <div class="space-y-4 text-base leading-8 text-brown/85">
                    <p>
                        The landscape holds a secret that the Maasai have always known: this particular 
                        corner of Narok County exists in its own microclimate — cooler, greener, and 
                        more alive than the landscapes that surround it. At Sidai Resort, we have not 
                        built against the forest. We have built within it, with it, and in profound 
                        respect of it.
                    </p>
                    <p>
                        The moment you step onto the grounds, the temperature drops a degree or two — 
                        a physical sensation, not a metaphor. Canopied trees filter the equatorial sun 
                        into something gentle and golden. Birdsong layers itself without conductor or 
                        script. The air itself tastes different here: clean in a way that urban lungs 
                        take a day or two to fully trust.
                    </p>
                    <p>
                        Walk the grounds in the early morning and you will understand why our guests 
                        so often find themselves reconsidering their checkout dates. The forest does 
                        not let go of you easily. Honestly? We hope it never does.
                    </p>
                </div>
            </div>
            <div class="order-1 lg:order-2 rounded-2xl overflow-hidden shadow-xl" data-aos="fade-left">
                <!-- IMAGE: Wide-angle shot of resort grounds through tall indigenous trees, dappled morning light, lush green undergrowth, path visible -->
                <img src="<?php echo WEB_ROOT; ?>/assets/images/swimming-pool.jpg" alt="Forested grounds" class="w-full h-80 object-cover">
            </div>
        </div>

        <!-- Sub-section 2: Eoshet -->
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-24">
            <div class="rounded-2xl overflow-hidden shadow-xl grid grid-cols-2 gap-2" data-aos="fade-right">
                <!-- IMAGE 1: Guests relaxing in shaded outdoor seating area -->
                <img src="<?php echo WEB_ROOT; ?>/assets/images/dining.jpg" alt="Eoshet shaded sitting area" class="w-full h-full object-cover col-span-2 sm:col-span-1 rounded-tl-2xl rounded-bl-2xl">
                <div class="grid grid-rows-2 gap-2 col-span-2 sm:col-span-1">
                    <!-- IMAGE 2: Close-up of Eoshet sitting area -->
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="Eoshet details" class="w-full h-full object-cover rounded-tr-2xl">
                    <!-- IMAGE 3: Aerial-ish or wide shot showing Eoshet area -->
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg" alt="Eoshet wide shot" class="w-full h-full object-cover rounded-br-2xl">
                </div>
            </div>
            <div class="elements-text-right">
                <h3 class="font-display text-4xl text-brown mb-6"><span class="italic text-gold">Eoshet</span> — Where the Shade Becomes a Destination</h3>
                <div class="space-y-4 text-base leading-8 text-brown/85 mb-8">
                    <p>
                        In Maasai tradition, the shade is sacred — a place for elders to speak and 
                        children to listen, for decisions to be made and stories to be told. Sidai 
                        Resort's <span class="italic text-gold">Eoshet</span> sitting areas carry that tradition forward into spaces designed 
                        for the particular pleasure of doing nothing in particular.
                    </p>
                    <p>
                        Scattered across the resort's grounds like gifts from the canopy above, the 
                        <span class="italic text-gold">Eoshet</span> terraces and pavilions offer shaded retreats where a book becomes a 
                        three-hour commitment and an afternoon tea becomes a ceremony. Each sitting 
                        area is positioned with care: framing a specific view, capturing a particular 
                        breeze, creating a pocket of quiet that feels discovered rather than designed.
                    </p>
                    <p>
                        This is where guests drift between sleep and reading. Where couples find the 
                        silence comfortable. Where solo travellers remember why they came. The <span class="italic text-gold">Eoshet</span> 
                        spaces are, in their unhurried way, the heart of what Sidai offers — the 
                        living proof that <span class="font-playfair italic text-gold">Nothing but the Best</span> sometimes means nothing at all, 
                        perfectly arranged.
                    </p>
                </div>
                <div class="grid sm:grid-cols-2 gap-4 text-sm font-medium text-brown">
                    <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg border border-brown/10 shadow-sm"><span class="text-lg">🌿</span> Multiple Locations Across Resort</div>
                    <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg border border-brown/10 shadow-sm"><span class="text-lg">🍃</span> Natural Shade, Zero Pretension</div>
                    <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg border border-brown/10 shadow-sm"><span class="text-lg">☕</span> In-Situ Refreshment Service</div>
                    <div class="flex items-center gap-2 bg-white/50 px-4 py-2 rounded-lg border border-brown/10 shadow-sm"><span class="text-lg">📖</span> The Only Meeting You Need</div>
                </div>
            </div>
        </div>

        <!-- Sub-section 3: Enkiu -->
        <div class="grid lg:grid-cols-2 gap-12 items-center mb-24">
            <div class="order-2 lg:order-1 elements-text-left">
                <h3 class="font-display text-4xl text-brown mb-6"><span class="italic text-gold text-5xl">Enkiu</span> — The Living Room the Forest Always Had</h3>
                <div class="space-y-4 text-base leading-8 text-brown/85 mb-8">
                    <p>
                        If the <span class="italic text-gold">Eoshet</span> areas are intimate moments of shade, <span class="italic text-gold">Enkiu</span> is the grand gesture. 
                        Our premier outdoor lounge is where Sidai's commitment to <span class="font-playfair italic text-gold">Nothing but the Best</span> 
                        finds its most social, most vibrant, most alive expression.
                    </p>
                    <p>
                        <span class="italic text-gold">Enkiu</span> has been designed with the understanding that the finest lounge experience 
                        should feel effortless — guests should arrive, settle, and immediately forget 
                        that anything has been arranged for them at all. The seating is generous and 
                        genuinely comfortable: deep cushions in earthy tones, low tables of reclaimed 
                        timber, ambient lighting that shifts with the hour. By day, <span class="italic text-gold">Enkiu</span> is drenched 
                        in filtered forest light. As afternoon deepens, string lights begin their slow 
                        conversation with the emerging stars. By night, <span class="italic text-gold">Enkiu</span> is simply extraordinary.
                    </p>
                    <p>
                        The bar service is full and attentive — from cold Tusker on a warm afternoon 
                        to craft cocktails as the equatorial stars emerge. The menu draws from our 
                        kitchen's finest without the formality of the dining room. You eat here the 
                        way you would eat in your dream home: well, easily, and without wanting it 
                        to end.
                    </p>
                    <p>
                        <span class="italic text-gold">Enkiu</span> is open to all resort guests. It is, without qualification, one of the 
                        finest places to spend an evening within a hundred kilometres of Nairobi.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-3 mb-8 text-xs font-semibold uppercase tracking-wider text-earth">
                    <span class="bg-brown/5 px-3 py-1.5 rounded-full">🍸 Full Bar Service</span>
                    <span class="bg-brown/5 px-3 py-1.5 rounded-full">🌅 Sunset Views</span>
                    <span class="bg-brown/5 px-3 py-1.5 rounded-full">🎵 Curated Ambient Music</span>
                    <span class="bg-brown/5 px-3 py-1.5 rounded-full">🌿 Forest Setting</span>
                    <span class="bg-brown/5 px-3 py-1.5 rounded-full">✨ Evening Atmosphere</span>
                    <span class="bg-brown/5 px-3 py-1.5 rounded-full">🍽️ Light Menu Available</span>
                </div>

                <a href="<?php echo WEB_ROOT; ?>/booking?type=lounge" class="inline-flex items-center justify-center rounded-xl bg-forest px-6 py-3.5 text-sm font-semibold uppercase tracking-wider text-cream hover:bg-forest-dark transition-colors shadow-md">
                    Reserve Your Evening at Enkiu &rarr;
                </a>
            </div>
            <div class="order-1 lg:order-2 rounded-2xl overflow-hidden shadow-xl" data-aos="fade-left">
                <!-- IMAGE: Wide golden-hour shot of Enkiu outdoor lounge -->
                <img src="/assets/images/conferencing.jpg" alt="Enkiu outdoor lounge" class="w-full h-96 object-cover">
            </div>
        </div>

    </div>
</section>

<!-- Sub-section 4: Enkima (Full dark width section) -->
<section id="enkima" class="relative bg-[#0A0A0A] text-cream py-32 overflow-hidden" x-data="enkimaEffect()" x-init="init()">
    <!-- Firefly / Embers Animation Canvas -->
    <canvas id="enkima-canvas" class="absolute inset-0 z-0 pointer-events-none opacity-80 w-full h-full"></canvas>
    
    <!-- Background subtle image blend -->
    <div class="absolute inset-0 z-0 opacity-20">
        <!-- IMAGE: Hero image — bonfire at night, guests around fire, Milky Way visible -->
        <img src="/assets/images/hero-sunset.jpg" alt="Enkima bonfire at night" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/80 to-[#0A0A0A]"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 text-center" id="enkima-text-container">
        <h3 class="font-display text-7xl sm:text-8xl mb-4 bg-gradient-to-br from-[#D4AF37] to-[#FF8C00] bg-clip-text text-transparent italic">
            Enkima
        </h3>
        <p class="font-playfair text-2xl sm:text-3xl italic text-gold mb-12">
            Fire, Stars, and the Ancient Art of Gathering<br>
            <span class="text-xl sm:text-2xl opacity-90 mt-2 block">Our Signature Experience Under the African Sky</span>
        </p>

        <div class="space-y-6 text-lg sm:text-xl leading-relaxed text-cream/90 max-w-4xl mx-auto text-left enkima-prose">
            <p>
                There is a moment, known to everyone who has ever sat around a fire in the 
                open air, when the conversation stops — not from awkwardness, but from 
                something approaching awe. The fire finds its rhythm. The darkness beyond 
                the circle deepens. Somewhere in the canopy, a night bird makes its singular 
                announcement. And in the space of three or four heartbeats, everyone present 
                understands simultaneously why human beings have been doing exactly this for 
                ten thousand years.
            </p>
            <p>
                <span class="italic text-gold">Enkima</span> — our signature bonfire experience — is built around that moment.
            </p>
            <p>
                As the night descends with its particular, unhurried drama, the 
                <span class="italic text-gold">Enkima</span> fire is laid and lit in the traditional manner. Guests gather in a 
                circle that has no head and no foot — the ancient wisdom of equality around 
                the flame. The sky above, unpolluted and immense, offers a star canopy that 
                city dwellers often see for the first time here, truly and completely. The 
                Milky Way is not an abstraction at <span class="italic text-gold">Enkima</span>. It is a presence. It is company.
            </p>
            <p>
                Around the <span class="italic text-gold">Enkima</span> fire, stories emerge that had no intention of being told. 
                Friendships form between strangers who arrived as guests. Couples find the 
                kind of quiet together that usually takes years to cultivate. Children stare 
                into the flames with an expression of perfect contentment that no screen has 
                ever produced.
            </p>
            <p>
                We serve traditional Maasai-inspired snacks and warming drinks around the 
                fire. Our staff — unobtrusive, attentive, present — ensure that the magic 
                is never interrupted by logistics.
            </p>
            <p>
                <span class="italic text-gold">Enkima</span> is available every evening, weather permitting, as part of the Sidai 
                experience. It is, in our honest assessment, one of the finest things we offer.
            </p>
            <p class="text-2xl text-center italic text-gold mt-12 mb-12">
                <span class="font-playfair">Nothing but the Best</span> — and the best, here, is fire and stars and the 
                irreducible pleasure of being alive in the right place at exactly the right time.
            </p>
        </div>

        <div class="mt-16 bg-white/5 border border-gold/20 p-8 rounded-2xl max-w-3xl mx-auto backdrop-blur-md">
            <p class="text-cream/80 text-sm mb-6">
                Enkima is included in all overnight stays at Sidai Resort. 
                Day guests and event groups may book the Enkima experience separately for <span class="text-gold font-bold">Ksh 2,500</span> per person. 
                We recommend arriving as the light fails. The forest will do the rest.
            </p>
            <a href="<?php echo WEB_ROOT; ?>/booking?type=enkima_bonfire" class="inline-flex items-center justify-center rounded-full bg-gradient-to-r from-[#D4AF37] to-[#FF8C00] px-8 py-4 text-sm font-bold uppercase tracking-widest text-night hover:shadow-[0_0_20px_rgba(255,140,0,0.6)] transition-all transform hover:-translate-y-1">
                Experience Enkima &rarr;
            </a>
        </div>
    </div>
</section>

<!-- The Elements Footer -->
<section class="bg-forest text-white py-16 text-center px-4 relative overflow-hidden">
    <div class="mx-auto max-w-4xl relative z-10" data-aos="fade-up">
        <p class="text-xl sm:text-2xl font-playfair italic leading-loose mb-8 text-cream/90">
            "The forest. The shade. The lounge. The fire.<br>
            Each element of the Sidai outdoor experience has been curated with one 
            governing principle: that nature, given the right stage, offers something 
            no interior in the world can replicate.<br><br>
            We have built that stage. The performance is nightly.<br>
            <span class="text-gold">Nothing but the Best</span> — in every breath of mountain air."
        </p>
        <div class="flex flex-wrap justify-center gap-4 mt-10">
            <a href="<?php echo WEB_ROOT; ?>/booking" class="inline-flex items-center justify-center rounded-full bg-gold px-8 py-3 text-sm font-bold uppercase tracking-widest text-night hover:bg-gold-light transition-colors shadow-lg">
                Book Your Outdoor Experience
            </a>
            <a href="<?php echo WEB_ROOT; ?>/services.php" class="inline-flex items-center justify-center rounded-full border border-gold/50 bg-transparent px-8 py-3 text-sm font-bold uppercase tracking-widest text-gold hover:bg-gold hover:text-night transition-colors">
                Explore All Services
            </a>
        </div>
    </div>
</section>

<script>
function enkimaEffect() {
    return {
        init() {
            const canvas = document.getElementById('enkima-canvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            
            // Resize handler
            const resize = () => {
                canvas.width = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            };
            window.addEventListener('resize', resize);
            resize();

            const particles = [];
            const particleCount = 60; // Embers and fireflies
            
            for (let i = 0; i < particleCount; i++) {
                particles.push({
                    x: Math.random() * canvas.width,
                    y: Math.random() * canvas.height,
                    radius: Math.random() * 2 + 0.5,
                    vx: Math.random() * 1 - 0.5,
                    vy: Math.random() * -1 - 0.5, // Move upwards generally
                    opacity: Math.random(),
                    color: Math.random() > 0.5 ? '#D4AF37' : '#FF8C00', // Gold and Orange
                    fadeRate: Math.random() * 0.02 + 0.005
                });
            }

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                
                particles.forEach(p => {
                    p.x += p.vx;
                    p.y += p.vy;
                    p.opacity -= p.fadeRate;

                    if (p.opacity <= 0 || p.y < 0) {
                        p.x = Math.random() * canvas.width;
                        p.y = canvas.height;
                        p.opacity = Math.random() * 0.8 + 0.2;
                    }

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = p.color;
                    ctx.globalAlpha = p.opacity;
                    ctx.fill();
                    
                    // Add subtle glow
                    ctx.shadowBlur = 10;
                    ctx.shadowColor = p.color;
                });
                
                ctx.globalAlpha = 1;
                ctx.shadowBlur = 0;
                requestAnimationFrame(draw);
            }
            
            draw();
        }
    }
}
</script>
