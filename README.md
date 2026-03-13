# 🌅 Sidai Resort — Official Website

> *Where 'Good' Meets Luxury* — A premium safari resort in **Naroosura, Narok County, Kenya**.

"Sidai" means "good" in the Maasai language. This website captures the warmth, beauty, and world-class hospitality of Sidai Resort through a modern, responsive, and performant web experience.

---

## ✨ Features

### Core Pages
- **Home** — Full-screen hero, parallax about section, services teaser, testimonials, and CTA
- **Services** — Tabbed interface for Conferencing, Pool, Dining, Spa & Classic Hospitality
- **Gallery** — Masonry grid with category filtering and full-screen lightbox
- **Contact** — Glassmorphism form with validation, Google Maps embed, and social links

### Legal & Compliance
- Privacy Policy (GDPR-aligned)
- Terms of Service (booking & cancellation terms)
- Cookie Policy (consent & usage details)

### Design Highlights
- 🎨 Maasai-inspired color palette & patterns
- 📱 Mobile-first responsive design
- ✨ Smooth animations (Framer Motion ready)
- 🌓 Dark mode support
- ♿ Accessible (ARIA labels, keyboard nav, semantic HTML)
- 🔍 SEO optimized with JSON-LD, OG tags, sitemap

---

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| [React 18](https://react.dev/) | UI framework |
| [TypeScript](https://www.typescriptlang.org/) | Type safety |
| [Vite](https://vitejs.dev/) | Build tool & dev server |
| [Tailwind CSS](https://tailwindcss.com/) | Utility-first styling |
| [shadcn/ui](https://ui.shadcn.com/) | UI component library |
| [React Router](https://reactrouter.com/) | Client-side routing |
| [Lucide React](https://lucide.dev/) | Icon library |
| [Zod](https://zod.dev/) | Form validation |
| [React Hook Form](https://react-hook-form.com/) | Form management |

---

## 🎨 Design System

| Token | HSL | Usage |
|---|---|---|
| Primary | `51 100% 50%` | Buttons, accents, highlights |
| Secondary | `120 61% 34%` | Nature elements, eco-sections |
| Accent | `16 100% 66%` | CTAs, warnings |
| Safari Blue | `210 87% 56%` | Water/pool themes |
| Background | `42 67% 95%` | Page backgrounds |

**Typography:** Montserrat (headings) + Playfair Display (body)

---

## 📁 Project Structure

```
├── public/
│   ├── favicon.ico
│   ├── manifest.json
│   ├── robots.txt
│   └── sitemap.xml
├── src/
│   ├── assets/              # Images (hero, services, etc.)
│   ├── components/
│   │   ├── ui/              # shadcn/ui base components
│   │   ├── Navigation.tsx   # Sticky header with mobile menu
│   │   └── Footer.tsx       # Newsletter, links, social
│   ├── pages/
│   │   ├── Home.tsx
│   │   ├── Services.tsx
│   │   ├── Gallery.tsx
│   │   ├── Contact.tsx
│   │   ├── PrivacyPolicy.tsx
│   │   ├── TermsOfService.tsx
│   │   ├── CookiePolicy.tsx
│   │   └── NotFound.tsx
│   ├── hooks/               # Custom React hooks
│   ├── lib/                 # Utility functions
│   ├── App.tsx              # Routes & layout
│   ├── main.tsx             # Entry point
│   └── index.css            # Design tokens & global styles
├── index.html               # SEO meta, JSON-LD, fonts
├── tailwind.config.ts
├── vite.config.ts
└── package.json
```

---

## 🚀 Getting Started

### Prerequisites
- Node.js 18+
- npm, yarn, or bun

### Installation

```bash
# Clone the repository
git clone <YOUR_GIT_URL>
cd sidai-resort

# Install dependencies
npm install

# Start dev server
npm run dev
```

Open [http://localhost:8080](http://localhost:8080) in your browser.

### Scripts

| Command | Description |
|---|---|
| `npm run dev` | Start development server |
| `npm run build` | Production build |
| `npm run preview` | Preview production build |
| `npm run lint` | Lint with ESLint |

---

## 🌐 Deployment

### Via Lovable (Recommended)
1. Open project in [Lovable](https://lovable.dev/projects/6a4bb6e9-8685-4710-868b-345ef7de4c05)
2. Click **Share → Publish**

### Custom Domain
1. Go to **Project → Settings → Domains**
2. Click **Connect Domain** and follow DNS instructions
3. [Full guide](https://docs.lovable.dev/tips-tricks/custom-domain)

---

## 🔍 SEO & Performance

- ✅ Open Graph & Twitter Card meta tags
- ✅ JSON-LD structured data (LodgingBusiness schema)
- ✅ `sitemap.xml` and `robots.txt`
- ✅ `manifest.json` for PWA support
- ✅ Lazy loading images
- ✅ Semantic HTML5
- ✅ Canonical URLs

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is proprietary to Sidai Resort. All rights reserved.

---

**Sidai Resort** · Naroosura, Narok County, Kenya · [hello@sidairesort.com](mailto:hello@sidairesort.com)
