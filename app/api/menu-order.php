<?php declare(strict_types=1);

require_once dirname(__DIR__) . '/includes/init.php';

use App\Models\Order;
use App\Models\Payment;
use App\Core\Mailer;

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Validate CSRF token
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        throw new Exception('Invalid CSRF token');
    }

    $bookingId = (int)($_POST['booking_id'] ?? 0);
    $guestId = (int)($_POST['guest_id'] ?? 0);
    $items = $_POST['items'] ?? [];
    $deliveryType = $_POST['delivery_type'] ?? 'dine_in';
    $specialInstructions = trim($_POST['special_instructions'] ?? '');

    if (empty($items) || empty($bookingId) && empty($guestId)) {
        throw new Exception('Invalid request parameters');
    }

    // Calculate totals
    $subtotal = 0;
    $validatedItems = [];

    foreach ($items as $item) {
        $itemId = (int)$item['id'];
        $quantity = (int)$item['quantity'];

        if ($quantity <= 0) {
            continue;
        }

        // Get item details
        $itemModel = new \App\Models\MenuItem();
        $menuItem = $itemModel->getById($itemId);

        if (!$menuItem) {
            throw new Exception("Item {$itemId} not found");
        }

        $itemTotal = $menuItem['price'] * $quantity;
        $subtotal += $itemTotal;

        $validatedItems[] = [
            'id' => $itemId,
            'name' => $menuItem['name'],
            'price' => $menuItem['price'],
            'quantity' => $quantity,
            'total' => $itemTotal,
        ];
    }

    $tax = $subtotal * 0.16; // 16% VAT
    $total = $subtotal + $tax;

    // Create order
    $orderModel = new Order();
    $orderId = $orderModel->create([
        'booking_id' => $bookingId ?: null,
        'guest_id' => $guestId ?: null,
        'items' => $validatedItems,
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total,
        'delivery_type' => $deliveryType,
        'special_instructions' => $specialInstructions,
    ]);

    if (!$orderId) {
        throw new Exception('Failed to create order');
    }

    // Create payment record
    $paymentModel = new Payment();
    $paymentModel->create([
        'order_id' => $orderId,
        'booking_id' => $bookingId ?: null,
        'amount' => $total,
        'method' => 'pending',
        'status' => 'pending',
    ]);

    log_audit_action('order.created', 'order', $orderId, null, ['items_count' => count($validatedItems), 'total' => $total]);

    echo json_encode([
        'success' => true,
        'order_id' => $orderId,
        'order_ref' => generate_order_ref(),
        'subtotal' => $subtotal,
        'tax' => $tax,
        'total' => $total,
        'message' => 'Order created successfully'
    ]);

} catch (Exception $exception) {
    log_error('Menu order creation failed', $exception);

    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $exception->getMessage()
    ]);
    exit(1);
}
