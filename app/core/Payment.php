<?php declare(strict_types=1);

namespace App\Core;

final class Payment
{
    private Database $database;
    private Logger $logger;

    public function __construct(?Database $database = null, ?Logger $logger = null)
    {
        $this->database = $database ?? Database::getInstance();
        $this->logger = $logger ?? new Logger();
    }

    public function initiateMpesa(string $phone, float $amount, int $bookingId): ?string
    {
        try {
            // Validate phone format
            $phone = $this->normalizeMpesaPhone($phone);
            if (!$this->isValidMpesaPhone($phone)) {
                throw new \Exception('Invalid phone number format');
            }

            // Get OAuth token
            $token = $this->getMpesaToken();
            if (!$token) {
                throw new \Exception('Failed to get OAuth token');
            }

            // Prepare STK Push request
            $timestamp = date('YmdHis');
            $password = base64_encode(MPESA_SHORTCODE . MPESA_PASSKEY . $timestamp);

            $payload = [
                'BusinessShortCode' => MPESA_SHORTCODE,
                'Password' => $password,
                'Timestamp' => $timestamp,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => (int)$amount,
                'PartyA' => $phone,
                'PartyB' => MPESA_SHORTCODE,
                'PhoneNumber' => $phone,
                'CallBackURL' => MPESA_CALLBACK_URL,
                'AccountReference' => MPESA_ACCOUNT_REFERENCE,
                'TransactionDesc' => MPESA_TRANSACTION_DESC,
            ];

            // Call STK Push API
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => MPESA_STK_PUSH_URL,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $token,
                    'Content-Type: application/json',
                ],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => json_encode($payload),
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $responseData = json_decode($response, true);

            if ($httpCode !== 200 || !isset($responseData['CheckoutRequestID'])) {
                $this->logger->logMpesa('stk_push_failed', $responseData);
                throw new \Exception('STK Push initiation failed');
            }

            $checkoutRequestId = $responseData['CheckoutRequestID'];

            // Store in payments table
            $this->database->query(
                'INSERT INTO payments (payment_ref, booking_id, amount, method, status, mpesa_checkout_request_id, mpesa_phone, created_at)
                 VALUES (:payment_ref, :booking_id, :amount, :method, :status, :checkout_id, :phone, NOW())',
                [
                    ':payment_ref' => generate_payment_ref(),
                    ':booking_id' => $bookingId,
                    ':amount' => $amount,
                    ':method' => 'mpesa',
                    ':status' => 'pending',
                    ':checkout_id' => $checkoutRequestId,
                    ':phone' => $phone,
                ]
            );

            $this->logger->logMpesa('stk_push_initiated', [
                'phone' => $phone,
                'amount' => $amount,
                'checkout_id' => $checkoutRequestId,
            ]);

            return $checkoutRequestId;
        } catch (\Throwable $exception) {
            $this->logger->logError('M-Pesa initiation failed', $exception);
            return null;
        }
    }

    public function getPaymentStatus(string $checkoutRequestId): ?array
    {
        try {
            $payment = $this->database->queryOne(
                'SELECT * FROM payments WHERE mpesa_checkout_request_id = :id LIMIT 1',
                [':id' => $checkoutRequestId]
            );

            return $payment;
        } catch (\Throwable $exception) {
            $this->logger->logError('Failed to get payment status', $exception);
            return null;
        }
    }

    public function processCallback(array $callbackData): bool
    {
        try {
            $resultCode = $callbackData['Body']['stkCallback']['ResultCode'] ?? null;
            $checkoutRequestId = $callbackData['Body']['stkCallback']['CheckoutRequestID'] ?? null;

            if ($checkoutRequestId === null) {
                return false;
            }

            $payment = $this->database->queryOne(
                'SELECT id, booking_id, amount FROM payments WHERE mpesa_checkout_request_id = :id LIMIT 1',
                [':id' => $checkoutRequestId]
            );

            if ($payment === null) {
                return false;
            }

            if ($resultCode === '0') {
                // Success
                $callbackMetadata = $callbackData['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];
                $mpesaReceiptNumber = null;
                $transactionDate = null;

                foreach ($callbackMetadata as $item) {
                    if ($item['Name'] === 'MpesaReceiptNumber') {
                        $mpesaReceiptNumber = $item['Value'];
                    }
                    if ($item['Name'] === 'TransactionDate') {
                        $transactionDate = $item['Value'];
                    }
                }

                $this->database->query(
                    'UPDATE payments SET status = :status, mpesa_receipt_number = :receipt, paid_at = NOW()
                     WHERE id = :id',
                    [
                        ':status' => 'completed',
                        ':receipt' => $mpesaReceiptNumber,
                        ':id' => $payment['id'],
                    ]
                );

                $this->database->query(
                    'UPDATE bookings SET payment_status = :status WHERE id = :id',
                    [
                        ':status' => 'paid',
                        ':id' => $payment['booking_id'],
                    ]
                );

                log_audit_action('payment.completed', 'payment', $payment['id']);
                return true;
            } else {
                // Failed
                $this->database->query(
                    'UPDATE payments SET status = :status WHERE id = :id',
                    [
                        ':status' => 'failed',
                        ':id' => $payment['id'],
                    ]
                );

                log_audit_action('payment.failed', 'payment', $payment['id']);
                return false;
            }
        } catch (\Throwable $exception) {
            $this->logger->logError('Callback processing failed', $exception);
            return false;
        }
    }

    private function getMpesaToken(): ?string
    {
        try {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => MPESA_OAUTH_URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERPWD => MPESA_CONSUMER_KEY . ':' . MPESA_CONSUMER_SECRET,
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $data = json_decode($response, true);

            return $data['access_token'] ?? null;
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function normalizeMpesaPhone(string $phone): string
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '07') || str_starts_with($phone, '01')) {
            $phone = '254' . substr($phone, 1);
        }

        return $phone;
    }

    private function isValidMpesaPhone(string $phone): bool
    {
        return preg_match('/^254[0-9]{9}$/', $phone) === 1;
    }
}
