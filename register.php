<?php
$title = "NagarYatra | Register";
session_start();
include_once 'config/connection.php'; // Include database connection
require 'mailer.php'; // Include PHPMailer file (if needed)


// when registration page opens delete the all records whose data is not verified
$sql = "DELETE FROM user WHERE is_verified = 0";

if ($conn->query($sql) === TRUE) {
  // echo "All unverified users deleted successfully.";
} else {
  // echo "Error deleting records: " . $conn->error;
}


$errors = []; // Array to store error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $gender = $_POST['gender'] ?? '';
  $address = trim($_POST['address']);
  $phone = trim($_POST['phone']);
  $date = trim($_POST['date']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirm_password = $_POST['confirm_password'];
  $latitude = $_POST['latitude'] ?? null;
  $longitude = $_POST['longitude'] ?? null;


  // Validate Name
  if (empty($name)) {
    $errors['name'] = "Name is required.";
  }

  // Validate Gender
  if (empty($gender)) {
    $errors['gender'] = "Please select your gender.";
  }

  // Validate Phone
  if (empty($phone)) {
    $errors['phone'] = "Phone is required.";
  }

  // Validate Address
  if (empty($address)) {
    $errors['address'] = "Address is required.";
  }

  // Validate Date
  if (empty($date)) {
    $errors['date'] = "Date is required.";
  }

  // Validate Email
  if (empty($email)) {
    $errors['email'] = "Email is required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = "Invalid email format.";
  } else {
    // Check if email already exists
    $checkEmail = "SELECT id FROM user WHERE email = ?";
    $stmt = $conn->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $errors['email'] = "Email already exists!";
    }

    // Free the result and close the statement properly
    $stmt->free_result();
    $stmt->close();
  }


  // Validate Nepali phone number
  if (empty($phone)) {
    $errors['phone'] = "Phone Number is required.";
  } elseif (!preg_match('/^(97|98|96)[0-9]{8}$/', $phone)) {
    $errors['phone'] = "Phone Number must start with 97, 98, or 96 and be exactly 10 digits.";
  }else {
    // Check if phone number already exists
    $checkPhone = "SELECT id FROM user WHERE phone = ?";
    $stmt = $conn->prepare($checkPhone);
    $stmt->bind_param("s", $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $errors['phone'] = "Phone Number already exists!";
    }

    // Free the result and close the statement properly
    $stmt->free_result();
    $stmt->close();
  }


  // Validate Password
  if (empty($password)) {
    $errors['password'] = "Password is required.";
  } elseif (strlen($password) < 8) {
    $errors['password'] = "Password must be at least 8 characters.";
  }

  // Validate Confirm Password
  if ($password !== $confirm_password) {
    $errors['confirm_password'] = "Passwords do not match.";
  }

  // If no errors, proceed with registration
  if (empty($errors)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $otp = rand(100000, 999999); // Generate OTP
    $otp_expiry = date("Y-m-d H:i:s", strtotime("+2 minutes")); // OTP expiry time

    $_SESSION['email'] = $email;
    // Insert user data with OTP
    $stmt = $conn->prepare("INSERT INTO user (name, gender, address, phone, dob, email, password, otp, otp_expiry, latitude, longitude) 
                                   VALUES (?, ?, ?, ?, ?,?, ?, ?, ?, ?,?)");
    $stmt->bind_param("sssssssssss", $name, $gender, $address, $phone, $date, $email, $hashed_password, $otp, $otp_expiry, $latitude, $longitude);

    if ($stmt->execute()) {
      // Close statement after execution
      $stmt->close();

      // Email Sending Code - PHPMailer
      require 'vendor/autoload.php'; // If you're using Composer for PHPMailer

      $mail = new PHPMailer\PHPMailer\PHPMailer();
      try {
        // Server settings
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host = 'smtp.gmail.com';                       // Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   // Enable SMTP authentication
        $mail->Username = 'nagarctservices@gmail.com';                 // SMTP username
        $mail->Password = 'gnpl gqhu pukx gmal';                    // SMTP password (Use App Password if 2FA is enabled)
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
           <h2 style='color: #2c3e50;'>Welcome to NagarYatra, $name! 🎉</h2>
           <p>We're excited to have you on board. To ensure the security of your account, please verify your email using the OTP below:</p>
           
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

        $mail->AltBody = "Welcome to NagarYatra, $name! Your OTP code is $otp. It will expire in 2 minutes. If you did not request this, please ignore this email.";


        $mail->send();
        echo 'Email has been sent successfully';

      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }

      //Store OTP and email in session
      $_SESSION['otp'] = $otp;
      $_SESSION['otp_expiry'] = $otp_expiry;
      $_SESSION['email'] = $email;

      //Redirect to OTP verification page
      header("Location: verify_otp"); // Redirect to OTP verification
      exit();
    } else {
      echo "Error: " . $stmt->error;
    }
  }

  //Close database connection at the end
  $conn->close();
}
include_once "register_login_header.php";
?>

<form action="" method="POST" class="sign-in-form" onsubmit="return validateForm()">
  <div class="first">
    <h2 class="title">Sign Up</h2>
    <div class="input-field">
      <i class="fas fa-user"></i>
      <input type="text" placeholder="Name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>"
        required />
    </div>
    <?php
    if (isset($errors['name'])) {
      ?>
      <div class="error">
        <small class="error"><?= $errors['name'] ?? '' ?></small>
      </div>
      <?php
    }
    ?>

    <div class="input-field">
      <i class="fas fa-address-card"></i>
      <input type="text" placeholder="Address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>"
        required />
    </div>
    <?php
    if (isset($errors['address'])) {
      ?>
      <div class="error">
        <small class="error">
          <?= $errors['address'] ?? '' ?>
        </small>
      </div>
      <?php
    }
    ?>
    <div class="input-field">
      <i class="fas fa-phone"></i>
      <input type="text" placeholder="9812345678" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>"
        required />
    </div>
    <?php
    if (isset($errors['phone'])) {
      ?>
      <div class="error">
        <small class="error">
          <?= $errors['phone'] ?? '' ?>
        </small>
      </div>
      <?php
    }
    ?>

    <div class="input-field">
      <label for="gender"><i class="fas fa-venus-mars"></i></label>
      <select id="gender" name="gender"
        style="background: none; border: none; outline: none;font-size: 1.1rem; color: #2E6A50; width: 100%;">
        <option value="" disabled <?= empty($_POST['gender']) ? 'selected' : '' ?>>Select your Gender</option>
        <option value="male" <?= ($_POST['gender'] ?? '') == 'male' ? 'selected' : '' ?>>Male</option>
        <option value="female" <?= ($_POST['gender'] ?? '') == 'female' ? 'selected' : '' ?>>Female</option>
        <option value="other" <?= ($_POST['gender'] ?? '') == 'other' ? 'selected' : '' ?>>Other</option>
      </select>
    </div>
    <?php
    if (isset($errors['gender'])) {
      ?>
      <div class="error">
        <small class="error"><?= $errors['gender'] ?? '' ?></small>
      </div>
      <?php
    }
    ?>
    <div class="input-field">
      <i class="fas fa-date"></i>
      <input type="date" placeholder="Birthdate" name="date" value="<?= htmlspecialchars($_POST['date'] ?? '') ?>"
        required />
    </div>
    <?php
    if (isset($errors['date'])) {
      ?>
      <div class="error">
        <small class="error"><?= $errors['date'] ?? '' ?></small>
      </div>
      <?php
    }
    ?>
    <div class="input-field">
      <i class="fas fa-envelope"></i>
      <input type="email" placeholder="Email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
        required />
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

    <div class="input-field">
      <i class="fas fa-lock"></i>
      <input type="password" placeholder="Password" id="password" name="password" required />
      <i id="togglePasswordIcon" class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
    </div>
    <?php
    if (isset($errors['password'])) {
      ?>
      <div class="error">
        <small class="error"><?= $errors['password'] ?? '' ?></small>
      </div>
      <?php
    }
    ?>
    <div class="input-field">
      <i class="fas fa-check"></i>
      <input type="password" placeholder="Confirm Password" id="confirm_password" name="confirm_password" required />
      <i id="toggleConfirmPasswordIcon" class="fas fa-eye toggle-password" onclick="toggleConfirmPassword()"></i>

    </div>
    <?php
    if (isset($errors['confirm_password'])) {
      ?>
      <div class="error">
        <small class="error"><?= $errors['confirm_password'] ?? '' ?></small>
      </div>
      <?php
    }
    ?>
    <input type="hidden" name="latitude" id="latitude">
    <input type="hidden" name="longitude" id="longitude">
    <div style="display: flex;justify-content:center;gap:10px;">
    <input type="checkbox" name="termsandConditions" class="termsandConditions" id="termsandConditions" required>
    <p class="terms">I have read and agreed to the <a href="user/terms_and_conditions.php">terms and conditions</a>.</p>
    </div>
    <button type="submit" class="btn solid">
      Register <i class="fas fa-arrow-right"></i>
    </button>
    <p class="social-text">Already have Account? Sign in <a href="login">here</a></p>
    <script>

      function fetchLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
          }, function (error) {
            console.error("Error getting location:", error);
          });
        } else {
          console.error("Geolocation is not supported by this browser.");
        }
      }

      // Call fetchLocation() when the page loads
      window.onload = fetchLocation;

    </script>
    <?php
    include_once "register_login_footer.php";
    ?>