<?php declare(strict_types=1);

/**
 * Admin Header Include
 * Provides the top navigation bar and mobile sidebar toggle.
 */

$staff = $_SESSION['staff'] ?? [];
$unreadRequestsCount = 0;
$pendingItemsCount = 0;

try {
    $db = \App\Core\Database::getInstance();
    $unreadRequestsCount = (int)($db->queryOne('SELECT COUNT(*) AS count FROM contact_messages WHERE is_read = 0')['count'] ?? 0);
    $pendingBookingsCount = (int)($db->queryOne("SELECT COUNT(*) AS count FROM bookings WHERE status = 'pending' AND deleted_at IS NULL")['count'] ?? 0);
    $pendingOrdersCount = (int)($db->queryOne("SELECT COUNT(*) AS count FROM orders WHERE status IN ('pending','preparing')")['count'] ?? 0);
    $pendingItemsCount = $pendingBookingsCount + $pendingOrdersCount;
} catch (Throwable $exception) {
    log_error('Failed to load admin header counters.', $exception);
}
?>
<header class="admin-header">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <button type="button" class="admin-sidebar-toggle" onclick="toggleAdminSidebar()" aria-label="Toggle sidebar">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
        </button>
        <div>
            <h1>Sidai Resort Admin</h1>
            <div class="page-indicator"><?php echo safe_html($pageTitle ?? 'Dashboard'); ?></div>
        </div>
    </div>
    
    <div style="flex: 1; max-width: 400px; margin: 0 2rem; position: relative;" class="admin-search-wrapper">
        <input type="text" placeholder="Search guests, bookings, orders..." class="admin-search-input" style="width: 100%; padding: 0.5rem 1rem 0.5rem 2.5rem; border-radius: 999px; border: 1px solid #E5E7EB; background: #F9FAFB; font-size: 0.875rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position: absolute; left: 0.8rem; top: 50%; transform: translateY(-50%);"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
    </div>

    <div class="user-section" style="display:flex; align-items:center; gap:1.5rem;">
        <div style="display:flex; align-items:center; gap:1rem;">
            <!-- Messages / SMS -->
            <a href="<?php echo WEB_ROOT; ?>/admin/requests.php" class="admin-icon-btn" aria-label="Messages" style="position: relative; background: none; border: none; cursor: pointer; color: #4B5563; display:inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                <?php if ($unreadRequestsCount > 0): ?>
                    <span style="position: absolute; top: -5px; right: -5px; background: #EF4444; color: white; font-size: 0.6rem; font-weight: bold; padding: 0.1rem 0.3rem; border-radius: 999px;"><?php echo $unreadRequestsCount; ?></span>
                <?php endif; ?>
            </a>
            
            <!-- Notifications -->
            <a href="<?php echo WEB_ROOT; ?>/admin/bookings.php" class="admin-icon-btn" aria-label="Notifications" style="position: relative; background: none; border: none; cursor: pointer; color: #4B5563; display:inline-flex;">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                <?php if ($pendingItemsCount > 0): ?>
                    <span style="position: absolute; top: -5px; right: -5px; background: #EAB308; color: white; font-size: 0.6rem; font-weight: bold; padding: 0.1rem 0.3rem; border-radius: 999px;"><?php echo $pendingItemsCount; ?></span>
                <?php endif; ?>
            </a>
        </div>
        
        <div style="height: 24px; width: 1px; background: #E5E7EB;"></div>

        <div style="display:flex; align-items:center; gap:0.75rem;">
            <span class="user-name" style="font-size: 0.875rem; font-weight: 500; color: #374151;">Hello, <?php echo safe_html($staff['full_name'] ?? 'Admin'); ?></span>
            <a href="<?php echo WEB_ROOT; ?>/admin/logout.php" class="btn-logout" style="font-size:0.75rem; padding: 0.3rem 0.75rem;">Logout</a>
        </div>
    </div>
</header>
<div class="admin-sidebar-overlay" onclick="toggleAdminSidebar()"></div>
