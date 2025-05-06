<?php
$title = "NagarYatra | Feedback";
$current_page = "feedback";

include_once 'master_header.php';
require_once '../config/connection.php';
require '../vendor/autoload.php';

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $subject = trim($_POST['subject']);
  $message = trim($_POST['message']);
  $user_id = $_SESSION['id'];
  $is_delete = 0;

  // Fetching the user data just for name and email

  $sql = "SELECT * FROM user WHERE  id = '$user_id'";
  $sresult = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($sresult);

  if ($row) {
    $name = $row['name'];
    $email = $row['email'];

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
      // Server settings
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'nagarctservices@gmail.com'; // Your Gmail
      $mail->Password = 'gnpl gqhu pukx gmal';        // App password
      $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Recipients
      $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');
      $mail->addAddress($email, $name); // User's email

      // Content
      $mail->isHTML(true);
      $mail->Subject = "Booking Confirmation - NagarYatra";

      $mail->Body = "
          <div style='font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; background-color: #f9fafb;'>
            <h2 style='color: #2c3e50; font-size: 24px; font-weight: bold;'>Thank You for Your Feedback! 🙏</h2>
            
            <p style='font-size: 16px; color: #555; line-height: 1.6;'>Dear $name,</p>
            
            <p style='font-size: 16px; color: #555; line-height: 1.6;'>Thank you for taking the time to share your valuable feedback with us. We appreciate your input as it helps us to continually improve and deliver the best experience to our customers.</p>

            <p style='font-size: 16px; color: #555; line-height: 1.6;'>Rest assured, our team will carefully review your feedback and take the necessary steps to address any concerns or suggestions you've raised. We are committed to making NagarYatra a better service for all our users.</p>

            <p style='font-size: 16px; color: #555; line-height: 1.6;'>Your thoughts and opinions are essential to us, and we truly value your contribution in helping us shape our service. If you have any further thoughts or would like to discuss your feedback in more detail, feel free to reach out to us anytime.</p>
            
            <h3 style='color: #2c3e50; font-size: 20px;'>Next Steps</h3>
            <p style='font-size: 16px; color: #555;'>We will review your feedback and get back to you as soon as possible with any updates or actions we've taken. Your satisfaction is our top priority, and we are always here to help!</p>
            
            <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
            
            <p style='color: #333; font-size: 16px;'><b>Best Regards,</b><br>
            NagarYatra Team<br>
            <a href='https://www.NagarYatra.com' style='color: #3498db; text-decoration: none; font-size: 16px;'>www.NagarYatra.com</a></p>
            
            <p style='font-size: 14px; color: #999; text-align: center;'>You are receiving this email because you submitted feedback to NagarYatra. We greatly appreciate your input, and we are committed to continually improving our service.</p>
          </div>
        ";

       $mail->AltBody = "Dear $name, \n\nThank you for your valuable feedback. Our team will review your comments and take necessary actions to improve our services. We truly appreciate your input and are always here to listen. \n\nBest Regards, \nNagarYatra Team\nVisit: www.NagarYatra.com";

      $mail->send();
      // echo 'Booking confirmation email sent successfully!';
    } catch (Exception $e) {
      echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }

  // php mailer ends



  $stmt = $conn->prepare("INSERT INTO feedback (subject, message, user_id, is_delete) VALUES (?, ?, ?, ?)");
  $stmt->bind_param("ssii", $subject, $message, $user_id, $is_delete);

  if ($stmt->execute()) {
    // $success = "Thank you for your feedback! Our team will get back to you shortly.";
    $_SESSION['feedback_success']="Thank you for your feedback! Our team will get back to you shortly.";
  } else {
    $error = "Oops! Something went wrong. Please try again later.";
  }

  $stmt->close();
  $conn->close();
}
?>
<!-- For feedback submission success -->
<?php if (isset($_SESSION['feedback_success'])): ?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "success",
            title: "Feedback Sent Successfully"
        });
    </script>
    <?php unset($_SESSION['feedback_success']); ?>
<?php endif; ?>

<div class="feedback-wrapper">
  <div class="feedback-box">
    <h2><i class="fas fa-comments"></i> We'd Love to Hear From You</h2>
    <p class="subtitle">Your feedback helps us improve. Please let us know if you faced any issues or have suggestions.
      Our support team will respond as soon as possible.</p>

    <?php if ($success): ?>
      <div class="alert success"><i class="fas fa-check-circle"></i> <?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= $error ?></div>
    <?php endif; ?>

    <form action="" method="POST" class="feedback-form">
      <div class="form-group">
        <label for="subject"><i class="fas fa-pen"></i> Subject</label>
        <input type="text" id="subject" name="subject" required placeholder="Enter the subject of your message">
      </div>
      <div class="form-group">
        <label for="message"><i class="fas fa-comment-dots"></i> Your Message</label>
        <textarea id="message" name="message" rows="5" required
          placeholder="Describe your issue or feedback in detail..."></textarea>
      </div>
      <button type="submit" class="submit-btn"><i class="fas fa-paper-plane"></i> Submit Feedback</button>
    </form>
  </div>
</div>

<!-- Font Awesome CDN -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

<style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f5f8fb;
    color: #092448;
  }

  .feedback-wrapper {
    display: flex;
    justify-content: center;
    /* padding: 50px 20px; */
  }

  .feedback-box {
    background-color: white;
    border-radius: 10px;
    max-width: 600px;
    padding: 35px;
    /* box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1); */
  }

  .feedback-box h2 {
    font-size: 24px;
    color: #092448;
    margin-bottom: 10px;
  }

  .feedback-box .subtitle {
    font-size: 15px;
    color: #444;
    margin-bottom: 25px;
  }

  .form-group {
    margin-bottom: 20px;
  }

  .form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 8px;
    color: #092448;
  }

  .form-group input,
  .form-group textarea {
    width: 100%;
    padding: 12px 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    transition: border-color 0.3s;
  }

  .form-group input:focus,
  .form-group textarea:focus {
    border-color: #092448;
    outline: none;
  }

  .submit-btn {
    background-color: #092448;
    color: #fff;
    padding: 12px 25px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: background-color 0.3s;
  }

  .submit-btn:hover {
    background-color: #001f3f;
  }

  .alert {
    padding: 12px 15px;
    margin-bottom: 20px;
    border-radius: 5px;
    font-size: 14px;
  }

  .alert.success {
    background-color: #e7f6e7;
    color: #2d6a2d;
    border-left: 5px solid #28a745;
  }

  .alert.error {
    background-color: #fdeaea;
    color: #a94442;
    border-left: 5px solid #dc3545;
  }
</style>

<?php include_once 'master_footer.php'; ?>