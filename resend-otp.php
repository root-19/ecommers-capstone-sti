<?php
session_start();
include 'components/connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if(isset($_SESSION['user_email'])){
    sendOTP($_SESSION['user_email']);
    header("Location: otp_verify.php");
    exit();
} else {
    header("Location: user_register.php");
    exit();
}

function sendOTP($email) {
    $otp = rand(100000, 999999); // Generate a 6-digit OTP
    $_SESSION['otp'] = $otp; // Store OTP in session
    $_SESSION['otp_expiry'] = time() + 300; // OTP is valid for 5 minutes

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wasieacuna@gmail.com';
        $mail->Password   = 'qipc vais smfq rwim';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('your-email@example.com', 'Your Website Name');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body    = 'Your OTP code is: ' . $otp;

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
