/**
 * Shared Tailwind config. This used to live as an inline `window.tailwind.config`
 * block on every page, alongside the Play CDN script that compiled CSS in the
 * visitor's browser on every page load. Same theme, built once at deploy time.
 *
 *   npx tailwindcss -i assets/css/tailwind.src.css -o assets/css/tailwind.css --minify
 */
module.exports = {
  content: ['./*.html', './assets/js/**/*.js'],
  theme: {
    extend: {
      colors: {
        gold: '#D4AF37',
        'gold-light': '#F0D060',
        'gold-dark': '#A08020',
        forest: '#1A4D2E',
        'forest-light': '#2D7A4A',
        earth: '#8B2500',
        cream: '#F5ECD7',
        'cream-dark': '#E8D5B5',
        brown: '#3D1C02',
        safari: '#1E6FAC',
        night: '#0A0A0A',
      },
      fontFamily: {
        display: ['Cormorant Garamond', 'serif'],
        sans: ['Montserrat', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        light: ['Lato', 'sans-serif'],
      },
      animation: {
        float: 'float 6s ease-in-out infinite',
        shimmer: 'shimmer 2s linear infinite',
        'fade-up': 'fadeUp 0.8s ease-out forwards',
      },
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-20px)' },
        },
        shimmer: {
          '0%': { backgroundPosition: '-200% 0' },
          '100%': { backgroundPosition: '200% 0' },
        },
        fadeUp: {
          '0%': { opacity: '0', transform: 'translateY(40px)' },
          '100%': { opacity: '1', transform: 'translateY(0)' },
        },
      },
    },
  },
  plugins: [],
};
