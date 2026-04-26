import re
from pathlib import Path

file_path = Path(r"c:\xampp\htdocs\sidai-safari-dreams\public-static\services.html")

with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# 1. Optimize Typography
content = content.replace('text-5xl text-white sm:text-6xl lg:text-7xl', 'text-4xl sm:text-5xl lg:text-6xl text-white leading-tight')
content = content.replace('text-4xl text-brown', 'text-3xl sm:text-4xl text-brown')
content = content.replace('text-3xl text-gold', 'text-2xl sm:text-3xl text-gold')
content = content.replace('text-lg leading-8', 'text-base sm:text-lg leading-7 sm:leading-8')

# 2. Optimize Padding & Spacing
content = content.replace('py-14', 'py-10 sm:py-14')
content = content.replace('p-8', 'p-5 sm:p-8')
content = content.replace('py-28', 'py-16 sm:py-24 lg:py-28')

# 3. Optimize Gallery Images height for mobile
content = content.replace('h-64', 'h-48 sm:h-64')

# 4. Optimize Tabs for Mobile (smaller text/padding on very small screens, snap scrolling)
# Current nav: <nav class="inline-flex min-w-max gap-2 rounded-2xl border border-gold/30 bg-white/80 p-2 overflow-x-auto whitespace-nowrap scrollbar-hide">
# Let's add snap-x snap-mandatory
content = content.replace(
    'inline-flex min-w-max gap-2 rounded-2xl border border-gold/30 bg-white/80 p-2 overflow-x-auto whitespace-nowrap scrollbar-hide',
    'inline-flex w-max min-w-full gap-2 rounded-2xl border border-gold/30 bg-white/80 p-1.5 sm:p-2 overflow-x-auto whitespace-nowrap scrollbar-hide snap-x snap-mandatory'
)

# Current button: <button type="button" @click="setTab('swimming')" class="group relative rounded-xl px-4 py-3 text-sm font-semibold uppercase tracking-[0.14em]" ...>
content = content.replace(
    'rounded-xl px-4 py-3 text-sm font-semibold',
    'rounded-xl px-3 py-2 sm:px-4 sm:py-3 text-xs sm:text-sm font-semibold snap-start shrink-0'
)

# Fix any video height issues
content = content.replace('class="w-full h-full object-cover"', 'class="w-full h-full object-cover object-center"')

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)

print("services.html mobile optimization applied successfully.")
