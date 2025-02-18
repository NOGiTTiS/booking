<?php
session_start();
include '../layouts/header.php';
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../../index.php');
    exit();
}
require_once '../../config/database.php';

$stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_name = 'default_booking_status'");
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$defaultStatus = isset($result['setting_value']) ? $result['setting_value'] : 'pending';
?>

<h2>ตั้งค่าระบบ</h2>
<form action="../../controllers/admin_controller.php" method="post">
    <div class="mb-3">
        <label for="default_booking_status" class="form-label">สถานะการจองเริ่มต้น</label>
        <select class="form-select" id="default_booking_status" name="default_booking_status">
            <option value="pending" <?php echo ($defaultStatus === 'pending') ? 'selected' : ''; ?>>รออนุมัติ</option>
            <option value="approved" <?php echo ($defaultStatus === 'approved') ? 'selected' : ''; ?>>อนุมัติ</option>
            <option value="rejected" <?php echo ($defaultStatus === 'rejected') ? 'selected' : ''; ?>>ปฏิเสธ</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary" name="save_settings">บันทึก</button>
</form>
<?php include '../layouts/footer.php'; ?>