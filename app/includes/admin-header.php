<?php declare(strict_types=1);

/**
 * Admin Header Include
 * Provides the top navigation bar and mobile sidebar toggle.
 */

$staff = $_SESSION['staff'] ?? [];
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
    <div class="user-section">
        <span class="user-name">Welcome, <?php echo safe_html($staff['full_name'] ?? 'Admin'); ?></span>
        <a href="<?php echo WEB_ROOT; ?>/admin/logout.php" class="btn-logout">Logout</a>
    </div>
</header>
<div class="admin-sidebar-overlay" onclick="toggleAdminSidebar()"></div>
