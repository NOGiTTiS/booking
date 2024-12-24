<?php
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require $_SERVER['DOCUMENT_ROOT'] . '/includes/PHPMailer/src/Exception.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/PHPMailer/src/PHPMailer.php';
require $_SERVER['DOCUMENT_ROOT'] . '/includes/PHPMailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'booking.room@tn.ac.th';
    $mail->Password = 'skwd kghl xtkz cbca';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    //Recipients
    $mail->setFrom('booking.room@tn.ac.th', 'Booking');
    $mail->addAddress('sittigon.b@tn.ac.th');
    $mail->addReplyTo('booking.room@tn.ac.th', 'Booking');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email From PHPMailer';
    $mail->Body = 'This is a test email sent using PHPMailer.';
    $mail->AltBody = 'This is a plain text message.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
