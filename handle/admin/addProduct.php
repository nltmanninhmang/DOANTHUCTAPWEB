<?php
include '../../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Validate required fields
if (!isset($_POST['name'], $_POST['category'], $_POST['amount'], $_POST['price'], $_FILES['image'])) {
    echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ thông tin và chọn ảnh sản phẩm']);
    exit;
}

$name = $_POST['name'];
$category = $_POST['category'];
$amount = $_POST['amount'];
$price = $_POST['price'];

// Validate numeric fields
if (!is_numeric($amount) || $amount < 0 || !is_numeric($price) || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Số lượng hoặc giá không hợp lệ']);
    exit;
}

// Handle file upload
if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
    switch ($_FILES['image']['error']) {
        case UPLOAD_ERR_NO_FILE:
            echo json_encode(['success' => false, 'message' => 'Không có file ảnh được tải lên']);
            break;
        case UPLOAD_ERR_INI_SIZE:
        case UPLOAD_ERR_FORM_SIZE:
            echo json_encode(['success' => false, 'message' => 'File ảnh quá lớn']);
            break;
        case UPLOAD_ERR_PARTIAL:
            echo json_encode(['success' => false, 'message' => 'File ảnh tải lên không hoàn chỉnh']);
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            echo json_encode(['success' => false, 'message' => 'Thiếu thư mục tạm để tải file']);
            break;
        case UPLOAD_ERR_CANT_WRITE:
            echo json_encode(['success' => false, 'message' => 'Không thể ghi file lên server']);
            break;
        case UPLOAD_ERR_EXTENSION:
            echo json_encode(['success' => false, 'message' => 'Phần mở rộng file không được hỗ trợ']);
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Lỗi không xác định khi tải ảnh']);
            break;
    }
    exit;
}

// Validate file type
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
if (!in_array($_FILES['image']['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Chỉ chấp nhận file JPEG, PNG hoặc GIF']);
    exit;
}

// Validate file size (5MB limit)
if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
    echo json_encode(['success' => false, 'message' => 'File ảnh quá lớn, tối đa 5MB']);
    exit;
}

$uploadDir = '../../Uploads/';
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        echo json_encode(['success' => false, 'message' => 'Không thể tạo thư mục Uploads']);
        exit;
    }
}

if (!is_writable($uploadDir)) {
    echo json_encode(['success' => false, 'message' => 'Thư mục Uploads không có quyền ghi']);
    exit;
}

$fileName = time() . '_' . basename($_FILES['image']['name']);
$uploadFile = $uploadDir . $fileName;

if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
    echo json_encode(['success' => false, 'message' => 'Không thể lưu ảnh sản phẩm, kiểm tra quyền thư mục']);
    exit;
}

$name_image = '/Uploads/' . $fileName;

try {
    // Insert product into database
    $stmt = $pdo->prepare("INSERT INTO sanpham (name, danhmuc, amount, name_image, price) VALUES (:name, :category, :amount, :name_image, :price)");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':amount', (int)$amount);
    $stmt->bindValue(':name_image', $name_image);
    $stmt->bindValue(':price', (float)$price);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Sản phẩm đã được thêm thành công']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Có lỗi xảy ra khi thêm sản phẩm vào cơ sở dữ liệu']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
}

$pdo = null; // Close connection
?>