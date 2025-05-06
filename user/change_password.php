<?php
session_start(); // Optional, if you're managing user sessions
include('../config/connection.php');
$user_id = $_SESSION['id'] ?? 1; // fallback 1 for testing


// Sanitize POST inputs
$user_id = $_POST['id'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

if ($new_password !== $confirm_password) {
    $_SESSION['password_mismatch'] = "New password and confirmation do not match.";
    header("Location: your_password_change_page.php"); // Redirect back to the form
    exit();
}

// Fetch user's current hashed password
$stmt = $conn->prepare("SELECT password FROM  user WHERE id = ?");
$stmt->bind_param("i", $user_id); // Bind the parameter for user_id as integer
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

if (!$hashed_password) {
    $_SESSION['user_not_found'] = "User Not Found";
    header("Location: profile.php"); // Redirect back to the form
    exit();
}

// Verify old password
if (!password_verify($old_password, $hashed_password)) {
    $_SESSION['incorrect_old'] = "Old password is incorrect.";
    header("Location: profile.php"); // Redirect back to the form
    exit();
}

// Hash the new password
$hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update the password in the database
$update = $conn->prepare("UPDATE  user SET password = ? WHERE id = ?");
$update->bind_param("si", $hashed_new_password, $user_id);
$success = $update->execute();

if ($success) {
    $_SESSION['password_successful'] = "Password updated successfully.";
} else {
    $_SESSION['password_failed'] = "Password update failed.";
}

// Close the database connection
$conn->close();

// Redirect back to the password change page with a session message
header("Location: profile.php");
exit();
?>
