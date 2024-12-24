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
$rooms = $roomModel->getAllRooms();
?>

<h2>จัดการห้องประชุม</h2>
<a href="create.php" class="btn btn-primary mb-3">เพิ่มห้องประชุม</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ชื่อห้อง</th>
            <th>ความจุ</th>
            <th>รายละเอียด</th>
            <th>สี</th>
            <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rooms as $room): ?>
            <tr>
                <td><?php echo $room['name']; ?></td>
                <td><?php echo $room['capacity']; ?></td>
                <td><?php echo $room['description']; ?></td>
                <td><?php echo $room['color']; ?><input type="color" class="form-control" id="color" name="color" value="<?php echo $room['color']; ?>"></td>

                <td>
                    <a href="edit.php?id=<?php echo $room['id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                    <a href="../../controllers/room_controller.php?delete=<?php echo $room['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบห้องประชุมนี้หรือไม่?')">ลบ</a>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>

<?php include '../layouts/footer.php';?>