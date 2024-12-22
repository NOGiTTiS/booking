<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../models/Equipment.php';

$equipmentModel = new Equipment($pdo);
$equipmentId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$equipmentId) {
    header('Location: list.php');
    exit();
}
$equipment = $equipmentModel->getEquipmentById($equipmentId);
if (!$equipment) {
    header('Location: list.php');
    exit();
}
?>
<h2>แก้ไขอุปกรณ์</h2>
<form action="../../controllers/equipment_controller.php" method="post">
    <input type="hidden" name="id" value="<?php echo $equipment['id']; ?>">
    <div class="mb-3">
        <label for="name" class="form-label">ชื่ออุปกรณ์</label>
        <input type="text" class="form-control" id="name" name="name" value="<?php echo $equipment['name']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">รายละเอียด</label>
        <textarea class="form-control" id="description" name="description"><?php echo $equipment['description']; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary" name="edit">บันทึก</button>
</form>
<?php include '../layouts/footer.php';?>