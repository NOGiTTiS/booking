<?php
session_start();
include '../layouts/header.php';
require_once '../../config/database.php';
require_once '../../models/Booking.php';
  require_once '../../models/Room.php';

$bookingModel = new Booking($pdo);
$roomModel = new Room($pdo);
$bookings = $bookingModel->getAllBookings(['status' => 'approved']);
$rooms = $roomModel->getAllRooms();
$roomColors = [];
  foreach($rooms as $room){
        $roomColors[$room['id']] = $room['color'];
  }
$events = [];

foreach ($bookings as $booking) {
    $events[] = [
       'id' => $booking['id'],
        'title' => $booking['subject'] . ' ('.$booking['room_name'].')',
         'start' => date('Y-m-d\TH:i:s', strtotime($booking['start_time'])),
       'end' => date('Y-m-d\TH:i:s', strtotime($booking['end_time'])),
      'color' => isset($roomColors[$booking['room_id']]) ? $roomColors[$booking['room_id']] : '#378006',
     ];
}
?>

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
url: '../../get_booking_details.php',
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
'<p><strong>ห้องประชุม:</strong> ' + data.room_name + '</p>' +
'<p><strong>ผู้จอง:</strong> ' + data.user_first_name + ' ' + data.user_last_name + '</p>' +
'<p><strong>ฝ่าย:</strong> ' + data.department + '</p>' +
'<p><strong>เบอร์โทร:</strong> ' + data.phone + '</p>' +
'<p><strong>จำนวนผู้เข้าใช้:</strong> ' + data.attendees + '</p>' +
'<p><strong>เวลาเริ่ม:</strong> ' + moment(data.start_time).format('DD/MM/YYYY HH:mm') + '</p>' +
'<p><strong>เวลาสิ้นสุด:</strong> ' + moment(data.end_time).format('DD/MM/YYYY HH:mm') + '</p>' +
'<p><strong>อุปกรณ์:</strong> ' + (data.equipment_names ? data.equipment_names : 'ไม่มี') + '</p>' +
'<p><strong>หมายเหตุ:</strong> ' + (data.note ? data.note : 'ไม่มี') + '</p>' +
'<p><strong>รูปแบบการจัดห้อง:</strong>' + (data.room_layout_image ? '<br><img src="' + data.room_layout_image + '" style="max-width: 200px; max-height: 200px;">' : 'ไม่มี') + '</p>' +
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
 <?php include '../layouts/footer.php'; ?>
