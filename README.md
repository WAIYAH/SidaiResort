# Sidai Resort Website

Production website for Sidai Resort & Hotel in Naroosura, Narok County, Kenya.

Primary domain: `https://sidairesort.com`

## Project Overview

This repository contains the main marketing and booking website for Sidai Resort.  
It is a static-first site built with hand-authored HTML, a shared minified CSS/JS layer, and CDN-delivered UI libraries.

Core goals:
- **Strong SEO**: Advanced JSON-LD (Hotel, Restaurant, Attractions) for Narok/Naroosura travel and hospitality intent.
- **Premium Aesthetics**: High-energy luxury design with cinematic GSAP animations and 3D perspective grids.
- **Visual Storytelling**: Integrated "Sidai in Motion" video walkthroughs and immersive photography.
- **Deep-Linking Integration**: Standardized cross-page navigation with tab-aware URL parameters for services.
- **Lightweight workflows**: Lead capture through booking/newsletter/menu workflows optimized for mobile.
- **Broad Compatibility**: Responsive, mobile-first design compatible across all modern browsers and static hosts.

## Tech Stack

- HTML5 pages (root-level `*.html`)
- Tailwind (runtime config in-page via CDN)
- Alpine.js (page interactivity with `x-data`, `x-show`, etc.)
- Shared UI/animation layer:
  - `assets/css/sidai.min.css`
  - `assets/js/app.min.js`
- Third-party libraries loaded via CDN:
  - GSAP (+ ScrollTrigger/TextPlugin/ScrollToPlugin)
  - AOS
  - Swiper
  - GLightbox
  - Flatpickr
  - Lenis
  - Particles.js
  - Chart.js
  - Three.js

## Repository Structure

```text
.
|- assets/
|  |- css/sidai.min.css
|  |- js/app.min.js
|  |- media/
|  |  |- images/experiences/
|  |  `- videos/experiences/
|  `- images/
|     |- branding/
|     |- dining/
|     |- events/
|     |- experiences/
|     |- facility/
|     |- hero/
|     |- nature/
|     |- rooms/
|     `- video-posters/
|- index.html
|- about.html
|- services.html
|- rooms.html
|- menu.html
|- booking.html
|- experiences.html
|- privacy-policy.html
|- cookie-policy.html
|- terms-of-service.html
|- contact.html          (redirect page to /about#contact)
|- gallery.html          (redirect page to /about#gallery)
|- events.html           (redirect page to /services#events)
|- 404.html
|- _redirects
|- netlify.toml
|- .htaccess
|- robots.txt
|- sitemap.xml
`- manifest.json
```

## Page Map

- `index.html`: Homepage with cinematic hero, integrated stats, "Sidai in Motion" video walkthrough, service highlights, and premium dining teaser.
- `about.html`: brand story, gallery, contact form section
- `services.html`: service catalog and booking CTAs
- `rooms.html`: accommodation listings
- `menu.html`: food and beverage menus + pre-order workflow
- `booking.html`: structured booking form + WhatsApp handoff
- `experiences.html`: video journal and moments wall
- `privacy-policy.html`, `cookie-policy.html`, `terms-of-service.html`: legal pages
- `contact.html`, `gallery.html`, `events.html`: canonical redirect helpers

## Local Development

### Option A: simple static server (recommended)

```bash
# from repo root
python -m http.server 5500
```

Open `http://localhost:5500`.

### Option B: Jekyll-compatible workflow (optional)

This repo includes `Gemfile` + `_config.yml` for static-host compatibility.

```bash
bundle install
bundle exec jekyll serve
```

## Content and Media Workflow

### Images

Place media in `assets/images/<category>/`:
- `branding`, `hero`, `rooms`, `dining`, `experiences`, `events`, `nature`, `facility`

Use descriptive lowercase filenames with hyphens where possible.

### Videos and posters (Moments page)

Videos live in `assets/media/videos/experiences/`.
Uploaded photos for the moments wall live in `assets/media/images/experiences/`.

Video poster images live in `assets/images/video-posters/` and should match each video 1:1 to avoid visual mismatch.

Example poster generation:

```bash
ffmpeg -y -ss 00:00:02 -i "assets/media/videos/experiences/<video>.mp4" -frames:v 1 -q:v 2 "assets/images/video-posters/<video>.jpg"
```

After adding/changing posters, update `featuredVideos` in `experiences.html`.

## Forms and Lead Capture

### Booking form (`booking.html`)
- sends payload to Formspree: `https://formspree.io/f/xjgjdaav`
- opens a WhatsApp summary for immediate follow-up

### Homepage newsletter (`index.html`)
- attempts `POST /api/newsletter-subscribe` on production domain
- falls back to WhatsApp flow on static/fallback hosts

### Menu pre-order (`menu.html`)
- attempts `POST app/api/menu-order.php`
- automatically falls back to WhatsApp if endpoint is unavailable

### About contact form (`about.html`)
- currently marked with `data-static-disabled="true"` and blocked in temporary static deployments

## Routing and URL Behavior

The live host is **Cloudflare Pages** (confirmed from production: `Server:
cloudflare`, and `/menu.html` answers `308 -> /menu`, which is Cloudflare's
built-in clean-URL redirect). Only two files affect routing and headers there:

- `_redirects` - redirect and rewrite rules
- `_headers` - security headers and asset caching

`netlify.toml` and `.htaccess` were also present but had no effect on this
host; the security headers in `netlify.toml` were never actually served, which
is why they now live in `_headers`. Both files were removed - `git log` has
them if the site ever moves to Netlify or Apache.

Pattern used:
- clean routes (`/about`) map to HTML files (`/about.html`)
- legacy routes (`/gallery`, `/contact`, `/events`) redirect to anchored sections on canonical pages

## SEO and Discovery

- Canonical tags and Open Graph metadata are embedded per page.
- Expanded JSON-LD schema (LodgingBusiness, Restaurant, TouristAttraction) is present on key pages.
- Standardized `tab` query parameters handle deep-linking between the homepage and service tabs.
- `robots.txt` and `sitemap.xml` are included.
- PWA manifest is configured in `manifest.json`.

## Deployment

The site is static and is deployed on Cloudflare Pages. It will run on Netlify
or Apache/Nginx too, but `_headers` and `_redirects` use the Cloudflare Pages
format and would need translating.

Typical Git deploy flow:

```bash
git add .
git commit -m "Describe your update"
git push
```

## Build

```bash
npm install          # once
npm run build        # rebuild Tailwind CSS, then sync shared partials
```

- `npm run build:css` compiles `assets/css/tailwind.src.css` to
  `assets/css/tailwind.css` using `tailwind.config.js`. The site used to load
  the Tailwind Play CDN and compile in the visitor's browser, which silently
  dropped the custom colour palette.
- `npm run sync` pushes `partials/header.html`, `partials/header-home.html`
  and `partials/footer.html` into every page. Edit the partial, not the eleven
  copies. `npm run check` reports drift without writing, for CI or a pre-push
  hook.

## Quality Checklist Before Push

- check desktop and mobile layouts on updated pages
- verify all image/video paths resolve (no 404s)
- verify links, especially anchored redirects (`about#gallery`, `about#contact`)
- validate form UX paths (success/fallback behavior)
- verify new assets are optimized and reasonably sized
- ensure metadata and alt text remain relevant

## Known Caveats

- The `blog/` folder has been separated from this repo, but some files may still reference `/blog` routes:
  - `_redirects`
  - `robots.txt`
  - `sitemap.xml`
- If blog is fully independent, update those references to avoid stale routes.

## Security and Config Notes

- `.env` and local env files are ignored by `.gitignore`.
- Security/cache headers are applied via `netlify.toml` and `.htaccess`.
- Some pages include embedded `_csrf` hidden values from prior generation; keep consistency with your form handling strategy.

## Contact

- Phone: `0703 761 951` / `0705 800 802`
- Email: `sidairesort21@gmail.com`
- WhatsApp: `https://wa.me/254703761951`
- Website: `https://sidairesort.com`

## Credits

Built and maintained for Sidai Resort.  
Implementation credits in-site footer currently reference Nakola Expert Systems.
