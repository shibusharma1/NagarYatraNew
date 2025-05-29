<?php
$title = "NagarYatra | Driver Earnings";
$current_page = "earning";

include_once 'master_header.php';
require_once '../config/connection.php';

// Fetch all drivers with bookings
$sql = "
  SELECT 
    u.id AS user_id,
    u.name,
    COUNT(b.id) AS total_rides,
    ROUND(AVG(b.rating), 1) AS average_rating,
    SUM(b.estimated_cost) AS total_earnings
  FROM user u
  INNER JOIN booking b ON u.vehicle_id = b.vehicle_id
  WHERE u.role = 1 AND b.status = 5
  GROUP BY u.id
";

$result = mysqli_query($conn, $sql);
?>
<style>
    .toggle-btn {
        background-color: transparent;
        border: none;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: background-color 0.3s ease;
    }

    .toggle-btn:hover {
        background-color: #f0f0f0;
    }

    .toggle-btn i {
        font-size: 16px;
        color: #092448;
        transition: transform 0.3s ease;
    }

    .toggle-btn.open i {
        transform: rotate(180deg);
        color: #dc3545;
    }

    .star {
        font-size: 18px;
        color: #ccc;
    }

    .star.filled {
        color: gold;
    }
</style>

<div class="table-heading">
    <div class="heading-2">
        <h2>Driver Earnings Summary</h2>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Driver Name</th>
            <th>Total Rides</th>
            <th>Average Rating</th>
            <th>Total Earnings (Rs)</th>
            <th>Details</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if (mysqli_num_rows($result) > 0) {
            $count = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <td><?= $count++; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= $row['total_rides']; ?></td>
                    <td><?= $row['average_rating'] ?? 'N/A'; ?></td>
                    <td>Rs. <?= number_format($row['total_earnings'], 2); ?></td>
                    <td>
                        <button class="toggle-btn" onclick="toggleDetails('details-<?= $row['user_id']; ?>')">
                            <i class="fa fa-chevron-down"></i>
                        </button>
                    </td>
                </tr>
                <tr id="details-<?= $row['user_id']; ?>" style="display: none;">
                    <td colspan="6">
                        <strong>Ride Details:</strong>
                        <table border="1" cellpadding="8" cellspacing="0" width="100%"
                            style="margin-top: 10px; border-collapse: collapse;">
                            <thead style="background-color:rgb(28, 47, 71);">
                                <tr>
                                    <th>Passenger Name</th>
                                    <th>Pickup</th>
                                    <th>Destination</th>
                                    <th>Estimated Cost</th>
                                    <th>Rating</th>
                                    <th>Booking Date</th>
                                    <th>Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $userId = $row['user_id'];
                                $rideSql = "
          SELECT 
            b.pick_up_place,
            b.destination,
            b.estimated_cost,
            b.rating,
            b.booking_date,
            b.booking_description,
            u2.name AS passenger_name
          FROM booking b
          JOIN user u2 ON b.user_id = u2.id
          WHERE b.status = 5 AND b.vehicle_id = (
            SELECT vehicle_id FROM user WHERE id = $userId LIMIT 1
          )
          ORDER BY b.booking_date DESC
        ";
                                $rideResult = mysqli_query($conn, $rideSql);
                                if (mysqli_num_rows($rideResult) > 0) {
                                    while ($ride = mysqli_fetch_assoc($rideResult)) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($ride['passenger_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($ride['pick_up_place']) . "</td>";
                                        echo "<td>" . htmlspecialchars($ride['destination']) . "</td>";
                                        echo "<td>Rs. " . number_format($ride['estimated_cost'], 2) . "</td>";
                                        // echo "<td>" . ($ride['rating'] !== null ? htmlspecialchars($ride['rating']) : 'N/A') . "</td>";
                                        echo "<td>";
                                        $rating = (int) $ride['rating'];
                                        for ($i = 1; $i <= 5; $i++) {
                                            if ($i <= $rating) {
                                                echo '<span class="star filled" style="color: gold; font-size: 18px;">★</span>';
                                            } else {
                                                echo '<span class="star" style="color: #ccc; font-size: 18px;">☆</span>';
                                            }
                                        }
                                        echo "</td>";

                                        echo "<td>" . date("d M Y", strtotime($ride['booking_date'])) . "</td>";
                                        echo "<td>" . nl2br(htmlspecialchars($ride['booking_description'])) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' style='text-align:center;'>No completed rides found for this driver.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <?php
            }
        } else {
            echo "<tr><td colspan='6'>No drivers with completed bookings found.</td></tr>";
        }
        ?>
    </tbody>
</table>

<script>
    function toggleDetails(id) {
        var element = document.getElementById(id);
        if (element.style.display === "none") {
            element.style.display = "table-row";
        } else {
            element.style.display = "none";
        }
    }
</script>

<?php include_once 'master_footer.php'; ?>