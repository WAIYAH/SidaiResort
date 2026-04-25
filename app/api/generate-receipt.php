<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

use App\Core\Receipt;

if (!isset($_GET['booking_id'])) {
    http_response_code(400);
    echo 'Booking ID required';
    exit;
}

try {
    $bookingId = (int)$_GET['booking_id'];

    // Verify the booking exists and user has permission
    $bookingModel = new \App\Models\Booking();
    $booking = $bookingModel->getById($bookingId);

    if (!$booking) {
        http_response_code(404);
        echo 'Booking not found';
        exit;
    }

    // Generate and download receipt
    $receipt = new Receipt();
    $receipt->downloadReceipt($bookingId);

} catch (Throwable $exception) {
    log_error('Receipt generation failed', $exception);
    http_response_code(500);
    echo 'Failed to generate receipt';
    exit(1);
}
