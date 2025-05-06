<?php
$title = "NagarYatra | Dashboard";
$current_page = "index";

include_once 'master_header.php';
require_once '../config/connection.php';

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
<!-- <h2>Dashboard</h2> -->
<!-- Users details -->
<div class="row my-5">
  <!-- Total Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
          <?php
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Total Users</p>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <i class="las la-users"></i>
      </div>
    </div>
  </div>

  <!-- Active Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-success font-weight-bold" style="color:#092448 !important;">
          <?php
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND status = 1"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Active Users</p>
      </div>
      <div class="icon s7__bg-success rounded-circle">
        <i class="las la-user-plus"></i>
      </div>
    </div>
  </div>

  <!-- Banned Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-danger font-weight-bold" style="color:#092448 !important;">
          <?php
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND status = 0"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Banned Users</p>
      </div>
      <div class="icon s7__bg-danger rounded-circle">
        <i class="las la-user-times"></i>
      </div>
    </div>
  </div>

  <!-- Today Join User Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-info font-weight-bold" style="color:#092448 !important;">
          <?php
          $today = date('Y-m-d');
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND status = 1 AND DATE(created_at) = '$today'"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Today Join User</p>
      </div>
      <div class="icon s7__bg-info rounded-circle">
        <i class="las la-user"></i>
      </div>
    </div>

  </div>
</div>

<!-- Total Driver -->

<div class="row my-5">
  <!-- Total Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
          <?php
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND role = 1"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Total Drivers</p>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <!-- <i class="las la-users"></i> -->
        <i class="las la-user-tie"></i>

      </div>
    </div>
  </div>

  <!-- Active Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-success font-weight-bold" style="color:#092448 !important;">
          <?php
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND status = 1 AND role = 1"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Active Drivers</p>
      </div>
      <div class="icon s7__bg-success rounded-circle">
        <!-- <i class="las la-user-plus"></i> -->
        <i class="las la-user-check"></i>
      </div>
    </div>
  </div>

  <!-- Banned Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-danger font-weight-bold" style="color:#092448 !important;">
          <?php
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND status = 0 AND role = 1"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Banned Drivers</p>
      </div>
      <div class="icon s7__bg-danger rounded-circle">
        <!-- <i class="las la-user-times"></i> -->
        <i class="las la-user-slash"></i>
      </div>
    </div>
  </div>

  <!-- Today Join User Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-info font-weight-bold" style="color:#092448 !important;">
          <?php
          $today = date('Y-m-d');
          $sql = "SELECT COUNT(id) AS total_users FROM user WHERE is_delete = 0 AND status = 1 AND role = 1 AND DATE(created_at) = '$today'"; // Ensure deleted users are excluded
          $result = mysqli_query($conn, $sql);
          if ($result) {
            $row = mysqli_fetch_assoc($result);
            $totalUsers = $row['total_users'];
            echo $totalUsers;
          } else {
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Today Join Drivers</p>
      </div>
      <div class="icon s7__bg-info rounded-circle">
        <!-- <i class="las la-user"></i> -->
        <i class="las la-user-plus"></i>
      </div>
    </div>

  </div>
</div>

<!-- Vehicle and booking -->
<div class="row my-5">
  <!-- Total Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-primary font-weight-bold" style="color:#092448 !important;">
          <?php
          // SQL query to count active vehicles
          $sql = "SELECT COUNT(id) AS total_active_vehicles FROM vehicle WHERE is_delete = 0";

          // Execute the query
          $result = mysqli_query($conn, $sql);

          // Check if query was successful
          if ($result) {
            // Fetch the result
            $row = mysqli_fetch_assoc($result);
            // Get the total count of active vehicles
            $totalActiveVehicles = $row['total_active_vehicles'];
            // Display the result
            echo $totalActiveVehicles;
          } else {
            // Display error if query fails
            echo "Error: " . mysqli_error($conn);
          }
          ?>
        </h1>
        <p class="mb-2 text-muted">Total Vehicle</p>
      </div>
      <div class="icon s7__bg-primary rounded-circle">
        <!-- <i class="las la-users"></i> -->
        <i class="las la-car"></i>
      </div>
    </div>
  </div>

  <!-- Active Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-success font-weight-bold" style="color:#092448 !important;">
          <?php
          // SQL query to count active vehicles
          $sql = "SELECT COUNT(id) AS total_active_vehicles FROM vehicle WHERE is_delete = 0 AND status = 1 AND is_approved = 1";

          // Execute the query
          $result = mysqli_query($conn, $sql);

          // Check if query was successful
          if ($result) {
            // Fetch the result
            $row = mysqli_fetch_assoc($result);
            // Get the total count of active vehicles
            $totalActiveVehicles = $row['total_active_vehicles'];
            // Display the result
            echo $totalActiveVehicles;
          } else {
            // Display error if query fails
            echo "Error: " . mysqli_error($conn);
          }
          ?>

        </h1>
        <p class="mb-2 text-muted">Active Vehicle</p>
      </div>
      <div class="icon s7__bg-success rounded-circle">
        <!-- <i class="las la-user-plus"></i> -->
        <i class="las la-shuttle-van"></i>
      </div>
    </div>
  </div>

  <!-- Banned Users Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-danger font-weight-bold" style="color:#092448 !important;">
          <?php
          // SQL query to count active vehicles
          $sql = "SELECT COUNT(id) AS total_active_vehicles FROM booking";

          // Execute the query
          $result = mysqli_query($conn, $sql);

          // Check if query was successful
          if ($result) {
            // Fetch the result
            $row = mysqli_fetch_assoc($result);
            // Get the total count of active vehicles
            $totalActiveVehicles = $row['total_active_vehicles'];
            // Display the result
            echo $totalActiveVehicles;
          } else {
            // Display error if query fails
            echo "Error: " . mysqli_error($conn);
          }
          ?>

        </h1>
        <p class="mb-2 text-muted">Total Booking</p>
      </div>
      <div class="icon s7__bg-danger rounded-circle">
        <!-- <i class="las la-user-times"></i> -->
        <i class="las la-book"></i> <!-- general bookings/reservations -->

      </div>
    </div>
  </div>

  <!-- Today Join User Card -->
  <div class="col-lg-3">
    <div class="s7__widget-three card shadow-sm  card-info">
      <div class="content">
        <h1 class="mb-0 text-info font-weight-bold" style="color:#092448 !important;">
          <?php
          // SQL query to count active vehicles
          $sql = "SELECT COUNT(id) AS total_active_vehicles FROM booking WHERE status = 2";

          // Execute the query
          $result = mysqli_query($conn, $sql);

          // Check if query was successful
          if ($result) {
            // Fetch the result
            $row = mysqli_fetch_assoc($result);
            // Get the total count of active vehicles
            $totalActiveVehicles = $row['total_active_vehicles'];
            // Display the result
            echo $totalActiveVehicles;
          } else {
            // Display error if query fails
            echo "Error: " . mysqli_error($conn);
          }
          ?>

        </h1>
        <p class="mb-2 text-muted">Pending Booking</p>
      </div>
      <div class="icon s7__bg-info rounded-circle">
        <!-- <i class="las la-user"></i> -->
        <i class="las la-user-clock"></i>

      </div>
    </div>

  </div>
</div>


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

  /* Responsive Layout for smaller screens */
  @media (max-width: 768px) {
    .col-md-6 {
      margin-bottom: 20px;
    }
  }
</style>


<!-- Two cards to show latest five Passanger and Driver -->
<!-- <div class="container mt-4"> -->
<div class="row">
  <!-- Latest 5 Passengers -->
  <div class="col-md-6">
    <div class="card shadow mb-4">
      <div class="card-header text-white" style="background-color: #092448;">
        <i class="fa fa-users"></i> Latest 5 Passengers
      </div>
      <div class="card-body p-3">
        <?php
        $passengerQuery = "SELECT name, email, phone, gender, created_at FROM user WHERE role = 0 AND is_delete = 0 ORDER BY created_at DESC LIMIT 5";
        $passengerResult = mysqli_query($conn, $passengerQuery);

        if (mysqli_num_rows($passengerResult) > 0) {
          echo "<ul class='list-group'>";
          while ($row = mysqli_fetch_assoc($passengerResult)) {
            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <div>
                                        <strong>" . htmlspecialchars($row['name']) . "</strong><br>
                                        <small class='text-muted'>" . htmlspecialchars($row['email']) . " | " . htmlspecialchars($row['phone']) . "</small><br>
                                        <span class='badge bg-secondary'>" . htmlspecialchars($row['gender']) . "</span>
                                    </div>
                                    <small class='text-muted'>" . date('d M Y', strtotime($row['created_at'])) . "</small>
                                </li>";
          }
          echo "</ul>";
        } else {
          echo "<p class='text-muted'>No recent passengers found.</p>";
        }
        ?>
      </div>
    </div>
  </div>

  <!-- Latest 5 Drivers -->
  <div class="col-md-6">
    <div class="card shadow mb-4">
      <div class="card-header text-white" style="background-color: #092448;">
        <i class="fa fa-car"></i> Latest 5 Drivers
      </div>
      <div class="card-body p-3">
        <?php
        $driverQuery = "SELECT name, email, phone, dl_number, created_at FROM user WHERE role = 1 AND is_delete = 0 ORDER BY created_at DESC LIMIT 5";
        $driverResult = mysqli_query($conn, $driverQuery);

        if (mysqli_num_rows($driverResult) > 0) {
          echo "<ul class='list-group'>";
          while ($row = mysqli_fetch_assoc($driverResult)) {
            echo "<li class='list-group-item d-flex justify-content-between align-items-center'>
                                    <div>
                                        <strong>" . htmlspecialchars($row['name']) . "</strong><br>
                                        <small class='text-muted'>" . htmlspecialchars($row['email']) . " | " . htmlspecialchars($row['phone']) . "</small><br>
                                        <span class='badge bg-dark'>DL: " . htmlspecialchars($row['dl_number']) . "</span>
                                    </div>
                                    <small class='text-muted'>" . date('d M Y', strtotime($row['created_at'])) . "</small>
                                </li>";
          }
          echo "</ul>";
        } else {
          echo "<p class='text-muted'>No recent drivers found.</p>";
        }
        ?>
      </div>
    </div>
  </div>
</div>
<!-- </div> -->



<!-- Php code for the bargraph and line graph -->
<?php
// Fetching last 7 days of new users
$newUsersQuery = "SELECT COUNT(id) AS user_count, DATE(created_at) AS join_date 
                  FROM user 
                  WHERE created_at >= CURDATE() - INTERVAL 7 DAY 
                  GROUP BY join_date 
                  ORDER BY join_date ASC";
$newUsersResult = mysqli_query($conn, $newUsersQuery);

// Prepare data for new users graph
$newUsersData = [];
$dates = [];
while ($row = mysqli_fetch_assoc($newUsersResult)) {
  $dates[] = $row['join_date'];
  $newUsersData[] = $row['user_count'];
}

// Fetching booking data for the last 7 days
$bookingQuery = "SELECT COUNT(id) AS booking_count, DATE(booking_date) AS booking_date 
                 FROM booking 
                 WHERE booking_date >= CURDATE() - INTERVAL 7 DAY 
                 GROUP BY booking_date 
                 ORDER BY booking_date ASC";
$bookingResult = mysqli_query($conn, $bookingQuery);

// Prepare data for booking graph
$bookingData = [];
$bookingDates = [];
while ($row = mysqli_fetch_assoc($bookingResult)) {
  $bookingDates[] = $row['booking_date'];
  $bookingData[] = $row['booking_count'];
}
?>
<!-- container for the bargraph and line graph -->
<!-- <div class="container mt-5"> -->
<div class="row">
  <!-- Bar Graph for Last 7 Days New Users -->
  <div class="col-md-6">
    <div class="card shadow mb-4">
      <div class="card-header text-white" style="background-color: #092448;">
        <i class="fa fa-users"></i> Last 7 Days New Users
      </div>
      <div class="card-body">
        <canvas id="newUsersChart"></canvas>
      </div>
    </div>
  </div>

  <!-- Line Graph for Booking Count in Last 7 Days -->
  <div class="col-md-6">
    <div class="card shadow mb-4">
      <div class="card-header text-white" style="background-color: #092448;">
        <i class="fa fa-calendar-check"></i> Booking Count in Last 7 Days
      </div>
      <div class="card-body">
        <canvas id="bookingChart"></canvas>
      </div>
    </div>
  </div>
</div>
<!-- </div> -->
<script>
  // Prepare data for new users graph
  const newUsersLabels = <?php echo json_encode($dates); ?>;
  const newUsersData = <?php echo json_encode($newUsersData); ?>;

  // Prepare data for booking graph
  const bookingLabels = <?php echo json_encode($bookingDates); ?>;
  const bookingData = <?php echo json_encode($bookingData); ?>;

  // Bar Chart for New Users
  const newUsersCtx = document.getElementById('newUsersChart').getContext('2d');
  const newUsersChart = new Chart(newUsersCtx, {
    type: 'bar',
    data: {
      labels: newUsersLabels,
      datasets: [{
        label: 'New Users',
        data: newUsersData,
        backgroundColor: 'rgba(9, 36, 72, 0.8)', // Theme color
        borderColor: 'rgba(9, 36, 72, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      animation: {
        duration: 1000,
        easing: 'easeOutBounce'
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });

  // Line Chart for Bookings
  const bookingCtx = document.getElementById('bookingChart').getContext('2d');
  const bookingChart = new Chart(bookingCtx, {
    type: 'line',
    data: {
      labels: bookingLabels,
      datasets: [{
        label: 'Bookings',
        data: bookingData,
        borderColor: 'rgba(9, 36, 72, 1)', // Theme color
        backgroundColor: 'rgba(9, 36, 72, 0.2)',
        fill: true,
        tension: 0.4,
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      animation: {
        duration: 1000,
        easing: 'easeOutBounce'
      },
      scales: {
        y: {
          beginAtZero: true
        }
      }
    }
  });
</script>


<div class="mt-4 text-center" style="display: none;">
  <label for="chartType">Select Chart Type:</label>
  <select id="chartType" class="form-select w-25 d-inline-block">
    <option value="bar">Bar Chart</option>
    <option value="line">Line Chart</option>
    <option value="pie">Pie Chart</option>
  </select>
</div>
<div class="chart-container mt-3" style="display: none;">
  <canvas id="dashboardChart"></canvas>
</div>
<div class="mt-4">
  <h4>Location Map</h4>
  <div id="map"></div>
</div>
</div>
<script>
  let ctx = document.getElementById('dashboardChart').getContext('2d');
  let chartType = document.getElementById('chartType').value;
  let dashboardChart = new Chart(ctx, {
    type: chartType,
    data: {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
      datasets: [{
        label: 'Users',
        data: [30, 50, 70, 40, 90, 60],
        backgroundColor: 'rgba(54, 162, 235, 0.5)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    }
  });
  document.getElementById('chartType').addEventListener('change', function () {
    dashboardChart.destroy();
    dashboardChart = new Chart(ctx, { type: this.value, data: dashboardChart.data });
  });

  function initMap() {
    let defaultLocation = { lat: 26.460763, lng: 87.265073 }; // Fallback location

    let map = new google.maps.Map(document.getElementById('map'), {
      center: defaultLocation,
      zoom: 100,
      mapTypeId: 'roadmap'
    });

    // Check if Geolocation is supported
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        function (position) {
          let userLocation = {
            lat: position.coords.latitude,
            lng: position.coords.longitude
          };

          // Set map center to user's current location
          map.setCenter(userLocation);

          // Add a marker at user's location
          new google.maps.Marker({
            position: userLocation,
            map: map,
            title: "You are here!"
          });
        },
        function (error) {
          console.error("Geolocation error:", error);
        },
        { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 } // High accuracy settings
      );
    } else {
      console.log("Geolocation is not supported by this browser.");
    }
  }

  window.onload = initMap;


</script>

<?php
include_once 'master_footer.php';
?>