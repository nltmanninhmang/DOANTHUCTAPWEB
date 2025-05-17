<?php
session_start();
include '../config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập.']);
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['id']) && is_numeric($_GET['id']) ? (int)$_GET['id'] : 0;

if ($order_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Mã đơn hàng không hợp lệ.']);
    exit();
}

try {
    // Lấy thông tin đơn hàng
    $stmt = $pdo->prepare("SELECT id, name, phone, address, email, payment_method, total, status, items, created_at FROM orders WHERE id = :order_id AND user_id = :user_id");
    $stmt->bindParam(':order_id', $order_id, PDO::PARAM_INT);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $order = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$order) {
        echo json_encode(['success' => false, 'message' => 'Đơn hàng không tồn tại hoặc không thuộc về bạn.']);
        exit();
    }

    // Giải mã JSON từ cột items
    $items = json_decode($order['items'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Lỗi giải mã dữ liệu sản phẩm.']);
        exit();
    }

    // Loại bỏ cột items khỏi $order để tránh trùng lặp
    unset($order['items']);

    echo json_encode([
        'success' => true,
        'order' => $order,
        'items' => $items
    ]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi cơ sở dữ liệu: ' . $e->getMessage()]);
}
?>