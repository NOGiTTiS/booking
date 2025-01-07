<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../models/User.php';

$userModel = new User($pdo);
$users = $userModel->getAllUsers();
?>

<h2>จัดการผู้ใช้งาน</h2>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>ชื่อผู้ใช้</th>
            <th>ชื่อ</th>
            <th>นามสกุล</th>
            <th>อีเมล</th>
            <th>เบอร์โทรศัพท์</th>
             <th>สิทธิ์</th>
             <th>จัดการ</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['first_name']; ?></td>
                <td><?php echo $user['last_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                 <td><?php echo $user['phone']; ?></td>
                   <td><?php echo $user['role']; ?></td>
                <td>
                    <a href="user_edit.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-warning">แก้ไข</a>
                    <a href="../../controllers/auth_controller.php?delete_user=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบผู้ใช้งานนี้หรือไม่?')">ลบ</a>
                </td>
            </tr>
        <?php endforeach;?>
    </tbody>
</table>
 <?php include '../layouts/footer.php';?>