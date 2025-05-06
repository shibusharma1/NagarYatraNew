<?php
require_once '../config/connection.php';

// Check if 'id' is passed in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $vehicle_id = $_GET['id'];

    // Hard delete: permanently remove the record
    $sql = "DELETE FROM vehicle_category WHERE id = $vehicle_id";

    if (mysqli_query($conn, $sql)) {
        header("Location: vehicles_category.php"); // Redirect to the category list page
        exit;
    } else {
        echo "<div class='error-msg'>Error deleting vehicle category: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='error-msg'>Invalid vehicle Category ID.</div>";
}

mysqli_close($conn);
?>
