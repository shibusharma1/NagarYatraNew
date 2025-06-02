<?php
include('../config/connection.php');
$bookingId = $_GET['booking_id'];
// $bookingId = $_SESSION['booking_id'];
$sql = "UPDATE booking SET status = 8 WHERE id = $bookingId";

if ($conn->query($sql) === TRUE) {
    $_SESSION['paid_via_esewa'] = 1;
    $sql = "UPDATE booking SET status = 8 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $booking_id);

    if (!$stmt->execute()) {
        echo "<script>console.error('Error: " . addslashes($stmt->error) . "');</script>";
    } ?>

    <script>
        var booking_id = 123; // Replace with your actual value dynamically
        window.location.href = "../esewa.php?booking_id=" + booking_id;
    </script>
<?php

    // header('Location: ride_history.php');
} else {
    echo "Error: " . $conn->error;
}

?>