<?php
session_start();
include '../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>สมัครสมาชิก</h2>
        <form action="../../controllers/auth_controller.php" method="post">
            <div class="mb-3">
                <label for="username" class="form-label">ชื่อผู้ใช้</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">รหัสผ่าน</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="first_name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
            </div>
             <div class="mb-3">
                <label for="last_name" class="form-label">นามสกุล</label>
                <input type="text" class="form-control" id="last_name" name="last_name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                <input type="text" class="form-control" id="phone" name="phone">
            </div>
            <button type="submit" class="btn btn-primary" name="register">สมัครสมาชิก</button>
             <p class="mt-2">มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
        </form>
    </div>
</div>
<?php
include '../layouts/footer.php';
?>