<?php declare(strict_types=1);

/**
 * Admin Sidebar Include
 * Provides the left-side navigation for admin pages.
 */

$currentPage = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?? '', '.php');

$navSections = [
    'Main' => [
        ['page' => 'index',    'icon' => '📊', 'label' => 'Dashboard'],
        ['page' => 'bookings', 'icon' => '📅', 'label' => 'Bookings'],
        ['page' => 'guests',   'icon' => '👥', 'label' => 'Guests'],
    ],
    'Operations' => [
        ['page' => 'rooms',    'icon' => '🛏️', 'label' => 'Rooms'],
        ['page' => 'events',   'icon' => '🎉', 'label' => 'Events'],
        ['page' => 'halls',    'icon' => '🏛️', 'label' => 'Halls'],
        ['page' => 'menu',     'icon' => '🍽️', 'label' => 'Menu'],
        ['page' => 'orders',   'icon' => '🧾', 'label' => 'Orders'],
    ],
    'Finance' => [
        ['page' => 'payments', 'icon' => '💳', 'label' => 'Payments'],
        ['page' => 'reports',  'icon' => '📈', 'label' => 'Reports'],
    ],
    'Content' => [
        ['page' => 'gallery',  'icon' => '🖼️', 'label' => 'Gallery'],
    ],
    'System' => [
        ['page' => 'staff',     'icon' => '👤', 'label' => 'Staff'],
        ['page' => 'audit-log', 'icon' => '📋', 'label' => 'Audit Log'],
        ['page' => 'settings',  'icon' => '⚙️', 'label' => 'Settings'],
    ],
];
?>
<aside class="admin-sidebar">
    <div class="brand">
        <div class="brand-name">Sidai Resort</div>
        <div class="brand-sub">Administration</div>
    </div>

    <?php foreach ($navSections as $sectionTitle => $links): ?>
        <div class="nav-section">
            <div class="nav-section-title"><?php echo safe_html($sectionTitle); ?></div>
            <?php foreach ($links as $link): ?>
                <a href="<?php echo WEB_ROOT; ?>/admin/<?php echo $link['page']; ?>.php"
                   class="nav-link <?php echo $currentPage === $link['page'] ? 'active' : ''; ?>">
                    <span class="icon"><?php echo $link['icon']; ?></span>
                    <span><?php echo safe_html($link['label']); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    <?php endforeach; ?>

    <div class="nav-divider"></div>
    <a href="<?php echo WEB_ROOT; ?>/" class="nav-link">
        <span class="icon">←</span>
        <span>Back to Website</span>
    </a>
</aside>
