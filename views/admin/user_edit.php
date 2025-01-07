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
$userId = isset($_GET['id']) ? $_GET['id'] : null;
if (!$userId) {
    header('Location: user_management.php');
    exit();
}
$user = $userModel->getUserById($userId);
if (!$user) {
    header('Location: user_management.php');
    exit();
}

?>
<h2>แก้ไขผู้ใช้งาน</h2>
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
        <input type="text" class="form-control" id="last_name" name="last_name"  value="<?php echo $user['last_name']; ?>" required>
  </div>
     <div class="mb-3">
         <label for="email" class="form-label">อีเมล</label>
        <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
   </div>
     <div class="mb-3">
        <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo $user['phone']; ?>">
    </div>
    <div class="mb-3">
        <label for="role" class="form-label">สิทธิ์</label>
       <select class="form-select" id="role" name="role">
             <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>User</option>
             <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
        </select>
    </div>
     <button type="submit" class="btn btn-primary" name="edit_user">บันทึก</button>
</form>

<?php include '../layouts/footer.php';?>