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

<h2>แก้ไขข้อมูลส่วนตัว</h2>
 <form action="../../controllers/auth_controller.php" method="post">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <div class="mb-3">
        <label for="username" class="form-label">ชื่อผู้ใช้</label>
        <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="first_name" class="form-label">ชื่อ</label>
        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $user['first_name']; ?>" required>
    </div>
     <div class="mb-3">
        <label for="last_name" class="form-label">นามสกุล</label>
         <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $user['last_name']; ?>" required>
     </div>
   <div class="mb-3">
        <label for="email" class="form-label">อีเมล</label>
       <input type="email" class="form-control" id="email" name="email"  value="<?php echo $user['email']; ?>" required>
    </div>
     <div class="mb-3">
         <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
         <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
     </div>
    <button type="submit" class="btn btn-primary" name="edit_profile">บันทึก</button>
     <a href="change_password.php" class="btn btn-secondary">เปลี่ยนรหัสผ่าน</a>
</form>
<?php include '../layouts/footer.php';?>