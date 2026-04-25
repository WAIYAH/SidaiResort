<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (isset($_SESSION['staff'])) {
    header('Location: ' . WEB_ROOT . '/admin/index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            $error = 'Email and password are required';
        } else {
            $auth = new \App\Core\Auth();
            if ($auth->login($email, $password)) {
                header('Location: ' . WEB_ROOT . '/admin/index.php');
                exit;
            } else {
                $error = 'Invalid email or password';
            }
        }
    } catch (Exception $exception) {
        $error = 'Login failed. Please try again.';
        log_error('Admin login error', $exception);
    }
}

$pageTitle = 'Admin Login';
?>
<?php include APP_PATH . '/includes/admin-head.php'; ?>

<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:linear-gradient(135deg,#0F3320 0%,#1A4D2E 50%,#0F3320 100%);">
    <div style="width:100%;max-width:420px;padding:0 1.5rem;">
        <div class="admin-card" style="padding:2.5rem;">
            <div style="text-align:center;margin-bottom:2rem;">
                <div style="display:inline-flex;align-items:center;justify-content:center;width:4rem;height:4rem;border-radius:50%;background:linear-gradient(135deg,#D4AF37,#A08020);margin-bottom:1rem;">
                    <span style="font-size:1.75rem;color:#0A0A0A;font-weight:bold;">S</span>
                </div>
                <h1 style="font-family:'Playfair Display',serif;font-size:1.75rem;color:#1A4D2E;margin:0;">Sidai Resort</h1>
                <p style="font-size:0.875rem;color:#6B7280;margin-top:0.25rem;">Admin Portal</p>
            </div>

            <?php if ($error): ?>
                <div class="admin-alert admin-alert-error" style="margin-bottom:1.5rem;">
                    <?php echo safe_html($error); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="admin-form">
                <div class="form-group" style="margin-bottom:1rem;">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus
                           value="<?php echo safe_html($_POST['email'] ?? ''); ?>"
                           placeholder="admin@sidairesort.com">
                </div>

                <div class="form-group" style="margin-bottom:1.5rem;">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Enter your password">
                </div>

                <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:0.75rem;">
                    Sign In
                </button>
            </form>

            <div style="margin-top:1.5rem;text-align:center;">
                <a href="<?php echo WEB_ROOT; ?>/" style="font-size:0.85rem;color:#D4AF37;text-decoration:none;">← Back to Website</a>
            </div>
        </div>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.8rem;color:rgba(255,255,255,0.5);">
            &copy; <?php echo date('Y'); ?> Sidai Safari Dreams. All rights reserved.
        </p>
    </div>
</div>

<?php include APP_PATH . '/includes/admin-footer.php'; ?>
