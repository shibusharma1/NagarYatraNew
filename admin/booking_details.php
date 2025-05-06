<?php
$title = "NagarYatra | Booking detail";

require('../config/connection.php');
include_once 'master_header.php';


if (!isset($_GET['id'])) {
    die("Booking ID is required.");
}

$bookingId = intval($_GET['id']);

// Fetch booking with user and vehicle details
$sql = "SELECT b.*, u.name AS user_name, u.email, v.vehicle_number
        FROM booking b
        LEFT JOIN user u ON b.user_id = u.id
        LEFT JOIN vehicle v ON b.vehicle_id = v.id
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bookingId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Booking not found.");
}

$booking = $result->fetch_assoc();
// $generatedTime = date("d M Y h:i A");
date_default_timezone_set('Asia/Kathmandu');
$generatedTime = date("d M Y h:i A");


ob_start();
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #092448;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .logo {
            width: 100px;
            margin-bottom: 10px;
        }

        .title {
            color: #092448;
            font-size: 24px;
            margin: 0;
        }

        .section-title {
            color: #092448;
            font-size: 18px;
            margin-top: 30px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .field {
            margin: 10px 0;
        }

        .field strong {
            color: #092448;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            text-align: center;
            border-top: 1px solid #ccc;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <img src="../assets/logo1.png" alt="NagarYatra" class="logo"> <!-- Adjust logo path -->
        <h1 class="title">Ride Summary</h1>
    </div>

    <div class="field"><strong>Generated On:</strong> <?= $generatedTime ?></div>

    <h2 class="section-title">Ride Details</h2>
    <div class="field"><strong>Pickup Location:</strong> <?= htmlspecialchars($booking['pick_up_place']) ?></div>
    <div class="field"><strong>Destination:</strong> <?= htmlspecialchars($booking['destination']) ?></div>
    <div class="field"><strong>Estimated Distance:</strong> <?= $booking['estimated_KM'] ?> KM</div>
    <div class="field"><strong>Estimated Duration:</strong> <?= $booking['estimated_ride_duration'] ?></div>
    <div class="field"><strong>Estimated Cost:</strong> Rs. <?= $booking['estimated_cost'] ?></div>
    <div class="field"><strong>Booking Date:</strong> <?= date("d M Y", strtotime($booking['booking_date'])) ?></div>
    <div class="field"><strong>Ride Status:</strong>
        <?php
        switch ($booking['status']) {
            case 1:
                echo "Cancelled";
                break;
            case 3:
                echo "Success";
                break;
            case 4:
                echo "Rejected";
                break;
            case 5:
                echo "Completed";
                break;
            default:
                echo "Pending";
        }
        ?>
    </div>

    <h2 class="section-title">User Details</h2>
    <div class="field"><strong>Name:</strong> <?= htmlspecialchars($booking['user_name']) ?></div>
    <div class="field"><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></div>

    <?php if (!empty($booking['vehicle_name'])): ?>
        <h2 class="section-title">Vehicle Details</h2>
        <div class="field"><strong>Number:</strong> <?= htmlspecialchars($booking['vehicle_number']) ?></div>
    <?php endif; ?>

    <h2 class="section-title">Additional Info</h2>
    <!-- <div class="field"><strong>Rating:</strong>  -->
    <?php
    // $rating = $booking['rating'];
// for ($i = 1; $i <= 5; $i++) {
//     if ($rating >= $i) {
//         echo '<i class="fas fa-star" style="color:gold;"></i>';
//     } else {
//         echo '<i class="fas fa-star" style="color:whitesmoke;"></i>';
//     }
// }
    ?>

    </div>
    <div class="field"><strong>OTP:</strong> <?= $booking['otp'] ?></div>
    <div class="field"><strong>Booking
            Description:</strong><br><?= nl2br(htmlspecialchars($booking['booking_description'])) ?></div>

<?php include_once 'master_footer.php'; ?>
