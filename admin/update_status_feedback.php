<?php
require_once '../config/connection.php';
require 'vendor/autoload.php'; // Composer autoload for PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['status'])) {
    $feedback_id = intval($_POST['user_id']); // This is the feedback ID
    $status = intval($_POST['status']);

    // Update feedback status
    $sql = "UPDATE feedback SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $feedback_id);

    if ($stmt->execute()) {

        // Fetch user ID from feedback
        $feedback_query = "SELECT f.subject, f.message, u.name, u.email 
                           FROM feedback f 
                           JOIN user u ON f.user_id = u.id 
                           WHERE f.id = ?";
        $feedback_stmt = $conn->prepare($feedback_query);
        $feedback_stmt->bind_param("i", $feedback_id);
        $feedback_stmt->execute();
        $feedback_result = $feedback_stmt->get_result();

        if ($feedback_result && $feedback_result->num_rows > 0) {
            $row = $feedback_result->fetch_assoc();
            $name = $row['name'];
            $email = $row['email'];
            $subject = $row['subject'];
            $message = $row['message'];

            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'nagarctservices@gmail.com'; // Your Gmail
                $mail->Password = 'xjoa yrzu odbc nezg';        // App Password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');
                $mail->addAddress($email, $name);

                // Email content
                $mail->isHTML(true);
                $mail->Subject = "Feedback Status Update - NagarYatra";

                
                $mail->Body = "
                            <div style='font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px;'>
                                <h2 style='color: #2c3e50;'>Hello, $name! 👋</h2>
                                <p>We sincerely thank you for your feedback shared with <strong>NagarYatra</strong>.</p>
                                <p><b>Feedback Subject:</b> $subject</p>
                                <p><b>Your Message:</b> $message</p>
                                <p><b>Status:</b> <span style='color: #092448; font-weight: bold;'>Reviewed</span></p>
                                <p>Your feedback has been <strong style='color: #28a745;'>reviewed and updated</strong> in our system. We highly appreciate your effort in helping us improve our services at <strong>NagarYatra</strong>.</p>
                                <p>If you have any additional thoughts or concerns, feel free to reply to this email. Your voice matters to us!</p>
                                <p style='color: #e67e22;'><i>We truly value your input and will use it to improve our services.</i></p>
                                <p>Feel free to share more thoughts or reach out for support anytime!</p>
                                <hr style='border: none; border-top: 1px solid #ddd; margin-top: 30px;'>
                                <p style='color: #333;'><b>Best Regards,</b><br>
                                NagarYatra Team<br>
                                <a href='https://www.NagarYatra.com' style='color: #3498db; text-decoration: none;'>www.NagarYatra.com</a></p>
                            </div>
                        ";


                $mail->AltBody = "Dear $name, your feedback regarding '$subject' has been updated. Thank you for helping NagarYatra improve.";

                $mail->send();
                // Email sent successfully
            } catch (Exception $e) {
                echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        header("Location: feedbacks.php");
        exit();
    } else {
        echo "Error updating feedback status: " . $conn->error;
    }

    $stmt->close();
}
?>