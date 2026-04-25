<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'About Us | Our Story & Gallery | Sidai Resort';
$pageDescription = "Learn about the story behind Sidai Resort, explore our gallery, and get in touch with us for your next luxury getaway.";
$pageImage = APP_URL . '/assets/images/hero-sunset.jpg';

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>

<main class="pt-28 lg:pt-32">
    <!-- Hero Section -->
    <section class="relative overflow-hidden h-[60vh] min-h-[500px]">
        <div class="absolute inset-0">
            <img
                src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg"
                alt="Sidai Resort About Us Hero"
                class="h-full w-full object-cover"
                data-parallax-speed="0.15"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-night via-night/50 to-transparent"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 h-full flex flex-col justify-center sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-gold mb-4" data-aos="fade-down">Discover Our Roots</p>
            <h1 class="font-display text-5xl text-white sm:text-6xl lg:text-7xl mb-8" data-aos="fade-up" data-aos-delay="100">About Sidai Resort</h1>
            
            <!-- Page Sub-Navigation -->
            <div class="flex flex-wrap justify-center gap-4" data-aos="fade-up" data-aos-delay="200">
                <a href="#our-story" class="rounded-full border border-gold/50 bg-night/50 px-6 py-2.5 text-sm font-semibold uppercase tracking-wider text-cream backdrop-blur-sm transition-colors hover:bg-gold hover:text-night">Our Story</a>
                <a href="#gallery" class="rounded-full border border-gold/50 bg-night/50 px-6 py-2.5 text-sm font-semibold uppercase tracking-wider text-cream backdrop-blur-sm transition-colors hover:bg-gold hover:text-night">Gallery</a>
                <a href="#contact" class="rounded-full border border-gold/50 bg-night/50 px-6 py-2.5 text-sm font-semibold uppercase tracking-wider text-cream backdrop-blur-sm transition-colors hover:bg-gold hover:text-night">Contact Us</a>
            </div>
        </div>
    </section>

    <!-- Our Story Section -->
    <section id="our-story" class="bg-cream py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div data-aos="fade-right">
                    <h2 class="font-display text-4xl text-brown mb-6">A Vision of Elegance</h2>
                    <p class="text-lg text-brown/80 mb-6 leading-relaxed">
                        Born from a deep reverence for the landscape, Sidai Resort was envisioned as a sanctuary where modern luxury meets the raw, untamed beauty of nature. The word <span class="font-playfair italic text-gold">Sidai</span> translates to "Good" in the Maasai language, but our promise to you is simpler and bolder: <span class="font-playfair italic text-gold">Nothing but the Best</span>.
                    </p>
                    <p class="text-lg text-brown/80 leading-relaxed">
                        Every stone laid, every path carved through the forest, and every culinary masterpiece crafted in our kitchens is a testament to our commitment to excellence. Whether you are here for a tranquil retreat, an opulent celebration, or a focused corporate gathering, our dedicated team ensures your experience is nothing short of extraordinary.
                    </p>
                </div>
                <div class="relative" data-aos="fade-left">
                    <div class="absolute -inset-4 rounded-3xl border border-gold/30 transform rotate-3"></div>
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="Sidai Resort Details" class="relative rounded-2xl shadow-2xl w-full h-[500px] object-cover">
                </div>
            </div>
        </div>
    </section>
    
    <?php include APP_PATH . '/includes/gallery_section.php'; ?>
    <!-- Contact Us Section -->
    <section id="contact" class="bg-white py-24 border-t border-brown/10">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="font-display text-4xl text-brown mb-4">Get In Touch</h2>
                <p class="text-lg text-brown/70">We'd love to hear from you. Drop us a message anytime.</p>
            </div>

            <form id="contactForm" method="POST" action="<?php echo WEB_ROOT; ?>/api/contact-submit.php" class="bg-cream rounded-2xl shadow-sm border border-gold/20 p-8" data-aos="fade-up" data-aos-delay="100">
                <?php echo class_exists('App\Core\CSRF') ? '<input type="hidden" name="csrf_token" value="' . App\Core\CSRF::token() . '">' : ''; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="full_name" class="block text-sm font-semibold text-brown mb-2">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required class="w-full px-4 py-3 bg-white border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-brown mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required class="w-full px-4 py-3 bg-white border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="phone" class="block text-sm font-semibold text-brown mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="+254..." class="w-full px-4 py-3 bg-white border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                    </div>
                    <div>
                        <label for="subject" class="block text-sm font-semibold text-brown mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" required class="w-full px-4 py-3 bg-white border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                    </div>
                </div>

                <div class="mb-8">
                    <label for="message" class="block text-sm font-semibold text-brown mb-2">Message</label>
                    <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 bg-white border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all"></textarea>
                </div>

                <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-night font-bold py-4 rounded-xl transition-colors shadow-md">
                    Send Message
                </button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-16" data-aos="fade-up" data-aos-delay="200">
                <div class="text-center p-6 rounded-2xl bg-cream border border-brown/5">
                    <div class="text-3xl mb-3">📞</div>
                    <h3 class="font-semibold text-brown mb-2">Phone</h3>
                    <p class="text-brown/70 text-sm"><?php echo APP_PHONE; ?></p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-cream border border-brown/5">
                    <div class="text-3xl mb-3">✉️</div>
                    <h3 class="font-semibold text-brown mb-2">Email</h3>
                    <p class="text-brown/70 text-sm"><?php echo APP_EMAIL; ?></p>
                </div>
                <div class="text-center p-6 rounded-2xl bg-cream border border-brown/5">
                    <div class="text-3xl mb-3">📍</div>
                    <h3 class="font-semibold text-brown mb-2">Location</h3>
                    <p class="text-brown/70 text-sm"><?php echo APP_ADDRESS; ?></p>
                </div>
            </div>
        </div>
    </section>
</main>

<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>
