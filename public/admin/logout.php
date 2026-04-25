<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

if (isset($_SESSION['staff'])) {
    $auth = new \App\Core\Auth();
    $auth->logout();
}

header('Location: ' . WEB_ROOT . '/admin/login.php');
exit;
