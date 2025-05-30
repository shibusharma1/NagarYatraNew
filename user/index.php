<?php
$title = "NagarYatra | Dashboard";
$current_page = "index";

include_once 'master_header.php';
require_once '../config/connection.php';




// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "You are not logged in.";
    exit();
}


?>
<?php if (isset($_SESSION['login_success'])): ?>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 2500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        Toast.fire({
            icon: "success",
            title: "Login successful"
        });
    </script>
    <?php unset($_SESSION['login_success']); ?>
<?php endif; ?>

<?php
if ($status == 0) {
    echo '
    <div class="alert alert-danger text-center m-3" role="alert" style="font-weight: bold;">
        You have been <b>blocked by admin</b>. Please contact us for more info or email us at 
        <a href="mailto:nagarctservices@gmail.com" class="alert-link">nagarctservices@gmail.com</a>.
    </div>';
}



// (Your PHP logic above…)
if (!$image) {
    echo '
    <div class="alert alert-warning alert-dismissible fade show text-center m-3" role="alert" style="font-weight: bold;">
      <strong>Warning!</strong> You haven\'t uploaded a profile picture yet. Please 
      <a href="profile.php" class="alert-link">update your profile picture</a> to continue.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>';
}
?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'rating-popup.php'; ?>


<h2 style="color:#092448;">Dashboard</h2>
<div class="header-location">
    <span>📍</span>
    <span id="location-name">Biratnagar Metropolitan City</span>

    <script>
        function getLocationName(lat, lon) {
            const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const displayName = data.address.city || data.address.town || data.address.village || data.display_name;
                    document.getElementById("location-name").textContent = displayName || "Biratnagar Metropolitan City";
                })
                .catch(() => {
                    document.getElementById("location-name").textContent = "Biratnagar Metropolitan City";
                });
        }

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    const lat = position.coords.latitude;
                    const lon = position.coords.longitude;
                    getLocationName(lat, lon);
                },
                () => {
                    // If geolocation fails or permission is denied, use default location
                    document.getElementById("location-name").textContent = "Biratnagar Metropolitan City";
                }
            );
        } else {
            // If geolocation is not supported, use default location
            document.getElementById("location-name").textContent = "Biratnagar Metropolitan City";
        }
    </script>

</div>
</header>





<!-- Users details -->
<div class="row my-4">
    <!-- Total Users Card -->
    <div class="col-lg-3">
        <div class="s7__widget-three card shadow-sm  card-info">
            <div class="content">
                <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
                    <?php

                    if ($_SESSION['role'] == 0) {
                        $userId = $_SESSION['id'];

                        $sql = "SELECT COUNT(id) AS total_bookings FROM booking WHERE user_id = $userId AND is_delete = 0";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalBookings = $row['total_bookings'];
                            echo $totalBookings;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        $userId = $_SESSION['vehicle_id'];

                        $sql = "SELECT COUNT(id) AS total_bookings FROM booking WHERE vehicle_id = $userId AND is_delete = 0";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalBookings = $row['total_bookings'];
                            echo $totalBookings;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    }
                    ?>
                </h1>
                <p class="mb-2 text-muted">Total Booking</p>
            </div>
            <div class="icon s7__bg-primary rounded-circle">
                <i class="las la-calendar-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="s7__widget-three card shadow-sm  card-info">
            <div class="content">
                <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
                    <?php

                    if ($_SESSION['role'] == 0) {
                        $userId = $_SESSION['id'];

                        $sql = "SELECT COUNT(id) AS completed_bookings FROM booking 
                                WHERE user_id = $userId 
                                AND status = 5 
                                AND is_delete = 0";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $completedBookings = $row['completed_bookings'];
                            echo $completedBookings;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        $userId = $_SESSION['vehicle_id'];

                        $sql = "SELECT COUNT(id) AS completed_bookings FROM booking 
                                WHERE vehicle_id = $userId 
                                AND status = 5 
                                AND is_delete = 0";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $completedBookings = $row['completed_bookings'];
                            echo $completedBookings;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    }
                    ?>
                </h1>
                <p class="mb-2 text-muted">Booking Completed</p>
            </div>
            <div class="icon s7__bg-primary rounded-circle">
                <i class="las la-check-circle"></i>
            </div>
        </div>
    </div>
    <!-- Only to show the driver -->
    <?php
    if ($_SESSION['role'] != 1) {
        ?>

        <div class="col-lg-3">
            <div class="s7__widget-three card shadow-sm  card-info">
                <div class="content">
                    <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
                        <?php
                        $userId = $_SESSION['id'];

                        $sql = "SELECT COUNT(id) AS completed_bookings FROM booking 
                                WHERE user_id = $userId 
                                AND status = 2 
                                AND is_delete = 0";
                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $completedBookings = $row['completed_bookings'];
                            echo $completedBookings;
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                        ?>
                    </h1>
                    <p class="mb-2 text-muted">Pending Booking</p>
                </div>
                <div class="icon s7__bg-primary rounded-circle">
                    <i class="las la-check-circle"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="s7__widget-three card shadow-sm  card-info">
                <div class="content">
                    <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
                        <?php
                        $userId = $_SESSION['id'];

                        $sql = "SELECT SUM(estimated_cost) AS total_earning FROM booking 
                                WHERE user_id = $userId 
                                AND status = 5 
                                AND is_delete = 0";

                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalEarning = $row['total_earning'] ?? 0;
                            echo number_format($totalEarning, 2); // Format to 2 decimal places
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                        ?>
                    </h1>
                    <p class="mb-2 text-muted">Total Bookings Cost</p>
                </div>
                <div class="icon s7__bg-primary rounded-circle">
                    <!-- <i class="las la-dollar-sign"></i> -->
                <span>रु</span>

                </div>
            </div>
        </div>
    </div>




    <!-- Pie charts and bar graph for user -->
    <?php
    $user_id = $_SESSION['id'];

    // Fetch booking count per day (for the last 7 days)
    $sql1 = "
    SELECT DATE(created_at) AS day, COUNT(*) AS bookings
    FROM booking
    WHERE user_id = ? AND is_delete = 0 AND created_at >= CURDATE() - INTERVAL 6 DAY
    GROUP BY day
    ORDER BY day
    ";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    $booking_data = [];
    while ($row = $result1->fetch_assoc()) {
        $booking_data[$row['day']] = $row['bookings'];
    }

    // Prepare last 7 days
    $labels = [];
    $bookings = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $labels[] = $date;
        $bookings[] = $booking_data[$date] ?? 0;
    }

    // Fetch booking status counts (all-time)
    if ($_SESSION['role'] == 0) {
        $user_id = $_SESSION['id'];
        $sql3 = "
    SELECT status, COUNT(*) AS status_count
    FROM booking
    WHERE user_id = ? AND is_delete = 0
    GROUP BY status
    ";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    } else {
        $user_id = $_SESSION['vehicle_id'];
        $sql3 = "
                SELECT status, COUNT(*) AS status_count
                FROM booking
                WHERE vehicle_id = ? AND is_delete = 0
                GROUP BY status
                ";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->bind_param("i", $user_id);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
    }


    $status_data = [
        'cancelled_by_user' => 0,
        'pending' => 0,
        'approved' => 0,
        'rejected_by_driver' => 0,
        'completed' => 0,
        'cancelled_by_driver' => 0
    ];

    while ($row = $result3->fetch_assoc()) {
        switch ($row['status']) {
            case 1: // cancelled by user
                $status_data['cancelled_by_user'] = $row['status_count'];
                break;
            case 2: // pending
                $status_data['pending'] = $row['status_count'];
                break;
            case 3: // approved
                $status_data['approved'] = $row['status_count'];
                break;
            case 4: // rejected by driver
                $status_data['rejected_by_driver'] = $row['status_count'];
                break;
            case 5: // completed
                $status_data['completed'] = $row['status_count'];
                break;
            case 6: // cancelled by driver
                $status_data['cancelled_by_driver'] = $row['status_count'];
                break;
        }
    }
    ?>

    <!-- Booking Chart -->
    <h3 class="text-center mb-4" style="color:#092448;">Your 7-Day Activity Overview</h3>
    <div class="row">
        <!-- Booking Chart -->
        <div class="col-md-6 mb-4">
            <canvas id="bookingChart" height="300"></canvas>
        </div>


        <!-- Booking Status Pie Chart -->
        <div class="col-md-6 mb-4">
            <canvas id="statusPieChart" height="300"></canvas>
        </div>
    </div>
    <script>
        const labels = <?= json_encode($labels); ?>;
        const bookings = <?= json_encode($bookings); ?>;
        const statusData = <?= json_encode($status_data); ?>;

        const themeColor = '#092448';

        // Create gradient for line charts
        function createGradient(ctx, height) {
            const gradient = ctx.createLinearGradient(0, 0, 0, height);
            gradient.addColorStop(0, 'rgba(9, 36, 72, 0.4)');
            gradient.addColorStop(1, 'rgba(9, 36, 72, 0.05)');
            return gradient;
        }

        // Create Line Chart for Bookings
        function createChart(canvasId, label, data, yLabel) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const gradient = createGradient(ctx, 300);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: themeColor,
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: themeColor,
                        pointHoverRadius: 6,
                        pointRadius: 4,
                        pointBorderColor: "#fff",
                        pointHoverBorderColor: "#fff"
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: label + ' (Last 7 Days)',
                            color: themeColor,
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: themeColor,
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        },
                        legend: {
                            display: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: yLabel,
                                color: themeColor,
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                color: themeColor
                            },
                            grid: {
                                color: '#ddd'
                            }
                        },
                        x: {
                            ticks: {
                                color: themeColor
                            },
                            grid: {
                                color: '#eee'
                            }
                        }
                    }
                }
            });
        }

        // Create Pie Chart for Status
        const statusLabels = [
            'Cancelled (by User)',
            'Pending',
            'Approved',
            'Rejected (by Driver)',
            'Completed',
            'Cancelled (by Driver)'
        ];

        const statusCounts = [
            statusData.cancelled_by_user,
            statusData.pending,
            statusData.approved,
            statusData.rejected_by_driver,
            statusData.completed,
            statusData.cancelled_by_driver
        ];

        const statusColors = [
            '#ff4d4d', // Red for Cancelled (by User)
            '#ffcc00', // Yellow for Pending
            '#4caf50', // Green for Approved
            '#f44336', // Red for Rejected (by Driver)
            '#2196f3', // Blue for Completed
            '#9e9e9e'  // Grey for Cancelled (by Driver)
        ];

        // Calculate total count
        const totalBookings = statusCounts.reduce((total, count) => total + count, 0);

        // Append percentage to the chart
        const statusPercentages = statusCounts.map(count => {
            return {
                count: count,
                percentage: totalBookings ? ((count / totalBookings) * 100).toFixed(2) : 0
            };
        });

        const ctxPie = document.getElementById('statusPieChart').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusCounts,
                    backgroundColor: statusColors,
                    borderColor: '#fff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    title: {
                        display: true,
                        text: 'Overall Booking Status',
                        color: '#092448',
                        font: {
                            size: 18,
                            weight: 'bold'
                        },
                        padding: {
                            top: 10,
                            bottom: 20
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function (tooltipItem) {
                                const idx = tooltipItem.dataIndex;
                                const count = tooltipItem.raw;
                                const percentage = statusPercentages[idx].percentage;
                                return `${statusLabels[idx]}: ${count} (${percentage}%)`;
                            }
                        },
                        backgroundColor: '#092448',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    },
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                weight: 'bold'
                            }
                        }
                    }
                }
            }
        });

        // Create the booking chart (line graph)
        createChart('bookingChart', 'Number of Bookings', bookings, 'Bookings');
    </script>


    <?php
    }
    ?>



<!-- Only to show the driver -->
<?php
if ($_SESSION['role'] == 1) {
    ?>
    <div class="col-lg-3">
        <div class="s7__widget-three card shadow-sm  card-info">
            <div class="content">
                <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
                    <?php

                    // For user
                    if ($_SESSION['role'] == 0) {
                        $userId = $_SESSION['id'];

                        $sql = "SELECT SUM(estimated_cost) AS total_earning FROM booking 
                                WHERE user_id = $userId 
                                AND status = 5 
                                AND is_delete = 0";

                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalEarning = $row['total_earning'] ?? 0;
                            echo number_format($totalEarning, 2); // Format to 2 decimal places
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }
                    } else {
                        $userId = $_SESSION['vehicle_id'];

                        $sql = "SELECT SUM(estimated_cost) AS total_earning FROM booking 
                                WHERE vehicle_id = $userId 
                                AND status = 5 
                                AND is_delete = 0";

                        $result = mysqli_query($conn, $sql);
                        if ($result) {
                            $row = mysqli_fetch_assoc($result);
                            $totalEarning = $row['total_earning'] ?? 0;
                            echo number_format($totalEarning, 2); // Format to 2 decimal places
                        } else {
                            echo "Error: " . mysqli_error($conn);
                        }

                    }

                    ?>
                </h1>
                <p class="mb-2 text-muted">Total Earnings</p>
            </div>
            <div class="icon s7__bg-primary rounded-circle">
               
                <span>रु</span>

            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="s7__widget-three card shadow-sm  card-info">
            <div class="content">
                <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
                    <?php
                    $userId = $_SESSION['vehicle_id'];

                    $sql = "SELECT SUM(estimated_cost) AS todays_earning FROM booking 
                                WHERE vehicle_id = $userId 
                                AND status = 5 
                                AND DATE(created_at) = CURDATE()
                                AND is_delete = 0";

                    $result = mysqli_query($conn, $sql);
                    if ($result) {
                        $row = mysqli_fetch_assoc($result);
                        $todaysEarning = $row['todays_earning'] ?? 0;
                        echo number_format($todaysEarning, 2); // Format with 2 decimals
                    } else {
                        echo "Error: " . mysqli_error($conn);
                    }
                    ?>
                </h1>
                <p class="mb-2 text-muted">Todays Earnings</p>
            </div>
            <div class="icon s7__bg-primary rounded-circle">
                <i class="las la-coins"></i>
            </div>
        </div>
    </div>
    </div>

    <?php

    $user_id = $_SESSION['vehicle_id'];


    // Fetch booking count per day
    $sql1 = "
    SELECT DATE(created_at) AS day, COUNT(*) AS bookings
    FROM booking
    WHERE vehicle_id = ? AND is_delete = 0 AND created_at >= CURDATE() - INTERVAL 6 DAY
    GROUP BY day
    ORDER BY day
";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("i", $user_id);
    $stmt1->execute();
    $result1 = $stmt1->get_result();

    $booking_data = [];
    while ($row = $result1->fetch_assoc()) {
        $booking_data[$row['day']] = $row['bookings'];
    }

    // Fetch earnings (SUM of estimated_cost) per day
    $sql2 = "
    SELECT DATE(created_at) AS day, SUM(estimated_cost) AS earnings
    FROM booking
    WHERE vehicle_id = ? AND is_delete = 0 AND created_at >= CURDATE() - INTERVAL 6 DAY
    GROUP BY day
    ORDER BY day
";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();

    $earning_data = [];
    while ($row = $result2->fetch_assoc()) {
        $earning_data[$row['day']] = round($row['earnings'], 2);
    }

    // Prepare last 7 days
    $labels = [];
    $bookings = [];
    $earnings = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = date('Y-m-d', strtotime("-$i days"));
        $labels[] = $date;
        $bookings[] = $booking_data[$date] ?? 0;
        $earnings[] = $earning_data[$date] ?? 0;
    }
    ?>

    <!-- <div class="container py-5"> -->
    <h3 class="text-center mb-4" style="color:#092448;">Your 7-Day Activity Overview</h3>
    <div class="row">
        <div class="col-md-6 mb-4">
            <canvas id="bookingChart" height="300"></canvas>
        </div>
        <div class="col-md-6 mb-4">
            <canvas id="earningChart" height="300"></canvas>
        </div>
    </div>
    <!-- </div> -->

    <script>
        const labels = <?= json_encode($labels); ?>;
        const bookings = <?= json_encode($bookings); ?>;
        const earnings = <?= json_encode($earnings); ?>;

        const themeColor = '#092448';

        function createGradient(ctx, height) {
            const gradient = ctx.createLinearGradient(0, 0, 0, height);
            gradient.addColorStop(0, 'rgba(9, 36, 72, 0.4)');
            gradient.addColorStop(1, 'rgba(9, 36, 72, 0.05)');
            return gradient;
        }

        function createChart(canvasId, label, data, yLabel) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            const gradient = createGradient(ctx, 300);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: label,
                        data: data,
                        fill: true,
                        backgroundColor: gradient,
                        borderColor: themeColor,
                        borderWidth: 2,
                        tension: 0.4,
                        pointBackgroundColor: themeColor,
                        pointHoverRadius: 6,
                        pointRadius: 4,
                        pointBorderColor: "#fff",
                        pointHoverBorderColor: "#fff"
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        title: {
                            display: true,
                            text: label + ' (Last 7 Days)',
                            color: themeColor,
                            font: {
                                size: 18,
                                weight: 'bold'
                            },
                            padding: {
                                top: 10,
                                bottom: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: themeColor,
                            titleColor: '#fff',
                            bodyColor: '#fff'
                        },
                        legend: {
                            display: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    },
                    scales: {
                        y: {
                            title: {
                                display: true,
                                text: yLabel,
                                color: themeColor,
                                font: {
                                    weight: 'bold'
                                }
                            },
                            ticks: {
                                color: themeColor
                            },
                            grid: {
                                color: '#ddd'
                            }
                        },
                        x: {
                            ticks: {
                                color: themeColor
                            },
                            grid: {
                                color: '#eee'
                            }
                        }
                    }
                }
            });
        }

        createChart('bookingChart', 'Number of Bookings', bookings, 'Bookings');
        createChart('earningChart', 'Earnings (NRs.)', earnings, 'Earnings in NRs.');
    </script>
    <?php
}
?>

<!-- CSS for card -->
<!-- Additional styling for hover and card effects -->
<style>
    .card-info {
        flex-direction: row-reverse;
        justify-content: space-around;
    }

    .s7__widget-three {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .s7__widget-three:hover {
        transform: translateY(-10px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .card {
        background-color: #fff;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .icon {

        font-size: 50px;
        color: #092448;
    }

    .content {
        text-align: end;
    }


    .content p {
        font-size: 1rem;
        font-weight: 500;
    }

    .content h3 {
        font-size: 30px;
        font-weight: 700;
    }

    /* Make sure the cards have smooth shadows */
    .card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .card:hover {
        transform: translateY(-10px);
    }

    /* Graphs Styling */
    canvas {
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    /* Header Styling */
    .card-header {
        background-color: #092448;
        font-weight: bold;
        font-size: 1.2rem;
    }

    canvas {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        padding: 20px;
    }

    /* Responsive Layout for smaller screens */
    @media (max-width: 768px) {
        .col-md-6 {
            margin-bottom: 20px;
        }
    }
</style>






<div class="recent_ride"
    style="max-width: 100%; margin: 30px auto; background: #f9f9f9; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05); padding: 20px;">
    <h3 style="text-align: left; color: #092448; font-size: 24px; margin-bottom: 20px;">Your Recent Ride</h3>

    <?php
    // Set $userId correctly based on role
    if ($_SESSION['role'] == 0) {
        $userId = $_SESSION['id']; // Normal user
        $sql = "SELECT * 
            FROM booking 
            WHERE user_id = ? AND status = 5 
            ORDER BY id DESC LIMIT 5";
    } else {
        $userId = $_SESSION['vehicle_id']; // Driver or vehicle owner
        $sql = "SELECT * 
            FROM booking 
            WHERE vehicle_id = ? AND status = 5 
            ORDER BY id DESC LIMIT 5";
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

    if ($result && $result->num_rows > 0): ?>
        <?php foreach ($result as $row): ?>
            <?php
            $pickup = htmlspecialchars($row['pick_up_place']);
            $destination = htmlspecialchars($row['destination']);
            $price = number_format($row['estimated_cost'], 2);
            ?>
            <div
                style="display: flex; justify-content: space-between; align-items: center; padding: 12px 15px; background: #fff; margin-bottom: 10px; border-radius: 6px; border: 1px solid #ddd;">
                <span style="font-size: 16px; color: #333; font-weight: 500;"><?= $pickup ?> → <?= $destination ?></span>
                <span style="color: green; font-weight: bold; font-size: 16px;">Rs. <?= $price ?></span>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align: center; color: #888; font-size: 15px; margin-top: 10px;">No bookings found.</div>
    <?php endif; ?>
</div>



<?php
if ($status != 0) {
    if ($_SESSION['role'] != 1) {
        ?>
        <div class="section">
            <h3>Take a ride to</h3>
            <div class="search-bar">
                <span>📍</span>
                <input type="text" placeholder="Search Destination" onclick="redirectToVehicleList()">
                <span class="search-icon">🔍</span>
            </div>

            <script>
                function redirectToVehicleList() {
                    window.location.href = "book_ride.php"; // Redirect to book_ride.php
                }
            </script>


            <div class="shortcut-bar">
                <div>
                    <div>🏠 Home</div>
                    <onsmall id="home-location">Fetching location...</onsmall>
                </div>
                <div>
                    <div>💼 Work</div>
                    <small>Set Address</small>
                </div>
            </div>

            <script>
                function getLocationName(lat, lon) {
                    const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lon}`;

                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            const displayName = data.address.city || data.address.town || data.address.village || data.display_name;
                            document.getElementById("home-location").textContent = displayName || "Location not found";
                        })
                        .catch(() => {
                            document.getElementById("home-location").textContent = "Unable to fetch location";
                        });
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        position => {
                            const lat = position.coords.latitude;
                            const lon = position.coords.longitude;
                            getLocationName(lat, lon);
                        },
                        () => {
                            document.getElementById("home-location").textContent = "Unable to fetch location";
                        }
                    );
                } else {
                    document.getElementById("home-location").textContent = "Geolocation not supported";
                }
            </script>

        </div>

        <hr>
    <?php }
} ?>


<?php
include_once 'master_footer.php';
?>