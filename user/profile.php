<?php
// include_once 'db.php'; // Your DB connection
include('../config/connection.php');

$title = "Edit User";
include_once 'master_header.php';
$current_page = "user";

// Example: Assuming user is logged in and ID is stored in session
// session_start();
$user_id = $_SESSION['id'] ?? 1; // fallback 1 for testing

// Fetch user data
$stmt = $conn->prepare("SELECT * FROM user WHERE id = ? AND is_delete = 0 LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    // Handle Image Upload
    if (!empty($_FILES['profile_image']['name'])) {
        $target_dir = "../admin/uploads/"; // Changed to the new directory
        $image_name = time() . "_" . basename($_FILES["profile_image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["profile_image"]["tmp_name"], $target_file);

        // Update including image
        $stmt = $conn->prepare("UPDATE user SET name=?, phone=?, address=?, image=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $phone, $address, $target_file, $id);
    } else {
        // Update without image
        $stmt = $conn->prepare("UPDATE user SET name=?, phone=?, address=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $phone, $address, $id);
    }

    if ($stmt->execute()) {
        $_SESSION['profile_success'] = "Profile updated successfully.";
        // Compose the notification message
        $message = "Dear {$name}, your profile has been updated successfully. Thank you for keeping your information up to date.";

        // Escape the message to avoid SQL errors
        $escaped_message = mysqli_real_escape_string($conn, $message);

        // Build the query (handle NULL for user_id)
        if ($user_id !== NULL) {
            $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$escaped_message')";
        } else {
            $sql = "INSERT INTO notifications (user_id, message) VALUES (NULL, '$escaped_message')";
        }

        // Execute the query
        if (mysqli_query($conn, $sql)) {
            // echo "Notification sent successfully!";
        } else {
            echo "Error: " . mysqli_error($conn);
        }

    } else {
        $_SESSION['error'] = "Error updating profile.";
    }
}

// header("Location: profile.php");
// exit;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>

    <style>
        .container {
            width: 75%;
            margin: 50px auto;
        }

        .profile-card {
            background: white;
            padding: 20px;
            text-align: center;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); */
            border-radius: 10px;
            border-top: 5px solid #092448;
        }

        .profile-img {
            width: 20rem !important;
            height: 20rem !important;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: contain;
            border: 1px solid #092448;
        }

        .profile-photo {
            width: 15rem !important;
            height: 15rem !important;
            border-radius: 50%;
            margin-bottom: 15px;
            object-fit: contain;
            border: 1px solid #092448;
        }

        .profile-header h2 {
            margin-top: 10px;
            color: #092448;
        }

        .profile-header p {
            color: gray;
            margin-bottom: 20px;
        }

        .profile-actions button {
            background: #092448;
            color: white;
            border: none;
            padding: 10px 20px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .profile-actions button:hover {
            background: #05192e;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 10;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            width: 400px;
            border-radius: 10px;
            position: relative;
            text-align: left;
            border-top: 5px solid #092448;
        }

        .modal-content h2 {
            margin-bottom: 20px;
            color: #092448;
        }

        .modal-content label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #092448;
        }

        .modal-content input[type="text"],
        .modal-content input[type="email"],
        .modal-content input[type="file"],
        .modal-content input[type="tel"],
        .modal-content textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .modal-content button[type="submit"] {
            background: #092448;
            color: white;
            padding: 10px;
            margin-top: 20px;
            width: 100%;
            border: none;
            border-radius: 5px;
        }

        .close {
            position: absolute;
            right: 15px;
            top: 10px;
            font-size: 24px;
            color: #092448;
            cursor: pointer;
        }
    </style>

</head>

<body>

    <!-- sweetAlert -->
    <!-- For profile update -->
    <?php if (isset($_SESSION['profile_success'])): ?>
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
                title: "Profile Updated successful"
            });
        </script>
        <?php unset($_SESSION['profile_success']); ?>
    <?php endif; ?>

    <!-- Password changed successfully -->
    <?php if (isset($_SESSION['password_successful'])): ?>
        <script>
            const Toast1 = Swal.mixin({
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

            Toast1.fire({
                icon: "success",
                title: "Password Changed successful"
            });
        </script>
        <?php unset($_SESSION['password_successful']); ?>
    <?php endif; ?>
    <!-- failed to update password -->
    <?php if (isset($_SESSION['password_failed'])): ?>
        <script>
            const Toast2 = Swal.mixin({
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

            Toast2.fire({
                icon: "error",
                title: "Failed to update password"
            });
        </script>
        <?php unset($_SESSION['password_failed']); ?>
    <?php endif; ?>

    <!-- For incorrect old password -->
    <?php if (isset($_SESSION['incorrect_old'])): ?>
        <script>
            const Toast3 = Swal.mixin({
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

            Toast3.fire({
                icon: "error",
                title: "<?php echo $_SESSION['incorrect_old']; ?>"
            });
        </script>
        <?php unset($_SESSION['incorrect_old']); ?>
    <?php endif; ?>
    <!-- for user not found -->
    <?php if (isset($_SESSION['user_not_found'])): ?>
        <script>
            const Toast4 = Swal.mixin({
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

            Toast4.fire({
                icon: "error",
                title: "<?php echo $_SESSION['user_not_found']; ?>"
            });
        </script>
        <?php unset($_SESSION['user_not_found']); ?>
    <?php endif; ?>










    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="<?php echo $user['image'] ? $user['image'] : '../assets/default_profile.png'; ?>"
                    alt="Profile" class="profile-photo">
                <h2><?php echo htmlspecialchars($user['name']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
                <p><?php echo htmlspecialchars($user['phone']); ?></p>
                <p><?php echo htmlspecialchars($user['address']); ?></p>
            </div>

            <div class="profile-actions">
                <button onclick="openEditModal()">Edit Profile</button>
                <button onclick="openPasswordModal()">Change Password</button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Profile</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                <label>Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

                <label>Phone:</label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>

                <label>Address:</label>
                <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address']); ?></textarea>

                <label>Profile Image:</label>
                <input type="file" name="profile_image">

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePasswordModal()"
                style="font-size: 28px; font-weight: bold; cursor: pointer; color: #aaa; float: right; margin-top: -10px;">&times;</span>

            <h2
                style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; color: #092448; text-align: center; margin-bottom: 20px;">
                Change Password</h2>

            <form action="change_password.php" method="POST"
                style="max-width: 400px; margin: auto; background: #f9f9f9; padding: 30px; border-radius: 10px; box-shadow: 0 0 15px rgba(0,0,0,0.1);">

                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Old Password:</label>
                <input type="password" name="old_password" required
                    style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">

                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">New Password:</label>
                <input type="password" name="new_password" required
                    style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">

                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #555;">Confirm
                    Password:</label>
                <input type="password" name="confirm_password" required
                    style="width: 100%; padding: 10px; margin-bottom: 30px; border: 1px solid #ccc; border-radius: 5px; font-size: 14px;">

                <button type="submit"
                    style="width: 100%; padding: 12px; background-color: #092448; color: white; font-weight: bold; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s ease;">
                    Update Password
                </button>
            </form>

        </div>
    </div>

    <script>
        function openEditModal() {
            document.getElementById("editProfileModal").style.display = "flex";
        }

        function closeEditModal() {
            document.getElementById("editProfileModal").style.display = "none";
        }

        function openPasswordModal() {
            document.getElementById("passwordModal").style.display = "flex";
        }

        function closePasswordModal() {
            document.getElementById("passwordModal").style.display = "none";
        }
    </script>

    <?php include_once 'master_footer.php'; ?>
</body>

</html>