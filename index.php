<?php
session_start();
include 'views/layouts/header.php';
require_once 'config/database.php';
require_once 'models/Booking.php';
require_once 'models/Room.php';

$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$bookings = $bookingModel->getAllBookings(['status' => 'approved']);
$rooms = $roomModel->getAllRooms();
$roomColors = [];
foreach ($rooms as $room) {
    $roomColors[$room['id']] = $room;
}
$events = [];

foreach ($bookings as $booking) {
    $events[] = [
        'id' => $booking['id'],
        'title' => $booking['subject'] . ' (' . $booking['room_name'] . ')',
        'start' => date('Y-m-d\TH:i:s', strtotime($booking['start_time'])),
        'end' => date('Y-m-d\TH:i:s', strtotime($booking['end_time'])),
        'color' => isset($roomColors[$booking['room_id']]['color']) ? $roomColors[$booking['room_id']]['color'] : '#378006', // Default color if not found
    ];
}
?>

<div class="row">
    <div class="col-md-12">
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>สวัสดี, <?php echo $_SESSION['username']; ?>!</p>
            <a href="/views/bookings/create.php" class="btn btn-primary mb-3">จองห้องประชุม</a>
        <?php else: ?>
            <p>กรุณาเข้าสู่ระบบเพื่อทำการจองห้องประชุม</p>
              <a href="/views/auth/login.php" class="btn btn-primary mb-3">เข้าสู่ระบบ</a>
             <a href="/views/auth/register.php" class="btn btn-primary mb-3">สมัครสมาชิก</a>
        <?php endif;?>
    </div>
</div>

<div class="row">
   <div class="col-md-12">
     <h2>ปฏิทินการจองห้องประชุม</h2>
      <div id="calendar"></div>
       <script>
           $(document).ready(function() {
                $('#calendar').fullCalendar({
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                   events: <?php echo json_encode($events); ?>,
                    eventClick: function(calEvent, jsEvent, view) {
                         var bookingId = calEvent.id;
                         $.ajax({
                               url: '/get_booking_details.php',
                               type: 'GET',
                               data: { id: bookingId },
                              dataType: 'json',
                             success: function(data) {
                                 if(data.error){
                                      alert('Error: ' + data.error);
                                      return;
                                 }
                                 $('#bookingModal .modal-title').text(data.subject);
                                   $('#bookingModal .modal-body').html(
                                     '<p><strong>หัวข้อ:</strong> ' + data.subject + '</p>' +
                                       '<p><strong>ห้องประชุม:</strong> ' + data.room_name + '</p>' +
                                      '<p><strong>ผู้จอง:</strong> ' + data.user_first_name + ' ' + data.user_last_name + '</p>' +
                                        '<p><strong>ฝ่าย/งาน:</strong> ' + data.department + '</p>' +
                                       '<p><strong>เบอร์โทร:</strong> ' + data.phone + '</p>' +
                                        '<p><strong>จำนวนผู้เข้าใช้:</strong> ' + data.attendees + '</p>' +
                                       '<p><strong>วันที่เริ่ม:</strong> ' + moment(data.start_time).format('DD/MM/YYYY HH:mm') + '</p>' +
                                       '<p><strong>วันที่สิ้นสุด:</strong> ' + moment(data.end_time).format('DD/MM/YYYY HH:mm') + '</p>' +
                                      '<p><strong>อุปกรณ์:</strong> ' + (data.equipment_names ? data.equipment_names : 'ไม่มี') + '</p>' +
                                       '<p><strong>หมายเหตุ:</strong> ' + (data.note ? data.note : 'ไม่มี') + '</p>' +
                                        '<p><strong>สถานะ:</strong> ' + getStatusLabel(data.status) + '</p>'
                                   );
                                  $('#bookingModal').modal('show');
                              },
                             error: function(xhr, status, error) {
                                  alert('Error: ' + error);
                             }
                         });
                    }
                });
               function getStatusLabel(status){
                   switch(status) {
                       case 'pending':
                            return '<span class="badge bg-warning">รออนุมัติ</span>';
                        case 'approved':
                              return '<span class="badge bg-success">อนุมัติ</span>';
                         case 'rejected':
                            return '<span class="badge bg-danger">ไม่อนุมัติ</span>';
                         default:
                           return '';
                     }
               }
           });
    </script>
   <div class="mt-3">
        <strong>คำอธิบาย:</strong><br>
        <?php foreach ($rooms as $room): ?>
           <span class="badge" style="background-color: <?php echo $room['color']; ?>"><?php echo $room['name']; ?></span>
         <?php endforeach;?>
  </div>
  </div>
</div>
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="bookingModalLabel"></h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body"></div>
</div>
</div>
</div>
<?php include 'views/layouts/footer.php';?>