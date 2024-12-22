<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
?>

<h2>เพิ่มอุปกรณ์</h2>
<form action="../../controllers/equipment_controller.php" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">ชื่ออุปกรณ์</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">รายละเอียด</label>
        <textarea class="form-control" id="description" name="description"></textarea>
    </div>
    <button type="submit" class="btn btn-primary" name="create">เพิ่มอุปกรณ์</button>
</form>

<?php include '../layouts/footer.php';?>