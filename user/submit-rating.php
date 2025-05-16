<?php
require '../config/connection.php'; // Replace with your actual DB connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : null;
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;

    if ($rating && $booking_id) {
        $stmt = $conn->prepare("UPDATE booking SET rating = ? WHERE id = ?");
        $stmt->bind_param("di", $rating, $booking_id);
        if ($stmt->execute()) {
            echo "<script>alert('Thank you for your feedback!'); window.location.href='dashboard.php';</script>";
        } else {
            echo "<script>alert('Error updating rating.'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Invalid input.'); window.history.back();</script>";
    }
}
?>
