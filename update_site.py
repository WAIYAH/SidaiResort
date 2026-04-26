import os
import re
import random
import urllib.parse
from pathlib import Path

base_dir = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static")
img_dir = base_dir / "assets" / "images"

def safe_filename(name):
    stem, ext = os.path.splitext(name)
    safe = stem.lower()
    safe = re.sub(r'[^a-z0-9]+', '-', safe)
    safe = safe.strip('-')
    return safe + ext.lower()

available_images = []
renamed_map = {}

# 1. Rename images
for file in img_dir.iterdir():
    if file.is_file() and not file.name.endswith('.mp4'):
        original_name = file.name
        safe_name = safe_filename(original_name)
        new_path = img_dir / safe_name
        if new_path != file:
            if new_path.exists():
                safe_name = f"{safe_name.replace(new_path.suffix, '')}-{random.randint(100,999)}{new_path.suffix}"
                new_path = img_dir / safe_name
            file.rename(new_path)
        available_images.append(safe_name)
        renamed_map[original_name] = safe_name
        
print(f"Total available images: {len(available_images)}")

html_files = list(base_dir.glob("*.html"))
used_images = set()

img_pattern = re.compile(r'(?:src|data-src|href)="assets/images/([^"]+)"')

for html_file in html_files:
    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()
    for match in img_pattern.findall(content):
        match_decoded = urllib.parse.unquote(match)
        used_images.add(match_decoded)

print(f"Total used images in HTML: {len(used_images)}")

# Missing images
missing_images = [img for img in used_images if img not in available_images and img.lower() != 'sidai-logo.png']

print(f"Missing images to map: {len(missing_images)}")

mapping = {}
if available_images:
    for missing in missing_images:
        # Avoid logo as a random image replacement
        choices = [img for img in available_images if img.lower() != 'sidai-logo.png']
        if not choices:
            choices = available_images
        mapping[missing] = random.choice(choices)

contact_replacements = [
    (r'\+254720000000', r'0703 761 951 / 0721 940 823'),
    (r'tel:\+254703%20761%20951%20/%200721%20940%20823', r'tel:+254703761951'),
    (r'tel:0703\s761\s951\s/\s0721\s940\s823', r'tel:+254703761951'),
    (r'href="tel:0703 761 951 / 0721 940 823"', r'href="tel:+254703761951"'),
    (r'href="tel:0703%20761%20951%20/%200721%20940%20823"', r'href="tel:+254703761951"'),
    (r'wa\.me/254720000000', r'wa.me/254703761951'),
    (r'hello@sidairesort\.com', r'sidairesort21@gmail.com'),
]

for html_file in html_files:
    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = content
    
    # 1. Update old un-safe names to safe names
    for orig, safe in renamed_map.items():
        if orig != safe:
            orig_encoded = urllib.parse.quote(orig)
            new_content = new_content.replace(f'assets/images/{orig}', f'assets/images/{safe}')
            new_content = new_content.replace(f'assets/images/{orig_encoded}', f'assets/images/{safe}')
    
    # 2. Map missing images
    for missing, chosen in mapping.items():
        missing_encoded = urllib.parse.quote(missing)
        new_content = new_content.replace(f'assets/images/{missing}', f'assets/images/{chosen}')
        new_content = new_content.replace(f'assets/images/{missing_encoded}', f'assets/images/{chosen}')
            
    # 3. Replace contact details
    for pattern, replacement in contact_replacements:
        new_content = re.sub(pattern, replacement, new_content)
        
    if new_content != content:
        with open(html_file, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Updated {html_file.name}")

print("Site update complete.")
