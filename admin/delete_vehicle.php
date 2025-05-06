<?php
require_once '../config/connection.php';

// Check if 'id' is passed in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $vehicle_id = $_GET['id'];

    // Update the 'is_delete' column to 1 (soft delete the vehicle)
    $sql = "UPDATE vehicle SET is_delete = 1 WHERE id = $vehicle_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: vehicles"); // Redirect to vehicle list after soft delete
        exit;
    } else {
        echo "<div class='error-msg'>Error deleting vehicle: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='error-msg'>Invalid vehicle ID.</div>";
}

mysqli_close($conn);
?>
