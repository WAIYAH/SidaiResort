<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

use App\Core\Payment;
use App\Core\Mailer;
use App\Core\Logger;

header('Content-Type: application/json');

$logger = new Logger();

try {
    // Get raw POST data
    $rawInput = file_get_contents('php://input');
    $callbackData = json_decode($rawInput, true);

    $logger->logMpesa('callback_received', $callbackData);

    if (!$callbackData || !isset($callbackData['Body'])) {
        echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'Invalid payload']);
        exit;
    }

    // Process the callback
    $payment = new Payment();
    $processed = $payment->processCallback($callbackData);

    if ($processed) {
        echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    } else {
        echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Accepted']); // Always accept to avoid retries
    }

} catch (Throwable $exception) {
    $logger->logError('M-Pesa callback processing failed', $exception);
    echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'Processing error']);
    exit(1);
}
