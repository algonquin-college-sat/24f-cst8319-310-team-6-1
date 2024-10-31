<?php
require '../../vendor/autoload.php'; // Include PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->CharSet = 'UTF-8';

// SMTP server settings
$mail->Host = 'smtp-mail.outlook.com'; //  SMTP server hostname
$mail->SMTPAuth = true;
$mail->Username = 'IuliiaObuk@outlook.com'; // SMTP username
$mail->Password = 'dxbqdcowseuekrft'; // SMTP password
$mail->Port = 587; // SMTP port (usually 587 for TLS)

// Email content
$mail->setFrom('IuliiaObuk@outlook.com', 'Iuliia Smith');
$mail->addAddress('eowugg@gmail.com', 'Eowug Eowug');
$mail->isHTML(true); // Set email format to HTML
$mail->Subject = 'Hello';
$mail->Body = 'This is me!';
$mail->AltBody = 'This is me!';

// Send the email
if ($mail->send()) {
    echo 'Email sent successfully!';
} else {
    echo 'Error: ' . $mail->ErrorInfo;
}
?>