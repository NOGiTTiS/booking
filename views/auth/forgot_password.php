<?php
    session_start();
    include '../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <h2>ลืมรหัสผ่าน</h2>
         <form action="../../controllers/auth_controller.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label">อีเมล</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary" name="forgot_password">ส่งลิงก์รีเซ็ตรหัสผ่าน</button>
        </form>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>