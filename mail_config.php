<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

function sendVerificationEmail($email, $token) {
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rakibulislamrifatt@gmail.com';     // your Gmail
        $mail->Password   = 'qsxz phfw ylbt gmlr';        // your Gmail app password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('rakibulislamrifatt@gmail.com', 'MME Slider');
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Verify Your Email Address';
        $mail->Body    = "
    <h2>Email Verification</h2>
    <p>Please click the link below to verify your email:</p>
    <a href='http://localhost/mme_slider/verify.php?token=$token'>Verify Email</a>
";


        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
