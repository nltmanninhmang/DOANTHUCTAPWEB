<?php
$host = 'localhost'; 
$dbname = 'snstech'; 
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Kết nối thất bại: " . $e->getMessage();
}

try {
    $settings = $pdo->query("SELECT website_name, phone, address, email, logo FROM settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching settings: " . $e->getMessage());
    $settings = null;
}
?>