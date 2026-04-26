# Sidai Resort Website

Official website codebase for Sidai Resort.

This repository supports two delivery modes:

1. Dynamic PHP site (`public/`) for full backend features (forms, APIs, admin, payments).
2. Static export (`public-static/`) for fast static hosting on Vercel.

## Project Overview

Sidai Resort is a hospitality website focused on:

- Accommodation and room discovery
- Services and guest experiences
- Food and drink menu browsing
- Booking and contact flows
- Legal/compliance pages and SEO-friendly indexing

## Main Pages

- `/` Home
- `/about`
- `/services`
- `/rooms`
- `/menu`
- `/booking`
- `/privacy-policy`
- `/cookie-policy`
- `/terms-of-service`
- `/404`

## Tech Stack

- PHP 8.2+ (server rendering and API endpoints)
- MySQL/MariaDB (data for bookings/menu/rooms/admin)
- Alpine.js + GSAP + Tailwind CSS CDN (frontend interactions and animation)
- Vercel static hosting via exported HTML (`public-static`)

## Repository Structure

```text
sidai-safari-dreams/
├── app/                    # PHP app config, core classes, shared includes
├── database/               # SQL/schema/seeding assets
├── public/                 # Dynamic PHP web root (production/full backend)
│   ├── api/                # Form/payment/newsletter endpoints
│   ├── admin/              # Admin panel pages
│   ├── assets/             # CSS/JS/images for dynamic site
│   └── *.php               # Route pages
├── public-static/          # Exported static site for Vercel
│   ├── assets/             # Static CSS/JS/images/icons
│   ├── *.html              # Static route pages
│   ├── sitemap.xml
│   ├── robots.txt
│   └── manifest.json
├── scripts/
│   └── export-static.ps1   # Generates public-static from PHP pages
├── storage/                # Logs/uploads/receipts
├── router.php              # Local PHP router entry
└── vercel.json             # Vercel config (serves public-static)
```

## Local Development (Dynamic PHP)

### Prerequisites

- XAMPP/WAMP or PHP + MySQL installed
- PHP executable available (project default expects `C:\xampp\php\php.exe`)

### 1) Configure `.env`

Create/update `.env` in repository root. Minimum keys:

```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost/sidai-safari-dreams/public
WEB_ROOT=/sidai-safari-dreams/public

DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=sidai_resort
DB_USER=root
DB_PASSWORD=
```

Optional but recommended:

- `MAIL_*` keys (emails)
- `MPESA_*` keys (payments)
- `APP_PHONE`, `APP_WHATSAPP`, `APP_EMAIL`

### 2) Start services

- Start Apache and MySQL
- Open:
  - `http://localhost/sidai-safari-dreams/public/`

## Static Export for Vercel

Generate fresh static files from PHP pages:

```powershell
.\scripts\export-static.ps1 -AppUrl "https://your-domain.vercel.app"
```

If your PHP path differs:

```powershell
.\scripts\export-static.ps1 -AppUrl "https://your-domain.vercel.app" -PhpPath "C:\path\to\php.exe"
```

Then deploy repository root to Vercel.

`vercel.json` is configured to serve from `public-static` with clean URLs.

## SEO and Indexing

SEO essentials are maintained in `public-static`:

- Per-page `<title>`, description, canonical, OG, Twitter metadata
- `sitemap.xml` (all main routes)
- `robots.txt` (crawler policy + sitemap reference)
- PWA icons and manifest (`manifest.json`, `favicon.ico`, touch/app icons)

When adding/removing pages, update:

1. Metadata on the new page
2. `public-static/sitemap.xml`
3. Internal nav/footer links

## Forms and API Behavior

- Dynamic mode (`public/`): forms post to real API endpoints.
- Static mode (`public-static/`): backend form actions are disabled or switched to fallbacks where configured.

Always test booking/contact/newsletter flows after export/deploy.

## Footer Credit

A build credit is included in static site footers:

- “Website by Nakola Expert Systems”
- Link: `https://nakolaexpertsystems.vercel.app/`

## Maintenance Checklist

- Run local visual QA on desktop + mobile
- Validate key pages load without console errors
- Confirm logo/icon visibility after deploy
- Re-submit sitemap in Google Search Console after major SEO changes

## License / Ownership

Private client project for Sidai Resort. Reuse only with owner approval.

