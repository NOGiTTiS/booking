<?php
session_start();
include '../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>เข้าสู่ระบบ</h2>
        <form action="../../controllers/auth_controller.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="login">เข้าสู่ระบบ</button>
            <p class="mt-2">ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
        </form>
    </div>
</div>
<?php
include '../layouts/footer.php';
?>