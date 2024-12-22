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
$equipments = $equipmentModel->getAllEquipments();
?>

<h2>จัดการอุปกรณ์</h2>
<a href="create.php" class="btn btn-primary mb-3">เพิ่มอุปกรณ์</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ชื่ออุปกรณ์</th>
            <th>รายละเอียด</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($equipments as $equipment): ?>
            <tr>
                <td><?php echo $equipment['name']; ?></td>
                <td><?php echo $equipment['description']; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $equipment['id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                    <a href="../../controllers/equipment_controller.php?delete=<?php echo $equipment['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบอุปกรณ์นี้หรือไม่?')">ลบ</a>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?php include '../layouts/footer.php';?>