<?php declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/app/includes/init.php';

use App\Core\CSRF;
use App\Core\Database;
use App\Core\Validator;

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
    json_response(['success' => false, 'message' => 'Method not allowed.'], 405);
}

$csrfToken = $_POST[CSRF_TOKEN_NAME] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
if (!CSRF::verify(is_string($csrfToken) ? $csrfToken : null)) {
    json_response(['success' => false, 'message' => 'Security validation failed. Please refresh and try again.'], 419);
}

$rateKey = 'contact_submit_attempts';
$windowSeconds = 3600;
$maxAttempts = RATE_LIMIT_CONTACT_PER_HOUR;

$_SESSION[$rateKey] = array_values(array_filter(
    $_SESSION[$rateKey] ?? [],
    static fn (int $timestamp): bool => (time() - $timestamp) < $windowSeconds
));

if (count($_SESSION[$rateKey]) >= $maxAttempts) {
    json_response(['success' => false, 'message' => 'Too many requests. Please try again later.'], 429);
}
$_SESSION[$rateKey][] = time();

$payload = [
    'full_name' => sanitize_input($_POST['full_name'] ?? null),
    'email' => strtolower(sanitize_input($_POST['email'] ?? null)),
    'phone' => sanitize_input($_POST['phone'] ?? null),
    'subject' => sanitize_input($_POST['subject'] ?? null),
    'message' => sanitize_input($_POST['message'] ?? null),
];

$validator = new Validator();
$validation = $validator->validate(
    $payload,
    [
        'full_name' => ['required', 'min:3', 'max:150'],
        'email' => ['required', 'email', 'max:200'],
        'phone' => ['required', 'phone'],
        'subject' => ['required', 'min:2', 'max:200'],
        'message' => ['required', 'min:10'],
    ]
);

if ($validation['valid'] !== true) {
    $firstError = 'Please provide valid details.';
    foreach ($validation['errors'] as $messages) {
        $firstError = (string)($messages[0] ?? $firstError);
        break;
    }

    json_response([
        'success' => false,
        'message' => $firstError,
        'errors' => $validation['errors'],
    ], 422);
}

try {
    $database = Database::getInstance();
    $database->beginTransaction();

    $database->query(
        'INSERT INTO contact_messages (
            full_name,
            email,
            phone,
            subject,
            message,
            is_read,
            created_at
        ) VALUES (
            :full_name,
            :email,
            :phone,
            :subject,
            :message,
            0,
            NOW()
        )',
        [
            ':full_name' => $payload['full_name'],
            ':email' => $payload['email'],
            ':phone' => $payload['phone'],
            ':subject' => $payload['subject'],
            ':message' => $payload['message'],
        ]
    );

    $messageId = $database->lastInsertId();

    $database->query(
        'INSERT INTO audit_log (
            staff_id,
            action,
            entity_type,
            entity_id,
            old_values,
            new_values,
            ip_address,
            user_agent,
            created_at
        ) VALUES (
            NULL,
            :action,
            :entity_type,
            :entity_id,
            NULL,
            :new_values,
            :ip_address,
            :user_agent,
            NOW()
        )',
        [
            ':action' => 'contact_message.create',
            ':entity_type' => 'contact_message',
            ':entity_id' => $messageId,
            ':new_values' => json_encode(
                [
                    'subject' => $payload['subject'],
                    'email' => $payload['email'],
                ],
                JSON_UNESCAPED_SLASHES
            ),
            ':ip_address' => get_client_ip(),
            ':user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
        ]
    );

    $database->commit();

    $subjectAdmin = APP_NAME . ' Contact Message: ' . $payload['subject'];
    $bodyAdmin = implode("\n", [
        'New contact message received',
        '',
        'Name: ' . $payload['full_name'],
        'Email: ' . $payload['email'],
        'Phone: ' . $payload['phone'],
        'Subject: ' . $payload['subject'],
        '',
        $payload['message'],
    ]);
    $headers = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';
    @mail(MAIL_ADMIN_ADDRESS, $subjectAdmin, $bodyAdmin, $headers);

    $subjectUser = APP_NAME . ' - We Received Your Message';
    $bodyUser = implode("\n", [
        'Hello ' . $payload['full_name'] . ',',
        '',
        'Thank you for contacting Sidai Resort.',
        'We have received your message and will respond shortly.',
        '',
        'Warm regards,',
        APP_NAME . ' Team',
    ]);
    @mail($payload['email'], $subjectUser, $bodyUser, $headers);

    json_response([
        'success' => true,
        'message' => 'Message sent successfully. We will respond shortly.',
    ]);
} catch (Throwable $exception) {
    if (isset($database)) {
        try {
            $database->rollback();
        } catch (Throwable $rollbackException) {
            log_error('Contact submission rollback failed.', $rollbackException);
        }
    }

    log_error('Contact submission failed.', $exception);
    json_response([
        'success' => false,
        'message' => 'Unable to send your message right now. Please try again later.',
    ], 500);
}
