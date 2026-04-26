<?php declare(strict_types=1);

use App\Core\CSRF;
use App\Core\Database;

if (!defined('APP_PATH')) {
    require_once dirname(__DIR__) . '/includes/init.php';
}

if (!function_exists('sidai_normalize_menu_phone')) {
    function sidai_normalize_menu_phone(string $phone): ?string
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

if (!function_exists('sidai_prepare_menu_items_payload')) {
    /**
     * @return array{items_json: string, total: float, items_text: string}
     */
    function sidai_prepare_menu_items_payload(array|string $itemsInput): array
    {
        $structuredItems = [];
        $total = 0.0;
        $itemsText = '';

        if (is_array($itemsInput)) {
            foreach ($itemsInput as $entry) {
                if (!is_array($entry)) {
                    continue;
                }

                $id = (int)($entry['id'] ?? 0);
                $name = trim((string)($entry['name'] ?? ''));
                $quantity = max(1, (int)($entry['quantity'] ?? 1));
                $price = (float)($entry['price'] ?? 0);

                if ($name === '' && $id <= 0) {
                    continue;
                }

                $lineTotal = $price > 0 ? $price * $quantity : 0;
                $total += $lineTotal;

                $structuredItems[] = [
                    'id' => $id > 0 ? $id : null,
                    'name' => $name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'line_total' => $lineTotal,
                ];
            }

            $itemsText = implode(', ', array_map(
                static fn (array $item): string => ($item['quantity'] ?? 1) . 'x ' . trim((string)($item['name'] ?? 'Menu Item')),
                $structuredItems
            ));
        } else {
            $itemsText = trim($itemsInput);
            if ($itemsText !== '') {
                $structuredItems[] = [
                    'name' => 'Custom pre-order',
                    'quantity' => 1,
                    'notes' => $itemsText,
                ];
            }
        }

        return [
            'items_json' => json_encode($structuredItems, JSON_UNESCAPED_SLASHES) ?: '[]',
            'total' => round($total, 2),
            'items_text' => $itemsText,
        ];
    }
}

if (!function_exists('sidai_handle_menu_order_request')) {
    function sidai_handle_menu_order_request(): never
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            json_response(['success' => false, 'message' => 'Method not allowed.'], 405);
        }

        $csrfToken = $_POST[CSRF_TOKEN_NAME] ?? ($_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null));
        if ($csrfToken !== null && $csrfToken !== '' && !CSRF::verify((string)$csrfToken)) {
            json_response(['success' => false, 'message' => 'Security validation failed. Please refresh and try again.'], 419);
        }

        $name = sanitize_input($_POST['name'] ?? ($_POST['full_name'] ?? null));
        $phoneRaw = sanitize_input($_POST['phone'] ?? null);
        $roomTable = sanitize_input($_POST['room_table'] ?? ($_POST['delivery_location'] ?? null));
        $dateTime = sanitize_input($_POST['date_time'] ?? null);
        $specialInstructions = sanitize_input($_POST['special_instructions'] ?? null);
        $itemsInput = $_POST['items'] ?? '';

        $phone = sidai_normalize_menu_phone($phoneRaw);
        if ($name === '' || $phone === null) {
            json_response([
                'success' => false,
                'message' => 'Please provide a valid name and phone number.',
            ], 422);
        }

        $itemsPayload = sidai_prepare_menu_items_payload(is_array($itemsInput) ? $itemsInput : (string)$itemsInput);
        if (trim($itemsPayload['items_text']) === '') {
            json_response([
                'success' => false,
                'message' => 'Please specify at least one menu item.',
            ], 422);
        }

        $combinedInstructions = trim(implode(' | ', array_filter([
            $specialInstructions !== '' ? $specialInstructions : null,
            $dateTime !== '' ? 'Requested time: ' . $dateTime : null,
            $name !== '' ? 'Requested by: ' . $name : null,
            'Phone: ' . $phone,
        ])));

        try {
            $database = Database::getInstance();
            $database->beginTransaction();

            $database->query(
                'INSERT INTO orders (
                    order_ref,
                    booking_id,
                    guest_id,
                    items,
                    subtotal,
                    tax,
                    total,
                    delivery_type,
                    delivery_location,
                    special_instructions,
                    status,
                    created_at,
                    updated_at
                ) VALUES (
                    :order_ref,
                    NULL,
                    NULL,
                    :items,
                    :subtotal,
                    :tax,
                    :total,
                    :delivery_type,
                    :delivery_location,
                    :special_instructions,
                    :status,
                    NOW(),
                    NOW()
                )',
                [
                    ':order_ref' => generate_order_ref(),
                    ':items' => $itemsPayload['items_json'],
                    ':subtotal' => $itemsPayload['total'],
                    ':tax' => 0,
                    ':total' => $itemsPayload['total'],
                    ':delivery_type' => 'dine_in',
                    ':delivery_location' => $roomTable !== '' ? $roomTable : null,
                    ':special_instructions' => $combinedInstructions !== '' ? $combinedInstructions : null,
                    ':status' => 'pending',
                ]
            );

            $orderId = $database->lastInsertId();

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
                    ':action' => 'order.preorder.create',
                    ':entity_type' => 'order',
                    ':entity_id' => $orderId,
                    ':new_values' => json_encode(
                        [
                            'customer_name' => $name,
                            'phone' => $phone,
                            'room_table' => $roomTable,
                            'items' => $itemsPayload['items_text'],
                        ],
                        JSON_UNESCAPED_SLASHES
                    ),
                    ':ip_address' => get_client_ip(),
                    ':user_agent' => substr((string)($_SERVER['HTTP_USER_AGENT'] ?? ''), 0, 500),
                ]
            );

            $database->commit();

            json_response([
                'success' => true,
                'message' => "We'll confirm via WhatsApp.",
                'whatsapp' => 'https://wa.me/254703761951',
            ], 200);
        } catch (Throwable $exception) {
            if (isset($database)) {
                try {
                    $database->rollback();
                } catch (Throwable $rollbackException) {
                    log_error('Menu order rollback failed.', $rollbackException);
                }
            }

            log_error('Menu order creation failed.', $exception);
            json_response([
                'success' => false,
                'message' => 'Could not place your order right now. Please use WhatsApp fallback.',
                'whatsapp' => 'https://wa.me/254703761951',
            ], 500);
        }
    }
}
