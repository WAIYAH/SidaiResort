<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Contact Sidai Resort | 0703 761 951 | sidairesort21@gmail.com | Narok Kenya';
$pageDescription = 'Contact Sidai Resort in Naroosura, Narok County. Call 0703 761 951 or email us for bookings, event planning, and enquiries.';
$pageImage = APP_URL . '/assets/images/sidai-logo.png';

include dirname(__DIR__) . '/app/includes/head.php';
include dirname(__DIR__) . '/app/includes/header.php';
?>

<main class="pt-28 lg:pt-32">
    <!-- Hero Section -->
    <section class="relative overflow-hidden h-[40vh] min-h-[300px]">
        <div class="absolute inset-0">
            <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-section.jpg" alt="Contact Sidai Resort" class="h-full w-full object-cover" data-parallax-speed="0.15">
            <div class="absolute inset-0 bg-gradient-to-t from-night via-night/60 to-transparent"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 h-full flex flex-col justify-center sm:px-6 lg:px-8 text-center">
            <h1 class="font-display text-5xl text-white sm:text-6xl lg:text-7xl mb-4" data-aos="fade-up" data-aos-delay="100">Let's Make It Happen</h1>
            <p class="text-lg text-cream/90 max-w-2xl mx-auto italic font-serif" data-aos="fade-up" data-aos-delay="200">Your next great experience starts with a conversation.</p>
        </div>
    </section>

    <!-- Contact Methods -->
    <section class="bg-cream py-16 -mt-10 relative z-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-brown/10 text-center transform transition hover:-translate-y-2" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 mx-auto bg-forest/10 text-forest flex items-center justify-center rounded-full text-3xl mb-6">📞</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Call Us</h3>
                    <p class="text-brown/80 mb-1 font-bold">0703 761 951</p>
                    <p class="text-brown/80 mb-4 font-bold">0721 940 823</p>
                    <p class="text-sm text-brown/60 mb-6 italic">Available daily — we love to talk</p>
                    <a href="tel:0703761951" class="inline-block w-full py-3 rounded-xl bg-forest text-white font-bold tracking-wider hover:bg-forest-light transition">Call Now</a>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-brown/10 text-center transform transition hover:-translate-y-2" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 mx-auto bg-gold/20 text-gold-dark flex items-center justify-center rounded-full text-3xl mb-6">✉️</div>
                    <h3 class="font-display text-2xl text-brown mb-2">Email Us</h3>
                    <p class="text-brown/80 mb-4 font-bold">sidairesort21@gmail.com</p>
                    <p class="text-sm text-brown/60 mb-6 italic">We respond within 24 hours</p>
                    <a href="mailto:sidairesort21@gmail.com" class="inline-block w-full py-3 rounded-xl bg-gold text-night font-bold tracking-wider hover:bg-gold-light transition mt-6">Send Email</a>
                </div>

                <!-- Card 3 -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border border-brown/10 text-center transform transition hover:-translate-y-2" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 mx-auto bg-[#25D366]/10 text-[#25D366] flex items-center justify-center rounded-full text-3xl mb-6"><?php echo social_icon('whatsapp', 28); ?></div>
                    <h3 class="font-display text-2xl text-brown mb-2">WhatsApp Us</h3>
                    <p class="text-brown/80 mb-4 font-bold">0703 761 951</p>
                    <p class="text-sm text-brown/60 mb-6 italic">Fastest way to reach us</p>
                    <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="inline-block w-full py-3 rounded-xl bg-[#25D366] text-white font-bold tracking-wider hover:bg-[#1DA851] transition mt-6">Chat on WhatsApp</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content: Form & Location -->
    <section class="bg-white py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16">
                <!-- Form -->
                <div data-aos="fade-right">
                    <h2 class="font-display text-4xl text-brown mb-6">Send a Message</h2>
                    <form id="contactForm" method="POST" action="<?php echo WEB_ROOT; ?>/api/contact-submit" class="space-y-6">
                        <?php echo class_exists('App\Core\CSRF') ? App\Core\CSRF::field() : ''; ?>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="full_name" class="block text-sm font-semibold text-brown mb-2">Full Name</label>
                                <input type="text" id="full_name" name="full_name" required class="w-full px-4 py-3 bg-cream border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-semibold text-brown mb-2">Email Address</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-3 bg-cream border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="phone" class="block text-sm font-semibold text-brown mb-2">Phone Number</label>
                                <input type="tel" id="phone" name="phone" placeholder="0700..." required class="w-full px-4 py-3 bg-cream border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-semibold text-brown mb-2">Subject</label>
                                <select id="subject" name="subject" required class="w-full px-4 py-3 bg-cream border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all">
                                    <option value="" disabled selected>Select an option</option>
                                    <option value="General Enquiry">General Enquiry</option>
                                    <option value="Booking">Booking</option>
                                    <option value="Event Planning">Event Planning</option>
                                    <option value="Conference">Conference</option>
                                    <option value="Nyama Choma">Nyama Choma</option>
                                    <option value="Birdwatching">Birdwatching</option>
                                    <option value="Farm Visit">Farm Visit</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-semibold text-brown mb-2">Message</label>
                            <textarea id="message" name="message" rows="5" required class="w-full px-4 py-3 bg-cream border border-brown/20 rounded-xl focus:outline-none focus:ring-2 focus:ring-gold focus:border-transparent transition-all"></textarea>
                        </div>

                        <button type="submit" class="w-full bg-gold hover:bg-gold-light text-night font-bold py-4 rounded-xl transition-colors shadow-md flex justify-center items-center gap-2">
                            <span>Send Message</span>
                            <span id="formSpinner" class="hidden animate-spin rounded-full h-5 w-5 border-b-2 border-night"></span>
                        </button>
                        <p id="formFeedback" class="mt-4 text-center font-bold text-sm hidden"></p>
                    </form>
                </div>

                <!-- Location -->
                <div data-aos="fade-left">
                    <h2 class="font-display text-4xl text-brown mb-6">Where to Find Us</h2>
                    <div class="bg-cream rounded-3xl p-8 mb-8 border border-brown/10">
                        <p class="font-bold text-brown mb-2">Naroosura, Loita Hills, Narok County, Kenya</p>
                        <p class="text-brown/80 mb-6">P.O Box 617 – 20500, Narok</p>
                        <div class="rounded-2xl overflow-hidden shadow-md">
                            <iframe
                                title="Sidai Resort location map"
                                src="https://www.google.com/maps?q=Naroosura%2C%20Narok%20County%2C%20Kenya&output=embed"
                                class="h-[250px] w-full border-0"
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </div>

                    <!-- Directions Accordion -->
                    <div class="space-y-4" x-data="{ active: null }">
                        <h3 class="font-display text-2xl text-brown mb-4">Directions</h3>
                        
                        <div class="border border-brown/20 rounded-xl overflow-hidden">
                            <button @click="active !== 1 ? active = 1 : active = null" class="w-full text-left px-6 py-4 bg-cream/50 hover:bg-cream transition flex justify-between items-center font-bold text-brown">
                                <span>From Nairobi</span>
                                <span x-show="active !== 1">▼</span>
                                <span x-show="active === 1">▲</span>
                            </button>
                            <div x-show="active === 1" x-collapse>
                                <div class="px-6 py-4 text-brown/80 bg-white">~180km via Narok town, approx 3 hours drive. The route offers scenic views of the Great Rift Valley.</div>
                            </div>
                        </div>

                        <div class="border border-brown/20 rounded-xl overflow-hidden">
                            <button @click="active !== 2 ? active = 2 : active = null" class="w-full text-left px-6 py-4 bg-cream/50 hover:bg-cream transition flex justify-between items-center font-bold text-brown">
                                <span>From Narok Town</span>
                                <span x-show="active !== 2">▼</span>
                                <span x-show="active === 2">▲</span>
                            </button>
                            <div x-show="active === 2" x-collapse>
                                <div class="px-6 py-4 text-brown/80 bg-white">~35km via the Naroosura road. Head south from Narok town towards the Loita Hills.</div>
                            </div>
                        </div>

                        <div class="border border-brown/20 rounded-xl overflow-hidden">
                            <button @click="active !== 3 ? active = 3 : active = null" class="w-full text-left px-6 py-4 bg-cream/50 hover:bg-cream transition flex justify-between items-center font-bold text-brown">
                                <span>From JKIA</span>
                                <span x-show="active !== 3">▼</span>
                                <span x-show="active === 3">▲</span>
                            </button>
                            <div x-show="active === 3" x-collapse>
                                <div class="px-6 py-4 text-brown/80 bg-white">Via Nairobi, then through the Mai Mahiu - Narok highway. Private transfers can be arranged upon request.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Media Block -->
    <section class="bg-night py-24 text-center">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="font-display text-4xl sm:text-5xl text-gold mb-6" data-aos="fade-up">Find Us on Social Media</h2>
            <p class="text-lg text-cream/80 max-w-2xl mx-auto mb-16" data-aos="fade-up" data-aos-delay="100">
                We are active on all major platforms. Follow us for daily content, behind-the-scenes moments, guest stories, and exclusive offers.
            </p>
            
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <a href="https://facebook.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-white/10 bg-white/5 hover:bg-[#1877F2]/10 transition-all" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-[#1877F2] text-white text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('facebook', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-cream mb-1">Sidai Resort</h3>
                    <p class="text-sm text-cream/50 mb-6">Facebook</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-cream/30 text-cream text-sm font-bold group-hover:bg-[#1877F2] group-hover:border-[#1877F2] group-hover:text-white transition-colors">Follow</span>
                </a>
                
                <a href="https://instagram.com/SidaiResort" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-white/10 bg-white/5 hover:bg-[#e1306c]/10 transition-all" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-gradient-to-tr from-[#fd1d1d] via-[#fcb045] to-[#833ab4] text-white text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('instagram', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-cream mb-1">@SidaiResort</h3>
                    <p class="text-sm text-cream/50 mb-6">Instagram</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-cream/30 text-cream text-sm font-bold group-hover:bg-[#e1306c] group-hover:border-[#e1306c] group-hover:text-white transition-colors">Follow</span>
                </a>
                
                <a href="https://tiktok.com/@SidaiResort" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-white/10 bg-white/5 hover:bg-black/20 transition-all" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-[#010101] text-white text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('tiktok', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-cream mb-1">@SidaiResort</h3>
                    <p class="text-sm text-cream/50 mb-6">TikTok</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-cream/30 text-cream text-sm font-bold group-hover:bg-[#010101] group-hover:border-[#010101] group-hover:text-white transition-colors">Follow</span>
                </a>
                
                <a href="https://wa.me/254703761951" target="_blank" rel="noopener noreferrer" class="group rounded-3xl p-8 border border-white/10 bg-white/5 hover:bg-[#25D366]/10 transition-all" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 mx-auto rounded-full flex items-center justify-center bg-[#25D366] text-white text-3xl mb-4 group-hover:scale-110 transition-transform">
                        <?php echo social_icon('whatsapp', 24); ?>
                    </div>
                    <h3 class="font-display text-2xl text-cream mb-1">0703 761 951</h3>
                    <p class="text-sm text-cream/50 mb-6">WhatsApp</p>
                    <span class="inline-block px-6 py-2 rounded-full border border-cream/30 text-cream text-sm font-bold group-hover:bg-[#25D366] group-hover:border-[#25D366] group-hover:text-white transition-colors">Message</span>
                </a>
            </div>
        </div>
    </section>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('contactForm');
    const feedback = document.getElementById('formFeedback');
    const spinner = document.getElementById('formSpinner');
    
    if (form) {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            feedback.classList.add('hidden');
            spinner.classList.remove('hidden');
            
            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                
                const data = await response.json();
                
                feedback.classList.remove('hidden');
                if (response.ok && data.success) {
                    feedback.textContent = data.message || 'Message sent successfully! We will get back to you soon.';
                    feedback.className = 'mt-4 text-center font-bold text-sm text-green-600 block';
                    form.reset();
                } else {
                    throw new Error(data.message || 'Something went wrong.');
                }
            } catch (error) {
                feedback.classList.remove('hidden');
                feedback.textContent = error.message;
                feedback.className = 'mt-4 text-center font-bold text-sm text-red-600 block';
            } finally {
                spinner.classList.add('hidden');
            }
        });
    }
});
</script>

<?php include dirname(__DIR__) . '/app/includes/footer.php'; ?>
