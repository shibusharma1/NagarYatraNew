<?php
// echo '<pre>';
// print_r($_GET);
// echo '</pre>';
include('config/connection.php');

// Check for encoded `data` parameter from eSewa
if (isset($_GET['data'])) {
    $rawData = $_GET['data'];
    $json = base64_decode($rawData);
    $info = json_decode($json, true);

    if (isset($info['status']) && $info['status'] === 'COMPLETE') {
        // echo "<h2 style='color: green;'>✅ Payment Successful and Verified!</h2>";
        // echo "<strong>Transaction Code:</strong> " . $info['transaction_code'] . "<br>";
        // echo "<strong>Amount:</strong> Rs. " . $info['total_amount'] . "<br>";
        // echo "<strong>Transaction ID:</strong> " . $info['transaction_uuid'] . "<br>";
        $bookingId = $_SESSION['booking_id'];

        $sql = "UPDATE booking SET status = 6 WHERE id = $bookingId";

        if ($conn->query($sql) === TRUE) {
            $_SESSION['paid_via_esewa'] = 1;
            $sql = "UPDATE booking SET status = 8 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $booking_id);

            if (!$stmt->execute()) {
                echo "<script>console.error('Error: " . addslashes($stmt->error) . "');</script>";
            }


            header('Location: ride_history.php');
        } else {
            echo "Error: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "<h2 style='color: orange;'>⚠️ Payment received but status not complete.</h2>";
        echo "<pre>";
        print_r($info);
        echo "</pre>";
    }

} else {
    echo "<h2 style='color: red;'>❌ Invalid or incomplete request!</h2>";
}
?>