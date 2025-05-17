<?php
include '../../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $id = $data['id'];
    $name = $data['name'];
    $email = $data['email'];
    $level = $data['level'];

    // Cập nhật thông tin người dùng trong cơ sở dữ liệu
    $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, level = :level WHERE id = :id");
    
    // Gán giá trị cho các tham số
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':level', $level, PDO::PARAM_INT);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not update user.']);
    }
}
$pdo = null; // Đóng kết nối
?>