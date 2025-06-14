<?php
$current_page = 'forget';
session_start();
require 'config/connection.php'; // Ensure this uses MySQLi connection
$title = "NagarYatra | Forget Password ";
include_once 'register_login_header.php';



require 'vendor/autoload.php'; // Include PHPMailer if using Composer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);

    // Check if email exists in the database
    $stmt = $conn->prepare("SELECT id, name FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if ($user) {
        $user_id = $user['id'];
        $name = $user['name']; // Get user's name from DB

        // Get current date and time
        $time = date("F j, Y, g:i a");

        // Get user agent
        $user_agent = $_SERVER['HTTP_USER_AGENT'];

        // Parse device/platform
        $platform = "Unknown OS";
        if (preg_match('/linux/i', $user_agent)) {
            $platform = "Linux";
        } elseif (preg_match('/macintosh|mac os x/i', $user_agent)) {
            $platform = "Mac";
        } elseif (preg_match('/windows|win32/i', $user_agent)) {
            $platform = "Windows";
        }

        // Parse browser
        $browser = "Unknown Browser";
        if (preg_match('/MSIE/i', $user_agent) || preg_match('/Trident/i', $user_agent)) {
            $browser = "Internet Explorer";
        } elseif (preg_match('/Firefox/i', $user_agent)) {
            $browser = "Firefox";
        } elseif (preg_match('/Chrome/i', $user_agent)) {
            $browser = "Chrome";
        } elseif (preg_match('/Safari/i', $user_agent)) {
            $browser = "Safari";
        } elseif (preg_match('/Opera/i', $user_agent)) {
            $browser = "Opera";
        }

        // Combine device info
        $device = "$platform $browser Browser";

        // Compose the message
        $message = "Dear {$name}, your password has been reset successfully on {$time} from the device: {$device}. If this wasn't you, please contact support immediately.";

        // Escape the message
        $escaped_message = mysqli_real_escape_string($conn, $message);

        // Insert notification
        if ($user_id !== NULL) {
            $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$escaped_message')";
        } else {
            $sql = "INSERT INTO notifications (user_id, message) VALUES (NULL, '$escaped_message')";
        }

        if (!mysqli_query($conn, $sql)) {
            echo "Error: " . mysqli_error($conn);
        }



        // Generate a 8 digit Alphanumeric password for user.
        $password = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz', 8)), 0, 8);
        $newpassword = password_hash($password, PASSWORD_DEFAULT);
        // Store password in the database
        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $newpassword, $email);
        $stmt->execute();
        $stmt->close();

        $_SESSION['forget_password'] = "Your Password has been reset successful.";

        // Email Sending Code - PHPMailer
        $mail = new PHPMailer(true);

        try {
            // SMTP Configuration
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nagarctservices@gmail.com';
            $mail->Password = 'xjoa yrzu odbc nezg'; // Ensure this is a secure App Password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Email Details
            $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');
            $mail->addAddress($email, $name);
            $mail->isHTML(true);
            $mail->Subject = "Reset Your Password - NagarYatra password Verification";

            $mail->Body = "
                            <div style='font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; background-color: #ffffff;'>
                                <h2 style='color: #2c3e50;'>Password Reset Requested, $name</h2>
                                
                                <p>We received a request to reset the password for your <strong>NagarYatra</strong> account.</p>
                                
                                <p style='margin-top: 15px;'>Your newly generated temporary password is:</p>
                                
                                <div style='text-align: center; font-size: 22px; font-weight: bold; background: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px dashed #ccc;'>
                                    <span style='color: #e74c3c;'>$password</span>
                                </div>

                                <p>Please use this password to log in and reset your password immediately.                                
                                <p>If you did not initiate this request, please disregard this message or contact our support team right away for assistance.</p>

                                <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>

                                <p style='color: #333;'><strong>Warm regards,</strong><br>
                                The NagarYatra Support Team<br>
                                <a href='https://www.NagarYatra.com' style='color: #3498db; text-decoration: none;'>www.NagarYatra.com</a></p>
                            </div>
                        ";

            $mail->AltBody = "Dear $name, you have requested to reset your NagarYatra password. Your temporary password is: $password. It will expire in 2 minutes. If you did not request this, please ignore this email or contact support.";

            $mail->send();
            $_SESSION['forget_password'];
            header("Location: login.php");


        } catch (Exception $e) {
            $_SESSION['error'] = "Email could not be sent. Error: " . $mail->ErrorInfo;
        }
    } else {
        $_SESSION['error'] = "Email not found! Please Enter the valid Email Address.";
    }
}
?>

<form action="" method="POST" class="sign-in-form">
    <div class="first">
        <h2 class="title" style="font-size: 2rem !important;">Forget Password</h2>
        <div class="input-field">
            <i class="fas fa-envelope"></i>
            <input type="email" placeholder="Enter your email" name="email"
                value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required />
        </div>
        <?php
        if (isset($errors['email'])) {
            ?>
            <div class="error">
                <small class="error"><?= $errors['email'] ?? '' ?></small>
            </div>
            <?php
        }
        ?>
        <div style="display: flex;justify-content:center;gap:10px;color:green;">
            <p class="terms"><small>The 8 digits password will be sent to your verified Email address.</small></p>
        </div>
        <button type="submit" class="btn solid">
            Send password <i class="fas fa-arrow-right"></i>
        </button>


        <?php
        include_once 'register_login_footer.php';
        ?>