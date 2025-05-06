<?php
require_once '../config/connection.php';

// Check if 'id' is passed in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_GET['id'];

    // Update the 'is_delete' column to 1 (soft delete the vehicle)
    $sql = "UPDATE user SET is_delete = 1 WHERE id = $user_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: users"); // Redirect to vehicle list after soft delete
        exit;
    } else {
        echo "<div class='error-msg'>Error deleting user: " . mysqli_error($conn) . "</div>";
    }
    } else {
        echo "<div class='error-msg'>Invalid user ID.</div>";
    }

    mysqli_close($conn);
    ?>