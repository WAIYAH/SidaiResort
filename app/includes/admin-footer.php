<?php declare(strict_types=1);

/**
 * Admin Footer Include
 * Loads admin-specific scripts.
 */
?>
    <script src="<?php echo WEB_ROOT; ?>/admin/assets/js/admin-config.js"></script>
    <script src="<?php echo WEB_ROOT; ?>/admin/assets/js/admin.js"></script>
    <?php if (isset($extraJs)): ?>
        <?php foreach ((array)$extraJs as $js): ?>
            <script src="<?php echo safe_html($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
