<?php
require_once '../config/connection.php';
require '../vendor/autoload.php';
$title = "NagarYatra | Add User";
$current_page = "user";

$errors = [];
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    // $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['dob']);
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $address = trim($_POST['address']); // Get address input

    $experience = $dl_number = $dl_expiry_date = $dl_image = null;
    $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);

    // Validate required fields
    if (empty($name)) $errors['name'] = "Full name is required.";
    if (empty($email)) $errors['email'] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email format.";
    if (empty($address)) $errors['address'] = "Address is required."; // Validate address
    if (empty($password)) $errors['password'] = "Password is required.";
    if (empty($phone) || strlen($phone) != 10) $errors['phone'] = "Valid 10-digit phone number is required.";
    if (empty($dob)) $errors['dob'] = "Date of birth is required.";
    if (empty($gender)) $errors['gender'] = "Gender is required.";
    if (!isset($role)) $errors['role'] = "Role selection is required.";

    // Handle Profile Image Upload
    $image = null;
    if (!empty($_FILES['image']['name'])) {
        $image_path = "uploads/user_images/";
        if (!is_dir($image_path)) mkdir($image_path, 0777, true); // Create folder if not exists
        $image = $image_path . basename($_FILES['image']['name']);

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            $errors['image'] = "Failed to upload profile image.";
        }
    }

    // If role is Driver, validate extra fields
    if ($role == "1") {
        $experience = trim($_POST['experience']);
        $dl_number = trim($_POST['dl_number']);
        $dl_expiry_date = $_POST['dl_expiry_date'];

        if (empty($dl_number)) $errors['dl_number'] = "Driver's license number is required.";
        if (empty($dl_expiry_date)) $errors['dl_expiry_date'] = "DL expiry date is required.";

        // Handle DL Image Upload
        if (!empty($_FILES['dl_image']['name'])) {
            $dl_path = "uploads/dl_images/";
            if (!is_dir($dl_path)) mkdir($dl_path, 0777, true);
            $dl_image = $dl_path . basename($_FILES['dl_image']['name']);

            if (!move_uploaded_file($_FILES['dl_image']['tmp_name'], $dl_image)) {
                $errors['dl_image'] = "Failed to upload DL image.";
            }
        }
    }

    // If no errors, proceed with insertion
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $otp = rand(100000, 999999);
        $otp_expiry = date('Y-m-d H:i:s', strtotime('+15 minutes'));
        $verified = 1;

        $stmt = $conn->prepare("INSERT INTO user 
            (name, email, password, phone, dob, gender, image, role, experience, dl_number, dl_image, dl_expiry_date, otp, otp_expiry, is_verified, address) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");

        $stmt->bind_param(
            "ssssssssssssssss",
            $name, $email, $hashed_password, $phone, $dob, $gender, $image, $role,
            $experience, $dl_number, $dl_image, $dl_expiry_date, $otp, $otp_expiry, $verified, $address
        );

        if ($stmt->execute()) {
            $success = "Registration successful!";
            $mail = new PHPMailer\PHPMailer\PHPMailer();

            try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'nagarctservices@gmail.com'; // Your Gmail
            $mail->Password = 'xjoa yrzu odbc nezg';        // App password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Recipients
            $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');
            $mail->addAddress($email, $name); // User's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = "Welcome to NagarYatra - Registration Successful";

            $mail->Body = "
                <div style='font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px; background-color: #f9fafb;'>
                    <h2 style='color: #092448; font-size: 24px; font-weight: bold;'>Welcome to NagarYatra! 🚗</h2>
            
                    <p style='font-size: 16px; color: #333; line-height: 1.6;'>Dear <strong>$name</strong>,</p>
            
                    <p style='font-size: 16px; color: #333; line-height: 1.6;'>We're thrilled to have you on board! Your registration with <strong>NagarYatra</strong> was successful.</p>
            
                    <h3 style='color: #092448; font-size: 20px;'>Your Account Details</h3>
                    <ul style='font-size: 16px; color: #333; line-height: 1.8;'>
                        <li><strong>Email:</strong> $email</li>
                        <li><strong>Password:</strong> $password</li>
                    </ul>
            
                    <p style='font-size: 16px; color: #d35400;'><strong>Important:</strong> For your account’s security, please change your password immediately after logging in.</p>
            
                    <p style='font-size: 16px; color: #333; line-height: 1.6;'>You can now log in to your NagarYatra account and start booking rides quickly and safely. We aim to make your travel smooth, reliable, and affordable.</p>
            
                    <p style='font-size: 16px; color: #333;'>Need help? Feel free to contact our support team anytime. We're here for you!</p>
            
                    <div style='text-align: center; margin-top: 30px;'>
                        <a href='https://localhost.nagaryatra.com/login' style='background-color: #092448; color: #fff; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-size: 16px;'>Login to Your Account</a>
                    </div>
            
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
            
                    <p style='color: #333; font-size: 16px;'><strong>Warm Regards,</strong><br>
                    NagarYatra Team<br>
                    <a href='https://www.NagarYatra.com' style='color: #092448; text-decoration: none;'>www.NagarYatra.com</a></p>
            
                    <p style='font-size: 14px; color: #999; text-align: center;'>You are receiving this email because you registered with NagarYatra. Please keep your login credentials secure.</p>
                </div>
            ";
            
            $mail->AltBody = "Dear $name,\n\nWelcome to NagarYatra! Your registration is successful.\n\nEmail: $email\nPassword: $password\n\nImportant: For your security, please change your password after your first login.\n\nLogin here: https://www.nagaryatra.com/login\n\nBest Regards,\nNagarYatra Team\nVisit: www.NagarYatra.com";
            
            $mail->send();
            // echo 'Booking confirmation email sent successfully!';
            } catch (Exception $e) {
            echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }

        // php mailer ends
            header("Location: users");
            exit();
        } else {
            $errors['db'] = "Database error: " . $stmt->error;
        }

        $stmt->close();
    }

?>

<?php include_once 'master_header.php'; ?>

<h2>User Registration</h2>

<?php if (!empty($success)) echo "<div class='success-msg'>$success</div>"; ?>
<?php if (!empty($errors)) foreach ($errors as $error) echo "<div class='error-msg'>$error</div>"; ?>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" required>
    </div>

    <div class="form-group" style="display: none;">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>

    <div class="form-group">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="number" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>" required>
    </div>

    <div class="form-group">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="MALE">Male</option>
            <option value="FEMALE">Female</option>
            <option value="OTHERS">Others</option>
        </select>
    </div>

    <div class="form-group">
        <label for="image" class="form-label">Profile Image</label>
        <input type="file" class="form-input" id="image" name="image" />
    </div>

    <div class="form-group">
        <label for="role" class="form-label">Role</label>
        <select class="form-control" id="role" name="role" required>
            <option value="0">Passenger</option>
            <option value="1">Driver</option>
        </select>
    </div>

    <div id="driverFields" style="display: none;">
        <div class="form-group">
            <label for="experience" class="form-label">Experience</label>
            <textarea class="form-control" id="experience" name="experience"></textarea>
        </div>

        <div class="form-group">
            <label for="dl_number" class="form-label">DL Number</label>
            <input type="text" class="form-control" id="dl_number" name="dl_number">
        </div>

        <div class="form-group">
            <label for="dl_image" class="form-label">DL Image</label>
            <input type="file" class="form-control" id="dl_image" name="dl_image">
        </div>

        <div class="form-group">
            <label for="dl_expiry_date" class="form-label">DL Expiry Date</label>
            <input type="date" class="form-control" id="dl_expiry_date" name="dl_expiry_date">
        </div>
    </div>

    <button type="submit" class="custom-btn">Submit</button>
</form>

<script>
    document.getElementById('role').addEventListener('change', function () {
        document.getElementById('driverFields').style.display = this.value == '1' ? 'block' : 'none';
    });
</script>

<?php include_once 'master_footer.php'; ?>
