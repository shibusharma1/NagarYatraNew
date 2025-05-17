<?php
session_start();
require 'config/connection.php';
      require 'vendor/autoload.php'; // If you're using Composer for PHPMailer


if (!isset($_SESSION['email'])) {
    echo "No email session found!";
    exit;
}

$email = $_SESSION['email'];
$otp = rand(100000, 999999); // Generate new OTP
$expiry = date("Y-m-d H:i:s", strtotime("+2 minutes"));

// Update OTP and expiry in database
$stmt = $conn->prepare("UPDATE user SET otp = ?, otp_expiry = ? WHERE email = ?");
$mail = new PHPMailer\PHPMailer\PHPMailer();
      try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'nagarctservices@gmail.com';                 // SMTP username
        $mail->Password = 'xjoa yrzu odbc nezg';                    // SMTP password (Use App Password if 2FA is enabled)
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
        $mail->Port = 587;                                    // TCP port to connect to

        // Recipients
        $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');        // Sender's email and name
        $mail->addAddress($email, $name); // Add a recipient

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = "Welcome, $name! Verify Your Email with OTP";

     $mail->Body = "
    <div style='font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px;'>
        <h2 style='color: #2c3e50;'>Here's Your Resent OTP, $name! 🔁</h2>
        <p>It seems you requested a new OTP. Please use the code below to verify your email address:</p>
        
        <div style='text-align: center; font-size: 22px; font-weight: bold; background: #f3f3f3; padding: 15px; border-radius: 5px; margin: 20px 0;'>
            Your OTP Code: <span style='color: #e74c3c;'>$otp</span>
        </div>

        <p style='color: #555;'>This OTP is valid for the next <b>2 minutes</b>. Please enter it promptly to complete your verification.</p>

        <p>If you did not request this OTP, please ignore this email or contact our support team.</p>

        <hr style='border: none; border-top: 1px solid #ddd;'>

        <p style='color: #333;'><b>Best Regards,</b><br>
        NagarYatra Team<br>
        <a href='https://www.NagarYatra.com' style='color: #3498db; text-decoration: none;'>www.NagarYatra.com</a></p>
    </div>
";


        $mail->AltBody = "Welcome to NagarYatra, $name! Your Resend OTP code is $otp. It will expire in 2 minutes. If you did not request this, please ignore this email.";


        $mail->send();
        echo 'Email has been sent successfully';

      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }

      //Store OTP and email in session
      $_SESSION['otp'] = $otp;
      $_SESSION['otp_expiry'] = $otp_expiry;
      $_SESSION['email'] = $email;


$stmt->bind_param("sss", $otp, $expiry, $email);
$stmt->execute();

// Send OTP to user (email function)
mail($email, "Your OTP Code", "Your OTP is: $otp. It will expire in 2 minutes.");

// Reset attempts
$_SESSION['otp_attempts'] = 0;

echo "New OTP sent!";
header("Location: verify_otp.php");
exit;
?>

