<?php
$title = "NagarYatra | Ride History";
$current_page = "ride_history";
include_once 'master_header.php';
include('../config/connection.php');

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "You are not logged in.";
    exit();
}

// Set $userId correctly based on role
if ($_SESSION['role'] == 0) {
    $userId = $_SESSION['id']; // Normal user
    $sql = "SELECT * 
            FROM booking 
            WHERE user_id = ? AND status != 2 
            ORDER BY id DESC";
} else {
    $userId = $_SESSION['vehicle_id']; // Driver or vehicle owner
    $sql = "SELECT * 
            FROM booking 
            WHERE vehicle_id = ? 
            ORDER BY id DESC";
}

// Pagination setup
$limit = 10; // items per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Modify SQL for pagination
if ($_SESSION['role'] == 0) {
    $sql = "SELECT * 
            FROM booking 
            WHERE user_id = ? AND status != 2 
            ORDER BY id DESC 
            LIMIT $limit OFFSET $offset";
} else {
    $sql = "SELECT * 
            FROM booking 
            WHERE vehicle_id = ? 
            ORDER BY id DESC 
            LIMIT $limit OFFSET $offset";
}

// Prepare and execute query
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Count total records for pagination
if ($_SESSION['role'] == 0) {
    $count_sql = "SELECT COUNT(*) as total 
                  FROM booking 
                  WHERE user_id = $userId AND status != 2";
} else {
    $count_sql = "SELECT COUNT(*) as total 
                  FROM booking 
                  WHERE vehicle_id = $userId";
}
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ride History</title>
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
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
        }

        th,
        td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #092448;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
        }

        .status-1 {
            background-color: #dc3545;
        }

        /* Cancelled */
        .status-3 {
            background-color: green;
        }

        /* Success */
        .status-4 {
            background-color: #6c757d;
        }

        /* Rejected */
        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: #092448;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #092448;
            margin: 0 2px;
            border-radius: 8px;
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
        <h2>Your Ride History</h2>
        <table>
            <thead>
                <tr>
                    <th>S.N</th>
                    <th>Pickup Location</th>
                    <th>Drop Location</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>

                <?php if (count($bookings) > 0): ?>
                    <?php $sn = ($page - 1) * $limit + 1;
                    foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= $sn++; ?></td>
                            <td><?= htmlspecialchars($booking['pick_up_place']);
                            if ($booking['pre_booking'] == 1) {
                                ?>
                                    <sup
                                        style="background-color: #092448;color:white;padding: 4px; border-radius:10px;margin-top:-20px;">Pre</sup>
                                    <?php
                            } ?>
                            </td>
                            <td><?= htmlspecialchars($booking['destination']); ?></td>
                            <td><?= date("d M Y", strtotime($booking['booking_date'])); ?></td>
                            <td>
                                <span class="status-badge status-<?= $booking['status']; ?>">
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
                                            echo "completed";
                                        default:
                                            echo "Unknown";
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No ride history found.</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>

        <!-- Pagination Links -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i == $page ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1 ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>
