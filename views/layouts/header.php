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
     <link rel="icon" href="/booking/assets/img/logo.png" type="image/png">
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css">

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
            background-color: #f8d7e9;
            font-family: 'Prompt', sans-serif; /* Apply Prompt Font to body */
        }

       .navbar-dark .navbar-nav .nav-link {
            color: #000; /* เปลี่ยนสีข้อความเป็นสีดำ */
       }

       .navbar-brand {
            color: #000; /* เปลี่ยนสีชื่อแบรนด์เป็นสีดำ */
       }

       .navbar-dark .navbar-nav .nav-link:hover {
        color: #fff; /* Darker Pink on Hover */
       }

       .dropdown-menu {
        background-color: #f8d7e9;
       }

       #cal{
        background-color: #fff;
       }

       #ft{
        background-color: #ddd;
       }
       </style>


 </head>
 
 <body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    <nav class="navbar navbar-expand-lg navbar-dark " style="background-color: #FF69B4;">
      <div class="container">
        <a class="navbar-brand" href="/booking/index.php">
          <img src="/booking/assets/img/logo.png" alt="Logo"  class="d-inline-block align-top me-2">
          ระบบจองห้องประชุม
          </a>
         <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
             <span class="navbar-toggler-icon"></span>
          </button>
         <div class="collapse navbar-collapse" id="navbarNav">
              <ul class="navbar-nav ms-auto">
                 <?php if (isset($_SESSION['user_id'])): ?>
                     <li class="nav-item"><a class="nav-link" href="/booking/index.php">หน้าแรก</a></li>
                     <li class="nav-item"><a class="nav-link" href="/booking/views/bookings/list.php">รายการจอง</a></li>
                     <li class="nav-item"><a class="nav-link" href="/booking/views/users/profile.php">แก้ไขข้อมูลส่วนตัว</a></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item dropdown">
                               <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   รายงาน
                               </a>
                               <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/reports/booking_report.php">รายงานห้องประชุม</a></li>
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/reports/user_booking_report.php">รายงานผู้ใช้งาน</a></li>
                               </ul>
                         </li>
                        <li class="nav-item dropdown">
                               <a class="nav-link dropdown-toggle" href="#" id="reportDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   จัดการระบบ
                               </a>
                               <ul class="dropdown-menu" aria-labelledby="reportDropdown">
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/admin/dashboard.php">แดชบอร์ด</a></li>
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/rooms/list.php">ห้องประชุม</a></li>
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/equipments/list.php">อุปกรณ์</a></li>
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/admin/user_management.php">จัดการผู้ใช้งาน</a></li>
                                  <li class="nav-item"><a class="nav-link" href="/booking/views/admin/settings.php">ตั้งค่าระบบ</a></li>
                               </ul>
                      <?php endif; ?>

                     <li class="nav-item"><a class="nav-link" href="/booking/controllers/auth_controller.php?logout=true">ออกจากระบบ</a></li>
                 <?php else: ?>
                    </li>
                      <li class="nav-item"><a class="nav-link" href="/booking/views/auth/login.php">เข้าสู่ระบบ</a></li>
                      <li class="nav-item"><a class="nav-link" href="/booking/views/auth/register.php">สมัครสมาชิก</a></li>
                 <?php endif; ?>
             </ul>
         </div>
      </div>
    </nav>

    <div class="container mt-4">
