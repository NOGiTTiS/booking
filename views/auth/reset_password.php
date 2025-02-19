<?php
    session_start();
    include '../layouts/header.php';

    $token    = isset($_GET['token']) ? $_GET['token'] : null;
    $username = isset($_GET['username']) ? $_GET['username'] : null;

    if (! $token || ! $username) {
        echo "Invalid token or username.";
        exit();
    }
?>

<div class="row justify-content-center">
    <div class="col-md-6">
    <div class="d-flex">
        <h2>ตั้งรหัสผ่านใหม่สำหรับ :&nbsp;</h2>
        <h2 class="text-danger"><?php echo htmlspecialchars(urldecode($username)); ?></h2>
    </div>
        <form action="../../controllers/auth_controller.php" method="post">
            <input type="hidden" name="token" value="<?php echo $token; ?>">
             <div class="mb-3">
                <label for="new_password" class="form-label">รหัสผ่านใหม่</label>
                 <input type="password" class="form-control" id="new_password" name="new_password" required>
             </div>
             <div class="mb-3">
                <label for="confirm_password" class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
             </div>
             <button type="submit" class="btn btn-primary" name="reset_password">ตั้งรหัสผ่านใหม่</button>
         </form>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>