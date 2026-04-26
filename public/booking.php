<?php declare(strict_types=1);

ob_start();

require_once dirname(__DIR__) . '/app/includes/init.php';

use App\Models\Hall;
use App\Models\Room;

$pageTitle = 'Book Your Stay or Event | Sidai Resort';
$pageDescription = 'Reserve rooms, halls, dining, pool sessions, and events at Sidai Resort in Narok County.';
$pageImage = APP_URL . '/assets/images/hero-sunset.jpg';
$pageKeywords = 'Sidai Resort booking, Narok resort booking, hall booking Kenya, room reservation';

$roomModel = new Room();
$hallModel = new Hall();

$rooms = [];
$halls = [];
$loadNotice = null;

try {
    $rooms = $roomModel->getAll();
    $halls = $hallModel->getAll();
} catch (Throwable $exception) {
    log_error('Failed loading booking form options.', $exception);
    $loadNotice = 'Some booking options are temporarily unavailable. You can still submit your request.';
}

$presetType = trim((string)($_GET['type'] ?? 'room'));
$allowedTypes = ['room', 'hall', 'pool', 'dining', 'event', 'spa', 'music_shoot', 'conference'];
if (!in_array($presetType, $allowedTypes, true)) {
    $presetType = 'room';
}

$prefilledRoomName = trim((string)($_GET['room'] ?? ''));
$serverError = trim((string)($_GET['error'] ?? ''));

include APP_PATH . '/includes/head.php';
include APP_PATH . '/includes/header.php';
?>
<main class="pt-28 lg:pt-32 pb-20 bg-cream min-h-screen">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0">
            <img src="<?php echo WEB_ROOT; ?>/assets/images/hero-sunset.jpg" alt="Sidai Resort booking hero" class="h-full w-full object-cover">
            <div class="absolute inset-0 bg-night/65"></div>
        </div>
        <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20 text-center">
            <p class="text-sm font-semibold uppercase tracking-[0.24em] text-gold">Booking Desk</p>
            <h1 class="mt-4 font-display text-4xl text-white sm:text-5xl">Plan Your Sidai Experience</h1>
            <p class="mx-auto mt-4 max-w-3xl text-sm leading-7 text-cream/90 sm:text-base">
                Fill in your details and we will confirm your booking request promptly by phone, email, or WhatsApp.
            </p>
        </div>
    </section>

    <section class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8 -mt-10 relative z-10">
        <div class="rounded-3xl border border-brown/10 bg-white p-6 shadow-xl sm:p-8">
            <?php if ($serverError !== ''): ?>
                <p class="mb-6 rounded-xl border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <?php echo safe_html($serverError); ?>
                </p>
            <?php endif; ?>

            <?php if ($loadNotice !== null): ?>
                <p class="mb-6 rounded-xl border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-800">
                    <?php echo safe_html($loadNotice); ?>
                </p>
            <?php endif; ?>

            <form id="bookingForm" method="post" action="<?php echo WEB_ROOT; ?>/api/booking-submit" class="space-y-8" novalidate>
                <?php echo csrf_token_field(); ?>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="booking_type" class="mb-2 block text-sm font-semibold text-brown">Booking Type</label>
                        <select id="booking_type" name="booking_type" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40" required>
                            <option value="room" <?php echo $presetType === 'room' ? 'selected' : ''; ?>>Room / Accommodation</option>
                            <option value="hall" <?php echo $presetType === 'hall' ? 'selected' : ''; ?>>Hall Booking</option>
                            <option value="pool" <?php echo $presetType === 'pool' ? 'selected' : ''; ?>>Pool Session</option>
                            <option value="dining" <?php echo $presetType === 'dining' ? 'selected' : ''; ?>>Dining Reservation</option>
                            <option value="event" <?php echo $presetType === 'event' ? 'selected' : ''; ?>>Event Package</option>
                            <option value="spa" <?php echo $presetType === 'spa' ? 'selected' : ''; ?>>Spa & Wellness</option>
                            <option value="music_shoot" <?php echo $presetType === 'music_shoot' ? 'selected' : ''; ?>>Music / Film Shoot</option>
                            <option value="conference" <?php echo $presetType === 'conference' ? 'selected' : ''; ?>>Conference</option>
                        </select>
                    </div>

                    <div>
                        <label for="full_name" class="mb-2 block text-sm font-semibold text-brown">Full Name</label>
                        <input id="full_name" name="full_name" type="text" autocomplete="name" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                    <div>
                        <label for="email" class="mb-2 block text-sm font-semibold text-brown">Email Address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                    <div>
                        <label for="phone" class="mb-2 block text-sm font-semibold text-brown">Phone Number</label>
                        <input id="phone" name="phone" type="tel" autocomplete="tel" placeholder="07xxxxxxxx or 2547xxxxxxxx" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                    <div>
                        <label for="num_guests" class="mb-2 block text-sm font-semibold text-brown">Number of Guests</label>
                        <input id="num_guests" name="num_guests" type="number" min="1" max="1000" value="1" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="check_in" class="mb-2 block text-sm font-semibold text-brown">Date</label>
                        <input id="check_in" name="check_in" type="date" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                    <div id="checkOutWrapper">
                        <label for="check_out" class="mb-2 block text-sm font-semibold text-brown">Check-out Date</label>
                        <input id="check_out" name="check_out" type="date" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                </div>

                <div id="roomFields" class="space-y-4">
                    <label for="room_id" class="mb-2 block text-sm font-semibold text-brown">Preferred Room</label>
                    <select id="room_id" name="room_id" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        <option value="">Select room (optional)</option>
                        <?php foreach ($rooms as $room): ?>
                            <?php
                                $roomName = (string)($room['name'] ?? 'Room');
                                $roomId = (int)($room['id'] ?? 0);
                                $roomPrice = (float)($room['price_per_night'] ?? 0);
                                $isSelectedRoom = $prefilledRoomName !== '' && strcasecmp($prefilledRoomName, $roomName) === 0;
                            ?>
                            <option value="<?php echo $roomId; ?>" <?php echo $isSelectedRoom ? 'selected' : ''; ?>>
                                <?php echo safe_html($roomName); ?> - <?php echo safe_html(format_kes($roomPrice)); ?>/night
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div id="hallFields" class="hidden space-y-5">
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="hall_id" class="mb-2 block text-sm font-semibold text-brown">Preferred Hall</label>
                            <select id="hall_id" name="hall_id" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                                <option value="">Select hall (optional)</option>
                                <?php foreach ($halls as $hall): ?>
                                    <?php
                                        $hallName = (string)($hall['name'] ?? 'Hall');
                                        $hallId = (int)($hall['id'] ?? 0);
                                        $hallCapacity = (int)($hall['capacity'] ?? 0);
                                    ?>
                                    <option value="<?php echo $hallId; ?>">
                                        <?php echo safe_html($hallName); ?><?php echo $hallCapacity > 0 ? ' (' . $hallCapacity . ' pax)' : ''; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div>
                            <label for="event_date" class="mb-2 block text-sm font-semibold text-brown">Event Date</label>
                            <input id="event_date" name="event_date" type="date" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        </div>
                    </div>
                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="event_type" class="mb-2 block text-sm font-semibold text-brown">Event Type</label>
                            <input id="event_type" name="event_type" type="text" placeholder="Wedding, workshop, celebration..." class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        </div>
                        <div>
                            <label for="event_setup" class="mb-2 block text-sm font-semibold text-brown">Setup Preference</label>
                            <select id="event_setup" name="event_setup" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                                <option value="">Select setup</option>
                                <option value="theatre">Theatre</option>
                                <option value="classroom">Classroom</option>
                                <option value="banquet">Banquet</option>
                                <option value="cocktail">Cocktail</option>
                                <option value="u_shape">U-Shape</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="hall_package" class="mb-2 block text-sm font-semibold text-brown">Hall Package</label>
                        <select id="hall_package" name="hall_package" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                            <option value="full_day">Full Day</option>
                            <option value="half_day">Half Day</option>
                            <option value="evening">Evening</option>
                        </select>
                    </div>
                </div>

                <div id="musicFields" class="hidden">
                    <label for="music_duration" class="mb-2 block text-sm font-semibold text-brown">Shoot Duration</label>
                    <select id="music_duration" name="music_duration" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                        <option value="half_day">Half Day</option>
                        <option value="full_day">Full Day</option>
                        <option value="overnight">Overnight</option>
                    </select>
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label for="payment_method" class="mb-2 block text-sm font-semibold text-brown">Payment Method</label>
                        <select id="payment_method" name="payment_method" required class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                            <option value="mpesa">M-Pesa</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                        </select>
                    </div>
                    <div id="mpesaPhoneWrapper">
                        <label for="mpesa_phone" class="mb-2 block text-sm font-semibold text-brown">M-Pesa Number</label>
                        <input id="mpesa_phone" name="mpesa_phone" type="tel" placeholder="2547xxxxxxxx" class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40">
                    </div>
                </div>

                <div>
                    <label for="special_requests" class="mb-2 block text-sm font-semibold text-brown">Special Requests</label>
                    <textarea id="special_requests" name="special_requests" rows="4" placeholder="Dietary needs, pickup requests, room setup, event notes..." class="w-full rounded-xl border border-brown/20 bg-cream px-4 py-3 text-sm text-brown focus:border-gold focus:outline-none focus:ring-2 focus:ring-gold/40"></textarea>
                </div>

                <label class="flex items-start gap-3 rounded-xl border border-brown/10 bg-cream/50 px-4 py-3 text-sm text-brown">
                    <input id="terms_accepted" name="terms_accepted" type="checkbox" value="1" class="mt-1 h-4 w-4 rounded border-brown/30 text-gold focus:ring-gold" required>
                    <span>I agree to the booking terms and understand that confirmation is subject to availability.</span>
                </label>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                    <button type="submit" class="inline-flex items-center justify-center rounded-full bg-gold px-8 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-night transition hover:bg-gold-light">
                        Confirm Booking Request
                    </button>
                    <a href="<?php echo WEB_ROOT; ?>/contact" class="inline-flex items-center justify-center rounded-full border border-brown/30 px-8 py-3 text-sm font-semibold uppercase tracking-[0.16em] text-brown transition hover:border-gold hover:text-gold-dark">
                        Need Help First?
                    </a>
                </div>
            </form>
        </div>
    </section>
</main>

<script>
(() => {
    const bookingType = document.getElementById('booking_type');
    const paymentMethod = document.getElementById('payment_method');
    const roomFields = document.getElementById('roomFields');
    const hallFields = document.getElementById('hallFields');
    const musicFields = document.getElementById('musicFields');
    const checkOutWrapper = document.getElementById('checkOutWrapper');
    const checkOutInput = document.getElementById('check_out');
    const eventDateInput = document.getElementById('event_date');
    const mpesaWrapper = document.getElementById('mpesaPhoneWrapper');
    const mpesaPhone = document.getElementById('mpesa_phone');
    const checkInInput = document.getElementById('check_in');
    const today = new Date().toISOString().slice(0, 10);

    if (checkInInput && !checkInInput.value) {
        checkInInput.value = today;
    }

    const updateByBookingType = () => {
        const type = bookingType ? bookingType.value : 'room';
        const isRoom = type === 'room';
        const isHallLike = type === 'hall' || type === 'event' || type === 'conference';
        const isMusic = type === 'music_shoot';

        if (roomFields) {
            roomFields.classList.toggle('hidden', !isRoom);
        }
        if (hallFields) {
            hallFields.classList.toggle('hidden', !isHallLike);
        }
        if (musicFields) {
            musicFields.classList.toggle('hidden', !isMusic);
        }
        if (checkOutWrapper) {
            checkOutWrapper.classList.toggle('hidden', !isRoom);
        }
        if (checkOutInput) {
            checkOutInput.required = isRoom;
            if (!isRoom) {
                checkOutInput.value = '';
            }
        }
        if (eventDateInput) {
            eventDateInput.required = isHallLike;
        }
    };

    const updateByPaymentMethod = () => {
        const method = paymentMethod ? paymentMethod.value : 'mpesa';
        const isMpesa = method === 'mpesa';
        if (mpesaWrapper) {
            mpesaWrapper.classList.toggle('hidden', !isMpesa);
        }
        if (mpesaPhone) {
            mpesaPhone.required = isMpesa;
            if (!isMpesa) {
                mpesaPhone.value = '';
            }
        }
    };

    if (bookingType) {
        bookingType.addEventListener('change', updateByBookingType);
    }
    if (paymentMethod) {
        paymentMethod.addEventListener('change', updateByPaymentMethod);
    }

    updateByBookingType();
    updateByPaymentMethod();
})();
</script>

<?php include APP_PATH . '/includes/footer.php'; ?>
