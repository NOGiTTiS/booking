<?php
session_start();
require_once '../config/database.php';
if (! isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save_settings'])) {
        $defaultStatus = $_POST['default_booking_status'];
        try {
            $stmt = $pdo->prepare("INSERT INTO settings (setting_name, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
            $stmt->execute(['default_booking_status', $defaultStatus, $defaultStatus]);
            $_SESSION['success_message'] = "บันทึกการตั้งค่าสำเร็จ";
            header('Location: ../views/admin/settings.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['error_message'] = "ไม่สามารถบันทึกการตั้งค่าได้: " . $e->getMessage();
            header('Location: ../views/admin/settings.php');
            exit();
        }

    }
} else {
    header('Location: ../../index.php');
    exit();
}
