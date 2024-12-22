<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../models/Booking.php';
require_once '../../models/Room.php';
require_once '../../models/User.php';
require_once '../../models/Equipment.php';

$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$userModel = new User($pdo);
$equipmentModel = new Equipment($pdo);

$totalBookings = count($bookingModel->getAllBookings());
$totalRooms = count($roomModel->getAllRooms());
$totalUsers = count($userModel->getAllUsers());
$totalEquipments = count($equipmentModel->getAllEquipments());
$pendingBookings = count($bookingModel->getAllBookings(['status' => 'pending']));
$approvedBookings = count($bookingModel->getAllBookings(['status' => 'approved']));
$rejectedBookings = count($bookingModel->getAllBookings(['status' => 'rejected']));

?>
<h2>แดชบอร์ดผู้ดูแลระบบ</h2>
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนการจองทั้งหมด</h5>
                <p class="card-text"><?php echo $totalBookings; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-secondary mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนห้องประชุม</h5>
                <p class="card-text"><?php echo $totalRooms; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนผู้ใช้งาน</h5>
                <p class="card-text"><?php echo $totalUsers; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
         <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนอุปกรณ์</h5>
                <p class="card-text"><?php echo $totalEquipments; ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
          <div class="card text-white bg-warning mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนการจองที่รออนุมัติ</h5>
                <p class="card-text"><?php echo $pendingBookings; ?></p>
            </div>
        </div>
    </div>
        <div class="col-md-3">
          <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนการจองที่อนุมัติแล้ว</h5>
                <p class="card-text"><?php echo $approvedBookings; ?></p>
            </div>
        </div>
    </div>
     <div class="col-md-3">
         <div class="card text-white bg-danger mb-3">
            <div class="card-body">
                <h5 class="card-title">จำนวนการจองที่ถูกปฏิเสธ</h5>
                <p class="card-text"><?php echo $rejectedBookings; ?></p>
            </div>
        </div>
    </div>

</div>
<?php include '../layouts/footer.php';?>