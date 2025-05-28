<?php
$title = "NagarYatra | Approved Ride";
$current_page = "approved_ride";
include_once 'master_header.php';
include('../config/connection.php');

$limit = 10;
$page = isset($_GET['page']) && (int)$_GET['page'] > 0 ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Get total count of approved bookings
$countQuery = "SELECT COUNT(*) AS total FROM booking WHERE is_delete = 0 AND status = 3";
$countResult = $conn->query($countQuery);
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $limit);

// Fetch the paginated approved bookings
$sql = "SELECT * FROM booking WHERE is_delete = 0 AND status = 3 ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);



?>

<!-- <div class="container"> -->
    <h2>Approved Ride</h2>
    <table>
        <tr>
            <th>S.N</th>
            <th>Pickup Location</th>
            <th>Destination Location</th>
            <th>Estimated Km</th>
            <th>Estimated Duration</th>
            <th>Estimated Cost</th>
            <th>OTP</th>
            <th>Map</th>
            <th>Action</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            $sn = $offset + 1;
            while ($row = $result->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $sn++;
                if ($row['pre_booking'] == 1) {
                    echo '<sup style="background-color: #092448;color:white;padding: 2px; border-radius:10px;">Pre</sup><br>';
                }
                echo '</td>';
                echo '<td>' . htmlspecialchars($row['pick_up_place']) . '</td>';
                echo '<td>' . htmlspecialchars($row['destination']) . '</td>';
                echo '<td>' . htmlspecialchars($row['estimated_KM']) . '</td>';
                echo '<td>' . htmlspecialchars($row['estimated_ride_duration']) . '</td>';
                echo '<td>' . htmlspecialchars($row['estimated_cost']) . '</td>';
                echo '<td>' . htmlspecialchars($row['otp']) . '</td>';
                echo '<td><a href="view_shortest_path.php?pickup_lat=' . $row['pickup_lat'] .
                    '&pickup_lng=' . $row['pickup_lng'] .
                    '&dest_lat=' . $row['destination_lat'] .
                    '&dest_lng=' . $row['destination_lng'] .
                    '" target="_blank">View Shortest Path</a></td>';
                echo '<td>
                    <form method="POST" action="approve_reject_ride.php" style="margin-bottom: 5px;">
                        <input type="hidden" name="booking_id" value="' . $row['id'] . '">
                        <input type="hidden" name="status_value" value="5">
                        <button type="submit" class="btn btn-success" style="padding: 5px 10px; border-radius: 12px; font-size: 12px; font-weight: 600; color: #fff; background-color: green; margin-bottom:3px;">Ride Complete</button>
                    </form>
                    <form method="POST" action="approve_reject_ride.php">
                        <input type="hidden" name="booking_id" value="' . $row['id'] . '">
                        <input type="hidden" name="status_id" value="6">
                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; border-radius: 12px; font-size: 14px; font-weight: 600; color: #fff; background-color: #dc3545;">Cancel</button>
                    </form>
                </td>';
                echo '</tr></table>';
            }
        } else {
            echo '<tr><td colspan="9" style="color:#092448;font-size:1.5rem;"><img src="../assets/search_no_data.svg" alt="No data" height="450px"></td></tr>';
        }
        ?>
    </table>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
<!-- </div> -->

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

    th, td {
        padding: 12px 16px;
        border: 1px solid #F0F0F0; /* Light gray border */
        text-align: center;
    }

    th {
        background-color: #092448;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    
    .btn{
        transition: transform 0.3s ease !important;
    }
    .btn:hover{
        transition: scale(1.05) !important;
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
        border: 1px solid #F0F0F0; /* Light gray border */
        border-radius: 6px;
        font-weight: bold;
        display: inline-block;
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
