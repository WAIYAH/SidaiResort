<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

$pageTitle = 'Booking Receipt | Sidai Resort';
$pageDescription = 'View your Sidai Resort booking receipt and payment summary.';
$pageImage = APP_URL . '/assets/images/sidai-logo.png';
$pageRobots = 'noindex, nofollow';

$bookingRef = trim((string)($_GET['ref'] ?? ''));
$booking = null;

if ($bookingRef !== '') {
    try {
        $bookingModel = new \App\Models\Booking();
        $booking = $bookingModel->getByRef($bookingRef);
    } catch (Throwable $exception) {
        log_error('Receipt page failed to load booking.', $exception);
    }
}

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>
<main class="pt-28 lg:pt-32 bg-cream min-h-screen pb-20">
    <section class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
        <?php if ($booking !== null): ?>
            <?php
                $subtotal = (float)($booking['subtotal'] ?? 0);
                $discount = (float)($booking['discount_amount'] ?? 0);
                $tax = (float)($booking['tax_amount'] ?? 0);
                $total = (float)($booking['total_amount'] ?? 0);
                $deposit = (float)($booking['deposit_amount'] ?? 0);
                $balance = (float)($booking['balance_due'] ?? max(0, $total - $deposit));
            ?>
            <article class="rounded-3xl border border-brown/10 bg-white p-6 shadow-lg sm:p-8">
                <div class="mb-8 border-b border-gold/30 pb-6 text-center">
                    <img src="<?php echo WEB_ROOT; ?>/assets/images/sidai-logo.png" alt="Sidai Resort logo" class="mx-auto h-16 w-auto">
                    <p class="mt-3 text-xs font-semibold uppercase tracking-[0.24em] text-earth">Official Receipt</p>
                    <h1 class="mt-2 font-display text-4xl text-brown">Booking Receipt</h1>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <div class="rounded-2xl border border-brown/10 bg-cream/45 p-5">
                        <h2 class="font-display text-2xl text-brown">Guest Information</h2>
                        <dl class="mt-4 space-y-2 text-sm text-brown/85">
                            <div>
                                <dt class="font-semibold">Name</dt>
                                <dd><?php echo safe_html((string)$booking['full_name']); ?></dd>
                            </div>
                            <div>
                                <dt class="font-semibold">Email</dt>
                                <dd><?php echo safe_html((string)$booking['email']); ?></dd>
                            </div>
                            <div>
                                <dt class="font-semibold">Phone</dt>
                                <dd><?php echo safe_html((string)$booking['phone']); ?></dd>
                            </div>
                        </dl>
                    </div>

                    <div class="rounded-2xl border border-brown/10 bg-cream/45 p-5">
                        <h2 class="font-display text-2xl text-brown">Booking Details</h2>
                        <dl class="mt-4 space-y-2 text-sm text-brown/85">
                            <div>
                                <dt class="font-semibold">Reference</dt>
                                <dd><?php echo safe_html((string)$booking['booking_ref']); ?></dd>
                            </div>
                            <div>
                                <dt class="font-semibold">Type</dt>
                                <dd><?php echo safe_html(ucfirst(str_replace('_', ' ', (string)$booking['booking_type']))); ?></dd>
                            </div>
                            <div>
                                <dt class="font-semibold">Status</dt>
                                <dd><?php echo safe_html(ucfirst(str_replace('_', ' ', (string)$booking['status']))); ?></dd>
                            </div>
                            <?php if (!empty($booking['check_in'])): ?>
                                <div>
                                    <dt class="font-semibold">Check-in</dt>
                                    <dd><?php echo safe_html(format_eat_date((string)$booking['check_in'])); ?></dd>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($booking['check_out'])): ?>
                                <div>
                                    <dt class="font-semibold">Check-out</dt>
                                    <dd><?php echo safe_html(format_eat_date((string)$booking['check_out'])); ?></dd>
                                </div>
                            <?php endif; ?>
                            <div>
                                <dt class="font-semibold">Guests</dt>
                                <dd><?php echo (int)($booking['num_guests'] ?? 1); ?></dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="mt-6 rounded-2xl border border-gold/30 bg-night p-5 text-cream">
                    <h2 class="font-display text-2xl text-gold">Amount Summary</h2>
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex items-center justify-between gap-3">
                            <dt>Subtotal</dt>
                            <dd><?php echo safe_html(format_kes($subtotal)); ?></dd>
                        </div>
                        <?php if ($discount > 0): ?>
                            <div class="flex items-center justify-between gap-3 text-green-300">
                                <dt>Discount</dt>
                                <dd>-<?php echo safe_html(format_kes($discount)); ?></dd>
                            </div>
                        <?php endif; ?>
                        <?php if ($tax > 0): ?>
                            <div class="flex items-center justify-between gap-3">
                                <dt>Tax</dt>
                                <dd><?php echo safe_html(format_kes($tax)); ?></dd>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between gap-3 border-t border-gold/25 pt-2 text-base font-semibold text-white">
                            <dt>Total</dt>
                            <dd><?php echo safe_html(format_kes($total)); ?></dd>
                        </div>
                        <?php if ($deposit > 0): ?>
                            <div class="flex items-center justify-between gap-3">
                                <dt>Deposit Paid</dt>
                                <dd><?php echo safe_html(format_kes($deposit)); ?></dd>
                            </div>
                        <?php endif; ?>
                        <div class="flex items-center justify-between gap-3 border-t border-gold/25 pt-2 text-base font-semibold text-gold">
                            <dt>Balance Due</dt>
                            <dd><?php echo safe_html(format_kes($balance)); ?></dd>
                        </div>
                    </dl>
                </div>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <button type="button" onclick="window.print()" class="inline-flex w-full items-center justify-center rounded-full border border-brown/30 px-6 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-brown transition hover:border-gold hover:text-gold-dark sm:w-auto">
                        Print
                    </button>
                    <a href="<?php echo WEB_ROOT; ?>/payment?ref=<?php echo rawurlencode((string)$booking['booking_ref']); ?>" class="inline-flex w-full items-center justify-center rounded-full bg-gold px-6 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-night transition hover:bg-gold-light sm:w-auto">
                        Pay Balance
                    </a>
                    <a href="<?php echo WEB_ROOT; ?>/" class="inline-flex w-full items-center justify-center rounded-full bg-forest px-6 py-3 text-sm font-semibold uppercase tracking-[0.14em] text-cream transition hover:bg-forest-light sm:w-auto">
                        Back Home
                    </a>
                </div>
            </article>
        <?php else: ?>
            <div class="rounded-3xl border border-red-200 bg-red-50 p-8 text-center">
                <h1 class="font-display text-4xl text-brown">Receipt Not Found</h1>
                <p class="mt-3 text-sm text-red-700">We could not find that booking reference. Please verify and try again.</p>
                <a href="<?php echo WEB_ROOT; ?>/booking" class="mt-5 inline-flex rounded-full bg-gold px-6 py-3 text-xs font-semibold uppercase tracking-[0.14em] text-night transition hover:bg-gold-light">
                    Create New Booking
                </a>
            </div>
        <?php endif; ?>
    </section>
</main>

<?php include APP_PATH . '/includes/footer.php'; ?>
