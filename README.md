# 🌅 Sidai Resort — Official Website

> *Sidai Resort: Nothing But the Best* — A premium luxury safari resort website built for ultimate performance and an unforgettable digital experience.

"Sidai" means "good" in the Maasai language. This application captures the warmth, beauty, and world-class hospitality of Sidai Resort through a modern, responsive, and server-rendered web architecture.

---

## ✨ Features

### Core Experience
- **Home** — Full-screen cinematic hero, parallax about section, visual room teasers, and direct calls to action.
- **Services** — Dynamic Alpine.js tabbed interface for Conferencing, Outdoor Activities, Playground, and Music Video Location packages.
- **Accommodation** — Filterable E-commerce style sanctuary listings (11 rooms) showing prices, amenities, and capacities.
- **Menus** — Dynamic food and drink listings supporting ranges from standard to premium Mbuzi Choma.
- **Booking & Contact** — Integrated forms and reservation flows to seamlessly connect guests with the resort staff.

### Design Highlights
- 🎨 Premium aesthetic with carefully tailored Maasai-inspired color palettes.
- 📱 Mobile-first responsive layouts leveraging utility classes.
- ✨ Smooth GSAP and Alpine.js animations.
- ♿ Accessible and semantically rich HTML5.
- 🔍 SEO optimized with dynamic meta tags, JSON-LD schema, and structured data.

---

## 🛠️ Tech Stack

This project was originally designed in React but has been completely converted to a high-performance **PHP 8.2 / MySQL** architecture.

| Technology | Purpose |
|---|---|
| **PHP 8.2** | Server-side rendering, routing, and backend logic |
| **MySQL 8** | Database management for menus, bookings, and configuration |
| **Alpine.js** | Lightweight, reactive frontend state management (Tabs, Filters) |
| **GSAP** | High-performance, cinematic scroll and entry animations |
| **Tailwind CSS** | Utility-first styling delivered via Play CDN for rapid iteration |

---

## 📁 Project Structure

```
├── app/
│   ├── config/              # Database credentials, constants, and global functions
│   ├── core/                # Core classes (Database wrapper, CSRF protection, etc.)
│   └── includes/            # Reusable partials (head, header, footer, elements)
├── public/
│   ├── assets/              # Images, fonts, and static resources
│   ├── about.php            # Our Story and Vision
│   ├── booking.php          # Reservation portal
│   ├── index.php            # Homepage
│   ├── menu.php             # Culinary offerings
│   ├── rooms.php            # Filterable e-commerce accommodation grid
│   └── services.php         # Conference halls, pool, and activities
├── .htaccess                # Apache routing rules
└── README.md                # Project documentation
```

---

## 🚀 Getting Started

### Prerequisites
- PHP 8.2 or higher
- MySQL 8.0 or MariaDB equivalent
- Apache web server (e.g., XAMPP, WAMP, or Laravel Valet)

### Installation

1. **Clone the repository:**
   Ensure the project folder is placed inside your server's document root (e.g., `C:\xampp\htdocs\sidai-safari-dreams`).

2. **Configure Environment:**
   Copy `.env.example` to `.env` and fill out your database credentials:
   ```env
   DB_HOST=127.0.0.1
   DB_NAME=sidai_resort
   DB_USER=root
   DB_PASS=
   ```

3. **Initialize Database:**
   Run the provided seeding scripts (like `db_seed_menu.php` or `db_seed_rooms.php`) via your browser to populate the base data.

4. **Launch:**
   Navigate to `http://localhost/sidai-safari-dreams/public/` to view the live application.

---

## 💼 Customization & Data

- **Currency:** All financial outputs utilize the globally defined `format_kes()` helper located in `app/config/constants.php` which automatically formats prices to `Ksh`.
- **Global Settings:** Modify `WEB_ROOT` or `APP_URL` in the `init.php` to adjust paths for subfolder vs. domain deployments.

---
*Developed with a commitment to Nothing But the Best.*

---

## ▲ Vercel Static Deploy

For temporary Vercel hosting without PHP execution:

1. Generate static pages:
   ```powershell
   .\scripts\export-static.ps1 -AppUrl "https://your-vercel-domain.vercel.app"
   ```
   If PHP is in a different location, add `-PhpPath "C:\path\to\php.exe"`.
2. Deploy the project root to Vercel.
3. `vercel.json` is already configured to serve from `public-static` with clean URLs.
