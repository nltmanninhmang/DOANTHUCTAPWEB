<?php
try {
    $settings = $pdo->query("SELECT website_name, phone, address, email, logo FROM settings LIMIT 1")->fetch(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log("Error fetching settings: " . $e->getMessage());
    $settings = null;
}
?>