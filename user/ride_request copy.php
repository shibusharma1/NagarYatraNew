<?php
$title = "NagarYatra | Ride request";
$current_page = "ride_request";
include_once 'master_header.php';
include('../config/connection.php');

// Pagination settings
$limit = 10; // Number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// Get vehicle ID from session
$userId = $_SESSION['vehicle_id'];

// Fetch total booking records for pagination
$countQuery = "SELECT COUNT(*) AS total FROM booking 
               WHERE vehicle_id = $userId 
               AND is_delete = 0 
               AND status = 2";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch booking records with LIMIT
$sql = "SELECT * FROM booking 
        WHERE vehicle_id = $userId 
        AND is_delete = 0 
        AND status = 2 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

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

        h1 {
            text-align: center;
            color: #092448;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
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

        .request-1 { background-color: gray; }
        .request-2 { background-color: orange; }
        .request-3 { background-color: green; }
        .request-4 { background-color: red; }

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

<body>
    <div class="container">
        <h1>Your Booking Request</h1>
        <table>
            <thead>
                <tr>
                    <th>SN</th>
                    <th>Pickup</th>
                    <th>Destination</th>
                    <th>Cost</th>
                    <th>Duration</th>
                    <th>Date</th>
                    <th>Map</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = $offset + 1; ?>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $sn++ ?></td>
                            <td>
                                <?= htmlspecialchars($row['pick_up_place']); 
                                 if ($row['pre_booking'] == 1) {
                                    ?>
                                        <sup style="background-color: #092448;color:white;padding: 2px; border-radius:10px;">Pre</sup>
                                <?php } ?><br>
                                <?php if (!empty($row['pickup_lat']) && !empty($row['pickup_lng'])): ?>
                                    <a href="https://www.google.com/maps?q=<?= $row['pickup_lat'] ?>,<?= $row['pickup_lng'] ?>" target="_blank">View Map</a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($row['destination']) ?><br>
                                <?php if (!empty($row['destination_lat']) && !empty($row['destination_lng'])): ?>
                                    <a href="https://www.google.com/maps?q=<?= $row['destination_lat'] ?>,<?= $row['destination_lng'] ?>" target="_blank">View Map</a>
                                <?php endif; ?>
                            </td>
                            <td>Rs. <?= $row['estimated_cost'] ?></td>
                            <td><?= $row['estimated_ride_duration'] ?></td>
                            <td><?= date('Y-m-d H:i:s', strtotime($row['created_at'])) ?></td>
                            <td>
                                <?php if ($row['pickup_lat'] != 0 && $row['pickup_lng'] != 0 && $row['destination_lat'] != 0 && $row['destination_lng'] != 0): ?>
                                    <a href="view_shortest_path.php?pickup_lat=<?= $row['pickup_lat'] ?>&pickup_lng=<?= $row['pickup_lng'] ?>&dest_lat=<?= $row['destination_lat'] ?>&dest_lng=<?= $row['destination_lng'] ?>" target="_blank">
                                        View Shortest Path
                                    </a>
                                <?php else: ?>
                                    Not Available
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="request-badge request-<?= $row['status'] ?>">
                                    <?php
                                    switch ($row['status']) {
                                        case 1: echo "Canceled"; break;
                                        case 2: echo "Pending"; break;
                                        case 3: echo "Success"; break;
                                        case 4: echo "Rejected"; break;
                                        default: echo "Unknown";
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="status_value" value="3">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #fff; background-color: green; margin-bottom:3px;">Approve</button>
                                </form>
                                <form method="POST" action="">
                                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>">
                                    <input type="hidden" name="status_id" value="4">
                                    <button type="submit" class="btn btn-danger" style="padding: 5px 10px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #fff; background-color: #dc3545;">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No bookings found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">Prev</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>">Next</a>
            <?php endif; ?>
        </div>
    </div>


<?php include_once 'master_footer.php'; ?>
