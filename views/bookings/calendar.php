<?php
session_start();
include '../layouts/header.php';
require_once '../../config/database.php';
require_once '../../models/Booking.php';

$bookingModel = new Booking($pdo);
$bookings = $bookingModel->getAllBookings(['status' => 'approved']);
$events = [];

foreach ($bookings as $booking) {
    $events[] = [
        'title' => $booking['subject'] . ' (' . $booking['room_name'] . ')',
        'start' => $booking['start_time'],
        'end' => $booking['end_time'],
    ];
}
?>

<h2>ปฏิทินการจองห้องประชุม</h2>
<div id="calendar"></div>
 <script>
   //console.log(<?php echo json_encode($events); ?>);
   $(document).ready(function() {
       $('#calendar').fullCalendar({
           header: {
               left: 'prev,next today',
               center: 'title',
               right: 'month,agendaWeek,agendaDay'
           },
           events: <?php echo json_encode($events); ?>,
             eventColor: '#378006',
       });
   });
</script>

<?php include '../layouts/footer.php';?>