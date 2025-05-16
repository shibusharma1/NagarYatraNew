<?php
$title = "NagarYatra | Ride History";
$current_page = "ride_history";
include_once 'master_header.php';
include('../config/connection.php');

if (!isset($_SESSION['id'])) {
    echo "You are not logged in.";
    exit();
}

if ($_SESSION['role'] == 0) {
    $userId = $_SESSION['id'];
    $sql = "SELECT * FROM booking WHERE user_id = ? AND status != 2 ORDER BY id DESC";
} else {
    $userId = $_SESSION['vehicle_id'];
    $sql = "SELECT * FROM booking WHERE vehicle_id = ? AND status != 2 ORDER BY id DESC";
}

$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$sql .= " LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$bookings = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if ($_SESSION['role'] == 0) {
    $count_sql = "SELECT COUNT(*) as total FROM booking WHERE user_id = $userId AND status != 2";
} else {
    $count_sql = "SELECT COUNT(*) as total FROM booking WHERE vehicle_id = $userId AND status != 2";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>

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
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border: 1px solid #F0F0F0;
        }

        th,
        td {
            padding: 14px 18px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border: 1px solid #F0F0F0;

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
        
        .status-2 {
            background-color: #E4A11B;
        }

        .status-3 {
            background-color: green;
        }

        .status-4 {
            background-color: #6c757d;
        }

        .status-5 {
            background-color: #28a745;
        }

        .status-6 {
            background-color:rgb(230, 80, 95);
        }

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

        a {
            text-decoration: none;
        }

        .pagination a:hover {
            background-color: #092448;
            color: white;
        }

        .modal-header {
            background-color: #092448;
            color: white;
            font-weight: bold;
            border-bottom: none;
        }

        .modal-body {
            padding: 20px;
            font-size: 15px;
        }

        .modal-footer {
            border-top: none;
            padding: 12px 20px;
        }

        .btn-info {
            background-color: #092448;
            border-color: #092448;
            color: white;
        }

        .btn-info:hover {
            background-color: #05417e;
            border-color: #05417e;
        }

        .btn-close {
            color: white;
        }

        .badge-prebooking {
            background-color: #092448;
            color: white;
            padding: 4px 6px;
            border-radius: 10px;
            font-size: 10px;
            margin-left: 5px;
        }

        .modal-backdrop.fade.show {
            display: none;
        }
    </style>
</head>

<body>
    <!-- <div class="container"> -->
    <h2>Ride History</h2>
    <table>
        <thead>
            <tr>
                <th>S.N</th>
                <th>Pickup Location</th>
                <th>Drop Location</th>
                <th>Booking Date</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php if (count($bookings) > 0): ?>
                <?php $sn = ($page - 1) * $limit + 1;
                foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= $sn++; ?></td>
                        <td>
                            <?= htmlspecialchars($booking['pick_up_place']); ?>
                            <?php if ($booking['pre_booking'] == 1): ?>
                                <span class="badge-prebooking">Pre</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($booking['destination']); ?></td>
                        <td><?= date("d M Y", strtotime($booking['booking_date'])); ?></td>
                        <td>
                            <span class="status-badge status-<?= $booking['status']; ?>">
                                <?php
                                switch ($booking['status']) {
                                    case 1:
                                        echo "Cancelled(U)";
                                        break;
                                        
                                    case 3:
                                        echo "Accepted";
                                        break;
                                    case 4:
                                        echo "Rejected";
                                        break;
                                    case 5:
                                        echo "Completed";
                                        break;
                                    case 6:
                                        echo "Cancelled(D)";
                                        break;
                                    default:
                                        echo "Unknown";
                                }
                                ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                data-bs-target="#rideDetailsModal<?= $booking['id']; ?>" style="margin:5px;">
                                <i class="fas fa-eye"></i>
                            </button>
                            <a href="generate_ride_summary.php?id=<?= $booking['id']; ?>" class="btn btn-success btn-sm"
                                target="_blank" style="margin:5px;background-color:#092448;color:white'">
                                <i class="fas fa-download"></i>
                            </a>
                        </td>
                    </tr>

                    <!-- Ride Details Modal -->
                    <div class="modal modal-fade fade" id="rideDetailsModal<?= $booking['id']; ?>"
                        aria-labelledby="rideDetailsModalLabel<?= $booking['id']; ?>">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="rideDetailsModalLabel<?= $booking['id']; ?>">Ride Details
                                    </h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Pickup Location:</strong> <?= htmlspecialchars($booking['pick_up_place']); ?>
                                    </p>
                                    <p><strong>Drop Location:</strong> <?= htmlspecialchars($booking['destination']); ?></p>
                                    <p><strong>Booking Date:</strong>
                                        <?= date("d M Y", strtotime($booking['booking_date'])); ?></p>
                                    <p><strong>Status:</strong>
                                        <?php
                                        switch ($booking['status']) {
                                            case 1:
                                                echo "Cancelled(U)";
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
                                            case 6:
                                                echo "Cancelled(D)";
                                                break;
                                            default:
                                                echo "Unknown";
                                        }
                                        ?>
                                    </p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">No ride history found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
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
    <!-- </div> -->

    <?php include_once 'master_footer.php'; ?>