<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

use App\Core\CSRF;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

$csrfToken = $_POST[CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
if (class_exists('App\Core\CSRF') && !CSRF::verify($csrfToken)) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Invalid security token.']);
    exit;
}

$name = trim($_POST['full_name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$message) {
    echo json_encode(['success' => false, 'message' => 'Please fill out all required fields.']);
    exit;
}

// Basic success response (you would typically save to DB or send email here)
echo json_encode([
    'success' => true, 
    'message' => 'Thank you for reaching out, ' . htmlspecialchars($name) . '. We will contact you soon!'
]);
exit;
