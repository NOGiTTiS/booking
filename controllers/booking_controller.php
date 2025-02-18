<?php
session_start();
require_once '../config/database.php';
require_once '../models/Booking.php';
require_once '../models/Room.php';
require_once '../models/Equipment.php';
require_once '../models/User.php';

if (! isset($_SESSION['user_id'])) {
    header('Location: ../views/auth/login.php');
    exit();
}
$bookingModel   = new Booking($pdo);
$roomModel      = new Room($pdo);
$equipmentModel = new Equipment($pdo);
$userModel      = new User($pdo);

// Function to send Telegram message
function sendTelegramMessage($message, $chatId, $botToken)
{
    $url  = "https://api.telegram.org/bot" . $botToken . "/sendMessage";
    $data = [
        'chat_id'    => $chatId,
        'text'       => $message,
        'parse_mode' => 'HTML', // ใช้ HTML formatting ได้
    ];

    $options = [
        CURLOPT_URL            => $url,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $data,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => false, // ไม่แนะนำให้ใช้ใน production จริง, ควรตั้งค่า SSL ให้ถูกต้อง
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    if ($response === false) {
        error_log("Telegram API Error: " . curl_error($ch));
    } else {
        $responseData = json_decode($response, true);
        if ($responseData === null || ! $responseData['ok']) {
            error_log("Telegram API Response Error: " . print_r($responseData, true));
        }
    }
    curl_close($ch);
}

// Get default booking status from settings
$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_name = 'default_booking_status'");
$stmt->execute();
$result        = $stmt->fetch(PDO::FETCH_ASSOC);
$defaultStatus = isset($result['setting_value']) ? $result['setting_value'] : 'pending';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['create'])) {
        // code สำหรับการสร้างการจอง
        $userId          = $_SESSION['user_id'];
        $roomId          = $_POST['room_id'];
        $subject         = $_POST['subject'];
        $department      = $_POST['department'];
        $phone           = $_POST['phone'];
        $attendees       = $_POST['attendees'];
        $startTime       = $_POST['start_time'];
        $endTime         = $_POST['end_time'];
        $equipmentIds    = isset($_POST['equipments']) ? $_POST['equipments'] : [];
        $note            = $_POST['note'];
        $roomLayoutImage = null;
        try {
            if (isset($_FILES['room_layout_image']) && $_FILES['room_layout_image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir  = $_SERVER['DOCUMENT_ROOT'] . '/booking/assets/img/room_layouts/';
                $uploadFile = $uploadDir . basename($_FILES['room_layout_image']['name']);
                if (move_uploaded_file($_FILES['room_layout_image']['tmp_name'], $uploadFile)) {
                    $roomLayoutImage = '/booking/assets/img/room_layouts/' . basename($_FILES['room_layout_image']['name']);
                } else {
                    error_log("Error moving uploaded file");
                }
            }
            $now           = new DateTime();
            $startDateTime = new DateTime($startTime);
            $interval      = $now->diff($startDateTime);
            if ($_SESSION['role'] !== 'admin' && $interval->days < 1) {
                $_SESSION['error_message'] = "ต้องจองล่วงหน้าอย่างน้อย 1 วัน";
                header("Location: ../views/bookings/create.php");
                exit();
            }
            if ($bookingModel->checkBookingAvailability($roomId, $startTime, $endTime)) {
                $_SESSION['error_message'] = "ไม่สามารถจองห้องได้ เนื่องจากช่วงเวลาดังกล่าวไม่ว่าง กรุณาเลือกช่วงเวลาอื่น";
                header("Location: ../views/bookings/create.php");
                exit();
            }

            if ($bookingId = $bookingModel->createBooking($userId, $roomId, $subject, $department, $phone, $attendees, $startTime, $endTime, $equipmentIds, $note, $roomLayoutImage, $defaultStatus)) {
                $_SESSION['success_message'] = "จองห้องสำเร็จ กรุณารอการอนุมัติ" . $message;
                header('Location: ../views/bookings/list.php');
                // Send Telegram notification to admin
                $room     = $roomModel->getRoomById($roomId);
                $roomName = $room ? $room['name'] : "Unknown Room"; // Handle case where room might not exist
                $link     = "/booking/views/bookings/list.php";
                $message  = urlencode("มีการจองห้องประชุมใหม่\n"
                    . "รหัสการจอง: " . $bookingId . "\n"
                    . "หัวข้อ: " . htmlspecialchars($subject) . "\n" // Use htmlspecialchars for safety
                    . "ห้อง: " . htmlspecialchars($roomName) . "\n"
                    . "เวลา: " . htmlspecialchars($startTime) . " - " . htmlspecialchars($endTime) . "\n"
                    . "ผู้จอง: " . htmlspecialchars($_SESSION['username']) . "\n"
                    . "ฝ่าย/งาน: " . htmlspecialchars($department) . "\n"
                    . "เบอร์โทร: " . htmlspecialchars($phone) . "\n"
                    . "จำนวนผู้เข้าใช้: " . htmlspecialchars($attendees) . "\n"
                    . "หมายเหตุ: " . htmlspecialchars($note));

                $adminChatId = '-4765744081';                                    // *** เปลี่ยนเป็น Chat ID ของแอดมิน ***
                $botToken    = '7760198064:AAFQFHyMPNoZv99ovHp6Jl5FfSZbYN_WDtI'; // *** เปลี่ยนเป็น Bot Token ของคุณ ***
                $url         = "https://api.telegram.org/bot$botToken/sendMessage?text=$message&chat_id=$adminChatId";
                $ch          = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                curl_close($ch);
                echo "<pre>";
                print_r($result);
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
        $bookingId       = $_POST['id'];
        $userId          = $_POST['user_id'];
        $roomId          = $_POST['room_id'];
        $subject         = $_POST['subject'];
        $department      = $_POST['department'];
        $phone           = $_POST['phone'];
        $attendees       = $_POST['attendees'];
        $startTime       = $_POST['start_time'];
        $endTime         = $_POST['end_time'];
        $equipmentIds    = isset($_POST['equipments']) ? $_POST['equipments'] : [];
        $note            = $_POST['note'];
        $booking         = $bookingModel->getBookingById($bookingId);
        $roomLayoutImage = $booking['room_layout_image'];

        if (isset($_FILES['room_layout_image']) && $_FILES['room_layout_image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir  = $_SERVER['DOCUMENT_ROOT'] . '/booking/assets/img/room_layouts/';
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
        $booking   = $bookingModel->getBookingById($bookingId);
        $user      = $userModel->getUserById($booking['user_id']);
        $adminId   = $_SESSION['user_id'];
        try {
            if ($bookingModel->approveBooking($bookingId, $adminId)) {
                $_SESSION['success_message'] = "อนุมัติการจองสำเร็จ";
                header('Location: ../views/bookings/list.php');
                exit();
            } else {
                $_SESSION['error_message'] = "ไม่สามารถอนุมัติการจองได้ กรุณาลองใหม่อีกครั้ง";
                header('Location: ../views/bookings/list.php');
                exit();
            }
        } catch (Exception $e) {
            $_SESSION['error_message'] = $e->getMessage();
            header('Location: ../views/bookings/list.php');
            exit();
        }
    } elseif (isset($_GET['reject'])) {
        $bookingId = $_GET['reject'];
        $booking   = $bookingModel->getBookingById($bookingId);
        $user      = $userModel->getUserById($booking['user_id']);
        $adminId   = $_SESSION['user_id'];
        if ($bookingModel->rejectBooking($bookingId, $adminId)) {
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
