<?php
include('../config/connection.php');

$booking_id = isset($_GET['booking_id']) ? (int)$_GET['booking_id'] : 0;

$response = ['vehicle_null' => false];

if ($booking_id > 0) {
    $stmt = $conn->prepare("SELECT vehicle_id FROM booking WHERE id = ?");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $stmt->bind_result($vehicle_id);
    if ($stmt->fetch()) {
        $response['vehicle_null'] = is_null($vehicle_id);
    }
    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);
?>
