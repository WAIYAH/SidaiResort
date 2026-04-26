<?php declare(strict_types=1);

use App\Core\CSRF;

if (ob_get_level() === 0) {
    ob_start();
}

$pageTitle = $pageTitle ?? APP_NAME . ' | Where Good Meets Luxury';
$pageDescription = $pageDescription ?? 'Sidai Resort. Luxury stays, event halls, dining, and safari hospitality.';
$pageImage = $pageImage ?? APP_URL . '/assets/images/hero-sunset.jpg';
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$normalizedPath = '/' . ltrim($requestPath, '/');
$pageUrl = $pageUrl ?? rtrim(APP_URL, '/') . ($normalizedPath === '/' ? '' : $normalizedPath);
$pageKeywords = $pageKeywords ?? 'Sidai Resort, Narok resort, Loita Hills resort, Kenya accommodation, Maasai hospitality';
$pageRobots = $pageRobots ?? 'index, follow';
$pageImageAlt = $pageImageAlt ?? APP_NAME . ' scenic view';
$csrfToken = class_exists(CSRF::class) ? CSRF::token() : '';

$structuredData = [
    '@context' => 'https://schema.org',
    '@type' => 'LodgingBusiness',
    'name' => APP_NAME,
    'description' => $pageDescription,
    'url' => APP_URL,
    'telephone' => APP_PHONE,
    'email' => APP_EMAIL,
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => APP_ADDRESS,
        'addressLocality' => 'Narok',
        'addressRegion' => 'Narok County',
        'addressCountry' => 'KE',
    ],
    'sameAs' => [SOCIAL_INSTAGRAM, SOCIAL_FACEBOOK],
    'currenciesAccepted' => 'KES',
];
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo safe_html($pageDescription); ?>">
    <meta name="keywords" content="<?php echo safe_html($pageKeywords); ?>">
    <meta name="robots" content="<?php echo safe_html($pageRobots); ?>">
    <meta name="theme-color" content="#D4AF37">
    <meta name="csrf-token" content="<?php echo safe_html($csrfToken); ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_KE">
    <meta property="og:site_name" content="<?php echo safe_html(APP_NAME); ?>">
    <meta property="og:title" content="<?php echo safe_html($pageTitle); ?>">
    <meta property="og:description" content="<?php echo safe_html($pageDescription); ?>">
    <meta property="og:url" content="<?php echo safe_html($pageUrl); ?>">
    <meta property="og:image" content="<?php echo safe_html($pageImage); ?>">
    <meta property="og:image:alt" content="<?php echo safe_html($pageImageAlt); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo safe_html($pageTitle); ?>">
    <meta name="twitter:description" content="<?php echo safe_html($pageDescription); ?>">
    <meta name="twitter:image" content="<?php echo safe_html($pageImage); ?>">
    <link rel="canonical" href="<?php echo safe_html($pageUrl); ?>">
    <link rel="icon" href="<?php echo WEB_ROOT; ?>/favicon.ico" sizes="any">
    <link rel="shortcut icon" href="<?php echo WEB_ROOT; ?>/favicon.ico">
    <link rel="apple-touch-icon" href="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo.png">
    <link rel="manifest" href="<?php echo WEB_ROOT; ?>/manifest.json">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;700&family=Lato:wght@300;400&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
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
                        night: '#0A0A0A'
                    },
                    fontFamily: {
                        display: ['Cormorant Garamond', 'serif'],
                        sans: ['Montserrat', 'sans-serif'],
                        serif: ['Playfair Display', 'serif'],
                        light: ['Lato', 'sans-serif']
                    },
                    animation: {
                        float: 'float 6s ease-in-out infinite',
                        shimmer: 'shimmer 2s linear infinite',
                        'fade-up': 'fadeUp 0.8s ease-out forwards'
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        },
                        shimmer: {
                            '0%': { backgroundPosition: '-200% 0' },
                            '100%': { backgroundPosition: '200% 0' }
                        },
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(40px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        };
    </script>

    <style>
        :root { color-scheme: light; }
        body { margin: 0; background: #F5ECD7; }
        .critical-shell { min-height: 100dvh; }
        [x-cloak] { display: none !important; }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="preload" href="<?php echo WEB_ROOT; ?>/assets/css/app.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/assets/css/app.css"></noscript>

    <script type="application/ld+json"><?php echo json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?></script>
    <title><?php echo safe_html($pageTitle); ?></title>
</head>
<body class="critical-shell font-sans text-brown antialiased" data-page-transition>
    <div id="page-transition-overlay" class="page-transition-overlay" aria-hidden="true"></div>
