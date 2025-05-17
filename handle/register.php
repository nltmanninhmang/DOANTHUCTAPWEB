<?php
include '../config.php'; // Kết nối đến cơ sở dữ liệu

// Lấy dữ liệu từ yêu cầu
$data = json_decode(file_get_contents("php://input"));

// Kiểm tra xem dữ liệu có được gửi đến hay không
if ($data === null) {
    echo json_encode(['success' => false, 'message' => 'Không có dữ liệu được gửi đến!']);
    exit;
}

$email = $data->email ?? null;
$name = $data->name ?? null;
$password = $data->password ?? null;

// Kiểm tra xem các trường có rỗng không
if (empty($email) || empty($name) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
    exit;
}

// Mã hóa mật khẩu
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Kiểm tra xem email đã tồn tại chưa
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);

if ($stmt->rowCount() > 0) {
    // Email đã tồn tại
    echo json_encode(['success' => false, 'message' => 'Email đã tồn tại!']);
} else {
    // Thêm người dùng mới vào cơ sở dữ liệu với level mặc định là 1
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, level) VALUES (:name, :email, :password, :level)");
    if ($stmt->execute(['name' => $name, 'email' => $email, 'password' => $hashedPassword, 'level' => 1])) {
        // Đăng ký thành công
        echo json_encode(['success' => true]);
    } else {
        // Đăng ký thất bại
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra, vui lòng thử lại!']);
    }
}
?>