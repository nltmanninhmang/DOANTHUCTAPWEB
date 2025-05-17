<?php
include '../../config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Validate required fields
if (!isset($_POST['id'], $_POST['name'], $_POST['category'], $_POST['amount'], $_POST['price'])) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit;
}

$id = $_POST['id'];
$name = $_POST['name'];
$category = $_POST['category'];
$amount = $_POST['amount'];
$price = $_POST['price'];

// Fetch current product to get existing image name
$stmt = $pdo->prepare("SELECT name_image FROM sanpham WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo json_encode(['success' => false, 'message' => 'Product not found']);
    exit;
}

$name_image = $product['name_image']; // Default to existing image

// Handle file upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = '../../Uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileName = time() . '_' . basename($_FILES['image']['name']);
    $uploadFile = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
        $name_image = '/Uploads/' . $fileName;
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to upload image']);
        exit;
    }
}

// Validate numeric fields
if (!is_numeric($amount) || $amount < 0 || !is_numeric($price) || $price < 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid amount or price']);
    exit;
}

try {
    // Update product in database
    $stmt = $pdo->prepare("UPDATE sanpham SET name = :name, danhmuc = :category, amount = :amount, name_image = :name_image, price = :price WHERE id = :id");
    $stmt->bindValue(':name', $name);
    $stmt->bindValue(':category', $category);
    $stmt->bindValue(':amount', (int)$amount);
    $stmt->bindValue(':name_image', $name_image);
    $stmt->bindValue(':price', (float)$price);
    $stmt->bindValue(':id', $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Product updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Could not update product']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
}

$pdo = null; // Close connection
?>