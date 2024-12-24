<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
?>
<h2>เพิ่มห้องประชุม</h2>
<form action="../../controllers/room_controller.php" method="post">
    <div class="mb-3">
       <label for="name" class="form-label">ชื่อห้องประชุม</label>
       <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
       <label for="capacity" class="form-label">ความจุ</label>
      <input type="number" class="form-control" id="capacity" name="capacity" required>
    </div>
     <div class="mb-3">
         <label for="description" class="form-label">รายละเอียด</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
     <div class="mb-3">
         <label for="color" class="form-label">สี</label>
         <input type="color" class="form-control" id="color" name="color" value="">
    </div>
    <button type="submit" class="btn btn-primary" name="create">เพิ่มห้องประชุม</button>
</form>
<?php include '../layouts/footer.php';?>