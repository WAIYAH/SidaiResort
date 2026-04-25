<?php declare(strict_types=1);

use App\Core\CSRF;
use App\Core\Database;

if (!function_exists('sidai_log_mpesa')) {
    function sidai_log_mpesa(string $event, array $payload = []): void
    {
        $line = json_encode([
            'time' => format_eat('now', 'Y-m-d H:i:s'),
            'event' => $event,
            'payload' => $payload,
        ], JSON_UNESCAPED_SLASHES);

        if ($line !== false) {
            error_log($line . PHP_EOL, 3, LOG_MPESA_FILE);
        }
    }
}

if (!function_exists('sidai_mask_phone')) {
    function sidai_mask_phone(string $phone): string
    {
        $length = strlen($phone);
        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return str_repeat('*', max(0, $length - 4)) . substr($phone, -4);
    }
}

if (!function_exists('sidai_normalize_mpesa_phone')) {
    function sidai_normalize_mpesa_phone(string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === null || $digits === '') {
            return null;
        }

        if (strlen($digits) === 10 && str_starts_with($digits, '0')) {
            $digits = '254' . substr($digits, 1);
        }

        if (strlen($digits) === 12 && str_starts_with($digits, '254')) {
            return preg_match('/^254(?:7\d{8}|1\d{8})$/', $digits) === 1 ? $digits : null;
        }

        return null;
    }
}

if (!function_exists('sidai_generate_payment_ref')) {
    function sidai_generate_payment_ref(Database $database): string
    {
        do {
            $candidate = sprintf(
                'PAY-%s-%05d',
                date('Ymd'),
                random_int(0, 99999)
            );

            $exists = $database->queryOne(
                'SELECT id FROM payments WHERE payment_ref = :payment_ref LIMIT 1',
                [':payment_ref' => $candidate]
            );
        } while ($exists !== null);

        return $candidate;
    }
}

if (!function_exists('sidai_initiate_mpesa_payment')) {
    function sidai_initiate_mpesa_payment(Database $database, array $payload): array
    {
        $bookingId = isset($payload['booking_id']) ? (int)$payload['booking_id'] : null;
        $bookingRef = trim((string)($payload['booking_ref'] ?? ''));
        $phone = sidai_normalize_mpesa_phone((string)($payload['phone'] ?? ''));
        $amount = (float)($payload['amount'] ?? 0);
        $notes = trim((string)($payload['notes'] ?? 'Booking deposit'));

        if ($bookingId === null || $bookingId <= 0 || $bookingRef === '') {
            return [
                'success' => false,
                'message' => 'Invalid booking reference for M-Pesa payment.',
            ];
        }

        if ($phone === null) {
            return [
                'success' => false,
                'message' => 'Invalid M-Pesa phone number.',
            ];
        }

        if ($amount <= 0) {
            return [
                'success' => false,
                'message' => 'Payment amount must be greater than zero.',
            ];
        }

        $checkoutRequestId = sprintf(
            'SIDAI-CHK-%s-%s',
            date('YmdHis'),
            strtoupper(bin2hex(random_bytes(3)))
        );

        $paymentRef = sidai_generate_payment_ref($database);

        $database->query(
            'INSERT INTO payments (
                payment_ref,
                booking_id,
                amount,
                method,
                status,
                mpesa_checkout_request_id,
                mpesa_phone,
                notes,
                created_at
            ) VALUES (
                :payment_ref,
                :booking_id,
                :amount,
                :method,
                :status,
                :checkout_request_id,
                :phone,
                :notes,
                NOW()
            )',
            [
                ':payment_ref' => $paymentRef,
                ':booking_id' => $bookingId,
                ':amount' => $amount,
                ':method' => 'mpesa',
                ':status' => 'pending',
                ':checkout_request_id' => $checkoutRequestId,
                ':phone' => $phone,
                ':notes' => $notes,
            ]
        );

        sidai_log_mpesa('mpesa_initiated', [
            'booking_ref' => $bookingRef,
            'checkout_request_id' => $checkoutRequestId,
            'amount' => $amount,
            'phone' => sidai_mask_phone($phone),
        ]);

        return [
            'success' => true,
            'checkout_request_id' => $checkoutRequestId,
            'message' => 'M-Pesa prompt initiated. Complete payment on your phone.',
        ];
    }
}

if (!function_exists('sidai_handle_mpesa_initiate_request')) {
    function sidai_handle_mpesa_initiate_request(): never
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            json_response(['success' => false, 'message' => 'Method not allowed.'], 405);
        }

        $csrfToken = $_POST[CSRF_TOKEN_NAME] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        if (!CSRF::verify(is_string($csrfToken) ? $csrfToken : null)) {
            json_response(['success' => false, 'message' => 'Security validation failed.'], 419);
        }

        $bookingRef = sanitize_input($_POST['booking_ref'] ?? null);
        $phone = sanitize_input($_POST['phone'] ?? null);
        $amount = (float)($_POST['amount'] ?? 0);

        if ($bookingRef === '' || $phone === '' || $amount <= 0) {
            json_response(['success' => false, 'message' => 'Required payment fields are missing.'], 422);
        }

        try {
            $database = Database::getInstance();

            $booking = $database->queryOne(
                'SELECT id, booking_ref
                 FROM bookings
                 WHERE booking_ref = :booking_ref
                 LIMIT 1',
                [':booking_ref' => $bookingRef]
            );

            if ($booking === null) {
                json_response(['success' => false, 'message' => 'Booking was not found.'], 404);
            }

            $result = sidai_initiate_mpesa_payment($database, [
                'booking_id' => (int)$booking['id'],
                'booking_ref' => $bookingRef,
                'phone' => $phone,
                'amount' => $amount,
                'notes' => 'M-Pesa STK initiated via payment page',
            ]);

            if (($result['success'] ?? false) !== true) {
                json_response(['success' => false, 'message' => (string)($result['message'] ?? 'Unable to initiate M-Pesa payment.')], 422);
            }

            json_response($result, 200);
        } catch (Throwable $exception) {
            log_error('M-Pesa initiate request failed.', $exception);
            json_response(['success' => false, 'message' => 'Unable to initiate payment at the moment.'], 500);
        }
    }
}
