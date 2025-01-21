<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
require_once '../../config/database.php';
require_once '../../models/User.php';
$userModel = new User($pdo);
$user = $userModel->getUserById($_SESSION['user_id']);
if (!$user) {
    header('Location: ../auth/login.php');
    exit();
}
?>

<h2>เปลี่ยนรหัสผ่าน</h2>
<form action="../../controllers/auth_controller.php" method="post">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <div class="mb-3">
        <label for="current_password" class="form-label">รหัสผ่านปัจจุบัน</label>
        <input type="password" class="form-control" id="current_password" name="current_password" required>
    </div>
    <div class="mb-3">
        <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
        <input type="password" class="form-control" id="new_password" name="new_password" required>
    </div>
   <div class="mb-3">
        <label for="confirm_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
       <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    <button type="submit" class="btn btn-primary" name="change_password">เปลี่ยนรหัสผ่าน</button>
</form>
<?php include '../layouts/footer.php';?>