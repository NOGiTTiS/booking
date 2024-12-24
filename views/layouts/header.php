<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>ระบบจองห้องประชุม</title>
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
     <link rel="stylesheet" href="../assets/css/style.css">
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
     <link href="https://fonts.googleapis.com/css2?family=Prompt:wght@300;400;500;600;700&display=swap" rel="stylesheet">
     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>

     <style>
       .navbar-brand img {
          max-height: 35px; /* Adjust the height of the logo as needed */
          border-radius: 50%; /* Make logo round */
          background-color: #fff; /* Set background color to white */
          padding: 3px; /* Add padding around logo */
       }

       body {
           display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Prompt', sans-serif; /* Apply Prompt Font to body */
        }

       .navbar-dark .navbar-nav .nav-link {
            color: #000; /* เปลี่ยนสีข้อความเป็นสีดำ */
          }
         .navbar-brand {
            color: #000; /* เปลี่ยนสีชื่อแบรนด์เป็นสีดำ */
       }
       </style>
 </head>
 <body>

  <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #FF69B4;">
      <div class="container">
        <a class="navbar-brand" href="/index.php">
          <img src="/assets/img/logo.png" alt="Logo"  class="d-inline-block align-top me-2">
          ระบบจองห้องประชุม
          </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
          </button>
         <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav ms-auto">
                 <?php if (isset($_SESSION['user_id'])): ?>
                     <li class="nav-item"><a class="nav-link" href="/index.php">หน้าแรก</a></li>
                     <li class="nav-item"><a class="nav-link" href="/views/bookings/list.php">รายการจอง</a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                         <li class="nav-item"><a class="nav-link" href="/views/rooms/list.php">ห้องประชุม</a></li>
                         <li class="nav-item"><a class="nav-link" href="/views/equipments/list.php">อุปกรณ์</a></li>
                          <li class="nav-item"><a class="nav-link" href="/views/admin/dashboard.php">แดชบอร์ด</a></li>
                          <li class="nav-item dropdown">
                               <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   รายงาน
                               </a>
                               <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                                    <li><a class="dropdown-item" href="/views/reports/booking_report.php">รายงานห้องประชุม</a></li>
                                    <li><a class="dropdown-item" href="/views/reports/user_booking_report.php">รายงานผู้ใช้งาน</a></li>
                                </ul>
                         </li>
                      <?php endif;?>
                     <li class="nav-item"><a class="nav-link" href="/controllers/auth_controller.php?logout=true">ออกจากระบบ</a></li>
                 <?php else: ?>
                      <li class="nav-item"><a class="nav-link" href="/views/auth/login.php">เข้าสู่ระบบ</a></li>
                      <li class="nav-item"><a class="nav-link" href="/views/auth/register.php">สมัครสมาชิก</a></li>
                 <?php endif;?>
             </ul>
         </div>
      </div>
  </nav>
  <div class="container mt-4">
     <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success"><?php echo $_SESSION['success_message'];unset($_SESSION['success_message']); ?></div>
       <?php endif;?>
         <?php if (isset($_SESSION['error_message'])): ?>
             <div class="alert alert-danger"><?php echo $_SESSION['error_message'];unset($_SESSION['error_message']); ?></div>
       <?php endif;?>