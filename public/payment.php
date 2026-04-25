<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Payment';
$pageDescription = 'Complete your payment';

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
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-playfair font-bold text-forest-green mb-4">Complete Payment</h1>
                <p class="text-lg text-gray-600">Secure payment through M-Pesa</p>
            </div>

            <?php if ($booking): ?>
                <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                    <h2 class="text-2xl font-bold text-forest-green mb-6">Booking Summary</h2>

                    <div class="space-y-3 mb-6 pb-6 border-b-2 border-gold-light">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Booking Reference:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($booking['booking_ref']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Guest Name:</span>
                            <span class="font-semibold"><?php echo htmlspecialchars($booking['full_name']); ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount Due:</span>
                            <span class="font-bold text-lg">KES <?php echo format_kes($booking['balance_due']); ?></span>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold text-forest-green mb-4">Payment Method</h3>

                    <form id="paymentForm" class="space-y-4">
                        <div>
                            <label for="phone" class="block text-sm font-semibold text-forest-green mb-2">Phone Number</label>
                            <input type="tel" id="phone" name="phone" placeholder="+254712345678" required
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-gold"
                                   value="<?php echo htmlspecialchars($booking['phone']); ?>">
                        </div>

                        <button type="submit" class="w-full bg-gold hover:bg-gold-dark text-white font-semibold py-3 rounded-lg transition">
                            Pay via M-Pesa
                        </button>

                        <p class="text-sm text-gray-600 text-center mt-4">
                            You will receive an M-Pesa STK prompt on your phone. Enter your M-Pesa PIN to complete the payment.
                        </p>
                    </form>

                    <div id="paymentStatus" class="hidden mt-6 p-4 rounded-lg">
                        <p id="statusMessage"></p>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <p class="text-gray-600 mb-4">No booking found. Please check your booking reference.</p>
                    <a href="<?php echo WEB_ROOT; ?>/booking.php" class="inline-block bg-gold hover:bg-gold-dark text-white font-semibold py-3 px-8 rounded-lg transition">
                        Make a New Booking
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <script>
        document.getElementById('paymentForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const phone = document.getElementById('phone').value;
            const bookingRef = '<?php echo htmlspecialchars($booking['booking_ref']); ?>';

            try {
                const response = await fetch('<?php echo WEB_ROOT; ?>/api/mpesa-initiate.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        phone: phone,
                        booking_ref: bookingRef,
                        amount: <?php echo $booking['balance_due']; ?>
                    })
                });

                const data = await response.json();

                if (data.success) {
                    showStatus('Check your phone for M-Pesa STK prompt...', 'info');
                    startPolling(data.checkout_request_id);
                } else {
                    showStatus('Failed to initiate payment: ' + data.message, 'error');
                }
            } catch (error) {
                showStatus('Error: ' + error.message, 'error');
            }
        });

        function showStatus(message, type) {
            const statusDiv = document.getElementById('paymentStatus');
            const msgDiv = document.getElementById('statusMessage');
            
            statusDiv.classList.remove('hidden', 'bg-green-100', 'bg-red-100', 'text-green-800', 'text-red-800');
            msgDiv.textContent = message;

            if (type === 'info') {
                statusDiv.classList.add('bg-blue-100', 'text-blue-800');
            } else if (type === 'error') {
                statusDiv.classList.add('bg-red-100', 'text-red-800');
            } else if (type === 'success') {
                statusDiv.classList.add('bg-green-100', 'text-green-800');
            }
        }

        function startPolling(checkoutRequestId) {
            const maxAttempts = 24; // 2 minutes with 5-second intervals
            let attempts = 0;

            const pollInterval = setInterval(async () => {
                attempts++;

                try {
                    const response = await fetch(`<?php echo WEB_ROOT; ?>/api/mpesa-status.php?checkout_request_id=${checkoutRequestId}`);
                    const data = await response.json();

                    if (data.status === 'completed') {
                        clearInterval(pollInterval);
                        showStatus('Payment successful! Redirecting...', 'success');
                        setTimeout(() => {
                            window.location.href = `<?php echo WEB_ROOT; ?>/receipt.php?ref=<?php echo htmlspecialchars($booking['booking_ref']); ?>`;
                        }, 2000);
                    } else if (data.status === 'failed') {
                        clearInterval(pollInterval);
                        showStatus('Payment failed. Please try again.', 'error');
                    } else if (attempts >= maxAttempts) {
                        clearInterval(pollInterval);
                        showStatus('Payment timeout. Please check your account and try again.', 'error');
                    }
                } catch (error) {
                    console.error('Polling error:', error);
                }
            }, 5000);
        }
    </script>

    <?php include APP_PATH . '/includes/footer.php'; ?>
</body>
</html>
