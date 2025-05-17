<?php
session_start();
include '../../config.php'; 

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: /");
    exit();
}

try {
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT level FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || $user['level'] != 2) {
        header("Location: /");
        exit();
    }
} catch (Exception $e) {
    header("Location: /");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A fully featured admin theme which can be used to build CRM, CMS, etc.">
    <link rel="shortcut icon" href="assets/favicon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/libs.bundle.css">
    <link rel="stylesheet" href="../../assets/css/theme.bundle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>SNSTECH Quản Trị</title>
</head>