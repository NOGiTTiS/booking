<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../models/Room.php';

$roomModel = new Room($pdo);
$roomId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$roomId) {
    header('Location: list.php');
    exit();
}
$room = $roomModel->getRoomById($roomId);
if (!$room) {
    header('Location: list.php');
    exit();
}
?>

    <h2>แก้ไขห้องประชุม</h2>
   <form action="../../controllers/room_controller.php" method="post">
         <input type="hidden" name="id" value="<?php echo $room['id']; ?>">
        <div class="mb-3">
              <label for="name" class="form-label">ชื่อห้องประชุม</label>
             <input type="text" class="form-control" id="name" name="name" value="<?php echo $room['name']; ?>" required>
         </div>
         <div class="mb-3">
            <label for="capacity" class="form-label">ความจุ</label>
             <input type="number" class="form-control" id="capacity" name="capacity" value="<?php echo $room['capacity']; ?>" required>
         </div>
        <div class="mb-3">
            <label for="description" class="form-label">รายละเอียด</label>
             <textarea class="form-control" id="description" name="description"><?php echo $room['description']; ?></textarea>
         </div>
        <div class="mb-3">
              <label for="color" class="form-label">สี</label>
           <input type="color" class="form-control" id="color" name="color" value="<?php echo $room['color']; ?>">
         </div>
          <button type="submit" class="btn btn-primary" name="edit">บันทึก</button>
  </form>
  <?php include '../layouts/footer.php';?>