import re
from pathlib import Path

file_path = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static\services.html")

# Define the 12 services with detailed content
services_data = [
    {
        "id": "swimming",
        "icon": "🏊",
        "title": "Swimming",
        "subtitle": "Dive into our pristine pool surrounded by forest",
        "description": "Our infinity-edge pool offers a serene escape. Whether you're swimming early morning laps while the forest wakes up, or lounging poolside with a cocktail in the afternoon, it's the ultimate relaxation spot.",
        "prices": [
            {"label": "Adults Day Pass", "value": "Ksh 300.00"},
            {"label": "Children Day Pass", "value": "Ksh 150.00"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/swimming-pool.jpeg"},
            {"type": "image", "src": "assets/images/cool-swimimng.jpeg"}
        ]
    },
    {
        "id": "birthdays",
        "icon": "🎂",
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
        "icon": "💍",
        "title": "Weddings & Receptions",
        "subtitle": "Your perfect day in a perfect setting",
        "description": "Say 'I do' against the breathtaking backdrop of the Loita Hills. Our dedicated wedding team ensures every detail—from the floral arches to the evening reception—is executed flawlessly.",
        "prices": [
            {"label": "Venue Hire", "value": "From Ksh 50,000"},
            {"label": "Full Wedding Package", "value": "Custom Quote"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/symboloflove-photo-area.jpeg"},
            {"type": "image", "src": "assets/images/congreagation.jpeg"},
            {"type": "video", "src": "assets/images/video-song.mp4"}
        ]
    },
    {
        "id": "conferences",
        "icon": "🏢",
        "title": "Conferences & Meetings",
        "subtitle": "Indoor and outdoor professional spaces",
        "description": "Get out of the city and into a space that inspires big ideas. We offer high-speed Wi-Fi, 4K projectors, and fully catered executive boardrooms and halls.",
        "prices": [
            {"label": "Executive Day Package", "value": "Ksh 5,500 / pax"},
            {"label": "Strategy Retreat (Overnight)", "value": "Ksh 12,500 / pax"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/conference-hall.jpeg"},
            {"type": "image", "src": "assets/images/conference-setting.jpeg"}
        ]
    },
    {
        "id": "goat-eating",
        "icon": "🍖",
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
        "icon": "🔥",
        "title": "Bonfires",
        "subtitle": "Magical evenings under a canopy of stars",
        "description": "Our signature Enkima experience. Gather around a crackling fire as the temperature drops, sharing stories, drinks, and warmth under an unpolluted sky filled with stars.",
        "prices": [
            {"label": "Private Bonfire Setup", "value": "Ksh 2,500 / group"},
            {"label": "Included for Overnight Guests", "value": "Free"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/bonfire-2.jpeg"},
            {"type": "image", "src": "assets/images/bornfire.jpeg"}
        ]
    },
    {
        "id": "accommodation",
        "icon": "🛏️",
        "title": "Accommodation",
        "subtitle": "Comfortable, elegantly appointed rooms",
        "description": "Wake up to the sounds of nature. Our rooms are designed to offer deep comfort, featuring premium bedding, hot showers, and private balconies overlooking the lush grounds.",
        "prices": [
            {"label": "Standard Double", "value": "From Ksh 8,000 / night"},
            {"label": "Executive Suite", "value": "From Ksh 15,000 / night"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/rooms.jpeg"},
            {"type": "image", "src": "assets/images/bedsetting.jpeg"}
        ]
    },
    {
        "id": "birdwatching",
        "icon": "🐦",
        "title": "Birdwatching",
        "subtitle": "Over 300 species in the Loita Hills ecosystem",
        "description": "The surrounding forests are an ornithologist's dream. Grab a pair of binoculars and let our local guides show you the vibrant, diverse birdlife that calls Sidai home.",
        "prices": [
            {"label": "Guided Bird Tour (2 Hours)", "value": "Ksh 1,000 / pax"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/bird.jpg"},
            {"type": "image", "src": "assets/images/birds-2.jpeg"}
        ]
    },
    {
        "id": "farm-visits",
        "icon": "🌾",
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
        "icon": "🌅",
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
        "icon": "🌳",
        "title": "Outdoor Meetings",
        "subtitle": "Fresh air, clear minds, better decisions",
        "description": "Break out of the boardroom. We set up professional, comfortable meeting spaces right in the shade of ancient trees, combining focus with the calming effect of nature.",
        "prices": [
            {"label": "Outdoor Setup Fee", "value": "Ksh 3,000"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/outdoor-meetings.jpeg"},
            {"type": "image", "src": "assets/images/green-outdoor.jpeg"}
        ]
    },
    {
        "id": "picnics",
        "icon": "🧺",
        "title": "Picnics",
        "subtitle": "Curated picnic experiences in our forested grounds",
        "description": "We provide the blanket, the basket, and the perfect secluded spot. Enjoy a chef-prepared meal in the absolute quiet of our private forested areas.",
        "prices": [
            {"label": "Standard Picnic Basket (For 2)", "value": "Ksh 3,500"},
            {"label": "Luxury Champagne Picnic", "value": "Ksh 8,000"}
        ],
        "media": [
            {"type": "image", "src": "assets/images/breakfast.jpeg"},
            {"type": "image", "src": "assets/images/beatiful-shades.jpeg"}
        ]
    }
]

# Generate Sidebar Links
sidebar_html = ""
for s in services_data:
    sidebar_html += f'''
        <a href="#{s['id']}" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gold/10 transition-colors group">
            <span class="text-xl group-hover:scale-110 transition-transform">{s['icon']}</span>
            <span class="text-sm font-semibold text-brown group-hover:text-gold transition-colors">{s['title']}</span>
        </a>
    '''

# Generate Content Blocks
content_html = ""
for s in services_data:
    # Build Pricing HTML
    prices_html = ""
    for p in s['prices']:
        prices_html += f'''
            <div class="flex justify-between items-center border-b border-brown/10 pb-2">
                <span class="text-sm text-brown/80">{p['label']}</span>
                <span class="font-semibold text-earth">{p['value']}</span>
            </div>
        '''

    # Build Media HTML
    media_html = ""
    for m in s['media']:
        if m['type'] == 'image':
            media_html += f'''
                <div class="rounded-2xl overflow-hidden border border-brown/10 shadow-sm">
                    <img src="{m['src']}" alt="{s['title']}" class="w-full h-48 md:h-64 object-cover hover:scale-105 transition-transform duration-500" loading="lazy">
                </div>
            '''
        elif m['type'] == 'video':
            media_html += f'''
                <div class="rounded-2xl overflow-hidden border border-brown/10 shadow-sm col-span-1 md:col-span-2">
                    <video src="{m['src']}" autoplay loop muted playsinline class="w-full h-48 md:h-64 object-cover"></video>
                </div>
            '''

    content_html += f'''
        <section id="{s['id']}" class="scroll-mt-32 border-b border-brown/10 pb-16" data-aos="fade-up">
            <div class="grid lg:grid-cols-[1fr_300px] gap-8">
                <div>
                    <div class="flex items-center gap-4 mb-4">
                        <span class="text-5xl">{s['icon']}</span>
                        <div>
                            <h2 class="font-display text-4xl text-brown">{s['title']}</h2>
                            <p class="text-sm font-semibold uppercase tracking-[0.15em] text-gold">{s['subtitle']}</p>
                        </div>
                    </div>
                    <p class="text-lg text-brown/80 leading-relaxed mb-8">
                        {s['description']}
                    </p>
                    
                    <!-- Media Gallery for this service -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                        {media_html}
                    </div>
                </div>

                <!-- Pricing & CTA Box -->
                <div>
                    <div class="sticky top-32 bg-cream rounded-3xl p-6 border border-gold/30 shadow-lg">
                        <h3 class="font-display text-2xl text-brown mb-4">Pricing & Details</h3>
                        <div class="space-y-4 mb-6">
                            {prices_html}
                        </div>
                        <a href="booking.html?service={s['id']}" class="block w-full text-center rounded-xl bg-gold px-4 py-3 text-sm font-semibold uppercase tracking-wider text-night hover:bg-night hover:text-gold transition-colors shadow-md">
                            Book {s['title']}
                        </a>
                    </div>
                </div>
            </div>
        </section>
    '''

main_html = f'''
<main class="pt-28 lg:pt-32 pb-0">
    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="assets/images/african-sunset.avif" alt="Sidai Resort Services" class="h-full w-full object-cover" fetchpriority="high">
            <div class="absolute inset-0 bg-gradient-to-r from-night/90 via-night/70 to-forest/60"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-24 sm:px-6 lg:px-8 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-gold" data-aos="fade-down">Everything We Offer</p>
            <h1 class="mt-4 font-display text-5xl text-white sm:text-6xl lg:text-7xl" data-aos="fade-up">Experiences at Sidai</h1>
            <p class="mt-6 max-w-2xl mx-auto text-lg leading-8 text-cream/90" data-aos="fade-up" data-aos-delay="100">
                Dive into our comprehensive list of world-class services, beautifully tailored to make your stay unforgettable.
            </p>
        </div>
    </section>

    <!-- Content Area -->
    <div class="bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-16">
            <div class="lg:grid lg:grid-cols-[250px_1fr] lg:gap-16 lg:items-start">
                
                <!-- Sticky Sidebar -->
                <aside class="hidden lg:block sticky top-32 space-y-1 border-r border-brown/10 pr-6 h-[calc(100vh-8rem)] overflow-y-auto custom-scrollbar">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-brown/50 mb-4 px-4">Quick Navigation</p>
                    {sidebar_html}
                </aside>

                <!-- Services List -->
                <div class="space-y-16">
                    {content_html}
                </div>

            </div>
        </div>
    </div>
</main>
'''

# Read current file
with open(file_path, 'r', encoding='utf-8') as f:
    full_content = f.read()

# Replace <main>...</main>
pattern = re.compile(r'<main.*?</main>', re.DOTALL)
new_content = pattern.sub(main_html, full_content)

# add simple scroll behavior to head if not present
if "scroll-behavior: smooth;" not in new_content:
    new_content = new_content.replace('</head>', '<style>html { scroll-behavior: smooth; } .custom-scrollbar::-webkit-scrollbar { width: 4px; } .custom-scrollbar::-webkit-scrollbar-thumb { background: #D4AF37; border-radius: 4px; }</style>\n</head>')


with open(file_path, 'w', encoding='utf-8') as f:
    f.write(new_content)

print("services.html has been fully updated professionally.")
