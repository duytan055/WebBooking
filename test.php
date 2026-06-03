<?php

require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'tann14195@gmail.com';
    $mail->Password   = 'dsgc wqzl gklo pipe';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    $mail->setFrom('tann14195@gmail.com', 'WebBooking');
    $mail->addAddress('tuantran12345689a@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'Gui mail thanh cong!';

    $mail->send();
    echo 'Email sent!';
} catch (Exception $e) {
    echo "Error: {$mail->ErrorInfo}";
}
