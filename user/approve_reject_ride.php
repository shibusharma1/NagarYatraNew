<?php
$title = "NagarYatra | Ride request";
$current_page = "ride_request";
include_once 'master_header.php';
include('../config/connection.php');
// Handle approve/cancel actions
if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    if (isset($_POST['status_value'])) {
        $status_value = $_POST['status_value'];
    } elseif (isset($_POST['status_id'])) {
        $status_value = $_POST['status_id'];
    } else {
        $status_value = 2; // Default to pending if nothing set
    }

    $updateQuery = "UPDATE booking SET status = $status_value WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "<script>window.location.href='ride_request.php?page=$page';</script>";
    } else {
        echo "<script>window.history.back();</script>";
    }
}
?>