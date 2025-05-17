<?php
session_start();
header('Content-Type: application/json');

// Xóa tất cả session
session_unset();
session_destroy();

echo json_encode([
    'success' => true,
    'message' => 'Đăng xuất thành công.'
]);
?>