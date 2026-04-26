<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Core\Receipt;

$pageTitle = 'Booking Receipt';
$pageDescription = 'Your booking receipt';

$bookingRef = $_GET['ref'] ?? null;
$booking = null;

if ($bookingRef) {
    $bookingModel = new \App\Models\Booking();
    $booking = $bookingModel->getByRef($bookingRef);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include APP_PATH . '/includes/head.php'; ?>
</head>
<body>
    <?php include APP_PATH . '/includes/header.php'; ?>

    <main class="min-h-screen bg-cream pt-20 pb-10">
        <div class="container mx-auto px-4 max-w-3xl">
            <?php if ($booking): ?>
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <div class="text-center mb-8 pb-8 border-b-2 border-gold flex flex-col items-center">
                        <img src="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo.png" alt="Sidai Resort" class="h-16 w-auto mb-4">
                        <p class="text-lg text-gold font-semibold tracking-wider">Booking Receipt</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 mb-2">GUEST INFORMATION</h3>
                            <p class="font-semibold text-forest-green"><?php echo htmlspecialchars($booking['full_name']); ?></p>
                            <p class="text-gray-600"><?php echo htmlspecialchars($booking['email']); ?></p>
                            <p class="text-gray-600"><?php echo htmlspecialchars($booking['phone']); ?></p>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-600 mb-2">BOOKING DETAILS</h3>
                            <p class="text-gray-600"><span class="font-semibold">Reference:</span> <?php echo htmlspecialchars($booking['booking_ref']); ?></p>
                            <p class="text-gray-600"><span class="font-semibold">Type:</span> <?php echo ucfirst(str_replace('_', ' ', $booking['booking_type'])); ?></p>
                            <p class="text-gray-600"><span class="font-semibold">Status:</span> <span class="text-gold font-semibold"><?php echo ucfirst($booking['status']); ?></span></p>
                        </div>
                    </div>

                    <div class="mb-8 pb-8 border-b-2 border-gold-light">
                        <h3 class="text-lg font-semibold text-forest-green mb-4">BOOKING DETAILS</h3>

                        <div class="grid grid-cols-2 gap-4">
                            <?php if ($booking['check_in']): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Check-in</p>
                                    <p class="font-semibold"><?php echo format_eat_date($booking['check_in']); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if ($booking['check_out']): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Check-out</p>
                                    <p class="font-semibold"><?php echo format_eat_date($booking['check_out']); ?></p>
                                </div>
                            <?php endif; ?>

                            <div>
                                <p class="text-sm text-gray-600">Number of Guests</p>
                                <p class="font-semibold"><?php echo $booking['num_guests']; ?></p>
                            </div>

                            <?php if ($booking['num_nights']): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Nights</p>
                                    <p class="font-semibold"><?php echo $booking['num_nights']; ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-forest-green mb-4">AMOUNT DETAILS</h3>

                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span>KES <?php echo format_kes($booking['subtotal']); ?></span>
                            </div>

                            <?php if ($booking['discount_amount'] > 0): ?>
                                <div class="flex justify-between text-green-600">
                                    <span>Discount:</span>
                                    <span>-KES <?php echo format_kes($booking['discount_amount']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($booking['tax_amount'] > 0): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Tax (16%):</span>
                                    <span>KES <?php echo format_kes($booking['tax_amount']); ?></span>
                                </div>
                            <?php endif; ?>

                            <div class="flex justify-between text-lg font-bold pt-2 border-t-2 border-gold">
                                <span>Total Amount:</span>
                                <span class="text-gold">KES <?php echo format_kes($booking['total_amount']); ?></span>
                            </div>

                            <?php if ($booking['deposit_amount'] > 0): ?>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Deposit Paid:</span>
                                    <span>KES <?php echo format_kes($booking['deposit_amount']); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if ($booking['balance_due'] > 0): ?>
                                <div class="flex justify-between font-semibold pt-2 border-t-2 border-gold-light">
                                    <span>Balance Due:</span>
                                    <span class="text-forest-green">KES <?php echo format_kes($booking['balance_due']); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <button onclick="window.print()" class="flex-1 bg-forest-green hover:bg-forest-green-dark text-white font-semibold py-3 rounded-lg transition">
                            🖨️ Print Receipt
                        </button>
                        <a href="<?php echo WEB_ROOT; ?>" class="flex-1 bg-gold hover:bg-gold-dark text-white font-semibold py-3 rounded-lg text-center transition">
                            Back to Home
                        </a>
                    </div>
                </div>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="text-sm text-gray-700">
                        <strong>Confirmation:</strong> A detailed receipt has been sent to your email address. Please check your email for further instructions.
                    </p>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <p class="text-gray-600 mb-4">Receipt not found. Please check your booking reference.</p>
                    <a href="<?php echo WEB_ROOT; ?>" class="inline-block bg-gold hover:bg-gold-dark text-white font-semibold py-3 px-8 rounded-lg transition">
                        Go to Home
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
