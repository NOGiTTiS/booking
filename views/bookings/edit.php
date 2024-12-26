<?php
session_start();
include '../layouts/header.php';
require_once '../../config/database.php';
require_once '../../models/Booking.php';
require_once '../../models/Room.php';
require_once '../../models/Equipment.php';

$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$equipmentModel = new Equipment($pdo);

$bookingId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$bookingId) {
    header('Location: list.php');
    exit();
}
$booking = $bookingModel->getBookingById($bookingId);
if (!$booking) {
    header('Location: list.php');
    exit();
}
$rooms = $roomModel->getAllRooms();
$equipments = $equipmentModel->getAllEquipments();
$selectedEquipments = [];
if (isset($booking['equipment_names'])) {
    $selectedEquipments = explode(",", $booking['equipment_names']);
}
?>
<h2>แก้ไขการจอง</h2>
<?php if ($booking['status'] === 'pending'): ?>
    <form action="../../controllers/booking_controller.php" method="post" enctype="multipart/form-data">
  <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
     <div class="mb-3">
         <label for="room_id" class="form-label">ห้องประชุม</label>
         <select class="form-select" id="room_id" name="room_id" required>
             <?php foreach ($rooms as $room): ?>
                 <option value="<?php echo $room['id']; ?>" <?php echo ($room['id'] == $booking['room_id']) ? 'selected' : ''; ?>><?php echo $room['name']; ?></option>
             <?php endforeach;?>
         </select>
     </div>
    <div class="mb-3">
         <label for="subject" class="form-label">หัวข้อการใช้งาน</label>
         <input type="text" class="form-control" id="subject" name="subject" value="<?php echo $booking['subject']; ?>" required>
     </div>
    <div class="mb-3">
        <label for="department" class="form-label">ฝ่าย/งานที่ขอใช้งาน</label>
        <input type="text" class="form-control" id="department" name="department" value="<?php echo $booking['department']; ?>" required>
    </div>
    <div class="mb-3">
         <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
         <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $booking['phone']; ?>" required>
     </div>
     <div class="mb-3">
         <label for="attendees" class="form-label">จำนวนผู้เข้าใช้</label>
         <input type="number" class="form-control" id="attendees" name="attendees" value="<?php echo $booking['attendees']; ?>" required>
     </div>
    <div class="mb-3">
         <label for="start_time" class="form-label">วันที่เริ่ม</label>
         <input type="datetime-local" class="form-control" id="start_time" name="start_time" value="<?php echo date('Y-m-d\TH:i', strtotime($booking['start_time'])); ?>" required>
    </div>
     <div class="mb-3">
         <label for="end_time" class="form-label">วันที่สิ้นสุด</label>
         <input type="datetime-local" class="form-control" id="end_time" name="end_time"  value="<?php echo date('Y-m-d\TH:i', strtotime($booking['end_time'])); ?>" required>
    </div>
     <div class="mb-3">
        <label class="form-label">อุปกรณ์</label>
         <?php foreach ($equipments as $equipment): ?>
             <div class="form-check">
                 <input type="checkbox" class="form-check-input" name="equipments[]" value="<?php echo $equipment['id']; ?>" id="equipment_<?php echo $equipment['id']; ?>" <?php echo in_array($equipment['name'], $selectedEquipments) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="equipment_<?php echo $equipment['id']; ?>"><?php echo $equipment['name']; ?></label>
            </div>
         <?php endforeach;?>
     </div>
     <div class="mb-3">
          <label for="room_layout_image" class="form-label">รูปแบบการจัดห้อง (ไฟล์รูปภาพ)</label>
           <input type="file" class="form-control" id="room_layout_image" name="room_layout_image">
     </div>
      <div class="mb-3">
           <label for="note" class="form-label">หมายเหตุ</label>
          <textarea class="form-control" id="note" name="note"><?php echo $booking['note']; ?></textarea>
     </div>
     <button type="submit" class="btn btn-primary" name="edit">บันทึก</button>
</form>
   <?php else: ?>
      <p class="alert alert-info">ไม่สามารถแก้ไขการจองได้เนื่องจากได้รับการอนุมัติหรือถูกปฏิเสธแล้ว</p>
   <?php endif;?>
<?php include '../layouts/footer.php';?>