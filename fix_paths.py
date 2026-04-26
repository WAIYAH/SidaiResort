import re
from pathlib import Path

base_dir = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static")

# Define replacements
# We want to replace exact matches or prefix matches for links
replacements = [
    (r'src="/assets/', r'src="assets/'),
    (r'data-src="/assets/', r'data-src="assets/'),
    (r'href="/assets/', r'href="assets/'),
    (r'src="/placeholder\.svg"', r'src="placeholder.svg"'),
    (r'href="/manifest\.json"', r'href="manifest.json"'),
    
    # Internal links
    (r'href="/"', r'href="index.html"'),
    (r'href="/services"', r'href="services.html"'),
    (r'href="/services#', r'href="services.html#'),
    (r'href="/rooms"', r'href="rooms.html"'),
    (r'href="/menu"', r'href="menu.html"'),
    (r'href="/about"', r'href="about.html"'),
    (r'href="/about#', r'href="about.html#'),
    (r'href="/booking"', r'href="booking.html"'),
    (r'href="/booking\?', r'href="booking.html?'),
    (r'href="/events"', r'href="events.html"'),
    (r'href="/privacy-policy"', r'href="privacy-policy.html"'),
    (r'href="/terms-of-service"', r'href="terms-of-service.html"'),
    (r'href="/cookie-policy"', r'href="cookie-policy.html"'),
    (r'href="/gallery"', r'href="gallery.html"'),
    (r'href="/gallery\?', r'href="gallery.html?'),
    
    # Form actions
    (r'action="/booking"', r'action="booking.html"'),
]

for html_file in base_dir.glob("*.html"):
    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()
        
    new_content = content
    for pattern, replacement in replacements:
        new_content = re.sub(pattern, replacement, new_content)
        
    if new_content != content:
        with open(html_file, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated paths in {html_file.name}")

print("Path fixing complete.")
