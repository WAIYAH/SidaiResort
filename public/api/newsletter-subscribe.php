<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

use App\Core\CSRF;
use App\Core\Database;
use App\Core\Validator;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response([
        'success' => false,
        'message' => 'Method not allowed.',
    ], 405);
}

$csrfToken = $_POST[CSRF_TOKEN_NAME] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
if (!CSRF::verify(is_string($csrfToken) ? $csrfToken : null)) {
    json_response([
        'success' => false,
        'message' => 'Security validation failed. Please refresh and try again.',
    ], 419);
}

$rateKey = 'api_newsletter_attempts';
$windowSeconds = 3600;
$maxAttempts = 6;

$_SESSION[$rateKey] = array_values(array_filter(
    $_SESSION[$rateKey] ?? [],
    static fn (int $timestamp): bool => (time() - $timestamp) < $windowSeconds
));

if (count($_SESSION[$rateKey]) >= $maxAttempts) {
    json_response([
        'success' => false,
        'message' => 'Too many requests. Please try again later.',
    ], 429);
}

$_SESSION[$rateKey][] = time();

$email = sanitize_input($_POST['email'] ?? null);

$validator = new Validator();
$validation = $validator->validate(
    ['email' => $email],
    ['email' => ['required', 'email', 'max:200']]
);

if ($validation['valid'] !== true) {
    json_response([
        'success' => false,
        'message' => $validation['errors']['email'][0] ?? 'Please provide a valid email.',
    ], 422);
}

try {
    $database = Database::getInstance();
    $database->beginTransaction();

    $database->query(
        'INSERT INTO newsletter_subscribers (email, is_active, verified_at, unsubscribed_at, created_at)
         VALUES (:email, 1, NULL, NULL, NOW())
         ON DUPLICATE KEY UPDATE is_active = 1, unsubscribed_at = NULL',
        [':email' => $email]
    );

    $database->commit();

    $subject = APP_NAME . ' Newsletter Subscription';
    $message = "Thank you for subscribing to Sidai Resort updates.\n\nYou will receive exclusive offers and event highlights.";
    $headers = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';
    @mail($email, $subject, $message, $headers);

    json_response([
        'success' => true,
        'message' => 'Subscription successful. Karibu Sidai.',
    ]);
} catch (Throwable $exception) {
    if (isset($database)) {
        try {
            $database->rollback();
        } catch (Throwable $rollbackException) {
            log_error('Newsletter API rollback failed.', $rollbackException);
        }
    }

    log_error('Newsletter subscription API failed.', $exception);

    json_response([
        'success' => false,
        'message' => 'Unable to process your request at the moment.',
    ], 500);
}
