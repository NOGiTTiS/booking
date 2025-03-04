<?php
session_start();
require_once '../config/database.php';
require_once '../models/User.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/booking/vendor/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/booking/vendor/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/booking/vendor/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function generateRandomString($length = 20)
{
    $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$userModel = new User($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['register'])) {
        $username  = $_POST['username'];
        $password  = $_POST['password'];
        $firstName = $_POST['first_name'];
        $lastName  = $_POST['last_name'];
        $email     = $_POST['email'];
        $phone     = $_POST['phone'];

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
        $username   = $_POST['username'];
        $password   = $_POST['password'];
        $rememberMe = isset($_POST['remember']);

        $user = $userModel->getUserByUsername($username);

        if ($user && $userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];
            $_SESSION['role']     = $user['role'];

            if ($rememberMe) {
                setcookie('remember_username', $username, time() + (86400 * 30), "/"); // Cookie lasts for 30 days
                setcookie('remember_password', $password, time() + (86400 * 30), "/");
            } else {
                setcookie('remember_username', '', time() - 3600, "/");
                setcookie('remember_password', '', time() - 3600, "/");
            }
            header('Location: ../index.php');
            exit();
        } else {
            $_SESSION['error_message'] = 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง';
            header('Location: ../views/auth/login.php');
            exit();
        }
    } elseif (isset($_POST['edit_user'])) {
        $id        = $_POST['id'];
        $username  = $_POST['username'];
        $firstName = $_POST['first_name'];
        $lastName  = $_POST['last_name'];
        $email     = $_POST['email'];
        $phone     = $_POST['phone'];
        $role      = $_POST['role'];
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
        $id        = $_POST['id'];
        $username  = $_POST['username'];
        $firstName = $_POST['first_name'];
        $lastName  = $_POST['last_name'];
        $email     = $_POST['email'];
        $phone     = $_POST['phone'];
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
        $id              = $_POST['id'];
        $currentPassword = $_POST['current_password'];
        $newPassword     = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $user            = $userModel->getUserById($id);
        if (! $user || ! $userModel->verifyPassword($currentPassword, $user['password'])) {
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
    } elseif (isset($_POST['forgot_password'])) {
        $email = $_POST['email'];
        $user  = $userModel->getUserByEmail($email);

        if ($user) {
            $token = generateRandomString();
            $userModel->saveResetToken($user['id'], $token);

            $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/booking/views/auth/reset_password.php?token=$token&username=".urlencode($user['username']);

            // Send email
            try {
                $mail = new PHPMailer(true);
                //Server settings
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com'; // ใส่ SMTP Host ของคุณ
                $mail->SMTPAuth   = true;
                $mail->Username   = 'knight.darkwing@gmail.com'; // ใส่ SMTP Username ของคุณ
                $mail->Password   = 'zmgn vsam lsik srgy'; // ใส่ SMTP Password ของคุณ
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                                                                                                     //Recipients
                $mail->setFrom('knight.darkwing@gmail.com', 'ระบบจองห้องประชุม'); // ใส่ email ของคุณ และชื่อที่จะแสดง
                $mail->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']);   // ใส่ email user
                                                                                                     //Content
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'รีเซ็ตรหัสผ่าน';
                $mail->Body    = "คลิกที่ลิงก์นี้เพื่อรีเซ็ตรหัสผ่านของคุณ: <a href='$resetLink'>$resetLink</a>";
                $mail->AltBody = "กรุณา copy link นี้ไปเปิดใน browser: $resetLink";

                $mail->send();
                $_SESSION['success_message'] = "ระบบได้ส่งลิงก์สำหรับรีเซ็ตรหัสผ่านไปยังอีเมลของคุณแล้ว";
                header('Location: ../views/auth/login.php');
                exit();
            } catch (Exception $e) {
                $_SESSION['error_message'] = "ไม่สามารถส่งอีเมลได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/auth/forgot_password.php');
                exit();
            }
        } else {
            $_SESSION['error_message'] = "ไม่พบผู้ใช้งานด้วยอีเมลนี้";
            header('Location: ../views/auth/forgot_password.php');
            exit();
        }
    } elseif (isset($_POST['reset_password'])) {
        $token           = $_POST['token'];
        $newPassword     = $_POST['new_password'];
        $confirmPassword = $_POST['confirm_password'];
        $user            = $userModel->getUserByToken($token);

        if ($user) {
            if ($newPassword === $confirmPassword) {
                if ($userModel->changePasswordWithToken($token, $newPassword)) {
                    $_SESSION['success_message'] = "ตั้งรหัสผ่านใหม่สำเร็จ";
                    header('Location: ../views/auth/login.php');
                    exit();
                } else {
                    $_SESSION['error_message'] = "ไม่สามารถตั้งรหัสผ่านใหม่ได้ กรุณาลองใหม่อีกครั้ง";
                    header("Location: ../views/auth/reset_password.php?token=$token");
                    exit();
                }
            } else {
                $_SESSION['error_message'] = "รหัสผ่านใหม่และยืนยันรหัสผ่านไม่ตรงกัน";
                header("Location: ../views/auth/reset_password.php?token=$token");
                exit();
            }
        } else {
            $_SESSION['error_message'] = "Invalid token";
            header('Location: ../views/auth/forgot_password.php');
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
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/admin/user_management.php');
            exit();
        }
    }
}
