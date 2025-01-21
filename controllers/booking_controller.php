<?php
session_start();
require_once '../config/database.php';
require_once '../models/Booking.php';
require_once '../models/Room.php';
require_once '../models/Equipment.php';
require_once '../models/User.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/auth/login.php');
    exit();
}
$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$equipmentModel = new Equipment($pdo);
$userModel = new User($pdo);

// Function to send Line Notify
function sendLineNotify($message, $token, $userId=null)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://notify-api.line.me/api/notify',
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => http_build_query(['message' => $message]),
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $token],
        CURLOPT_RETURNTRANSFER => 1,
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    if ($result !== false) {
        // Log successful response
        error_log("Line Notify sent successfully. Response: " . $result);
    } else {
        // Log curl error
        error_log("Error sending Line Notify: " . curl_error($ch));
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // code สำหรับการสร้างการจอง
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
        $roomLayoutImage = null;
        try {
            if (isset($_FILES['room_layout_image']) && $_FILES['room_layout_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/booking/assets/img/room_layouts/';
                $uploadFile = $uploadDir . basename($_FILES['room_layout_image']['name']);
                if (move_uploaded_file($_FILES['room_layout_image']['tmp_name'], $uploadFile)) {
                    $roomLayoutImage = '/booking/assets/img/room_layouts/' . basename($_FILES['room_layout_image']['name']);
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
                // Send Line Notify notification to admin
                $adminLineToken = ''; // ใส่ Line Notify Token ของ Admin
                $message = "มีการจองห้องประชุมใหม่ รหัสการจอง: $bookingId, หัวข้อ: $subject, ห้อง: $roomId, เวลา: $startTime - $endTime";
                $link = "/booking/views/bookings/list.php";
                $message .= "\n" . "http://" . $_SERVER['HTTP_HOST'] . $link;
                sendLineNotify($message, $adminLineToken);
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
        $userId = $_POST['user_id'];
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
            $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/booking/assets/img/room_layouts/';
            $uploadFile = $uploadDir . basename($_FILES['room_layout_image']['name']);
            if (move_uploaded_file($_FILES['room_layout_image']['tmp_name'], $uploadFile)) {
                $roomLayoutImage = '/booking/assets/img/room_layouts/' . basename($_FILES['room_layout_image']['name']);
            } else {
                error_log("Error moving uploaded file");
            }
        }
        if ($booking['status'] !== 'pending' && $_SESSION['role'] !== 'admin') {
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
        $adminId = $_SESSION['user_id'];
        try{
            if ($bookingModel->approveBooking($bookingId,$adminId)) {
                $_SESSION['success_message'] = "อนุมัติการจองสำเร็จ";
                header('Location: ../views/bookings/list.php');
                // Send Line Notify notification to user
                $userLineToken = ''; // ใส่ Line Notify Token ของ User
                $message = "การจองห้องประชุมของคุณ รหัสการจอง: $bookingId, หัวข้อ: {$booking['subject']} ได้รับการอนุมัติแล้ว";
                $link = "/booking/views/bookings/list.php";
                $message .= "\n" . "http://" . $_SERVER['HTTP_HOST'] . $link;
                sendLineNotify($message, $userLineToken);
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถอนุมัติการจองได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/bookings/list.php');
                exit();
            }
        }catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/bookings/list.php');
            exit();
        }
    } elseif (isset($_GET['reject'])) {
        $bookingId = $_GET['reject'];
        $booking = $bookingModel->getBookingById($bookingId);
        $user = $userModel->getUserById($booking['user_id']);
        $adminId = $_SESSION['user_id'];
        if ($bookingModel->rejectBooking($bookingId,$adminId)) {
            $_SESSION['success_message'] = "ปฏิเสธการจองสำเร็จ";
            header('Location: ../views/bookings/list.php');
            // Send Line Notify notification to user
            $userLineToken = ''; // ใส่ Line Notify Token ของ User
            $message = "การจองห้องประชุมของคุณ รหัสการจอง: $bookingId, หัวข้อ: {$booking['subject']} ถูกปฏิเสธ";
            $link = "/booking/views/bookings/list.php";
            $message .= "\n" . "http://" . $_SERVER['HTTP_HOST'] . $link;
            sendLineNotify($message, $userLineToken);
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
