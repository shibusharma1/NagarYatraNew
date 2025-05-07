<?php
// Database connection
$title = "NagarYatra | Ride request";
$current_page = "ride_request";
include_once 'master_header.php';
include('../config/connection.php');
// Check if the user is logged in
// session_start();
$login_user = $_SESSION['id']; // assuming logged-in user ID is stored in session
$user_id = $_SESSION['id'];

// Haversine formula to calculate distance between two lat/lng points
function haversineDistance($lat1, $lng1, $lat2, $lng2)
{
    $earth_radius = 6371; // in kilometers

    $lat1_rad = deg2rad($lat1);
    $lng1_rad = deg2rad($lng1);
    $lat2_rad = deg2rad($lat2);
    $lng2_rad = deg2rad($lng2);

    $lat_diff = $lat2_rad - $lat1_rad;
    $lng_diff = $lng2_rad - $lng1_rad;

    $a = sin($lat_diff / 2) * sin($lat_diff / 2) + cos($lat1_rad) * cos($lat2_rad) * sin($lng_diff / 2) * sin($lng_diff / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earth_radius * $c; // returns distance in kilometers
}

$validBookingIds = [];

// Fetch all bookings where vehicle_id IS NULL
$sql = "SELECT id, nearest_users, pickup_lat, pickup_lng FROM booking WHERE vehicle_id IS NULL";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nearest_users_array = explode(',', $row['nearest_users']);
        $pickup_lat = $row['pickup_lat'];
        $pickup_lng = $row['pickup_lng'];

        // Check if the current logged-in user is in the nearest_users list
        if (in_array($login_user, array_map('trim', $nearest_users_array))) {
            // Get current user's location
            $stmt = $conn->prepare("SELECT latitude, longitude FROM user WHERE id = ?");
            $stmt->bind_param("i", $login_user);
            $stmt->execute();
            $stmt->bind_result($user_lat, $user_lng);
            if ($stmt->fetch()) {
                // Calculate the distance between the user and the booking pickup location
                $distance = haversineDistance($pickup_lat, $pickup_lng, $user_lat, $user_lng);
                if ($distance < 10) {
                    // Add the booking ID to the valid booking list if within 10KM
                    $validBookingIds[] = $row['id'];
                }
            }
            $stmt->close();
        }
    }
}

// Paginate and fetch bookings that are valid
$limit = 10; // number of records per page
// $page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // current page
// $offset = ($page - 1) * $limit;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;


if (!empty($validBookingIds)) {
    // Convert valid booking IDs to a comma-separated string
    $ids = implode(',', $validBookingIds);

    // Get total count of valid bookings
    $countQuery = "SELECT COUNT(*) AS total FROM booking WHERE id IN ($ids) AND is_delete = 0 AND status = 2";
    $countResult = $conn->query($countQuery);
    $totalRecords = $countResult->fetch_assoc()['total'];
    $totalPages = ceil($totalRecords / $limit);

    // Fetch the paginated valid bookings
    $sql = "SELECT * FROM booking WHERE id IN ($ids) AND is_delete = 0 AND status = 2 ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
    $result = $conn->query($sql);

    echo '<table>';
    echo '<h2>Booking Request</h2>';
    echo '<tr><th>S.N</th><th>Pickup Location</th><th>Destination Location</th><th>Estimated Km</th><th>Estimated duration</th><th>Estimated cost</th><th>Map</th><th>Action</th></tr>';
    if ($result && $result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {
            $sn = 1;
            echo '<tr>';
            echo '<td>' . $sn++;
            if ($row['pre_booking'] == 1) {

                echo '<sup style="background-color: #092448;color:white;padding: 2px; border-radius:10px;">Pre</sup>
        <br>
            </td>';
            }
            echo '<td>' . $row['pick_up_place'] . '</td>';
            echo '<td>' . $row['destination'] . '</td>';
            echo '<td>' . $row['estimated_KM'] . '</td>';
            echo '<td>' . $row['estimated_ride_duration'] . '</td>';
            echo '<td>' . $row['estimated_cost'] . '</td>';
            echo '<td><a href="view_shortest_path.php?pickup_lat=' . $row['pickup_lat'] .
                '&pickup_lng=' . $row['pickup_lng'] .
                '&dest_lat=' . $row['destination_lat'] .
                '&dest_lng=' . $row['destination_lng'] .
                '" target="_blank">View Shortest Path</a></td>';

            // echo '<td>' . ($row['status'] == 2 ? 'Pending' : 'Completed') . '</td>';
            // echo '<td><a href="approve_booking.php?id=' . $row['id'] . '">Approve</a> | <a href="reject_booking.php?id=' . $row['id'] . '">Reject</a></td>';
            echo '<td> <form method="POST" action="approve_reject_ride.php">
                                    <input type="hidden" name="booking_id" value="'.$row['id'].'">
                                    <input type="hidden" name="status_value" value="3">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #fff; background-color: green; margin-bottom:3px;">Approve</button>
                                </form>
                                <form method="POST" action="approve_reject_ride.php">
                                    <input type="hidden" name="booking_id" value="'. $row['id'].'">
                                    <input type="hidden" name="status_id" value="4">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #fff; background-color: #dc3545;">Reject</button>
                                </form></td>';
            echo '</tr>';
        }

        echo '</table>';

        // Pagination links
        echo '<div class="pagination">';
        for ($i = 1; $i <= $totalPages; $i++) {
            echo '<a href="?page=' . $i . '">' . $i . '</a>';
        }
        echo '</div>';
    } else {
        echo "<td colspan=8 style='color:#092448;font-size:2rem;'>No bookings available within your area.</td>";
    }
} else {
    // echo "No bookings available within 10KM.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="64x64" href="../assets/logo1.png" />
    <title>Booking request</title>
    <style>
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #092448;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px 16px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #092448;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .request-badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
        }

        .request-1 {
            background-color: gray;
        }

        .request-2 {
            background-color: orange;
        }

        .request-3 {
            background-color: green;
        }

        .request-4 {
            background-color: red;
        }

        /* Pagination styles */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: #092448;
            padding: 8px 14px;
            margin: 0 4px;
            text-decoration: none;
            border: 1px solid #092448;
            border-radius: 6px;
            font-weight: bold;
        }

        .pagination a.active {
            background-color: #092448;
            color: white;
        }

        .pagination a:hover {
            background-color: #092448;
            color: white;
        }
    </style>
</head>



</html>