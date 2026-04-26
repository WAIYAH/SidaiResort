<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Complete Payment | Sidai Resort';
$pageDescription = 'Secure M-Pesa payment for your Sidai Resort booking.';
$pageImage = APP_URL . '/assets/images/hero-sunset.jpg';
$pageRobots = 'noindex, nofollow';

$bookingRef = trim((string)($_GET['ref'] ?? ''));
$booking = null;

if ($bookingRef !== '') {
    try {
        $bookingModel = new \App\Models\Booking();
        $booking = $bookingModel->getByRef($bookingRef);
    } catch (Throwable $exception) {
        log_error('Payment page failed to fetch booking.', $exception);
    }
}

$amountDue = 0.0;
if ($booking !== null) {
    $amountDue = (float)($booking['balance_due'] ?? ((float)($booking['total_amount'] ?? 0) - (float)($booking['deposit_amount'] ?? 0)));
    if ($amountDue < 0) {
        $amountDue = 0.0;
    }
}

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>
<main class="pt-28 lg:pt-32 bg-cream min-h-screen pb-20">
    <section class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-3xl border border-brown/10 bg-white p-6 shadow-lg sm:p-8">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.24em] text-earth">Payment</p>
                <h1 class="mt-2 font-display text-4xl text-brown">Complete Your Booking Payment</h1>
                <p class="mt-3 text-sm leading-7 text-brown/75">Secure mobile money payment powered by M-Pesa.</p>
            </div>

            <?php if ($booking !== null): ?>
                <div class="rounded-2xl border border-gold/25 bg-cream/45 p-5">
                    <h2 class="font-display text-2xl text-brown">Booking Summary</h2>
                    <dl class="mt-4 space-y-3 text-sm text-brown/85">
                        <div class="flex items-center justify-between gap-3">
                            <dt>Booking Reference</dt>
                            <dd class="font-semibold"><?php echo safe_html((string)$booking['booking_ref']); ?></dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt>Guest Name</dt>
                            <dd class="font-semibold"><?php echo safe_html((string)$booking['full_name']); ?></dd>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <dt>Amount Due</dt>
                            <dd class="text-base font-bold text-gold"><?php echo safe_html(format_kes($amountDue)); ?></dd>
                        </div>
                    </dl>
                </div>

                <form id="paymentForm" class="mt-6 space-y-4">
                    <?php echo csrf_token_field(); ?>
                    <input type="hidden" name="booking_ref" value="<?php echo safe_html((string)$booking['booking_ref']); ?>">
                    <input type="hidden" name="amount" value="<?php echo safe_html((string)$amountDue); ?>">

                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-brown">M-Pesa Phone Number</label>
                        <input id="phone" name="phone" type="tel" required value="<?php echo safe_html((string)($booking['phone'] ?? '')); ?>" placeholder="2547xxxxxxxx" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>

                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-night transition hover:bg-gold-light">
                        Pay via M-Pesa
                    </button>

                    <p class="text-xs leading-6 text-brown/70">After you submit, check your phone for the STK push prompt and enter your M-Pesa PIN.</p>
                </form>

                <div id="paymentStatus" class="mt-5 hidden rounded-xl border px-4 py-3 text-sm font-medium"></div>
            <?php else: ?>
                <div class="rounded-2xl border border-red-200 bg-red-50 p-5 text-center">
                    <p class="text-sm text-red-700">Booking reference was not found. Please check your link and try again.</p>
                    <a href="<?php echo WEB_ROOT; ?>/booking" class="mt-4 inline-flex rounded-full bg-gold px-6 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-night transition hover:bg-gold-light">Create New Booking</a>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<script>
(() => {
    const form = document.getElementById('paymentForm');
    const statusBox = document.getElementById('paymentStatus');

    const renderStatus = (message, type) => {
        if (!statusBox) {
            return;
        }
        statusBox.classList.remove('hidden', 'border-red-300', 'bg-red-50', 'text-red-700', 'border-green-300', 'bg-green-50', 'text-green-700', 'border-blue-300', 'bg-blue-50', 'text-blue-700');
        if (type === 'error') {
            statusBox.classList.add('border-red-300', 'bg-red-50', 'text-red-700');
        } else if (type === 'success') {
            statusBox.classList.add('border-green-300', 'bg-green-50', 'text-green-700');
        } else {
            statusBox.classList.add('border-blue-300', 'bg-blue-50', 'text-blue-700');
        }
        statusBox.textContent = message;
    };

    const pollMpesaStatus = async (checkoutRequestId, bookingRef) => {
        let attempts = 0;
        const maxAttempts = 24;
        const pollTimer = setInterval(async () => {
            attempts += 1;
            try {
                const response = await fetch(`<?php echo WEB_ROOT; ?>/api/mpesa-status.php?checkout_request_id=${encodeURIComponent(checkoutRequestId)}`);
                const result = await response.json();
                if (result.status === 'completed') {
                    clearInterval(pollTimer);
                    renderStatus('Payment confirmed. Redirecting to your receipt...', 'success');
                    window.setTimeout(() => {
                        window.location.href = `<?php echo WEB_ROOT; ?>/receipt?ref=${encodeURIComponent(bookingRef)}`;
                    }, 1400);
                    return;
                }
                if (result.status === 'failed') {
                    clearInterval(pollTimer);
                    renderStatus('Payment failed or was cancelled. Please retry.', 'error');
                    return;
                }
                if (attempts >= maxAttempts) {
                    clearInterval(pollTimer);
                    renderStatus('Still waiting for payment confirmation. You can refresh this page shortly.', 'info');
                }
            } catch (_error) {
                if (attempts >= maxAttempts) {
                    clearInterval(pollTimer);
                    renderStatus('Unable to confirm payment status right now. Please refresh shortly.', 'error');
                }
            }
        }, 5000);
    };

    if (!form) {
        return;
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        renderStatus('Initiating M-Pesa prompt...', 'info');

        const payload = new FormData(form);
        try {
            const response = await fetch('<?php echo WEB_ROOT; ?>/api/mpesa-initiate.php', {
                method: 'POST',
                body: payload,
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();

            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Unable to start M-Pesa payment.');
            }

            renderStatus('STK push sent. Complete payment on your phone.', 'info');
            pollMpesaStatus(result.checkout_request_id, payload.get('booking_ref') || '');
        } catch (error) {
            renderStatus(error && error.message ? error.message : 'Payment request failed.', 'error');
        }
    });
})();
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>
