<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];

    // Xóa sản phẩm
    $stmt = $pdo->prepare("DELETE FROM sanpham WHERE id = :id");

    // Gán giá trị cho tham số
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not delete product.']);
    }
}
$pdo = null; // Đóng kết nối
?>