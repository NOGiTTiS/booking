<?php
session_start();
require_once 'config/database.php';
require_once 'models/Booking.php';

$bookingId = isset($_GET['id']) ? $_GET['id'] : null;

// Initialize response array
$response = [];
if ($bookingId) {
    try {
        $bookingModel = new Booking($pdo);
        $booking = $bookingModel->getBookingById($bookingId);

        if ($booking) {
            // Set header only when sending JSON
            header('Content-Type: application/json');
            $response = $booking;
        } else {
            $response = ['error' => 'ไม่พบข้อมูล'];
        }
    } catch (PDOException $e) {
        $response = ['error' => 'Database error: ' . $e->getMessage()];
    }
} else {
    $response = ['error' => 'Invalid booking id'];
}
// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
