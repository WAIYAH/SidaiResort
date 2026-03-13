# Sidai Resort Website

A modern, responsive, and visually stunning website for Sidai Resort - a luxury safari resort in Naroosura, Narok County, Kenya. "Sidai" means "good" in the Maasai language, reflecting the resort's commitment to excellence and warm Kenyan hospitality.

![Sidai Resort Hero](src/assets/hero-sunset.jpg)

## 🌟 Project Overview

This is a React-based single-page application (SPA) designed to showcase Sidai Resort's world-class amenities, services, and unique Maasai cultural experiences. The website combines modern web technologies with authentic African design elements to create an immersive digital experience.

**Live Preview**: https://lovable.dev/projects/6a4bb6e9-8685-4710-868b-345ef7de4c05

## ✨ Features

### Core Pages
- **Home**: Full-screen hero with sunset imagery, about section with parallax scrolling, services teaser, testimonials carousel, and call-to-action banner
- **Services**: Tabbed layout showcasing conferencing facilities, swimming pools, dining options, spa & wellness, and classic hospitality
- **Gallery**: Responsive masonry grid with category filtering and full-screen lightbox viewer
- **Contact**: Glassmorphism contact form with validation, Google Maps integration, and social media links

### Legal Pages (Compliant & Comprehensive)
- **Privacy Policy**: Data protection, user rights, and cookie usage guidelines
- **Terms of Service**: Booking terms, cancellation policies, liability clauses
- **Cookie Policy**: Detailed cookie usage, consent management, and privacy compliance

### Design & UX Highlights
- 🎨 **Maasai-Inspired Theme**: Authentic African patterns, nature motifs, and vibrant color palette
- 🌅 **Glassmorphism & Neumorphism**: Modern UI elements with depth and visual interest
- 📱 **Fully Responsive**: Optimized for mobile, tablet, and desktop with fluid layouts
- ✨ **Smooth Animations**: Framer Motion powered transitions, hover effects, and scroll-triggered reveals
- ♿ **Accessibility**: ARIA labels, semantic HTML, keyboard navigation support
- 🚀 **SEO Optimized**: Meta tags, structured data, fast loading, and responsive images

### Technical Features
- React 18 with TypeScript for type safety
- React Router for seamless SPA navigation
- Tailwind CSS for utility-first styling
- shadcn/ui components for consistent UI
- Framer Motion for advanced animations
- Lucide React for consistent iconography
- React Hook Form for form management
- Zod for form validation

## 🎨 Design System

### Color Palette
| Token | Hex | Usage |
|-------|-----|-------|
| Primary | `#FFD700` | Buttons, highlights, accents |
| Secondary | `#228B22` | Nature elements, eco-friendly sections |
| Accent | `#FF4500` | Calls-to-action, warnings |
| Blue | `#1E90FF` | Water/swimming themes |
| Beige | `#F5F5DC` | Backgrounds, neutral areas |

### Typography
- **Headings**: Montserrat (Bold, Sans-serif) - Clean and modern
- **Body**: Playfair Display (Serif) - Elegant and readable

### UI Patterns
- Glassmorphism: Semi-transparent overlays with backdrop blur
- Neumorphism: Soft shadows for depth on cards and buttons
- Maasai Pattern: Subtle background patterns for cultural flair

## 🛠️ Tech Stack

| Technology | Purpose |
|------------|---------|
| [React 18](https://react.dev/) | Frontend framework |
| [TypeScript](https://www.typescriptlang.org/) | Type safety |
| [Vite](https://vitejs.dev/) | Build tool & dev server |
| [Tailwind CSS](https://tailwindcss.com/) | Utility-first styling |
| [shadcn/ui](https://ui.shadcn.com/) | UI component library |
| [Framer Motion](https://www.framer.com/motion/) | Animations & transitions |
| [React Router](https://reactrouter.com/) | Client-side routing |
| [Lucide React](https://lucide.dev/) | Icon library |
| [Zod](https://zod.dev/) | Schema validation |

## 📁 Project Structure

```
src/
├── components/           # Reusable UI components
│   ├── ui/              # shadcn/ui base components
│   ├── Navigation.tsx   # Fixed navigation with mobile menu
│   └── Footer.tsx       # Site footer with links
├── pages/               # Route pages
│   ├── Home.tsx         # Landing page
│   ├── Services.tsx     # Services showcase
│   ├── Gallery.tsx      # Image gallery with lightbox
│   ├── Contact.tsx      # Contact form & info
│   ├── PrivacyPolicy.tsx
│   ├── TermsOfService.tsx
│   ├── CookiePolicy.tsx
│   └── NotFound.tsx     # 404 page
├── assets/              # Images and media
│   ├── hero-sunset.jpg
│   ├── conferencing.jpg
│   ├── dining.jpg
│   ├── spa-wellness.jpg
│   └── swimming-pool.jpg
├── hooks/               # Custom React hooks
├── lib/                 # Utility functions
├── App.tsx              # Main application component
├── main.tsx             # Entry point
└── index.css            # Global styles & design tokens
```

## 🚀 Getting Started

### Prerequisites
- Node.js 18+ (recommended: use [nvm](https://github.com/nvm-sh/nvm))
- npm or yarn package manager

### Installation

1. **Clone the repository**
   ```bash
   git clone <YOUR_GIT_URL>
   cd <PROJECT_NAME>
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Start development server**
   ```bash
   npm run dev
   ```

4. **Open in browser**
   Navigate to `http://localhost:8080`

### Available Scripts

| Command | Description |
|---------|-------------|
| `npm run dev` | Start development server with hot reload |
| `npm run build` | Build for production |
| `npm run build:dev` | Build for development |
| `npm run preview` | Preview production build locally |
| `npm run lint` | Run ESLint for code quality |

## 📱 Responsive Breakpoints

| Breakpoint | Width | Target Devices |
|------------|-------|----------------|
| `sm` | 640px | Large phones |
| `md` | 768px | Tablets |
| `lg` | 1024px | Laptops |
| `xl` | 1280px | Desktops |
| `2xl` | 1400px | Large screens |

## 🔍 SEO & Performance

- **Meta Tags**: Complete Open Graph and Twitter Card metadata
- **Semantic HTML**: Proper heading hierarchy and landmark regions
- **Image Optimization**: Lazy loading, responsive sizes, and WebP format support
- **Fast Loading**: Optimized bundle size, code splitting, and efficient caching
- **Accessibility**: WCAG 2.1 AA compliance with ARIA labels and keyboard navigation

## 🌐 Deployment

### Via Lovable (Recommended)
1. Open your project at [Lovable](https://lovable.dev/projects/6a4bb6e9-8685-4710-868b-345ef7de4c05)
2. Click **Share** → **Publish**
3. Your site will be live with a custom URL

### Custom Domain
1. Go to **Project** → **Settings** → **Domains**
2. Click **Connect Domain**
3. Follow the DNS configuration instructions
4. [Full guide](https://docs.lovable.dev/tips-tricks/custom-domain#step-by-step-guide)

## 📝 Content Management

### Updating Content
- **Text**: Edit the corresponding `.tsx` files in `src/pages/`
- **Images**: Replace files in `src/assets/` (keep same filenames for consistency)
- **Colors**: Modify CSS variables in `src/index.css`
- **Fonts**: Update `tailwind.config.ts` font families

### Adding New Pages
1. Create a new `.tsx` file in `src/pages/`
2. Add the route in `src/App.tsx`
3. Update navigation in `src/components/Navigation.tsx`
4. Add footer link in `src/components/Footer.tsx` (if needed)

## 🎯 Key Features by Page

### Home Page
- Full-screen hero with sunset background
- Animated text reveal on load
- Parallax about section
- Glassmorphism feature cards
- Testimonials carousel with auto-scroll
- Mobile-optimized navigation overlay

### Services Page
- Tabbed interface for service categories
- Accordion details for each service
- Image galleries with zoom effects
- Capacity and pricing information
- Booking CTA buttons

### Gallery Page
- Category filtering (All, Resort, Safari, Dining, Wellness)
- Masonry grid layout
- Full-screen lightbox modal
- Touch/swipe support for mobile
- Lazy loading for performance

### Contact Page
- Validated contact form with glassmorphism design
- Google Maps embed with pin animation
- Contact cards with phone, email, address
- Social media links with hover animations
- Success message on form submission

## 🔒 Legal Compliance

The website includes comprehensive legal pages:
- **Privacy Policy**: GDPR-compliant data protection
- **Terms of Service**: Booking terms, cancellations, liability
- **Cookie Policy**: Cookie usage and consent

These are located in `src/pages/` and follow best practices for hospitality businesses.

## 🤝 Contributing

This project is managed through Lovable. To contribute:
1. Make changes in the Lovable editor or locally
2. Test on multiple devices and browsers
3. Ensure accessibility standards are met
4. Submit changes via your preferred workflow

## 📄 License

This project is proprietary to Sidai Resort. All rights reserved.

## 📞 Support

For technical issues or questions:
- **Lovable Support**: [docs.lovable.dev](https://docs.lovable.dev)
- **Project URL**: https://lovable.dev/projects/6a4bb6e9-8685-4710-868b-345ef7de4c05

---

**Sidai Resort** • Naroosura, Narok County, Kenya  
*Where "Good" Meets Luxury*
