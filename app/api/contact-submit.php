<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

use App\Models\ContactMessage;
use App\Core\Mailer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Validate CSRF token
    $csrfToken = $_POST[CSRF_TOKEN_NAME] ?? ($_POST['csrf_token'] ?? '');
    if (!verify_csrf_token(is_string($csrfToken) ? $csrfToken : null)) {
        throw new Exception('Invalid CSRF token');
    }

    // Validate input
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$fullName || !$email || !$subject || !$message) {
        throw new Exception('Missing required fields');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('Invalid email address');
    }

    // Validate phone if provided
    if ($phone && !preg_match('/^[0-9+\s\-()]+$/', $phone)) {
        throw new Exception('Invalid phone format');
    }

    // Create contact message
    $contactModel = new ContactMessage();
    $created = $contactModel->create([
        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'message' => $message,
    ]);

    if (!$created) {
        throw new Exception('Failed to save contact message');
    }

    // Send notification to admin
    $mailer = new Mailer();
    $adminBody = <<<HTML
<!DOCTYPE html>
<html>
<body>
    <p>New contact message received:</p>
    <p><strong>Name:</strong> {$fullName}</p>
    <p><strong>Email:</strong> {$email}</p>
    <p><strong>Phone:</strong> {$phone}</p>
    <p><strong>Subject:</strong> {$subject}</p>
    <p><strong>Message:</strong></p>
    <p>{$message}</p>
</body>
</html>
HTML;

    $mailer->send(RESORT_EMAIL, APP_NAME, 'New Contact Message: ' . $subject, $adminBody);

    // Send confirmation to user
    $userBody = <<<HTML
<!DOCTYPE html>
<html>
<body>
    <p>Dear {$fullName},</p>
    <p>Thank you for contacting Sidai Resort. We have received your message and will respond within 24 hours.</p>
    <p>Best regards,<br>Sidai Resort Team</p>
</body>
</html>
HTML;

    $mailer->send($email, $fullName, 'We received your message', $userBody);

    log_audit_action('contact.submitted', 'contact_message', null);

    echo json_encode([
        'success' => true,
        'message' => 'Message sent successfully. We will respond within 24 hours.'
    ]);

} catch (Exception $exception) {
    log_error('Contact submission failed', $exception);

    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage()
    ]);
    exit(1);
}
