<?php declare(strict_types=1);

use App\Core\Database;

if (!function_exists('sidai_fetch_mpesa_status')) {
    function sidai_fetch_mpesa_status(Database $database, string $checkoutRequestId): array
    {
        $payment = $database->queryOne(
            'SELECT status, mpesa_receipt_number
             FROM payments
             WHERE mpesa_checkout_request_id = :checkout_request_id
             ORDER BY id DESC
             LIMIT 1',
            [':checkout_request_id' => $checkoutRequestId]
        );

        if ($payment === null) {
            return [
                'success' => false,
                'status' => 'failed',
                'message' => 'Payment request was not found.',
            ];
        }

        $rawStatus = strtolower((string)($payment['status'] ?? 'pending'));
        $status = match ($rawStatus) {
            'completed' => 'completed',
            'failed', 'refunded', 'disputed' => 'failed',
            default => 'pending',
        };

        return [
            'success' => true,
            'status' => $status,
            'receipt' => (string)($payment['mpesa_receipt_number'] ?? ''),
        ];
    }
}

if (!function_exists('sidai_handle_mpesa_status_request')) {
    function sidai_handle_mpesa_status_request(): never
    {
        $checkoutRequestId = trim((string)($_GET['checkout_request_id'] ?? $_POST['checkout_request_id'] ?? ''));
        if ($checkoutRequestId === '') {
            json_response(['success' => false, 'message' => 'checkout_request_id is required.'], 422);
        }

        try {
            $database = Database::getInstance();
            $result = sidai_fetch_mpesa_status($database, $checkoutRequestId);

            if (($result['success'] ?? false) !== true) {
                json_response($result, 404);
            }

            json_response($result, 200);
        } catch (Throwable $exception) {
            log_error('M-Pesa status request failed.', $exception);
            json_response(['success' => false, 'message' => 'Unable to fetch payment status right now.'], 500);
        }
    }
}
