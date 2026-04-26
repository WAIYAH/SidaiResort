<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (!isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/login.php');
    exit;
}

// Only super_admin can access settings
$auth = new \App\Core\Auth();
if (!$auth->hasRole('super_admin')) {
    http_response_code(403);
    echo 'Access denied. Only Super Admins can manage settings.';
    exit;
}

$staff = $_SESSION['staff'];
$pageTitle = 'Settings';

$settingsModel = new \App\Models\SiteSetting();
$allSettings = $settingsModel->getAll();

// Group settings
$grouped = [];
foreach ($allSettings as $setting) {
    $group = $setting['setting_group'] ?? 'general';
    $grouped[$group][] = $setting;
}

// Handle save
$message = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (string)($_POST['action'] ?? '') === 'save_settings') {
    $token = $_POST[CSRF_TOKEN_NAME] ?? null;
    if (!\App\Core\CSRF::verify(is_string($token) ? $token : null)) {
        $message = ['type' => 'error', 'text' => 'Security validation failed. Please refresh and retry.'];
    } else {
        $updates = $_POST['settings'] ?? [];

        foreach ($updates as $key => $value) {
            $settingsModel->set($key, $value);
        }

        log_audit_action('settings.update', 'site_settings', null, ['keys' => array_keys($updates)]);
        $message = ['type' => 'success', 'text' => 'Settings saved successfully.'];
        $allSettings = $settingsModel->getAll();
        $grouped = [];
        foreach ($allSettings as $setting) {
            $group = $setting['setting_group'] ?? 'general';
            $grouped[$group][] = $setting;
        }
    }
}
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<?php include APP_PATH . '/includes/admin-header.php'; ?>

<div class="admin-layout">
    <?php include APP_PATH . '/includes/admin-sidebar.php'; ?>

    <main class="admin-main">
        <div class="admin-page-header">
            <h2 class="admin-page-title">Site Settings</h2>
        </div>

        <?php if ($message): ?>
            <div class="admin-alert admin-alert-<?php echo $message['type']; ?>" data-auto-dismiss="4000">
                <?php echo safe_html($message['text']); ?>
            </div>
        <?php endif; ?>

        <?php if (empty($grouped)): ?>
            <div class="admin-card">
                <div class="admin-card-body">
                    <div class="admin-empty">
                        <div class="empty-icon">⚙️</div>
                        <div class="empty-text">No settings configured yet.</div>
                        <p style="margin-top:0.75rem;font-size:0.875rem;color:#6B7280;">Settings will appear here once added to the site_settings database table.</p>
                    </div>
                </div>
            </div>

            <!-- Default Settings Quick-Add -->
            <div class="admin-card" style="margin-top:1.5rem;">
                <div class="admin-card-header">
                    <h3 class="admin-card-title">Initialize Default Settings</h3>
                </div>
                <div class="admin-card-body">
                    <p style="font-size:0.875rem;color:#6B7280;margin-bottom:1rem;">Add common settings to the database:</p>
                    <form method="post">
                        <?php echo \App\Core\CSRF::field(); ?>
                        <input type="hidden" name="action" value="save_settings">
                        <div class="admin-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Resort Name</label>
                                    <input type="text" name="settings[resort_name]" value="<?php echo safe_html(APP_NAME); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Contact Email</label>
                                    <input type="email" name="settings[contact_email]" value="<?php echo safe_html(APP_EMAIL); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Contact Phone</label>
                                    <input type="tel" name="settings[contact_phone]" value="<?php echo safe_html(APP_PHONE); ?>">
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="settings[address]" value="<?php echo safe_html(APP_ADDRESS); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Check-in Time</label>
                                    <input type="text" name="settings[checkin_time]" value="14:00">
                                </div>
                                <div class="form-group">
                                    <label>Check-out Time</label>
                                    <input type="text" name="settings[checkout_time]" value="10:00">
                                </div>
                            </div>
                        </div>
                        <div style="margin-top:1rem;">
                            <button type="submit" class="btn btn-gold">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <form method="post" class="admin-form">
                <?php echo \App\Core\CSRF::field(); ?>
                <input type="hidden" name="action" value="save_settings">

                <?php foreach ($grouped as $group => $settings): ?>
                    <div class="admin-card" style="margin-bottom:1.5rem;">
                        <div class="admin-card-header">
                            <h3 class="admin-card-title"><?php echo ucfirst(str_replace('_', ' ', $group)); ?> Settings</h3>
                        </div>
                        <div class="admin-card-body">
                            <div class="form-row">
                                <?php foreach ($settings as $setting): ?>
                                    <div class="form-group">
                                        <label for="setting_<?php echo safe_html($setting['setting_key']); ?>">
                                            <?php echo ucwords(str_replace('_', ' ', $setting['setting_key'])); ?>
                                        </label>
                                        <?php if ($setting['setting_type'] === 'boolean'): ?>
                                            <select id="setting_<?php echo safe_html($setting['setting_key']); ?>"
                                                    name="settings[<?php echo safe_html($setting['setting_key']); ?>]">
                                                <option value="1" <?php echo $setting['setting_value'] === '1' ? 'selected' : ''; ?>>Yes</option>
                                                <option value="0" <?php echo $setting['setting_value'] !== '1' ? 'selected' : ''; ?>>No</option>
                                            </select>
                                        <?php elseif ($setting['setting_type'] === 'json'): ?>
                                            <textarea id="setting_<?php echo safe_html($setting['setting_key']); ?>"
                                                      name="settings[<?php echo safe_html($setting['setting_key']); ?>]"
                                                      rows="3"><?php echo safe_html($setting['setting_value'] ?? ''); ?></textarea>
                                        <?php else: ?>
                                            <input type="<?php echo $setting['setting_type'] === 'email' ? 'email' : 'text'; ?>"
                                                   id="setting_<?php echo safe_html($setting['setting_key']); ?>"
                                                   name="settings[<?php echo safe_html($setting['setting_key']); ?>]"
                                                   value="<?php echo safe_html($setting['setting_value'] ?? ''); ?>">
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div style="margin-top:1rem;">
                    <button type="submit" class="btn btn-gold">Save All Settings</button>
                </div>
            </form>
        <?php endif; ?>
    </main>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
