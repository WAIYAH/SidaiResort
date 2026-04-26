<?php declare(strict_types=1);

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

final class Mailer
{
    private PHPMailer $mail;
    private Database $database;

    public function __construct(?Database $database = null)
    {
        $this->database = $database ?? Database::getInstance();
        $this->mail = new PHPMailer(true);
        $this->configure();
    }

    private function configure(): void
    {
        try {
            $this->mail->isSMTP();
            $this->mail->Host = SMTP_HOST;
            $this->mail->Port = SMTP_PORT;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = SMTP_USERNAME;
            $this->mail->Password = SMTP_PASSWORD;
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $this->mail->setFrom(MAIL_FROM_ADDRESS, MAIL_FROM_NAME);
            $this->mail->isHTML(true);
        } catch (Exception $exception) {
            log_error('Mailer configuration failed.', $exception);
        }
    }

    public function send(
        string $recipientEmail,
        string $recipientName,
        string $subject,
        string $body,
        ?array $attachments = null
    ): bool {
        try {
            $this->mail->clearAllRecipients();
            $this->mail->clearAttachments();

            $this->mail->addAddress($recipientEmail, $recipientName);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->AltBody = strip_tags($body);

            if ($attachments !== null) {
                foreach ($attachments as $path => $name) {
                    $this->mail->addAttachment($path, $name);
                }
            }

            if (!$this->mail->send()) {
                log_error("Failed to send email to {$recipientEmail}: {$this->mail->ErrorInfo}");
                return false;
            }

            return true;
        } catch (Exception $exception) {
            log_error("Email sending exception: {$recipientEmail}", $exception);
            return false;
        }
    }

    public function sendBookingConfirmation(int $bookingId): bool
    {
        $booking = $this->database->queryOne(
            'SELECT b.*, g.full_name, g.email FROM bookings b
             JOIN guests g ON b.guest_id = g.id WHERE b.id = :id',
            [':id' => $bookingId]
        );

        if ($booking === null) {
            return false;
        }

        $subject = "Booking Confirmation — {$booking['booking_ref']}";
        $body = $this->renderTemplate('booking-confirmation', [
            'TITLE' => 'Booking Confirmation',
            'SUBTITLE' => 'Your reservation is confirmed',
            'GUEST_NAME' => $booking['full_name'],
            'BOOKING_REF' => $booking['booking_ref'],
            'BOOKING_TYPE' => ucfirst(str_replace('_', ' ', $booking['booking_type'])),
            'CHECK_IN' => $booking['check_in'] ? format_eat($booking['check_in'], 'l, d M Y') : 'N/A',
            'CHECK_OUT' => $booking['check_out'] ? format_eat($booking['check_out'], 'l, d M Y') : 'N/A',
            'NUM_GUESTS' => (string)($booking['num_guests'] ?? 1),
            'TOTAL_AMOUNT' => format_kes($booking['total_amount']),
            'BOOKING_URL' => rtrim(APP_URL, '/') . '/receipt.php?ref=' . urlencode($booking['booking_ref']),
        ]);

        return $this->send($booking['email'], $booking['full_name'], $subject, $body);
    }

    public function sendPaymentReceipt(int $paymentId): bool
    {
        $payment = $this->database->queryOne(
            'SELECT p.*, b.booking_ref, g.full_name, g.email
             FROM payments p
             JOIN bookings b ON p.booking_id = b.id
             JOIN guests g ON b.guest_id = g.id
             WHERE p.id = :id',
            [':id' => $paymentId]
        );

        if ($payment === null) {
            return false;
        }

        $subject = "Payment Receipt — {$payment['payment_ref']}";
        $body = $this->renderTemplate('payment-receipt', [
            'TITLE' => 'Payment Receipt',
            'SUBTITLE' => 'Thank you for your payment',
            'GUEST_NAME' => $payment['full_name'],
            'PAYMENT_REF' => $payment['payment_ref'],
            'BOOKING_REF' => $payment['booking_ref'],
            'PAYMENT_METHOD' => ucfirst($payment['method']),
            'AMOUNT' => format_kes($payment['amount']),
            'PAID_AT' => $payment['paid_at'] ? format_eat($payment['paid_at'], 'd M Y, H:i') : 'N/A',
            'TRANSACTION_ID' => $payment['mpesa_receipt'] ?? $payment['payment_ref'],
            'RECEIPT_URL' => rtrim(APP_URL, '/') . '/receipt.php?ref=' . urlencode($payment['booking_ref']),
        ]);

        return $this->send($payment['email'], $payment['full_name'], $subject, $body);
    }

    public function sendCheckinWelcome(int $bookingId, array $wifiInfo = []): bool
    {
        $booking = $this->database->queryOne(
            'SELECT b.*, g.full_name, g.email, r.name as room_name, r.room_number
             FROM bookings b
             JOIN guests g ON b.guest_id = g.id
             LEFT JOIN rooms r ON b.room_id = r.id
             WHERE b.id = :id',
            [':id' => $bookingId]
        );

        if ($booking === null) {
            return false;
        }

        $subject = "Welcome to Sidai Resort — {$booking['booking_ref']}";
        $body = $this->renderTemplate('checkin-welcome', [
            'TITLE' => 'Welcome',
            'SUBTITLE' => "We're glad you're here",
            'GUEST_NAME' => $booking['full_name'],
            'BOOKING_REF' => $booking['booking_ref'],
            'ROOM_NAME' => $booking['room_name'] ?? 'Your Room',
            'ROOM_NUMBER' => $booking['room_number'] ?? '',
            'CHECK_OUT' => $booking['check_out'] ? format_eat($booking['check_out'], 'l, d M Y') : 'N/A',
            'WIFI_NAME' => $wifiInfo['name'] ?? 'SidaiResort-Guest',
            'WIFI_PASSWORD' => $wifiInfo['password'] ?? 'WelcomeGuest2026',
        ]);

        return $this->send($booking['email'], $booking['full_name'], $subject, $body);
    }

    public function sendCheckoutThankYou(int $bookingId): bool
    {
        $booking = $this->database->queryOne(
            'SELECT b.*, g.full_name, g.email FROM bookings b
             JOIN guests g ON b.guest_id = g.id WHERE b.id = :id',
            [':id' => $bookingId]
        );

        if ($booking === null) {
            return false;
        }

        $subject = "Thank You for Staying — {$booking['booking_ref']}";
        $body = $this->renderTemplate('checkout-thankyou', [
            'TITLE' => 'Thank You',
            'SUBTITLE' => 'We hope to see you again',
            'GUEST_NAME' => $booking['full_name'],
            'BOOKING_REF' => $booking['booking_ref'],
            'CHECK_IN' => $booking['check_in'] ? format_eat($booking['check_in'], 'd M Y') : 'N/A',
            'CHECK_OUT' => $booking['check_out'] ? format_eat($booking['check_out'], 'd M Y') : 'N/A',
            'TOTAL_AMOUNT' => format_kes($booking['total_amount']),
            'REVIEW_URL' => rtrim(APP_URL, '/') . '/contact.php',
        ]);

        return $this->send($booking['email'], $booking['full_name'], $subject, $body);
    }

    /**
     * Render an email template by name with variable substitution.
     */
    private function renderTemplate(string $templateName, array $variables = []): string
    {
        $basePath = APP_PATH . '/templates/email/base.html';
        $contentPath = APP_PATH . '/templates/email/' . $templateName . '.html';

        if (!file_exists($basePath) || !file_exists($contentPath)) {
            log_error("Email template not found: {$templateName}");
            return '';
        }

        $base = file_get_contents($basePath);
        $content = file_get_contents($contentPath);

        // Default variables
        $defaults = [
            'YEAR' => date('Y'),
            'UNSUBSCRIBE_URL' => rtrim(APP_URL, '/') . '/unsubscribe.php',
            'TITLE' => 'Sidai Resort',
            'SUBTITLE' => '',
        ];

        $variables = array_merge($defaults, $variables);

        // Insert content into base
        $html = str_replace('{{CONTENT}}', $content, $base);

        // Replace all variables
        foreach ($variables as $key => $value) {
            $html = str_replace('{{' . $key . '}}', htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'), $html);
        }

        return $html;
    }
}


