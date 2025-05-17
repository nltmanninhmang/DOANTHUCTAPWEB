<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
    // Lấy dữ liệu từ body
    $data = json_decode(file_get_contents("php://input"), true);
    $id = isset($data['id']) ? $data['id'] : null; // Kiểm tra xem id có tồn tại không

    if ($id === null) {
        echo json_encode(['success' => false, 'message' => 'ID is required.']);
        exit;
    }

    // Xóa người dùng
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not delete user.']);
    }
}
$pdo = null; // Đóng kết nối
?>