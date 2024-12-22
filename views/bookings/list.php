<?php
session_start();
include '../layouts/header.php';
require_once '../../config/database.php';
require_once '../../models/Booking.php';

$bookingModel = new Booking($pdo);
$where = [];
if ($_SESSION['role'] !== 'admin') {
    $where = ['user_id' => $_SESSION['user_id']];
}
$bookings = $bookingModel->getAllBookings($where);

?>

<h2>รายการจองห้องประชุม</h2>
 <?php if ($_SESSION['role'] !== 'admin'): ?>
<a href="create.php" class="btn btn-primary mb-3">จองห้องประชุม</a>
<?php endif;?>
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
             <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
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
                 <td>
                     <?php if ($booking['status'] == 'pending'): ?>
                         <span class="badge bg-warning">รออนุมัติ</span>
                     <?php elseif ($booking['status'] == 'approved'): ?>
                         <span class="badge bg-success">อนุมัติ</span>
                    <?php else: ?>
                        <span class="badge bg-danger">ไม่อนุมัติ</span>
                   <?php endif;?>
                 </td>
                 <td>
                      <?php if ($_SESSION['role'] === 'admin'): ?>
                            <a href="/meeting_room_booking/views/bookings/edit.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                           <?php if ($_SESSION['role'] === 'admin' && $booking['status'] === 'pending'): ?>
                                  <a href="/meeting_room_booking/controllers/booking_controller.php?approve=<?php echo $booking['id']; ?>" class="btn btn-sm btn-success">อนุมัติ</a>
                                 <a href="/meeting_room_booking/controllers/booking_controller.php?reject=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger">ปฏิเสธ</a>
                         <?php endif;?>
                              <a href="/meeting_room_booking/controllers/booking_controller.php?delete=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบการจองนี้หรือไม่?')">ลบ</a>
                     <?php else: ?>
                         <?php if ($booking['user_id'] === $_SESSION['user_id']): ?>
                             <a href="/meeting_room_booking/views/bookings/edit.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                           <a href="/meeting_room_booking/controllers/booking_controller.php?delete=<?php echo $booking['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบการจองนี้หรือไม่?')">ลบ</a>
                          <?php endif;?>
                    <?php endif;?>
                 </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
<?php include '../layouts/footer.php';?>