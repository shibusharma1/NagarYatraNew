<?php
require '../config/connection.php';

$message = '';
$redirect = 'ride_history.php'; // Default redirect

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : null;
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    $description = isset($_POST['experience_description']) ? trim($_POST['experience_description']) : '';

    if ($rating && $booking_id > 0) {
        $stmt = $conn->prepare("UPDATE booking SET rating = ?, remarks = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("dsi", $rating, $description, $booking_id);
            if ($stmt->execute()) {
                $message = 'Thank you for rating your ride!';
                $status = 'success';
            } else {
                $message = 'Failed to submit rating. Execution Error.';
                $status = 'error';
            }
            $stmt->close();
        } else {
            $message = 'Database Error: Prepare failed.';
            $status = 'error';
        }
    } else {
        $message = 'Please select a valid rating.';
        $status = 'error';
    }
} else {
    $message = 'Invalid request method.';
    $status = 'error';
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rating Feedback</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
    console.log("Rating submission status: '<?= $status ?>'");
    console.log("Message: '<?= $message ?>'");
    
    Swal.fire({
        title: '<?= $message ?>',
        icon: '<?= $status ?>',
        confirmButtonColor: '#092448',
        confirmButtonText: 'OK'
    }).then(() => {
        window.location.href = '<?= $redirect ?>';
    });
</script>
</body>
</html>
