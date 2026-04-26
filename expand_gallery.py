import re
import json
import random
from pathlib import Path

base_dir = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static")
img_dir = base_dir / "assets" / "images"

available_images = [file.name for file in img_dir.iterdir() if file.is_file() and not file.name.endswith('.mp4') and file.name.lower() != 'sidai-logo.png']

categories = ["pool", "dining", "events", "rooms", "nature", "weddings", "parties"]

new_gallery_data = []
for i, img in enumerate(available_images):
    title = img.replace('-', ' ').replace('.jpeg', '').replace('.jpg', '').replace('.avif', '').replace('.png', '').title()
    category = random.choice(categories)
    is_featured = 1 if i < 4 else 0
    
    item = {
        "title": title,
        "description": f"Beautiful moments at Sidai Resort ({title})",
        "image_path": f"assets/images/{img}",
        "category": category,
        "is_featured": is_featured
    }
    new_gallery_data.append(item)

# convert to JS string
json_str = json.dumps(new_gallery_data, separators=(',', ':'))

about_file = base_dir / "about.html"
with open(about_file, 'r', encoding='utf-8') as f:
    content = f.read()

# Replace const SIDAI_GALLERY_DATA = [...];
pattern = re.compile(r'const SIDAI_GALLERY_DATA = \[.*?\];', re.DOTALL)
new_content = pattern.sub(f'const SIDAI_GALLERY_DATA = {json_str};', content)

if new_content != content:
    with open(about_file, 'w', encoding='utf-8') as f:
        f.write(new_content)
    print("Gallery expanded with ALL images.")
else:
    print("Pattern not found or no changes.")
