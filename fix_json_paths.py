from pathlib import Path

base_dir = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static")
html_files = list(base_dir.glob("*.html"))

for html_file in html_files:
    with open(html_file, 'r', encoding='utf-8') as f:
        content = f.read()
    
    new_content = content
    # Fix paths that missed the prefix
    new_content = new_content.replace('"/assets/', '"assets/')
    new_content = new_content.replace("'/assets/", "'assets/")
    
    # Also just in case, ensure the map actually hit everything
    
    if new_content != content:
        with open(html_file, 'w', encoding='utf-8') as f:
            f.write(new_content)
        print(f"Fixed remaining absolute paths in {html_file.name}")

print("Done.")
