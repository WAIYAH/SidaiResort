# Sidai Resort Website

Production website for Sidai Resort & Hotel in Naroosura, Narok County, Kenya.

Primary domain: `https://sidairesort.com`

## Project Overview

This repository contains the main marketing and booking website for Sidai Resort.  
It is a static-first site built with hand-authored HTML, a shared minified CSS/JS layer, and CDN-delivered UI libraries.

Core goals:
- strong SEO for Narok/Naroosura travel and hospitality intent
- rich visual storytelling (gallery, videos, experiences)
- lightweight lead capture through booking/newsletter/menu workflows
- compatibility across static hosts (Cloudflare, Netlify, Apache)

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
|  `- images/
|     |- 1. Videos/
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

- `index.html`: homepage, hero, services highlights, events/testimonials, newsletter CTA
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

Videos live in `assets/images/1. Videos/`.

Video poster images live in `assets/images/video-posters/` and should match each video 1:1 to avoid visual mismatch.

Example poster generation:

```bash
ffmpeg -y -ss 00:00:02 -i "assets/images/1. Videos/<video>.mp4" -frames:v 1 -q:v 2 "assets/images/video-posters/<video>.jpg"
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

Routing is defined in multiple host-compatible layers:

- `_redirects` (Cloudflare/Netlify style rules)
- `netlify.toml` (Netlify redirects + headers)
- `.htaccess` (Apache rewrites + headers + caching)

Pattern used:
- clean routes (`/about`) map to HTML files (`/about.html`)
- legacy routes (`/gallery`, `/contact`, `/events`) redirect to anchored sections on canonical pages

## SEO and Discovery

- Canonical tags and Open Graph metadata are embedded per page.
- JSON-LD schema is present on key pages.
- `robots.txt` and `sitemap.xml` are included.
- PWA manifest is configured in `manifest.json`.

## Deployment

The site is static and can be deployed on Cloudflare Pages, Netlify, Apache/Nginx static hosting, or similar.

Typical Git deploy flow:

```bash
git add .
git commit -m "Describe your update"
git push
```

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

- Phone: `0703 761 951` / `0721 940 823`
- Email: `sidairesort21@gmail.com`
- WhatsApp: `https://wa.me/254703761951`
- Website: `https://sidairesort.com`

## Credits

Built and maintained for Sidai Resort.  
Implementation credits in-site footer currently reference Nakola Expert Systems.
