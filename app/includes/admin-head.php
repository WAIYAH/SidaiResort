<?php declare(strict_types=1);

/**
 * Admin Head Include
 * Simplified <head> for admin pages — no public page libraries (particles, swiper, etc).
 */

use App\Core\CSRF;

$pageTitle = ($pageTitle ?? 'Dashboard') . ' — Sidai Resort Admin';
$csrfToken = class_exists(CSRF::class) ? CSRF::token() : '';
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="csrf-token" content="<?php echo safe_html($csrfToken); ?>">
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo WEB_ROOT; ?>/admin/assets/css/admin.css">
    <?php if (isset($extraCss)): ?>
        <?php foreach ((array)$extraCss as $css): ?>
            <link rel="stylesheet" href="<?php echo safe_html($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <title><?php echo safe_html($pageTitle); ?></title>
</head>
<body class="admin-body">
