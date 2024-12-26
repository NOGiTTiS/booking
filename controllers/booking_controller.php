<?php
session_start();
require_once '../config/database.php';
require_once '../models/Booking.php';
require_once '../models/Room.php';
require_once '../models/Equipment.php';
require_once '../models/User.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/src/Exception.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/src/PHPMailer.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/vendor/PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/auth/login.php');
    exit();
}
$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$equipmentModel = new Equipment($pdo);
$userModel = new User($pdo);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['create'])) {

        $userId = $_SESSION['user_id'];
        $roomId = $_POST['room_id'];
        $subject = $_POST['subject'];
        $department = $_POST['department'];
        $phone = $_POST['phone'];
        $attendees = $_POST['attendees'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $equipmentIds = isset($_POST['equipments']) ? $_POST['equipments'] : [];
        $note = $_POST['note'];
        //$roomLayoutImage = null;
        try {
            // Upload image if it exists and no error
            $roomLayoutImage = null; // Initialize $roomLayoutImage
            if (isset($_FILES['room_layout_image']) && $_FILES['room_layout_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/room_layouts/'; // Define upload directory
                $uploadFile = $uploadDir . basename($_FILES['room_layout_image']['name']);
                if (move_uploaded_file($_FILES['room_layout_image']['tmp_name'], $uploadFile)) {
                    $roomLayoutImage = '/assets/img/room_layouts/' . basename($_FILES['room_layout_image']['name']);
                    //error_log("uploadfile: " . $uploadFile);
                    //error_log("roomLayoutImage: " . $roomLayoutImage);
                } else {
                    error_log("Error moving uploaded file");
                }
            }
            if ($bookingModel->checkBookingAvailability($roomId, $startTime, $endTime)) {
                $_SESSION['error_message'] = "ไม่สามารถจองห้องได้ เนื่องจากช่วงเวลาดังกล่าวไม่ว่าง กรุณาเลือกช่วงเวลาอื่น";
                header("Location: ../views/bookings/create.php");
                exit();
            }

            if ($bookingId = $bookingModel->createBooking($userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds, $note, $roomLayoutImage)) {
                $_SESSION['success_message'] = "จองห้องสำเร็จ กรุณารอการอนุมัติ";
                header('Location: ../views/bookings/list.php');
                // Send email notification
                try {
                    $mail = new PHPMailer(true);
                    //Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // ใส่ SMTP Host ของคุณ
                    $mail->SMTPAuth = true;
                    $mail->Username = 'booking.room@tn.ac.th'; // ใส่ SMTP Username ของคุณ
                    $mail->Password = 'skwd kghl xtkz cbca'; // ใส่ SMTP Password ของคุณ
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    //Recipients
                    $mail->setFrom('booking.room@tn.ac.th', 'ระบบจองห้องประชุม'); // ใส่ email ของคุณ และชื่อที่จะแสดง
                    $mail->addAddress('booking.room@tn.ac.th', 'Admin'); // ใส่ email admin และชื่อที่จะแสดง
                    //Content
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    $mail->Subject = 'มีการจองห้องประชุมใหม่';
                    $mail->Body = "มีการจองห้องประชุมใหม่ รหัสการจอง: $bookingId, หัวข้อ: $subject, ห้อง: $roomId, เวลา: $startTime - $endTime";
                    $mail->send();
                } catch (Exception $e) {
                    // Handle email sending error (Optional)
                    error_log("Email sending error: " . $e->getMessage());
                }
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถจองห้องได้ กรุณาลองใหม่อีกครั้ง";
                header("Location: ../views/bookings/create.php");
                exit();
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ../views/bookings/create.php");
            exit();
        }

    } elseif (isset($_POST['edit'])) {
        $bookingId = $_POST['id'];
        $userId = $_SESSION['user_id'];
        $roomId = $_POST['room_id'];
        $subject = $_POST['subject'];
        $department = $_POST['department'];
        $phone = $_POST['phone'];
        $attendees = $_POST['attendees'];
        $startTime = $_POST['start_time'];
        $endTime = $_POST['end_time'];
        $equipmentIds = isset($_POST['equipments']) ? $_POST['equipments'] : [];
        $note = $_POST['note'];
        $booking = $bookingModel->getBookingById($bookingId);
        $roomLayoutImage = $booking['room_layout_image'];
        if (isset($_FILES['room_layout_image']) && $_FILES['room_layout_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/assets/img/room_layouts/'; // Define upload directory (use absolute path)
            $uploadFile = $uploadDir . basename($_FILES['room_layout_image']['name']);
            if (move_uploaded_file($_FILES['room_layout_image']['tmp_name'], $uploadFile)) {
                $roomLayoutImage = '/assets/img/room_layouts/' . basename($_FILES['room_layout_image']['name']); // Set full path to store in db
            } else {
                error_log("Error moving uploaded file");
            }
        }
        if ($booking['status'] !== 'pending' || $booking['user_id'] !== $userId) {
            $_SESSION['error_message'] = "ไม่สามารถแก้ไขการจองได้เนื่องจากได้รับการอนุมัติหรือถูกปฏิเสธแล้ว หรือไม่ใช่รายการจองของคุณ";
            header("Location: ../views/bookings/edit.php?id=$bookingId");
            exit();
        }
        try {
            if ($bookingModel->checkBookingAvailability($roomId, $startTime, $endTime, $bookingId)) {
                $_SESSION['error_message'] = "ไม่สามารถแก้ไขการจองได้ เนื่องจากช่วงเวลาดังกล่าวไม่ว่าง กรุณาเลือกช่วงเวลาอื่น";
                header("Location: ../views/bookings/edit.php?id=$bookingId");
                exit();
            }
            if ($bookingModel->updateBooking($bookingId, $userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds, $note, $roomLayoutImage)) {
                $_SESSION['success_message'] = "แก้ไขการจองสำเร็จ";
                header('Location: ../views/bookings/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถแก้ไขการจองได้ กรุณาลองใหม่อีกครั้ง";
                header("Location: ../views/bookings/edit.php?id=$bookingId");
                exit();
            }

        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header("Location: ../views/bookings/edit.php?id=$bookingId");
            exit();
        }
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (isset($_GET['approve'])) {
        $bookingId = $_GET['approve'];
        $booking = $bookingModel->getBookingById($bookingId);
        $user = $userModel->getUserById($booking['user_id']);
        if ($bookingModel->approveBooking($bookingId)) {
            $_SESSION['success_message'] = "อนุมัติการจองสำเร็จ";
            header('Location: ../views/bookings/list.php');
            // Send email notification to user
            try {
                $mail = new PHPMailer(true);
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // ใส่ SMTP Host ของคุณ
                $mail->SMTPAuth = true;
                $mail->Username = 'booking.room@tn.ac.th'; // ใส่ SMTP Username ของคุณ
                $mail->Password = 'skwd kghl xtkz cbca'; // ใส่ SMTP Password ของคุณ
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('booking.room@tn.ac.th', 'ระบบจองห้องประชุม'); // ใส่ email ของคุณ และชื่อที่จะแสดง
                $mail->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']); // ใส่ email user

                //Content
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Add character set here
                $mail->Subject = 'การจองห้องประชุมของคุณได้รับการอนุมัติ';
                $mail->Body = "การจองห้องประชุมของคุณ รหัสการจอง: $bookingId, หัวข้อ: {$booking['subject']} ได้รับการอนุมัติแล้ว";
                $mail->send();
            } catch (Exception $e) {
                // Handle email sending error (Optional)
                error_log("Email sending error: " . $e->getMessage());
            }
            exit();
        } else {
            $_SESSION['error_message'] = "ไม่สามารถอนุมัติการจองได้ กรุณาลองใหม่อีกครั้ง";
            header('Location: ../views/bookings/list.php');
            exit();
        }
    } elseif (isset($_GET['reject'])) {
        $bookingId = $_GET['reject'];
        $booking = $bookingModel->getBookingById($bookingId);
        $user = $userModel->getUserById($booking['user_id']);

        if ($bookingModel->rejectBooking($bookingId)) {
            $_SESSION['success_message'] = "ปฏิเสธการจองสำเร็จ";
            header('Location: ../views/bookings/list.php');
            // Send email notification to user
            try {
                $mail = new PHPMailer(true);
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // ใส่ SMTP Host ของคุณ
                $mail->SMTPAuth = true;
                $mail->Username = 'booking.room@tn.ac.th'; // ใส่ SMTP Username ของคุณ
                $mail->Password = 'skwd kghl xtkz cbca'; // ใส่ SMTP Password ของคุณ
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                //Recipients
                $mail->setFrom('booking.room@tn.ac.th', 'ระบบจองห้องประชุม'); // ใส่ email ของคุณ และชื่อที่จะแสดง
                $mail->addAddress($user['email'], $user['first_name'] . ' ' . $user['last_name']); // ใส่ email user
                //Content
                $mail->isHTML(true);
                $mail->CharSet = 'UTF-8'; // Add character set here
                $mail->Subject = 'การจองห้องประชุมของคุณถูกปฏิเสธ';
                $mail->Body = "การจองห้องประชุมของคุณ รหัสการจอง: $bookingId, หัวข้อ: {$booking['subject']} ถูกปฏิเสธ";
                $mail->send();
            } catch (Exception $e) {
                // Handle email sending error (Optional)
                error_log("Email sending error: " . $e->getMessage());
            }
            exit();
        } else {
            $_SESSION['error_message'] = "ไม่สามารถปฏิเสธการจองได้ กรุณาลองใหม่อีกครั้ง";
            header('Location: ../views/bookings/list.php');
            exit();
        }

    } elseif (isset($_GET['delete'])) {
        $bookingId = $_GET['delete'];

        try {
            if ($bookingModel->deleteBooking($bookingId)) {
                $_SESSION['success_message'] = "ลบการจองสำเร็จ";
                header('Location: ../views/bookings/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถลบการจองได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/bookings/list.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/bookings/list.php');
            exit();
        }
    }
}
