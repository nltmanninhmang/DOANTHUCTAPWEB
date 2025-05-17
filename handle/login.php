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
$password = $data->password ?? null;

// Kiểm tra xem các trường có rỗng không
if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin!']);
    exit;
}

// Kiểm tra xem email có tồn tại trong cơ sở dữ liệu không
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute(['email' => $email]);

if ($stmt->rowCount() === 0) {
    // Email không tồn tại
    echo json_encode(['success' => false, 'message' => 'Email không tồn tại!']);
    exit;
}

// Lấy thông tin người dùng
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Kiểm tra mật khẩu
if (!password_verify($password, $user['password'])) {
    // Mật khẩu không đúng
    echo json_encode(['success' => false, 'message' => 'Mật khẩu không đúng!']);
    exit;
}

// Đăng nhập thành công
// Bạn có thể lưu thông tin người dùng vào session nếu cần
session_start();
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_level'] = $user['level'];

// Trả về thông báo thành công
echo json_encode(['success' => true]);
?>