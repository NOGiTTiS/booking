<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../models/Booking.php';
require_once '../../models/User.php';

$bookingModel = new Booking($pdo);
$userModel = new User($pdo);
$users = $userModel->getAllUsers();
$selectedUser = isset($_GET['user_id']) ? $_GET['user_id'] : '';
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$where = [];
if ($startDate && $endDate) {
    $where = ['start_time >=' => $startDate, 'end_time <=' => $endDate];
}
if ($selectedUser) {
    $where = array_merge($where, ['user_id' => $selectedUser]);
}

$bookings = $bookingModel->getAllBookings($where);
$bookingSummary = $bookingModel->getBookingSummaryByUser($where);

function getStatusLabel($status)
{
    switch ($status) {
        case 'pending':
            return '<span class="badge bg-warning">รออนุมัติ</span>';
        case 'approved':
            return '<span class="badge bg-success">อนุมัติ</span>';
        case 'rejected':
            return '<span class="badge bg-danger">ไม่อนุมัติ</span>';
        default:
            return '';
    }
}
?>
<h2>รายงานการจองห้องประชุมของผู้ใช้งาน</h2>
  <form method="GET">
    <div class="row mb-3">
        <div class="col-md-3">
            <label for="user_id" class="form-label">ผู้ใช้งาน</label>
             <select class="form-select" id="user_id" name="user_id">
                 <option value="">ทั้งหมด</option>
                <?php foreach ($users as $user): ?>
                    <option value="<?php echo $user['id']; ?>" <?php echo ($user['id'] == $selectedUser) ? 'selected' : ''; ?>><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></option>
               <?php endforeach;?>
           </select>
       </div>
       <div class="col-md-3">
          <label for="start_date" class="form-label">วันที่เริ่มต้น</label>
          <input type="date" class="form-control" id="start_date" name="start_date" value="<?php echo $startDate; ?>">
        </div>
       <div class="col-md-3">
           <label for="end_date" class="form-label">วันที่สิ้นสุด</label>
           <input type="date" class="form-control" id="end_date" name="end_date" value="<?php echo $endDate; ?>">
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary mt-4">ค้นหา</button>
       </div>
    </div>
 </form>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>หัวข้อ</th>
            <th>ห้องประชุม</th>
             <th>ผู้จอง</th>
             <th>ฝ่าย</th>
            <th>เบอร์โทร</th>
            <th>จำนวนผู้เข้าใช้</th>
            <th>เวลาเริ่ม</th>
             <th>เวลาสิ้นสุด</th>
            <th>อุปกรณ์</th>
            <th>หมายเหตุ</th>
            <th>สถานะ</th>
       </tr>
   </thead>
    <tbody>
       <?php if (count($bookings) > 0): ?>
           <?php foreach ($bookings as $booking): ?>
             <tr>
                    <td><?php echo $booking['subject']; ?></td>
                     <td><?php echo $booking['room_name']; ?></td>
                    <td><?php echo $booking['user_first_name'] . " " . $booking['user_last_name']; ?></td>
                    <td><?php echo $booking['department']; ?></td>
                    <td><?php echo $booking['phone']; ?></td>
                     <td><?php echo $booking['attendees']; ?></td>
                   <td><?php echo date('d/m/Y H:i', strtotime($booking['start_time'])); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($booking['end_time'])); ?></td>
                     <td><?php echo $booking['equipment_names']; ?></td>
                     <td><?php echo $booking['note']; ?></td>
                     <td><?php echo getStatusLabel($booking['status']); ?></td>
                </tr>
            <?php endforeach;?>
      <?php else: ?>
            <tr><td colspan="11" class="text-center">ไม่พบข้อมูล</td></tr>
        <?php endif;?>
    </tbody>
</table>
<h3 class="mt-4">สรุปการจองใช้งานของผู้ใช้ (อนุมัติแล้วเท่านั้น)</h3>
<table class="table table-bordered">
    <thead>
       <tr>
            <th>ผู้ใช้งาน</th>
            <th>จำนวนครั้ง</th>
      </tr>
  </thead>
    <tbody>
        <?php foreach ($bookingSummary as $summary): ?>
             <tr>
                 <td><?php echo $summary['user_first_name'] . ' ' . $summary['user_last_name']; ?></td>
                  <td><?php echo $summary['booking_count']; ?></td>
             </tr>
         <?php endforeach;?>
   </tbody>
</table>
<?php include '../layouts/footer.php';?>