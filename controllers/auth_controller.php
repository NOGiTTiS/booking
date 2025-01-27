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
            session_destroy();
            header('Location: ../views/auth/login.php');
            exit();
        }
    } elseif (isset($_POST['edit_user'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $role = $_POST['role'];
        try {
            if ($userModel->updateUser($id, $username, $firstName, $lastName, $email, $phone, $role)) {
                $_SESSION['success_message'] = "แก้ไขผู้ใช้งานสำเร็จ";
                header('Location: ../views/admin/user_management.php');
                exit();
            } else {
                $_SESSION['error_message'] = 'แก้ไขผู้ใช้งานไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
                header("Location: ../views/admin/user_edit.php?id=$id");
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ../views/admin/user_edit.php?id=$id");
            exit();
        }

    } elseif (isset($_POST['edit_profile'])) {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        try {
            if ($userModel->updateUser($id, $username, $firstName, $lastName, $email, $phone, $_SESSION['role'])) {
                $_SESSION['success_message'] = "แก้ไขข้อมูลส่วนตัวสำเร็จ";
                header('Location: ../views/users/profile.php');
                exit();
            } else {
                $_SESSION['error_message'] = 'แก้ไขข้อมูลส่วนตัวไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
                header('Location: ../views/users/profile.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/users/profile.php');
            exit();
        }

    } elseif (isset($_POST['change_password'])) {
        $id = $_POST['id'];
        $currentPassword = $_POST['current_password'];
        $newPassword = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $user = $userModel->getUserById($id);
        if (!$user || !$userModel->verifyPassword($currentPassword, $user['password'])) {
            $_SESSION['error_message'] = 'รหัสผ่านปัจจุบันไม่ถูกต้อง';
            header('Location: ../views/users/change_password.php');
            exit();
        }
        if ($newPassword !== $confirmPassword) {
            $_SESSION['error_message'] = 'รหัสผ่านใหม่และยืนยันไม่ตรงกัน';
            header('Location: ../views/users/change_password.php');
            exit();
        }
        try {
            if ($userModel->changePassword($id, $newPassword)) {
                $_SESSION['success_message'] = 'เปลี่ยนรหัสผ่านสำเร็จ';
                header('Location: ../views/users/profile.php');
                exit();
            } else {
                $_SESSION['error_message'] = 'เปลี่ยนรหัสผ่านไม่สำเร็จ กรุณาลองใหม่อีกครั้ง';
                header('Location: ../views/users/change_password.php');
                exit();
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/users/change_password.php');
            exit();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['logout'])) {
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
    if (isset($_GET['delete_user'])) {
        $id = $_GET['delete_user'];
        try {
            if ($userModel->deleteUser($id)) {
                $_SESSION['success_message'] = "ลบผู้ใช้งานสำเร็จ";
                header('Location: ../views/admin/user_management.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถลบผู้ใช้งานได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/admin/user_management.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/admin/user_management.php');
            exit();
        }
    }
    if (isset($_GET['reset_password'])) {
        $id = $_GET['reset_password'];
         try {
            if ($userModel->resetPassword($id)) {
                $_SESSION['success_message'] = "รีเซ็ตรหัสผ่านผู้ใช้สำเร็จ";
                header('Location: ../views/admin/user_management.php');
                exit();
            } else {
                 $_SESSION['error_message'] = "ไม่สามารถรีเซ็ตรหัสผ่านได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/admin/user_management.php');
                 exit();
           }
       } catch (Exception $e) {
            $_SESSION['error_message'] =  $e->getMessage();
            header('Location: ../views/admin/user_management.php');
             exit();
       }
    }
}