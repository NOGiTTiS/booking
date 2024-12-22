<?php
session_start();
require_once '../config/database.php';
require_once '../models/Booking.php';
require_once '../models/Room.php';
require_once '../models/Equipment.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../views/auth/login.php');
    exit();
}
$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$equipmentModel = new Equipment($pdo);
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
        try {

            if ($bookingModel->checkBookingAvailability($roomId, $startTime, $endTime)) {
                $_SESSION['error_message'] = "ไม่สามารถจองห้องได้ เนื่องจากช่วงเวลาดังกล่าวไม่ว่าง กรุณาเลือกช่วงเวลาอื่น";
                header("Location: ../views/bookings/create.php");
                exit();
            }

            if ($bookingModel->createBooking($userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds, $note)) {
                $_SESSION['success_message'] = "จองห้องสำเร็จ กรุณารอการอนุมัติ";
                header('Location: ../views/bookings/list.php');
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

        if ($booking['status'] !== 'pending') {
            $_SESSION['error_message'] = "ไม่สามารถแก้ไขการจองได้เนื่องจากได้รับการอนุมัติหรือถูกปฏิเสธแล้ว";
            header("Location: ../views/bookings/edit.php?id=$bookingId");
            exit();
        }
        try {
            if ($bookingModel->checkBookingAvailability($roomId, $startTime, $endTime, $bookingId)) {
                $_SESSION['error_message'] = "ไม่สามารถแก้ไขการจองได้ เนื่องจากช่วงเวลาดังกล่าวไม่ว่าง กรุณาเลือกช่วงเวลาอื่น";
                header("Location: ../views/bookings/edit.php?id=$bookingId");
                exit();
            }

            if ($bookingModel->updateBooking($bookingId, $userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds, $note)) {
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

        if ($bookingModel->approveBooking($bookingId)) {
            $_SESSION['success_message'] = "อนุมัติการจองสำเร็จ";
            header('Location: ../views/bookings/list.php');
            exit();
        } else {
            $_SESSION['error_message'] = "ไม่สามารถอนุมัติการจองได้ กรุณาลองใหม่อีกครั้ง";
            header('Location: ../views/bookings/list.php');
            exit();
        }
    } elseif (isset($_GET['reject'])) {
        $bookingId = $_GET['reject'];

        if ($bookingModel->rejectBooking($bookingId)) {
            $_SESSION['success_message'] = "ปฏิเสธการจองสำเร็จ";
            header('Location: ../views/bookings/list.php');
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
