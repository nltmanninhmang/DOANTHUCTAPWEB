<?php
session_start();
include '../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $payment_method = isset($_POST['payment_method']) && in_array($_POST['payment_method'], ['cash', 'qr']) ? $_POST['payment_method'] : null;

    if (empty($name) || empty($phone) || empty($address) || empty($email) || !$payment_method) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin']);
        exit;
    }

    if (!preg_match('/^[0-9]{10,11}$/', $phone)) {
        echo json_encode(['success' => false, 'message' => 'Số điện thoại phải có 10-11 chữ số']);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Email không hợp lệ']);
        exit;
    }

    $cart = [];
    if (isset($_COOKIE['cart'])) {
        $cart = json_decode(urldecode($_COOKIE['cart']), true);
    }

    if (empty($cart)) {
        echo json_encode(['success' => false, 'message' => 'Giỏ hàng trống']);
        exit;
    }

    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $status = 'Đang xử lý'; // Set status to 'Đang xử lý' for both cash and qr
    $items = json_encode($cart);

    $stmt = $pdo->prepare("
        INSERT INTO orders (user_id, name, phone, address, email, payment_method, total, status, items, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([$user_id, $name, $phone, $address, $email, $payment_method, $total, $status, $items]);

    if ($user_id) {
        $stmt = $pdo->prepare("
            UPDATE users
            SET name = ?, phone = ?, address = ?, email = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $phone, $address, $email, $user_id]);
    }

    // Clear cart after successful order
    setcookie('cart', '', time() - 3600, '/');
    
    echo json_encode(['success' => true, 'message' => 'Đặt hàng thành công']);
} catch (Exception $e) {
    error_log("Checkout error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
}
?>