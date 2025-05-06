<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include PHPMailer

function sendOTPEmail($email, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        // $mail->Host = 'smtp.example.com';
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'nagarctservices@gmail.com'; 
        $mail->Password = 'NagarYatra123'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
    
        $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');
        $mail->addAddress($email);
    
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "Your OTP code is <b>$otp</b>. It will expire in 2 minutes.";
        $mail->AltBody = "Your OTP code is $otp. It will expire in 2 minutes.";
    
        $mail->send();
    } catch (Exception $e) {
        error_log("Mailer Error: " . $mail->ErrorInfo);
    }
    
}
?>
