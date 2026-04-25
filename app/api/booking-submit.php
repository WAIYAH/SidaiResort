<?php declare(strict_types=1);

use App\Core\Database;
use App\Core\CSRF;
use App\Core\Validator;

require_once __DIR__ . '/mpesa-initiate.php';

if (!function_exists('sidai_is_json_request')) {
    function sidai_is_json_request(): bool
    {
        $requestedWith = strtolower((string)($_SERVER['HTTP_X_REQUESTED_WITH'] ?? ''));
        $accept = strtolower((string)($_SERVER['HTTP_ACCEPT'] ?? ''));

        return $requestedWith === 'xmlhttprequest' || str_contains($accept, 'application/json');
    }
}

if (!function_exists('sidai_enforce_booking_rate_limit')) {
    function sidai_enforce_booking_rate_limit(): bool
    {
        $key = 'booking_submit_attempts';
        $windowSeconds = 3600;
        $maxAttempts = RATE_LIMIT_BOOKINGS_PER_HOUR;

        $_SESSION[$key] = array_values(array_filter(
            $_SESSION[$key] ?? [],
            static fn (int $timestamp): bool => (time() - $timestamp) < $windowSeconds
        ));

        if (count($_SESSION[$key]) >= $maxAttempts) {
            return false;
        }

        $_SESSION[$key][] = time();
        return true;
    }
}

if (!function_exists('sidai_normalize_kenyan_phone')) {
    function sidai_normalize_kenyan_phone(string $phone): ?string
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

if (!function_exists('sidai_is_valid_date')) {
    function sidai_is_valid_date(string $date): bool
    {
        $object = DateTimeImmutable::createFromFormat('Y-m-d', $date);
        return $object !== false && $object->format('Y-m-d') === $date;
    }
}

if (!function_exists('sidai_generate_booking_ref')) {
    function sidai_generate_booking_ref(Database $database): string
    {
        do {
            $candidate = sprintf(
                'SID-%s-%05d',
                date('Ym'),
                random_int(0, 99999)
            );

            $exists = $database->queryOne(
                'SELECT id FROM bookings WHERE booking_ref = :booking_ref LIMIT 1',
                [':booking_ref' => $candidate]
            );
        } while ($exists !== null);

        return $candidate;
    }
}

if (!function_exists('sidai_generate_payment_ref_local')) {
    function sidai_generate_payment_ref_local(Database $database): string
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

if (!function_exists('sidai_get_setting_amount')) {
    function sidai_get_setting_amount(Database $database, string $key, float $default): float
    {
        $row = $database->queryOne(
            'SELECT setting_value
             FROM site_settings
             WHERE setting_key = :setting_key
             LIMIT 1',
            [':setting_key' => $key]
        );

        if ($row === null) {
            return $default;
        }

        $value = trim((string)($row['setting_value'] ?? ''));
        if ($value === '' || !is_numeric($value)) {
            return $default;
        }

        return (float)$value;
    }
}

if (!function_exists('sidai_calculate_booking_totals')) {
    function sidai_calculate_booking_totals(Database $database, array $input): array
    {
        $bookingType = (string)$input['booking_type'];
        $numGuests = max(1, (int)$input['num_guests']);
        $checkIn = (string)$input['check_in'];
        $checkOut = (string)$input['check_out'];
        $roomId = isset($input['room_id']) ? (int)$input['room_id'] : null;
        $hallId = isset($input['hall_id']) ? (int)$input['hall_id'] : null;
        $hallPackage = (string)($input['hall_package'] ?? 'full_day');
        $musicDuration = (string)($input['music_duration'] ?? 'full_day');

        $subtotal = 0.0;
        $roomPrice = null;
        $hallPrice = null;

        if ($bookingType === 'room') {
            $nights = max(1, (int)((new DateTimeImmutable($checkOut))->diff(new DateTimeImmutable($checkIn))->days));

            if ($roomId !== null && $roomId > 0) {
                $room = $database->queryOne(
                    'SELECT id, price_per_night
                     FROM rooms
                     WHERE id = :room_id
                     LIMIT 1',
                    [':room_id' => $roomId]
                );

                if ($room !== null) {
                    $roomPrice = (float)$room['price_per_night'];
                }
            }

            if ($roomPrice === null) {
                $roomType = (string)($input['room_type'] ?? 'standard');
                $fallback = $database->queryOne(
                    'SELECT MIN(price_per_night) AS min_price
                     FROM rooms
                     WHERE type = :room_type AND is_available = 1',
                    [':room_type' => $roomType]
                );
                $roomPrice = (float)($fallback['min_price'] ?? 12000);
                if ($roomPrice <= 0) {
                    $roomPrice = 12000.0;
                }
            }

            $subtotal = $roomPrice * $nights;
        } elseif ($bookingType === 'hall' || $bookingType === 'event') {
            if ($hallId !== null && $hallId > 0) {
                $hall = $database->queryOne(
                    'SELECT id, price_full_day, price_half_day, price_evening
                     FROM halls
                     WHERE id = :hall_id
                     LIMIT 1',
                    [':hall_id' => $hallId]
                );

                if ($hall !== null) {
                    $hallPrice = match ($hallPackage) {
                        'half_day' => (float)($hall['price_half_day'] ?? 0),
                        'evening' => (float)($hall['price_evening'] ?? 0),
                        default => (float)($hall['price_full_day'] ?? 0),
                    };
                }
            }

            if ($hallPrice === null || $hallPrice <= 0) {
                $hallPrice = match ($hallPackage) {
                    'half_day' => sidai_get_setting_amount($database, 'hall_default_half_day_rate', 85000),
                    'evening' => sidai_get_setting_amount($database, 'hall_default_evening_rate', 75000),
                    default => sidai_get_setting_amount($database, 'hall_default_full_day_rate', 140000),
                };
            }

            $subtotal = $hallPrice;
        } elseif ($bookingType === 'pool') {
            $poolRate = sidai_get_setting_amount($database, 'pool_day_pass_adult', 2500);
            $subtotal = $poolRate * $numGuests;
        } elseif ($bookingType === 'dining') {
            $diningRate = sidai_get_setting_amount($database, 'dining_reservation_per_guest', 3000);
            $subtotal = $diningRate * $numGuests;
        } elseif ($bookingType === 'spa') {
            $spaRate = sidai_get_setting_amount($database, 'spa_base_price', 6500);
            $subtotal = $spaRate * $numGuests;
        } elseif ($bookingType === 'music_shoot') {
            $subtotal = match ($musicDuration) {
                'half_day' => sidai_get_setting_amount($database, 'music_shoot_half_day', 85000),
                'overnight' => sidai_get_setting_amount($database, 'music_shoot_overnight', 220000),
                default => sidai_get_setting_amount($database, 'music_shoot_full_day', 150000),
            };
        } elseif ($bookingType === 'conference') {
            $conferenceRate = sidai_get_setting_amount($database, 'conference_per_delegate', 5500);
            $subtotal = $conferenceRate * $numGuests;
        } else {
            $subtotal = sidai_get_setting_amount($database, 'booking_default_amount', 10000);
        }

        $taxRate = sidai_get_setting_amount($database, 'booking_tax_rate', 0);
        if ($taxRate > 1) {
            $taxRate = $taxRate / 100;
        }
        $taxRate = max(0, $taxRate);

        $taxAmount = round($subtotal * $taxRate, 2);
        $totalAmount = round($subtotal + $taxAmount, 2);

        return [
            'subtotal' => round($subtotal, 2),
            'tax_amount' => $taxAmount,
            'total_amount' => $totalAmount,
            'room_id' => $roomId !== null && $roomId > 0 ? $roomId : null,
            'hall_id' => $hallId !== null && $hallId > 0 ? $hallId : null,
        ];
    }
}

if (!function_exists('sidai_handle_booking_submit_request')) {
    function sidai_handle_booking_submit_request(): never
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            json_response(['success' => false, 'message' => 'Method not allowed.'], 405);
        }

        $expectsJson = sidai_is_json_request();
        $csrfToken = $_POST[CSRF_TOKEN_NAME] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        if (!CSRF::verify(is_string($csrfToken) ? $csrfToken : null)) {
            $message = 'Security validation failed. Please refresh and try again.';
            if ($expectsJson) {
                json_response(['success' => false, 'message' => $message], 419);
            }

            header('Location: /booking?error=' . urlencode($message));
            exit;
        }

        if (!sidai_enforce_booking_rate_limit()) {
            $message = 'Too many booking attempts. Please try again in one hour.';
            if ($expectsJson) {
                json_response(['success' => false, 'message' => $message], 429);
            }

            header('Location: /booking?error=' . urlencode($message));
            exit;
        }

        $input = [
            'booking_type' => sanitize_input($_POST['booking_type'] ?? null),
            'full_name' => sanitize_input($_POST['full_name'] ?? null),
            'email' => strtolower(sanitize_input($_POST['email'] ?? null)),
            'phone' => sanitize_input($_POST['phone'] ?? null),
            'id_number' => sanitize_input($_POST['id_number'] ?? null),
            'check_in' => sanitize_input($_POST['check_in'] ?? $_POST['date'] ?? null),
            'check_out' => sanitize_input($_POST['check_out'] ?? null),
            'event_date' => sanitize_input($_POST['event_date'] ?? null),
            'event_type' => sanitize_input($_POST['event_type'] ?? null),
            'event_setup' => sanitize_input($_POST['event_setup'] ?? null),
            'hall_package' => sanitize_input($_POST['hall_package'] ?? null),
            'music_duration' => sanitize_input($_POST['music_duration'] ?? null),
            'room_type' => sanitize_input($_POST['room_type'] ?? null),
            'room_id' => sanitize_input($_POST['room_id'] ?? null),
            'hall_id' => sanitize_input($_POST['hall_id'] ?? null),
            'num_guests' => sanitize_input($_POST['num_guests'] ?? '1'),
            'special_requests' => sanitize_input($_POST['special_requests'] ?? null),
            'payment_method' => sanitize_input($_POST['payment_method'] ?? null),
            'mpesa_phone' => sanitize_input($_POST['mpesa_phone'] ?? null),
            'terms_accepted' => sanitize_input($_POST['terms_accepted'] ?? null),
            'notes' => sanitize_input($_POST['notes'] ?? null),
        ];

        $validator = new Validator();
        $baseValidation = $validator->validate(
            $input,
            [
                'booking_type' => ['required'],
                'full_name' => ['required', 'min:3', 'max:150'],
                'email' => ['required', 'email', 'max:200'],
                'phone' => ['required', 'phone'],
                'check_in' => ['required', 'date'],
                'num_guests' => ['required', 'numeric'],
                'payment_method' => ['required'],
            ]
        );

        $errors = [];

        if ($baseValidation['valid'] !== true) {
            foreach ($baseValidation['errors'] as $field => $messages) {
                $errors[$field] = (string)($messages[0] ?? 'Invalid value.');
            }
        }

        $allowedBookingTypes = ['room', 'pool', 'hall', 'dining', 'event', 'spa', 'music_shoot', 'conference'];
        if (!in_array($input['booking_type'], $allowedBookingTypes, true)) {
            $errors['booking_type'] = 'Please select a valid booking type.';
        }

        $allowedPaymentMethods = ['mpesa', 'cash', 'bank'];
        if (!in_array($input['payment_method'], $allowedPaymentMethods, true)) {
            $errors['payment_method'] = 'Please select a valid payment method.';
        }

        $normalizedPhone = sidai_normalize_kenyan_phone($input['phone']);
        if ($normalizedPhone === null) {
            $errors['phone'] = 'Please provide a valid Kenyan phone number.';
        }

        $numGuests = max(1, (int)$input['num_guests']);
        if ($numGuests > 1000) {
            $errors['num_guests'] = 'Guest count is too high for a single booking.';
        }

        if (!sidai_is_valid_date($input['check_in'])) {
            $errors['check_in'] = 'Check-in date must be in YYYY-MM-DD format.';
        } else {
            $today = new DateTimeImmutable('today');
            $checkInDate = new DateTimeImmutable($input['check_in']);
            if ($checkInDate < $today) {
                $errors['check_in'] = 'Bookings for past dates are not allowed.';
            }
        }

        if ($input['booking_type'] === 'room') {
            if ($input['check_out'] === '' || !sidai_is_valid_date($input['check_out'])) {
                $errors['check_out'] = 'Check-out date is required for room stays.';
            } elseif (sidai_is_valid_date($input['check_in'])) {
                $checkInDate = new DateTimeImmutable($input['check_in']);
                $checkOutDate = new DateTimeImmutable($input['check_out']);
                if ($checkOutDate <= $checkInDate) {
                    $errors['check_out'] = 'Check-out date must be after check-in date.';
                }
            }
        }

        if (in_array($input['booking_type'], ['hall', 'event'], true) && $input['event_date'] !== '' && !sidai_is_valid_date($input['event_date'])) {
            $errors['event_date'] = 'Event date must be in YYYY-MM-DD format.';
        }

        if ($input['payment_method'] === 'mpesa') {
            $normalizedMpesa = sidai_normalize_mpesa_phone($input['mpesa_phone']);
            if ($normalizedMpesa === null) {
                $errors['mpesa_phone'] = 'Please provide a valid M-Pesa phone number.';
            }
        }

        if ($input['terms_accepted'] !== '1') {
            $errors['terms_accepted'] = 'You must accept the terms before confirming.';
        }

        if ($errors !== []) {
            if ($expectsJson) {
                json_response([
                    'success' => false,
                    'message' => 'Please correct the highlighted fields.',
                    'errors' => $errors,
                ], 422);
            }

            header('Location: /booking?error=' . urlencode('Please correct the highlighted fields.'));
            exit;
        }

        $bookingRef = '';

        try {
            $database = Database::getInstance();
            $database->beginTransaction();

            $existingGuest = $database->queryOne(
                'SELECT id
                 FROM guests
                 WHERE email = :email AND deleted_at IS NULL
                 LIMIT 1',
                [':email' => $input['email']]
            );

            if ($existingGuest !== null) {
                $guestId = (int)$existingGuest['id'];
                $database->query(
                    'UPDATE guests
                     SET full_name = :full_name,
                         phone = :phone,
                         id_number = :id_number,
                         special_requests = :special_requests,
                         updated_at = NOW()
                     WHERE id = :id',
                    [
                        ':full_name' => $input['full_name'],
                        ':phone' => $normalizedPhone,
                        ':id_number' => $input['id_number'] !== '' ? $input['id_number'] : null,
                        ':special_requests' => $input['special_requests'] !== '' ? $input['special_requests'] : null,
                        ':id' => $guestId,
                    ]
                );
            } else {
                $database->query(
                    'INSERT INTO guests (
                        full_name,
                        email,
                        phone,
                        id_number,
                        special_requests,
                        created_at,
                        updated_at
                    ) VALUES (
                        :full_name,
                        :email,
                        :phone,
                        :id_number,
                        :special_requests,
                        NOW(),
                        NOW()
                    )',
                    [
                        ':full_name' => $input['full_name'],
                        ':email' => $input['email'],
                        ':phone' => $normalizedPhone,
                        ':id_number' => $input['id_number'] !== '' ? $input['id_number'] : null,
                        ':special_requests' => $input['special_requests'] !== '' ? $input['special_requests'] : null,
                    ]
                );
                $guestId = $database->lastInsertId();
            }

            $totals = sidai_calculate_booking_totals($database, [
                'booking_type' => $input['booking_type'],
                'num_guests' => $numGuests,
                'check_in' => $input['check_in'],
                'check_out' => $input['check_out'] !== '' ? $input['check_out'] : $input['check_in'],
                'room_id' => $input['room_id'] !== '' ? (int)$input['room_id'] : null,
                'hall_id' => $input['hall_id'] !== '' ? (int)$input['hall_id'] : null,
                'hall_package' => $input['hall_package'] !== '' ? $input['hall_package'] : 'full_day',
                'music_duration' => $input['music_duration'] !== '' ? $input['music_duration'] : 'full_day',
                'room_type' => $input['room_type'] !== '' ? $input['room_type'] : 'standard',
            ]);

            $bookingRef = sidai_generate_booking_ref($database);
            $eventDate = $input['event_date'] !== '' ? $input['event_date'] : null;
            $effectiveCheckIn = $input['booking_type'] === 'room' ? $input['check_in'] : ($eventDate ?? $input['check_in']);
            $effectiveCheckOut = $input['booking_type'] === 'room' ? $input['check_out'] : null;

            $notes = trim(implode(' | ', array_filter([
                $input['notes'],
                $input['booking_type'] === 'pool' ? 'Pool slot: ' . sanitize_input($_POST['pool_time_slot'] ?? '') : '',
                $input['booking_type'] === 'dining' ? 'Dining time: ' . sanitize_input($_POST['dining_time'] ?? '') : '',
                $input['booking_type'] === 'music_shoot' ? 'Music duration: ' . ($input['music_duration'] !== '' ? $input['music_duration'] : 'full_day') : '',
            ])));

            $database->query(
                'INSERT INTO bookings (
                    booking_ref,
                    guest_id,
                    booking_type,
                    room_id,
                    hall_id,
                    check_in,
                    check_out,
                    event_date,
                    event_type,
                    event_setup,
                    num_guests,
                    subtotal,
                    discount_amount,
                    tax_amount,
                    total_amount,
                    deposit_amount,
                    status,
                    payment_status,
                    payment_method,
                    notes,
                    created_at,
                    updated_at
                ) VALUES (
                    :booking_ref,
                    :guest_id,
                    :booking_type,
                    :room_id,
                    :hall_id,
                    :check_in,
                    :check_out,
                    :event_date,
                    :event_type,
                    :event_setup,
                    :num_guests,
                    :subtotal,
                    :discount_amount,
                    :tax_amount,
                    :total_amount,
                    :deposit_amount,
                    :status,
                    :payment_status,
                    :payment_method,
                    :notes,
                    NOW(),
                    NOW()
                )',
                [
                    ':booking_ref' => $bookingRef,
                    ':guest_id' => $guestId,
                    ':booking_type' => $input['booking_type'],
                    ':room_id' => $totals['room_id'],
                    ':hall_id' => $totals['hall_id'],
                    ':check_in' => $effectiveCheckIn,
                    ':check_out' => $effectiveCheckOut,
                    ':event_date' => $eventDate,
                    ':event_type' => $input['event_type'] !== '' ? $input['event_type'] : null,
                    ':event_setup' => $input['event_setup'] !== '' ? $input['event_setup'] : null,
                    ':num_guests' => $numGuests,
                    ':subtotal' => $totals['subtotal'],
                    ':discount_amount' => 0,
                    ':tax_amount' => $totals['tax_amount'],
                    ':total_amount' => $totals['total_amount'],
                    ':deposit_amount' => 0,
                    ':status' => 'pending',
                    ':payment_status' => 'unpaid',
                    ':payment_method' => $input['payment_method'],
                    ':notes' => $notes !== '' ? $notes : null,
                ]
            );

            $bookingId = $database->lastInsertId();
            $checkoutRequestId = null;

            if ($input['payment_method'] === 'mpesa') {
                $mpesaResult = sidai_initiate_mpesa_payment($database, [
                    'booking_id' => $bookingId,
                    'booking_ref' => $bookingRef,
                    'phone' => $input['mpesa_phone'],
                    'amount' => $totals['total_amount'],
                    'notes' => 'Booking payment request',
                ]);

                if (($mpesaResult['success'] ?? false) !== true) {
                    throw new RuntimeException((string)($mpesaResult['message'] ?? 'Unable to initiate M-Pesa payment.'));
                }

                $checkoutRequestId = (string)($mpesaResult['checkout_request_id'] ?? '');
            } else {
                $paymentRef = sidai_generate_payment_ref_local($database);

                $database->query(
                    'INSERT INTO payments (
                        payment_ref,
                        booking_id,
                        amount,
                        method,
                        status,
                        notes,
                        created_at
                    ) VALUES (
                        :payment_ref,
                        :booking_id,
                        :amount,
                        :method,
                        :status,
                        :notes,
                        NOW()
                    )',
                    [
                        ':payment_ref' => $paymentRef,
                        ':booking_id' => $bookingId,
                        ':amount' => $totals['total_amount'],
                        ':method' => $input['payment_method'],
                        ':status' => 'pending',
                        ':notes' => 'Payment method selected during booking confirmation.',
                    ]
                );
            }

            $database->query(
                'INSERT INTO audit_log (
                    staff_id,
                    action,
                    entity_type,
                    entity_id,
                    old_values,
                    new_values,
                    ip_address,
                    user_agent,
                    created_at
                ) VALUES (
                    NULL,
                    :action,
                    :entity_type,
                    :entity_id,
                    NULL,
                    :new_values,
                    :ip_address,
                    :user_agent,
                    NOW()
                )',
                [
                    ':action' => 'booking.create',
                    ':entity_type' => 'booking',
                    ':entity_id' => $bookingId,
                    ':new_values' => json_encode([
                        'booking_ref' => $bookingRef,
                        'booking_type' => $input['booking_type'],
                        'payment_method' => $input['payment_method'],
                        'total_amount' => $totals['total_amount'],
                    ], JSON_UNESCAPED_SLASHES),
                    ':ip_address' => get_client_ip(),
                    ':user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
                ]
            );

            $subject = APP_NAME . ' Booking Confirmation - ' . $bookingRef;
            $emailMessage = implode("\n", [
                'Hello ' . $input['full_name'] . ',',
                '',
                'Thank you for choosing Sidai Resort.',
                'Your booking reference is: ' . $bookingRef,
                'Booking type: ' . ucfirst(str_replace('_', ' ', $input['booking_type'])),
                'Check-in date: ' . $effectiveCheckIn,
                'Guests: ' . $numGuests,
                'Total amount: ' . format_kes($totals['total_amount']),
                '',
                'We look forward to hosting you.',
                APP_NAME,
            ]);
            $headers = 'From: ' . MAIL_FROM_NAME . ' <' . MAIL_FROM_ADDRESS . '>';
            @mail($input['email'], $subject, $emailMessage, $headers);

            $database->commit();

            $response = [
                'success' => true,
                'booking_ref' => $bookingRef,
                'redirect' => '/receipt?ref=' . rawurlencode($bookingRef),
                'total_amount' => $totals['total_amount'],
            ];

            if ($checkoutRequestId !== null && $checkoutRequestId !== '') {
                $response['checkout_request_id'] = $checkoutRequestId;
            }

            if ($expectsJson) {
                json_response($response, 200);
            }

            header('Location: ' . $response['redirect']);
            exit;
        } catch (Throwable $exception) {
            if (isset($database)) {
                try {
                    $database->rollback();
                } catch (Throwable $rollbackException) {
                    log_error('Failed to rollback booking transaction.', $rollbackException);
                }
            }

            log_error('Booking submission failed.', $exception);

            $message = 'We could not complete your booking right now. Please try again or contact support.';

            if ($expectsJson) {
                json_response([
                    'success' => false,
                    'message' => $message,
                ], 500);
            }

            header('Location: /booking?error=' . urlencode($message));
            exit;
        }
    }
}
