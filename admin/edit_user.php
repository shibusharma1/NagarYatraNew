<?php
require_once '../config/connection.php';
$title = "Edit User";
$current_page = "user";
include_once 'master_header.php';


$errors = [];
$success = "";
$user = [
    'id' => '',
    'name' => '',
    'email' => '',
    'phone' => '',
    'dob' => '',
    'gender' => '',
    'role' => '',
    'experience' => '',
    'dl_number' => '',
    'dl_expiry_date' => '',
    'dl_image' => '',
    'address' => '',
    'image' => '', // Add profile image field here
];

// Fetch user data if editing
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user = $row;
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_POST['user_id'] ?? '';
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $dob = trim($_POST['dob']);
    $gender = $_POST['gender'];
    $role = $_POST['role'];
    $address = trim($_POST['address']);
    $experience = $dl_number = $dl_expiry_date = null;
    $dl_image = $user['dl_image']; // Keep existing DL image unless updated
    $image = $user['image']; // Keep existing profile image unless updated

    // Validation
    if (empty($name))
        $errors['name'] = "Full name is required.";
    if (empty($email))
        $errors['email'] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        $errors['email'] = "Invalid email format.";
    if (empty($address))
        $errors['address'] = "Address is required.";
    if (!empty($password) && strlen($password) < 6)
        $errors['password'] = "Password must be at least 6 characters.";
    if (empty($phone) || strlen($phone) != 10)
        $errors['phone'] = "Valid 10-digit phone number is required.";
    if (empty($dob))
        $errors['dob'] = "Date of birth is required.";
    if (empty($gender))
        $errors['gender'] = "Gender is required.";
    if (!isset($role))
        $errors['role'] = "Role selection is required.";

    // If role is Driver, validate additional fields
    if ($role == "1") {
        $experience = trim($_POST['experience']);
        $dl_number = trim($_POST['dl_number']);
        $dl_expiry_date = $_POST['dl_expiry_date'];

        if (empty($dl_number))
            $errors['dl_number'] = "Driver's license number is required.";
        if (empty($dl_expiry_date))
            $errors['dl_expiry_date'] = "DL expiry date is required.";

        // Handle DL image upload
        if (!empty($_FILES['dl_image']['name'])) {
            $dl_image = "uploads/" . basename($_FILES['dl_image']['name']);
            if (!move_uploaded_file($_FILES['dl_image']['tmp_name'], $dl_image)) {
                $errors['dl_image'] = "Failed to upload DL image.";
            }
        }
    }

    // Handle profile image upload
    if (!empty($_FILES['image']['name'])) {
        $image = "uploads/user_images/" . basename($_FILES['image']['name']);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            $errors['image'] = "Failed to upload profile image.";
        }
    }

    // If no errors, proceed with database operation
    if (empty($errors)) {
        if (!empty($user_id)) {
            // UPDATE existing user
            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE user SET name=?, email=?, password=?, phone=?, dob=?, gender=?, role=?, experience=?, dl_number=?, dl_image=?, dl_expiry_date=?, address=?, image=? WHERE id=?");
                $stmt->bind_param("sssssssssssssss", $name, $email, $hashed_password, $phone, $dob, $gender, $role, $experience, $dl_number, $dl_image, $dl_expiry_date, $address, $image, $user_id);
            } else {
                $stmt = $conn->prepare("UPDATE user SET name=?, email=?, phone=?, dob=?, gender=?, role=?, experience=?, dl_number=?, dl_image=?, dl_expiry_date=?, address=?, image=? WHERE id=?");
                $stmt->bind_param("sssssssssssss", $name, $email, $phone, $dob, $gender, $role, $experience, $dl_number, $dl_image, $dl_expiry_date, $address, $image, $user_id);
            }
            if ($stmt->execute()) {
                $success = "User updated successfully!";
                $_SESSION['user_updated'] = "User updated successfully!";
                // Get current date and time
                $time = date("F j, Y, g:i a");

                // Compose the message
                $message = "Dear {$name}, your details have been updated by an admin on {$time}. If you did not make these changes, please contact us at <a href='mailto:nagarctservices@gmail.com'>nagarctservices@gmail.com</a> for assistance.";

                // Escape the message for safety
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

                ?>
                <script>
                    window.location.href = "users";
                </script>
                <?php
                exit;
            } else {
                $errors['db'] = "Database error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>
<style>
    .menu-bar{
            margin-top: -1.5rem !important;
    }
     .note-editor.note-frame {
    border: 1px solid #ced4da;
    border-radius: 4px;
  }
</style>

<?php include_once 'master_header.php'; ?>

<h2><?= !empty($user['id']) ? "Edit User" : "User Registration" ?></h2>

<?php if (!empty($success))
    echo "<div class='success-msg'>$success</div>"; ?>
<?php if (!empty($errors))
    foreach ($errors as $error)
        echo "<div class='error-msg'>$error</div>"; ?>

<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['id']) ?>">

    <div class="form-group">
        <label for="name" class="form-label">Full Name</label>
        <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($user['name']) ?>"
            required>
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>"
            required>
    </div>

    <div class="form-group">
        <label for="address" class="form-label">Address</label>
        <input type="text" class="form-control" id="address" name="address"
            value="<?= htmlspecialchars($user['address']) ?>" required>
    </div>

    <div class="form-group" style="display: none;">
        <label for="password" class="form-label">Password (Leave blank to keep current password)</label>
        <input type="password" class="form-control" id="password" name="password">
    </div>

    <div class="form-group">
        <label for="phone" class="form-label">Phone Number</label>
        <input type="number" class="form-control" id="phone" name="phone"
            value="<?= htmlspecialchars($user['phone']) ?>" required>
    </div>

    <div class="form-group">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="date" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($user['dob']) ?>"
            required>
    </div>

    <div class="form-group">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-control" id="gender" name="gender" required>
            <option value="MALE" <?= $user['gender'] == 'MALE' ? 'selected' : '' ?>>Male</option>
            <option value="FEMALE" <?= $user['gender'] == 'FEMALE' ? 'selected' : '' ?>>Female</option>
            <option value="OTHERS" <?= $user['gender'] == 'OTHERS' ? 'selected' : '' ?>>Others</option>
        </select>
    </div>

    <div class="form-group">
        <label for="image" class="form-label">Profile Image</label>
        <input type="file" class="form-input" id="image" name="image" onchange="updateProfileImage(event)" />
        <img id="image_display" src="<?= !empty($user['image']) ? $user['image'] : '' ?>" alt="Profile Image"
            style="margin-top:1rem;width: 200px; height: auto; display: <?= !empty($user['image']) ? 'block' : 'none' ?>;">
    </div>

    <script>
        function updateProfileImage(event) {
            const fileInput = event.target;
            const imageDisplay = document.getElementById('image_display');

            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    imageDisplay.src = e.target.result;
                    imageDisplay.style.display = 'block'; // Ensure image is shown after it's selected
                }

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>

    <div class="form-group">
        <label for="role" class="form-label">Role</label>
        <select class="form-control" id="role" name="role" required onchange="toggleDriverFields()">
            <option value="0" <?= $user['role'] == "0" ? "selected" : "" ?>>Passenger</option>
            <option value="1" <?= $user['role'] == "1" ? "selected" : "" ?>>Driver</option>
        </select>
    </div>

    <div id="driverFields" style="display: <?= ($user['role'] == '1') ? 'block' : 'none' ?>;">
        <div class="form-group">
            <label for="experience" class="form-label">Experience</label>
            <textarea class="form-control" id="experience"
                name="experience"><?= !empty($user['experience']) ? htmlspecialchars($user['experience']) : ''; ?></textarea>
        </div>

        <div class="form-group">
            <label for="dl_number" class="form-label">DL Number</label>
            <input type="text" class="form-control" id="dl_number" name="dl_number"
                value="<?= htmlspecialchars($user['dl_number']) ?>">
        </div>

        <div class="form-group">
            <label for="dl_image" class="form-label">DL Image</label>
            <input type="file" class="form-control" id="dl_image" name="dl_image" onchange="updateImageDisplay(event)">
            <img id="dl_image_display" src="<?= $user['dl_image'] ?>" alt="DL Image"
                style="margin-top:1rem;width: 200px; height: auto; display: <?= empty($user['dl_image']) ? 'none' : 'block' ?>;">
        </div>

        <div class="form-group">
            <label for="dl_expiry_date" class="form-label">DL Expiry Date</label>
            <input type="date" class="form-control" id="dl_expiry_date" name="dl_expiry_date"
                value="<?= htmlspecialchars($user['dl_expiry_date']) ?>">
        </div>
    </div>

    <button type="submit" class="custom-btn">Save</button>
</form>

<script>
    function toggleDriverFields() {
        const roleSelect = document.getElementById('role');
        const driverFields = document.getElementById('driverFields');
        driverFields.style.display = (roleSelect.value === '1') ? 'block' : 'none';
    }
</script>
<script>
  $(document).ready(function () {
    $('#experience').summernote({
      placeholder: 'Describe your experience...',
      tabsize: 2,
      height: 200,
      toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'italic', 'underline', 'clear']],
        ['fontsize', ['fontsize']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
      ]
    });
  });
</script>


<?php include_once 'master_footer.php'; ?>