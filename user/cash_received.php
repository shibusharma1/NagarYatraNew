<?php
session_start();
// include 'db.php'; // Your database connection file
include('../config/connection.php');

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    $sql = "UPDATE booking SET status = 7 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        // Set a session variable to show SweetAlert after redirect
        $_SESSION['cash_paid_success'] = true;
        header("Location: ride_history.php"); // Redirect to your ride_history page
        exit();
    } else {
        $_SESSION['cash_paid_failed'] = true;
        header("Location: ride_history.php");
        exit();
    }
} else {
    header("Location: ride_history.php");
    exit();
}
?>
