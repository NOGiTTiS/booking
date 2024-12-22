<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        try {
            if ($userModel->createUser($username, $password, $firstName, $lastName, $email, $phone)) {
                $_SESSION['success_message'] = "สมัครสมาชิกสำเร็จ";
                header('Location: ../views/auth/login.php');
                exit();
            } else {
                $_SESSION['error_message'] = 'สมัครสมาชิกไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
                header('Location: ../views/auth/register.php');
                exit();
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/auth/register.php');
            exit();
        }

    } elseif (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $user = $userModel->getUserByUsername($username);

        if ($user && $userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            header('Location: ../views/auth/login.php');
            exit();
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../index.php');
    exit();
}
