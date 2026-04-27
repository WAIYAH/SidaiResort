# 📸 Image Organization & Optimization Guide

## Step 1: Create New Folder Structure

Create these folders inside `assets/images/`:

```bash
# Run in terminal or file explorer
mkdir assets/images/branding
mkdir assets/images/hero
mkdir assets/images/rooms
mkdir assets/images/dining
mkdir assets/images/experiences
mkdir assets/images/events
mkdir assets/images/nature
mkdir assets/images/facility
mkdir assets/images/farm-visits
```

---

## Step 2: Organize Images by Category

### 🏷️ BRANDING (Logo & Icons)

**Move these files to `assets/images/branding/`:**
```
sidai-logo.png
sidai-icon-32.png
sidai-icon-192.png
sidai-icon-512.png
apple-touch-icon.png
```

---

### 🎬 HERO (Landing & Hero Images)

**Move these to `assets/images/hero/`:**
```
hero-section.jpg
african-sunset.avif
Sunset.jpeg
night-view-trees.jpeg
```

---

### 🛏️ ROOMS (Bedroom & Accommodation)

**Move these to `assets/images/rooms/`:**
```
rooms.jpeg
rooms-side.jpeg
bedsetting.jpeg
balconyview.jpeg
Television.jpeg
```

---

### 🍽️ DINING (Food & Beverages)

**Move these to `assets/images/dining/`:**
```
menu-breakfast.png
menu-beverages.png
mbuzi-choma.jpg
ugali beef.avif
rice&beef.jpg
chapati beef.jpg
goat-soup.jpg
plain-chips.jpeg
potatoes.jpg
Breakfast.jpeg
mandazis.webp
milk.webp
tea.webp
sodas drinks.webp
Wines.jpeg
black coffee.png
Soft_Drinks_Image.webp
nyama-choma-and-soup.jpeg
```

---

### 🏊 EXPERIENCES (Activities & Recreation)

**Move these to `assets/images/experiences/`:**
```
cool-swimimng.jpeg
swimming-pool.jpeg
swimming.jpeg
nightview-swimmingpool.jpeg
pool-area.jpeg
deep-side-pool.jpeg
gentsswimming.jpeg
children-swimming.jpeg
bonfire-2.jpeg
BornFire.jpeg
at-the-fireside.jpeg
amanenjoyingatthefireside.jpeg
couples shootat LoveHeart.jpeg
```

---

### 🎪 EVENTS (Conferences & Gatherings)

**Move these to `assets/images/events/`:**
```
conference-hall.jpeg
conference-setting.jpeg
Conference.jpeg
outdoor-meetings.jpeg
outdoor-congreagation.jpeg
congreagation.jpeg
people-event.jpeg
green-outdoor.jpeg
Margical Garden.jpeg
coolbeatifulflowers.jpeg
```

---

### 🌿 NATURE (Landscape & Ambient)

**Move these to `assets/images/nature/`:**
```
serene-places.jpeg
Emirishoi.jpeg
Enkanasa.jpeg
Enkipai.jpeg
Eoshet.jpeg
Ereto.jpeg
Karibu.jpeg
night-view-flowers.jpeg
nightlights.jpeg
beatiful-shades.jpeg
beatifullightsnight.jpeg
birds-2.jpeg
Bird.jpg
Birds.jpeg
flags-sidai.jpeg
Walkways.jpeg
symboloflove-photo-area.jpeg
nightlights.jpeg
```

---

### 🏢 FACILITY (Infrastructure & Amenities)

**Move these to `assets/images/facility/`:**
```
EntracetoSidairesort.jpeg
farm-eden-entrance.jpeg
carsattheparkingentrance.jpeg
before-at-2017.jpeg
```

---

### 🚜 FARM VISITS (Organized activity content)

**If needed, organize inside `assets/images/farm-visits/`:**
```
(Keep Farm Visits folder or reorganize content)
```

---

## Step 3: Fix File Names

### Before → After

| Before | After |
|--------|-------|
| `chapati beef.jpg` | `chapati-beef.jpg` |
| `ugali beef.avif` | `ugali-beef.avif` |
| `rice&beef.jpg` | `rice-beef.jpg` |
| `goat-soup.jpg` | `goat-soup.jpg` ✅ |
| `plain-chips.jpeg` | `plain-chips.jpeg` ✅ |
| `Breakfast.jpeg` | `breakfast.jpeg` |
| `Wines.jpeg` | `wines.jpeg` |
| `BornFire.jpeg` | `bonfire.jpeg` |
| `couples shootat LoveHeart.jpeg` | `couple-love-heart.jpeg` |
| `coolbeatifulflowers.jpeg` | `cool-beautiful-flowers.jpeg` |
| `EntracetoSidairesort.jpeg` | `entrance-sidai.jpeg` |
| `carsattheparkingentrance.jpeg` | `cars-parking-entrance.jpeg` |
| `nightview-swimmingpool.jpeg` | `nightview-swimming-pool.jpeg` |
| `congreagation.jpeg` | `congregation.jpeg` |

**Rules:**
- ✅ Use lowercase
- ✅ Replace spaces with hyphens
- ✅ Remove special characters (&, @, etc.)
- ✅ Use descriptive names

---

## Step 4: Update HTML Image Paths

### Before:
```html
<img src="assets/images/hero-section.jpg" alt="Hero">
<img src="assets/images/mbuzi-choma.jpg" alt="Food">
```

### After:
```html
<img src="assets/images/hero/hero-section.jpg" alt="Hero" loading="lazy">
<img src="assets/images/dining/mbuzi-choma.jpg" alt="Mbuzi Choma" loading="lazy">
```

### HTML Replacement Pattern

**Search and replace in HTML files:**

```html
<!-- For hero images -->
src="assets/images/hero-section.jpg"
→ src="assets/images/hero/hero-section.jpg" loading="lazy"

<!-- For room images -->
src="assets/images/rooms.jpeg"
→ src="assets/images/rooms/rooms.jpeg" loading="lazy"

<!-- For dining images -->
src="assets/images/mbuzi-choma.jpg"
→ src="assets/images/dining/mbuzi-choma.jpg" loading="lazy"

<!-- For event images -->
src="assets/images/conference-hall.jpeg"
→ src="assets/images/events/conference-hall.jpeg" loading="lazy"
```

---

## Step 5: Image Format Optimization

### Recommended Conversions

| Current | Recommended | Tool |
|---------|-------------|------|
| JPEG | AVIF | https://squoosh.app/ |
| JPEG | WebP | TinyPNG |
| PNG | AVIF | Squoosh |
| Multiple formats | Picture element | Code |

### Picture Element (Progressive Enhancement)

```html
<picture>
  <source srcset="assets/images/dining/mbuzi-choma.avif" type="image/avif">
  <source srcset="assets/images/dining/mbuzi-choma.webp" type="image/webp">
  <img src="assets/images/dining/mbuzi-choma.jpg" alt="Mbuzi Choma" loading="lazy" decoding="async">
</picture>
```

---

## Step 6: Lazy Loading Implementation

### Native Lazy Loading (Simple)
```html
<img src="assets/images/dining/mbuzi-choma.jpg" alt="Mbuzi Choma" loading="lazy">
```

### JavaScript Lazy Loading (Advanced)

```javascript
// Add to assets/js/app.js
document.addEventListener('DOMContentLoaded', function() {
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.dataset.src;
                    img.classList.add('loaded');
                    observer.unobserve(img);
                }
            });
        });
        
        document.querySelectorAll('img[data-src]').forEach(img => observer.observe(img));
    }
});
```

### HTML with JS Lazy Loading
```html
<img src="placeholder.png" data-src="assets/images/dining/mbuzi-choma.jpg" alt="Mbuzi Choma" loading="lazy">
```

---

## Step 7: Compression Targets

### Image Size Targets

| Type | Current | Target | Method |
|------|---------|--------|--------|
| Hero (1920x1080) | 200KB | 80KB | AVIF compression |
| Room (800x600) | 150KB | 50KB | AVIF compression |
| Food (600x400) | 120KB | 35KB | AVIF compression |
| Thumbnail (400x300) | 80KB | 20KB | WebP compression |

### Compression Steps

**Using Squoosh (https://squoosh.app/):**
1. Upload image
2. Select AVIF format
3. Reduce quality to 75-80
4. Export
5. Verify file size

**Using TinyPNG (https://tinypng.com/):**
1. Upload images
2. Download optimized versions
3. Check file reduction

**Using ImageMagick (CLI):**
```bash
# AVIF compression
magick input.jpg -quality 80 output.avif

# WebP compression
magick input.jpg -quality 80 output.webp

# Batch convert
magick mogrify -format avif -quality 80 *.jpg
```

---

## Step 8: Performance Impact

### Current vs Optimized

**Before:**
- Hero image: 200KB
- Room images (5): 750KB
- Dining images (18): 2.1MB
- Total loaded above-the-fold: ~3-4MB

**After (with optimization):**
- Hero image: 60KB (70% reduction)
- Room images (lazy): 0KB initially
- Dining images (lazy): 0KB initially
- Total loaded above-the-fold: ~60-100KB

**Result: 95% faster initial load!**

---

## Step 9: Testing Checklist

- [ ] All images display correctly
- [ ] Images load from new folder paths
- [ ] Lazy loading works (scroll to verify)
- [ ] AVIF/WebP fallbacks work
- [ ] Mobile responsive images load correctly
- [ ] File sizes reduced by 60-70%
- [ ] Google PageSpeed improved
- [ ] No broken image links (404 errors)

---

## Quick Command Reference

### Batch Rename Images (Windows PowerShell)
```powershell
# Navigate to folder
cd "assets\images\dining"

# Rename - remove spaces and convert to lowercase
Get-ChildItem | Rename-Item -NewName { $_.Name -replace ' ', '-' }
```

### Batch Rename (Mac/Linux)
```bash
cd assets/images/dining

# Remove spaces
for f in *\ *; do mv "$f" "${f// /-}"; done

# Convert to lowercase
for f in *; do mv "$f" "${f,,}"; done
```

---

## Summary

✅ **Organized**: Images in logical categories  
✅ **Optimized**: 60-70% file size reduction  
✅ **Fast**: Lazy loading for non-critical images  
✅ **Modern**: AVIF/WebP formats  
✅ **Responsive**: Picture elements for different devices  

---

**Estimated Time**: 2-3 hours  
**Performance Gain**: 95% faster initial load  
**Priority**: 🔴 HIGH

