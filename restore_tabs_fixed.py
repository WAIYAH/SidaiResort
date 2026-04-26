import re
from pathlib import Path

file_path = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static\services.html")

services_data = [
    {
        "id": "swimming",
        "title": "Swimming",
        "subtitle": "Dive into our pristine pool surrounded by forest",
        "description": "Our infinity-edge pool offers a serene escape. Whether you're swimming early morning laps while the forest wakes up, or lounging poolside with a cocktail in the afternoon, it's the ultimate relaxation spot.",
        "prices": [
            {"label": "Adults Day Pass", "value": "Ksh 300.00"},
            {"label": "Children Day Pass", "value": "Ksh 150.00"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/swimming-pool.jpeg"},
            {"type": "image", "src": "assets/images/cool-swimimng.jpeg"},
            {"type": "image", "src": "assets/images/deep-side-pool.jpeg"},
            {"type": "image", "src": "assets/images/swimming.jpeg"},
            {"type": "image", "src": "assets/images/pool-area.jpeg"},
            {"type": "image", "src": "assets/images/nightview-swimmingpool.jpeg"}
        ]
    },
    {
        "id": "birthdays",
        "title": "Birthday Parties & Sherehe",
        "subtitle": "Celebrate in style with full event support",
        "description": "Milestones deserve to be celebrated properly. We provide complete setup, catering, music, and an unbeatable vibe. Bring your friends, we'll handle the logistics of your sherehe.",
        "prices": [
            {"label": "Party Packages", "value": "From Ksh 15,000"},
            {"label": "Custom Cake & Decor", "value": "Available on Request"}
        ],
        "media": [
            {"type": "video", "src": "assets/images/benja-video.mp4"},
            {"type": "image", "src": "assets/images/people-event.jpeg"}
        ]
    },
    {
        "id": "weddings",
        "title": "Weddings & Receptions",
        "subtitle": "Your perfect day in a perfect setting",
        "description": "Say 'I do' against the breathtaking backdrop of the Loita Hills. Our dedicated wedding team ensures every detail—from the floral arches to the evening reception—is executed flawlessly.",
        "prices": [
            {"label": "Venue Hire", "value": "From Ksh 50,000"},
            {"label": "Full Wedding Package", "value": "Custom Quote"}
        ],
        "media": [
            {"type": "video", "src": "assets/images/video-song.mp4"},
            {"type": "image", "src": "assets/images/symboloflove-photo-area.jpeg"},
            {"type": "image", "src": "assets/images/congreagation.jpeg"}
        ]
    },
    {
        "id": "conferences",
        "title": "Conferences & Meetings",
        "subtitle": "Indoor and outdoor professional spaces",
        "description": "Get out of the city and into a space that inspires big ideas. We offer high-speed Wi-Fi, 4K projectors, and fully catered executive boardrooms and halls.",
        "prices": [
            {"label": "Executive Day Package", "value": "Ksh 5,500 / pax"},
            {"label": "Strategy Retreat (Overnight)", "value": "Ksh 12,500 / pax"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/conference-hall.jpeg"},
            {"type": "image", "src": "assets/images/conference-setting.jpeg"},
            {"type": "image", "src": "assets/images/conference.jpeg"}
        ]
    },
    {
        "id": "goat-eating",
        "title": "Goat Eating",
        "subtitle": "Authentic Maasai-style nyama choma experiences",
        "description": "A true Kenyan tradition elevated. Enjoy a whole goat roasted slowly over an open fire by our expert grillers, served with traditional accompaniments and our signature bone soup.",
        "prices": [
            {"label": "Whole Roast Goat (Feeds 10-15)", "value": "Ksh 12,000"},
            {"label": "Half Goat", "value": "Ksh 6,500"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/mbuzi-choma.jpg"},
            {"type": "image", "src": "assets/images/goat-soup.jpeg"},
            {"type": "image", "src": "assets/images/nyama-choma-and-soup.jpeg"}
        ]
    },
    {
        "id": "bonfires",
        "title": "Bonfires",
        "subtitle": "Magical evenings under a canopy of stars",
        "description": "Our signature Enkima experience. Gather around a crackling fire as the temperature drops, sharing stories, drinks, and warmth under an unpolluted sky filled with stars.",
        "prices": [
            {"label": "Private Bonfire Setup", "value": "Ksh 2,500 / group"},
            {"label": "Included for Overnight Guests", "value": "Free"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/bonfire-2.jpeg"},
            {"type": "image", "src": "assets/images/bornfire.jpeg"},
            {"type": "image", "src": "assets/images/at-the-fireside.jpeg"}
        ]
    },
    {
        "id": "accommodation",
        "title": "Accommodation",
        "subtitle": "Comfortable, elegantly appointed rooms",
        "description": "Wake up to the sounds of nature. Our rooms are designed to offer deep comfort, featuring premium bedding, hot showers, and private balconies overlooking the lush grounds.",
        "prices": [
            {"label": "Standard Double", "value": "From Ksh 8,000 / night"},
            {"label": "Executive Suite", "value": "From Ksh 15,000 / night"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/rooms.jpeg"},
            {"type": "image", "src": "assets/images/rooms-side.jpeg"},
            {"type": "image", "src": "assets/images/bedsetting.jpeg"}
        ]
    },
    {
        "id": "birdwatching",
        "title": "Birdwatching",
        "subtitle": "Over 300 species in the Loita Hills ecosystem",
        "description": "The surrounding forests are an ornithologist's dream. Grab a pair of binoculars and let our local guides show you the vibrant, diverse birdlife that calls Sidai home.",
        "prices": [
            {"label": "Guided Bird Tour (2 Hours)", "value": "Ksh 1,000 / pax"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/bird.jpg"},
            {"type": "image", "src": "assets/images/birds.jpeg"},
            {"type": "image", "src": "assets/images/birds-2.jpeg"}
        ]
    },
    {
        "id": "farm-visits",
        "title": "Farm Visits",
        "subtitle": "Connect with the land and local agricultural life",
        "description": "Take a walk through our neighboring Eden farms. Learn about local agricultural practices, harvest fresh produce, and reconnect with the simple, grounding rhythm of farm life.",
        "prices": [
            {"label": "Farm Tour & Experience", "value": "Ksh 500 / pax"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/farm-eden-entrance.jpeg"},
            {"type": "image", "src": "assets/images/walkways.jpeg"}
        ]
    },
    {
        "id": "sundowners",
        "title": "Sundowners",
        "subtitle": "Golden hour drinks from our stunning balcony",
        "description": "There is no better way to end the day. Enjoy curated cocktails and light bitings from our elevated terrace as the sun casts a golden glow over the Narok plains.",
        "prices": [
            {"label": "Sundowner Drink Packages", "value": "From Ksh 1,500 / pax"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/sunset.jpeg"},
            {"type": "image", "src": "assets/images/african-sunset.avif"}
        ]
    },
    {
        "id": "outdoor-meetings",
        "title": "Outdoor Meetings",
        "subtitle": "Fresh air, clear minds, better decisions",
        "description": "Break out of the boardroom. We set up professional, comfortable meeting spaces right in the shade of ancient trees, combining focus with the calming effect of nature.",
        "prices": [
            {"label": "Outdoor Setup Fee", "value": "Ksh 3,000"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/outdoor-meetings.jpeg"},
            {"type": "image", "src": "assets/images/green-outdoor.jpeg"},
            {"type": "image", "src": "assets/images/outdoor-congreagation.jpeg"}
        ]
    },
    {
        "id": "picnics",
        "title": "Picnics",
        "subtitle": "Curated picnic experiences in our forested grounds",
        "description": "We provide the blanket, the basket, and the perfect secluded spot. Enjoy a chef-prepared meal in the absolute quiet of our private forested areas.",
        "prices": [
            {"label": "Standard Picnic Basket (For 2)", "value": "Ksh 3,500"},
            {"label": "Luxury Champagne Picnic", "value": "Ksh 8,000"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/breakfast.jpeg"},
            {"type": "image", "src": "assets/images/beatiful-shades.jpeg"},
            {"type": "image", "src": "assets/images/serene-places.jpeg"}
        ]
    }
]

# Generate Tabs HTML
tabs_html = ""
for i, s in enumerate(services_data):
    active_class_cond = f"activeTab === '{s['id']}'"
    tabs_html += f'''
        <button type="button" @click="setTab('{s['id']}')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" :class="{active_class_cond} ? 'text-gold bg-night/90' : 'text-brown hover:text-gold'">
            {s['title']}
            <span class="absolute inset-x-3 bottom-1 h-0.5 bg-gold transition-transform duration-300" :class="{active_class_cond} ? 'scale-x-100' : 'scale-x-0 group-hover:scale-x-75'"></span>
        </button>
    '''

# Generate Panels HTML
panels_html = ""
for s in services_data:
    prices_html = ""
    for p in s['prices']:
        prices_html += f'''
            <p class="flex items-center justify-between rounded-xl border border-gold/20 px-4 py-3">
                <span>{p['label']}</span>
                <strong>{p['value']}</strong>
            </p>
        '''

    media_html = ""
    for m in s['media']:
        if m['type'] == 'image':
            media_html += f'''
                <article class="overflow-hidden rounded-2xl border border-brown/10 shadow-sm h-64">
                    <img src="{m['src']}" alt="{s['title']}" class="w-full h-full object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </article>
            '''
        elif m['type'] == 'video':
            media_html += f'''
                <article class="overflow-hidden rounded-2xl border border-brown/10 shadow-sm h-64 sm:col-span-2">
                    <video src="{m['src']}" autoplay loop muted playsinline class="w-full h-full object-cover"></video>
                </article>
            '''

    panels_html += f'''
        <section data-tab-panel="{s['id']}" x-show="activeTab === '{s['id']}'" x-transition.opacity.duration.250ms class="mt-10 space-y-8" style="display: none;">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-start">
                <article class="rounded-3xl border border-brown/10 bg-white p-8 shadow-sm">
                    <h2 class="font-display text-4xl text-brown">{s['title']}</h2>
                    <h4 class="text-sm font-semibold uppercase tracking-[0.15em] text-gold mt-2">{s['subtitle']}</h4>
                    <p class="mt-4 text-base leading-8 text-brown/85">
                        {s['description']}
                    </p>
                </article>

                <aside class="rounded-3xl border border-gold/30 bg-night p-8 text-cream">
                    <h3 class="font-display text-3xl text-gold">Pricing & Details</h3>
                    <div class="mt-5 space-y-3 text-sm">
                        {prices_html}
                    </div>
                    <a href="booking.html?type={s['id']}" class="mt-6 inline-flex rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.12em] text-night transition hover:bg-gold-light">Book Now</a>
                </aside>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                {media_html}
            </div>
        </section>
    '''

main_html = f'''
<main class="pt-28 lg:pt-32 pb-0" x-data="servicesTabs()" x-init="init()">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="assets/images/african-sunset.avif" alt="Sidai Resort Services" class="h-full w-full object-cover" fetchpriority="high">
            <div class="absolute inset-0 bg-gradient-to-r from-night/90 via-night/70 to-forest/60"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-28 sm:px-6 lg:px-8 lg:py-36 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-gold" data-aos="fade-down">Everything We Offer</p>
            <h1 class="mt-4 font-display text-5xl text-white sm:text-6xl lg:text-7xl" data-aos="fade-up">Experiences at Sidai</h1>
            <p class="mt-6 max-w-2xl mx-auto text-lg leading-8 text-cream/90" data-aos="fade-up" data-aos-delay="100">
                Discover refined experiences designed for unforgettable leisure stays, premium celebrations, business gatherings, and creative productions.
            </p>
        </div>
    </section>

    <!-- Services Tabs -->
    <section class="bg-cream py-14">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-x-auto pb-4">
                <nav class="inline-flex min-w-max gap-2 rounded-2xl border border-gold/30 bg-white/80 p-2 overflow-x-auto whitespace-nowrap scrollbar-hide">
                    {tabs_html}
                </nav>
            </div>

            <div class="relative min-h-[500px]">
                {panels_html}
            </div>
        </div>
    </section>

    <!-- Alpine.js Tabs Logic -->
    <script>
    function servicesTabs() {{
        const tabIds = {repr([s['id'] for s in services_data])};
        return {{
            validTabs: tabIds,
            activeTab: '{services_data[0]['id']}',

            init() {{
                const hash = window.location.hash.replace('#', '');
                if (this.validTabs.includes(hash)) {{
                    this.activeTab = hash;
                }} else {{
                    this.activeTab = tabIds[0];
                }}

                this.$watch('activeTab', (value) => {{
                    const nextHash = `#${{value}}`;
                    if (window.location.hash !== nextHash) {{
                        history.replaceState(null, '', nextHash);
                    }}
                    this.animateCurrentTab();
                }});

                window.addEventListener('hashchange', () => {{
                    const next = window.location.hash.replace('#', '');
                    if (this.validTabs.includes(next)) {{
                        this.activeTab = next;
                    }}
                }});

                this.$nextTick(() => {{
                    this.animateCurrentTab();
                }});
            }},

            setTab(tab) {{
                if (!this.validTabs.includes(tab)) return;
                this.activeTab = tab;
            }},

            animateCurrentTab() {{
                if (!window.gsap) return;
                
                setTimeout(() => {{
                    const panel = document.querySelector(`[data-tab-panel="${{this.activeTab}}"]`);
                    if (!panel) return;

                    window.gsap.fromTo(
                        panel,
                        {{ opacity: 0, y: 16 }},
                        {{ opacity: 1, y: 0, duration: 0.35, ease: 'power2.out' }}
                    );
                }}, 50);
            }}
        }};
    }}
    </script>
</main>
'''

# Read current file
with open(file_path, 'r', encoding='utf-8') as f:
    full_content = f.read()

# Fix the missing </main> issue by replacing from <main to just before <footer
pattern = re.compile(r'<main.*?(?=\s*<footer)', re.DOTALL)
new_content = pattern.sub(main_html + '\n', full_content)

if new_content == full_content:
    print("WARNING: Replacement failed! The pattern didn't match.")
else:
    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("services.html has been updated to use the tabs slider layout with all 12 services and specific images.")
