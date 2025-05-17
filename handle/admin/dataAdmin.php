<?php
session_start();
include '../../config.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM users");
    $stmt->execute();
    $totalUsers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM sanpham");
    $stmt->execute();
    $totalProducts = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM orders WHERE DATE(created_at) = CURDATE()");
    $stmt->execute();
    $totalOrdersToday = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Monthly Revenue
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(total), 0) as total_revenue FROM orders WHERE YEAR(created_at) = YEAR(CURDATE()) AND MONTH(created_at) = MONTH(CURDATE())");
    $stmt->execute();
    $monthlyRevenue = $stmt->fetch(PDO::FETCH_ASSOC)['total_revenue'];

    // Today's Orders List
    $stmt = $pdo->prepare("SELECT id, name, total, status, created_at FROM orders WHERE DATE(created_at) = CURDATE() ORDER BY created_at DESC");
    $stmt->execute();
    $ordersToday = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'totalUsers' => $totalUsers,
            'totalProducts' => $totalProducts,
            'totalOrdersToday' => $totalOrdersToday,
            'monthlyRevenue' => $monthlyRevenue,
            'ordersToday' => $ordersToday
        ]
    ]);
} catch (Exception $e) {
    error_log("API error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
}
?>