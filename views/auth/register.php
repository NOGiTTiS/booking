<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ระบบจองห้องประชุม - สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" href="/booking/assets/img/logo.png" type="image/png">
<style>
    body {
        background-color: #f8e8ee;
         display: flex;
        align-items: center;
         justify-content: center;
        min-height: 100vh;
        font-family: 'Prompt', sans-serif;
     }
     .login-container {
        background-color: #fff;
        border-radius: 15px;
         padding: 40px;
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        width: 800px;
         max-width: 95%;
        display: flex;
          transition: transform 0.3s ease-in-out;
    }
     .login-container:hover {
        transform: scale(1.02);
    }
    .login-container .icon-container {
         flex: 0 0 30%;
        text-align: center;
        padding: 20px;
         border-right: 1px solid #ddd;
       display: flex;
        align-items: center;
        justify-content: center;
     }
    .login-container .icon-container img {
         max-width: 200px;
        height: auto;
         display: block;
    }
    .login-container .form-container {
        flex: 0 0 70%;
        padding: 20px;
     }
     .login-container h2 {
         text-align: center;
         color: #333;
       margin-bottom: 30px;
        font-weight: 700;
        letter-spacing: 1px;
         text-transform: uppercase;
   }
    .form-group {
         margin-bottom: 20px;
    }
   .form-label {
      font-weight: 600;
        color: #555;
       margin-bottom: 7px;
        display: block;
    }
     .form-control {
       border-radius: 10px;
       border: 1px solid #ddd;
       padding: 14px;
       transition: border-color 0.3s ease;
    }
    .form-control:focus {
       border-color: #e91e63;
        outline: none;
       box-shadow: 0 0 0 0.25rem rgba(233, 30, 99, 0.25);
     }
    .btn-primary {
        background-color: #e91e63; /* Pink Button */
        border-color: #e91e63;
       border-radius: 10px;
       padding: 14px 25px;
         transition: background-color 0.3s ease;
         font-weight: 600;
         letter-spacing: 0.7px;
       width: 100%;
   }
     .btn-primary:hover {
         background-color: #c2185b; /* Darker Pink on Hover */
        border-color: #c2185b;
     }
    .remember-me {
         margin-top: 20px;
         display: flex;
        align-items: center;
    }
    .remember-me input[type="checkbox"] {
         margin-right: 7px;
     }
     .forgot-password {
        text-align: center;
        margin-top: 25px;
     }
     .forgot-password a {
         color: #e91e63;
         text-decoration: none;
   }
    .forgot-password a:hover {
        text-decoration: underline;
   }
   .adventure-logo {
       text-align: center;
       color: #e91e63;
   }
    .login-link {
       text-align: center;
         margin-top: 15px;
     }
     .login-link a {
         color: #e91e63;
        text-decoration: none;
     }
   .login-link a:hover {
         text-decoration: underline;
   }
/* Media queries for responsiveness */
    @media (max-width: 768px) {
        .login-container {
            flex-direction: column; /* Stack logo and form on small screens */
           padding: 20px;
       }
      .login-container .icon-container {
          border-right: none;
           padding-bottom: 15px;
       }
        .login-container .form-container {
             padding-top: 15px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

<div class="login-container">
    <div class="icon-container">
        <img src="/booking/assets/img/logo.png" alt="Logo">
   </div>
   <div class="form-container">
     <h2>สมัครสมาชิก</h2>
         <form action="../../controllers/auth_controller.php" method="post">
             <div class="form-group">
                 <label for="username" class="form-label">Username</label>
                 <input type="text" class="form-control" id="username" name="username" required>
             </div>
             <div class="form-group">
                 <label for="password" class="form-label">Password</label>
                 <input type="password" class="form-control" id="password" name="password" required>
             </div>
            <div class="form-group">
                <label for="first_name" class="form-label">ชื่อ</label>
                <input type="text" class="form-control" id="first_name" name="first_name" required>
             </div>
            <div class="form-group">
               <label for="last_name" class="form-label">นามสกุล</label>
               <input type="text" class="form-control" id="last_name" name="last_name" required>
             </div>
             <div class="form-group">
                 <label for="email" class="form-label">อีเมล</label>
                 <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone" class="form-label">เบอร์โทรศัพท์</label>
                <input type="text" class="form-control" id="phone" name="phone" minlength="10" maxlength="10" pattern="[0-9]{10}" title="กรุณาใส่เบอร์โทรศัพท์ 10 หลัก" required>
             </div>
            <button type="submit" class="btn btn-primary" name="register">สมัครสมาชิก</button>
           <div class="login-link">
                 <a href="/booking/views/auth/login.php">มีบัญชีอยู่แล้ว? เข้าสู่ระบบ</a>
            </div>
         </form>
   </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ!',
               text: "<?php echo $_SESSION['success_message']; ?>",
                timer: 2500,
               showConfirmButton: false
            });
        </script>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
       <script>
            Swal.fire({
                icon: 'error',
                 title: 'เกิดข้อผิดพลาด!',
                text: "<?php echo $_SESSION['error_message']; ?>",
             });
        </script>
     <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

</body>
</html>
