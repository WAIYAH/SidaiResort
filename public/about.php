<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'About Sidai Resort — Excellence in the Loita Hills | Narok Kenya';
$pageDescription = "Learn about the story behind Sidai Resort, explore our gallery, and get in touch with us for your next luxury getaway.";
$pageImage = APP_URL . '/assets/images/sidai-logo.png';

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>

<main class="pt-28 lg:pt-32">
    <!-- Hero Section -->
    <section class="relative overflow-hidden h-[60vh] min-h-[500px]">
        <div class="absolute inset-0">
            <img
                src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg"
                alt="Sidai Resort Loita Hills"
                class="h-full w-full object-cover"
                data-parallax-speed="0.15"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-night via-night/60 to-transparent"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 h-full flex flex-col justify-center sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-gold mb-4" data-aos="fade-down">Naroosura, Loita Hills, Narok County, Kenya</p>
            <h1 class="font-display text-5xl text-white sm:text-6xl lg:text-7xl mb-8" data-aos="fade-up" data-aos-delay="100">Our Story. Our Hills. Our Excellence.</h1>
        </div>
    </section>

    <!-- SECTION 1: WHO WE ARE -->
    <section id="who-we-are" class="bg-cream py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8 text-center" data-aos="fade-up">
            <img src="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo.png" alt="Sidai Resort Logo" class="mx-auto h-24 w-auto mb-8">
            <h2 class="font-display text-4xl text-brown mb-8 sm:text-5xl">Sidai Resort — Excellence Is Our Name</h2>
            <div class="space-y-6 text-lg text-brown/90 leading-relaxed font-serif">
                <p>
                    Nestled on the breathtaking slopes of the Loita Hills in Naroosura, Narok County, Sidai Resort is nature's finest address — a sanctuary where the wild beauty of the Maasai heartland meets world-class hospitality.
                </p>
                <p>
                    'Sidai' is a Maasai word meaning excellence — and excellence is not an aspiration here, it is the irreducible minimum. From the moment you arrive, exceptional service is simply the standard. Nothing less. Nothing but the best.
                </p>
                <p>
                    Whether you are seeking a tranquil escape from the city, a vibrant celebration with your loved ones, a productive conference away from the noise, or a soul-stirring encounter with raw nature — Sidai is the place to be.
                </p>
                <p>
                    Here, ancient forests whisper, over 300 bird species sing at dawn, and every sunset from the balcony is a masterpiece painted just for you. We are where the Loita Hills begin — and where your most unforgettable experiences come to life.
                </p>
            </div>
        </div>
    </section>

    <!-- SECTION 2: WHAT SIDAI MEANS -->
    <section id="what-sidai-means" class="bg-night py-24 text-center">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8" data-aos="fade-up">
            <blockquote class="font-display text-4xl sm:text-5xl text-gold italic leading-tight mb-8">
                "Sidai. Excellence. It is not what we aspire to.<br>It is simply who we are."
            </blockquote>
            <div class="flex justify-center">
                <svg width="100" height="20" viewBox="0 0 100 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M0 10H40L50 0L60 10H100" stroke="#D4AF37" stroke-width="2"/>
                    <path d="M40 10L50 20L60 10" stroke="#D4AF37" stroke-width="2"/>
                </svg>
            </div>
        </div>
    </section>

    <!-- SECTION 3: OUR LOCATION -->
    <section id="our-location" class="bg-white py-24 border-t border-brown/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <h2 class="font-display text-4xl text-brown mb-6 sm:text-5xl">Where We Are</h2>
                    <p class="text-lg text-brown/80 mb-8 leading-relaxed">
                        We are found on the slopes of the Loita Hills in Naroosura — a cool, forested corner of Narok County where the air is clean, the hills roll endlessly, and the Maasai spirit is alive in every direction. We are a retreat from everything ordinary. We are Sidai.
                    </p>
                    <div class="bg-cream p-6 rounded-2xl border border-brown/10">
                        <p class="font-semibold text-brown mb-2 text-xl">Address</p>
                        <p class="text-brown/80">Naroosura, Loita Hills, Narok County, Kenya</p>
                        <p class="text-brown/80">P.O Box 617 – 20500, Narok</p>
                    </div>
                </div>
                <div class="relative rounded-3xl overflow-hidden shadow-xl" data-aos="fade-left">
                    <iframe
                        title="Sidai Resort location map"
                        src="https://www.google.com/maps?q=Naroosura%2C%20Narok%20County%2C%20Kenya&output=embed"
                        class="h-[400px] w-full border-0"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 4: OUR PROMISE -->
    <section id="our-promise" class="bg-cream py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="font-display text-4xl text-brown sm:text-5xl">Our Promise</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="bg-white p-10 rounded-3xl shadow-lg border border-gold/20 text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="text-4xl mb-6">🎯</div>
                    <h3 class="font-display text-2xl text-brown mb-4">Your Needs Are Our Goals</h3>
                    <p class="text-brown/80 leading-relaxed">Every request, every preference, every dream for your stay is taken seriously and delivered with care.</p>
                </div>
                <div class="bg-white p-10 rounded-3xl shadow-lg border border-gold/20 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl mb-6">⭐</div>
                    <h3 class="font-display text-2xl text-brown mb-4">Excellence Without Exception</h3>
                    <p class="text-brown/80 leading-relaxed">Sidai means excellence. We deliver it not sometimes, not mostly — but always, to every single guest.</p>
                </div>
                <div class="bg-white p-10 rounded-3xl shadow-lg border border-gold/20 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl mb-6">🤝</div>
                    <h3 class="font-display text-2xl text-brown mb-4">The Maasai Spirit of Welcome</h3>
                    <p class="text-brown/80 leading-relaxed">Hospitality is in our roots. We welcome every guest as the Maasai welcome the honoured — wholeheartedly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 5: FIND US ONLINE -->
    <section id="social-media" class="bg-white py-24 border-t border-brown/10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-display text-4xl sm:text-5xl text-brown mb-4" data-aos="fade-up">Find Us Online</h2>
            <p class="text-lg text-brown/70 mb-16 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">We are active on Facebook, Instagram, TikTok, and WhatsApp. Search Sidai Resort on any platform and join our growing community.</p>
            
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

</main>
<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>