<?php
$title = "NagarYatra | Ride Status";
$current_page = "ride_status";
include_once 'master_header.php';
include('../config/connection.php');

// Get user ID from session
$userId = $_SESSION['id'];

// Pagination setup
$limit = 10; // records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Fetch booking records with pagination (NO bind_param)
$sql = "SELECT * FROM booking 
        WHERE user_id = $userId 
        AND is_delete = 0 
        AND status = 2 
        ORDER BY created_at DESC 
        LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);

// Count total records for pagination
$count_sql = "SELECT COUNT(*) as total FROM booking 
              WHERE user_id = $userId 
              AND is_delete = 0 
              AND status = 2";
$count_result = $conn->query($count_sql);
$total_records = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_records / $limit);

// echo $result->num_rows;
// exit;

if (isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    $updateQuery = "UPDATE booking SET status = 1 WHERE id = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("i", $booking_id);

    if ($stmt->execute()) {
        echo "<script>
            window.location.href='ride_status.php'; // change to your page
        </script>";
    } else {
        echo "<script>
            window.history.back();
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="64x64" href="../assets/logo1.png" />
    <title>Booking Status</title>
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

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
        }

        .status-1 {
            background-color: gray;
        }

        .status-2 {
            background-color: orange;
        }

        .status-3 {
            background-color: green;
        }

        .status-4 {
            background-color: red;
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

        .pagination a:hover {
            background-color: #092448;
            color: white;
        }
    </style>
</head>

<body>
    <!-- <div class="container"> -->
        <h2 style="text-align: center;color:#092448;">Booking Status</h2>
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
                <?php $sn = ($page - 1) * $limit + 1; ?>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $sn++ ;
                            if ($row['pre_booking'] == 1) {
                                    ?>
                                        <sup style="background-color: #092448;color:white;padding: 2px; border-radius:10px;margin-left:40px;"> Pre</sup>
                                    <?php
                                } ?><br>
                            
                        </td>
                            <td>
                                <?= htmlspecialchars($row['pick_up_place']);?>
                               
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
                            <!-- <td>
                                <a href="view_shortest_path.php?pickup_lat=<?= $row['pickup_latitude'] ?>&pickup_lng=<?= $row['pickup_longitude'] ?>&dest_lat=<?= $row['destination_latitude'] ?>&dest_lng=<?= $row['destination_longitude'] ?>"
                                    target="_blank">
                                    View Shortest Path
                                </a>
                            </td> -->
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
                                <span class="status-badge status-<?= $row['status'] ?>">
                                    <?php
                                    switch ($row['status']) {
                                        case 1:
                                            echo "Canceled";
                                            break;
                                        case 2:
                                            echo "Pending";
                                            break;
                                        case 3:
                                            echo "Success";
                                            break;
                                        case 4:
                                            echo "Rejected";
                                            break;
                                        default:
                                            echo "Unknown";
                                    }
                                    ?>
                                </span>
                            </td>
                            <td>
                            <form method="POST" action="" id="cancelForm">
                                    <input type="hidden" name="booking_id" value="<?= $row['id'] ?>"> <!-- Replace $row['id'] with your booking ID -->
                                    <button type="button" id="cancelButton" class="btn btn-danger" style="padding: 5px 10px; 
                                        border-radius: 12px; font-size: 14px; font-weight: 600; color: #fff; background-color: #dc3545;">
                                        Cancel
                                    </button>
                                </form>

                                <script>
                                    // Get the cancel button element
                                    document.getElementById('cancelButton').addEventListener('click', function(event) {
                                        // Prevent form submission to show the SweetAlert first
                                        event.preventDefault();
                                        
                                        // SweetAlert confirmation
                                        Swal.fire({
                                            title: 'Are you sure?',
                                            text: "You won't be able to revert this!",
                                            icon: 'warning',
                                            showCancelButton: true,
                                            confirmButtonText: 'Yes, cancel it!',
                                            cancelButtonText: 'No, keep it'
                                        }).then((result) => {
                                            // If the user clicked "Yes"
                                            if (result.isConfirmed) {
                                                // Submit the form
                                                document.getElementById('cancelForm').submit();
                                            }
                                        });
                                    });
                                </script>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No bookings found.</td>
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

    <!-- </div> -->
</body>

</html>

<?php include_once 'master_footer.php'; ?>
