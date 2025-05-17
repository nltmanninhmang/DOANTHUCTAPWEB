<?php
include '../../config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    // Retrieve form data
    $website_name = isset($_POST['website_name']) ? trim($_POST['website_name']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $logo = isset($_FILES['logo']) ? $_FILES['logo'] : null;

    // Server-side validation
    if (empty($website_name) || empty($phone) || empty($address) || empty($email)) {
        echo json_encode(['success' => false, 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc']);
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

    // Handle logo upload
    $logo_path = null;
    if ($logo && $logo['size'] > 0) {
        $upload_dir = '../../Uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $logo_ext = strtolower(pathinfo($logo['name'], PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($logo_ext, $allowed_ext)) {
            echo json_encode(['success' => false, 'message' => 'Định dạng logo không hợp lệ. Chỉ chấp nhận JPG, PNG, GIF']);
            exit;
        }

        if ($logo['size'] > 5 * 1024 * 1024) { // 5MB limit
            echo json_encode(['success' => false, 'message' => 'Logo vượt quá kích thước 5MB']);
            exit;
        }

        $logo_name = 'logo_' . time() . '.' . $logo_ext;
        $logo_path = $upload_dir . $logo_name;

        if (!move_uploaded_file($logo['tmp_name'], $logo_path)) {
            echo json_encode(['success' => false, 'message' => 'Tải logo thất bại']);
            exit;
        }
        $logo_path = '/Uploads/' . $logo_name; // Relative path for database
    }

    // Check for existing settings
    $stmt = $pdo->query("SELECT website_name, phone, address, email, logo FROM settings LIMIT 1");
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        // Compare fields to detect changes
        $updates = [];
        $params = [];

        if ($website_name !== $existing['website_name']) {
            $updates[] = "website_name = ?";
            $params[] = $website_name;
        }
        if ($phone !== $existing['phone']) {
            $updates[] = "phone = ?";
            $params[] = $phone;
        }
        if ($address !== $existing['address']) {
            $updates[] = "address = ?";
            $params[] = $address;
        }
        if ($email !== $existing['email']) {
            $updates[] = "email = ?";
            $params[] = $email;
        }
        if ($logo_path !== null && $logo_path !== $existing['logo']) {
            $updates[] = "logo = ?";
            $params[] = $logo_path;
        }

        // Always update updated_at
        $updates[] = "updated_at = NOW()";

        // Perform update if there are changes
        if (!empty($updates)) {
            $sql = "UPDATE settings SET " . implode(', ', $updates) . " WHERE id = 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
    } else {
        // Insert new settings
        $stmt = $pdo->prepare("
            INSERT INTO settings (id, website_name, phone, address, email, logo, updated_at)
            VALUES (1, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([$website_name, $phone, $address, $email, $logo_path]);
    }

    echo json_encode(['success' => true, 'message' => 'Lưu cài đặt thành công']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi server: ' . $e->getMessage()]);
}

$pdo = null;
?>