<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../login");
    // echo "invalid!!!";
}
?>

<!-- code to extract role -->
<?php
require_once('../config/connection.php');

// SQL query
$id = $_SESSION['id'];
$sql = "SELECT * FROM user WHERE id = $id";
$result = mysqli_query($conn, $sql);

// Fetch the role
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $role = $row['role'];
    $status = $row['status'];
    $name = $row['name'];
    $image = $row['image'];
} else {
    $role = null; // or handle error
    $status = 1;
    $name = "Nagar Yatra";
    $image = "../assets/logo1.png";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>
        <?php echo $title; ?>
    </title>
    <link rel="icon" type="image/png" sizes="64x64" href="../assets/logo1.png" />
    <link rel="stylesheet" href="../css/admin_header.css">
    <!-- <link rel="stylesheet" href="../css/admin_feedback_form.css"> -->
    <link rel="stylesheet" href="../css/feedback.css">
    <link rel="stylesheet" href="../css/show_vehicle.css">
    <link rel="stylesheet" href="../css/users.css">
    <link rel="stylesheet" href="../css/userindex.css">
    <link rel="stylesheet" href="../css/show_user.css">
    <link rel="stylesheet" href="../css/emergencycontacts.css">
    <link rel="stylesheet" href="../css/mechanicscontacts.css">
    <link rel="stylesheet" href="../css/book_ride.css">
    <link rel="stylesheet" href="../css/inviteandshare.css">
    <link rel="stylesheet" href="../css/add_vehicle.css">
    <!-- summernote editor -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote-lite.min.js"></script>
    <!-- summernote end -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- For Dashboard -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDumdDv9jxmpC0yaURPXnqkk4kssB8R3C4&libraries=places"></script>


    <!-- Google API:   AIzaSyDumdDv9jxmpC0yaURPXnqkk4kssB8R3C4 -->
    <!-- Emergency Contacts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <!-- For mechanics -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- sweetalert CDN-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Include Bootstrap for grid layout -->
    <link rel="stylesheet" href="https://cdn.lineawesome.com/1.3.0/line-awesome.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.lineawesome.com/1.3.0/line-awesome.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css"
        integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Add in <head> or before closing </body> -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


    <style>
        a {
            text-decoration: none;
        }

        .dashboard-card {
            padding: 20px;
            border-radius: 10px;
            color: #fff;
        }

        .bg-blue {
            background-color: #007bff;
        }

        .bg-green {
            background-color: #28a745;
        }

        .bg-yellow {
            background-color: #ffc107;
        }

        .bg-red {
            background-color: #dc3545;
        }

        .chart-container {
            width: 100%;
            max-width: 600px;
            margin: auto;
        }

        #map {
            height: 300px;
            border-radius: 10px;
        }

        .menu-bar {
            border-right: 2px solid #092448;
        }

        .menu-bar .logo-img {
            padding-top: 18px;
        }

        a {
            text-decoration: none !important;
        }

        .heading-2 h2 {
            color: #092448;
        }

        /* CSS for the notifications */
        .dropdown {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
        }

        .notif-item {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .notif-item:last-child {
            border-bottom: none;
        }

        .notif-item.unread {
            font-weight: 300;
            background-color: #fff8c6 !important;
            /* light yellow */
            border-left: 3px solid orange;
            padding: 8px;
            margin-bottom: 5px;
        }

        .view-all {
            text-align: center;
            margin-top: 10px;
        }

        .view-all a {
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
        }

        .view-all a:hover {
            text-decoration: underline;
        }

        .red-bell {
            color: red;
        }

        .shake {
            animation: shake 0.5s infinite;
        }

        @keyframes shake {
            0% {
                transform: rotate(0deg);
            }

            25% {
                transform: rotate(10deg);
            }

            50% {
                transform: rotate(-10deg);
            }

            75% {
                transform: rotate(10deg);
            }

            100% {
                transform: rotate(0deg);
            }
        }

        .dropdown {
            position: absolute;
            background: #fff;
            border: 1px solid #ccc;
            padding: 10px;
            z-index: 1000;
        }

        .notif-item.unread {
            font-weight: 600;
            background-color: #f9f9f9;
        }
    </style>

</head>

<body style="padding: 0;">
    <!-- Sidebar Menu -->
    <div class="menu-bar" style="
    margin-top: -1.5rem !important;
">

        <a href="index">
            <div class="logo-img">
                <img src="../assets/logo1.png" alt="NagarYatra" style="
    margin-top: -20px;
">
            </div>
        </a>

        <div class="menu-items">
            <!-- Time to become a driver -->

            <?php
            if ($row['role'] != 1) {

                // date_default_timezone_set('UTC'); // Adjust timezone if needed
                date_default_timezone_set('Asia/Kathmandu');


                $is_within_5_min = false;
                $seconds_left = 0; // How many seconds still remaining
            
                if (!empty($row) && !empty($row['created_at'])) {
                    $created_at_timestamp = strtotime($row['created_at']);
                    $current_timestamp = time();

                    if ($created_at_timestamp !== false) {
                        $diff_in_seconds = $current_timestamp - $created_at_timestamp;

                        if ($diff_in_seconds >= 0 && $diff_in_seconds < 300) {
                            $is_within_5_min = true;
                            $seconds_left = 300 - $diff_in_seconds; // Remaining seconds
                        }
                    }
                }
                ?>

                <?php
                if ($is_within_5_min): ?>
                    <a href="add_vehicle" id="become-driver-button">
                        <div class="item <?php echo ($current_page == 'add_vehicle') ? 'active' : ''; ?>">
                            <i class="fa fa-car" aria-hidden="true"></i> &nbsp; Become a Driver
                        </div>
                    </a>

                    <script>
                        // JavaScript to auto-hide after remaining seconds
                        setTimeout(function () {
                            var btn = document.getElementById('become-driver-button');
                            if (btn) {
                                btn.style.display = 'none';
                            }
                        }, <?php echo ($seconds_left * 1000); ?>); // PHP calculates seconds, JS uses milliseconds
                    </script>
                <?php endif;
            } ?>


            <a href="index">
                <div class="item <?php echo ($current_page == 'index') ? 'active' : ''; ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> &nbsp; Dashboard
                </div>
            </a>

            <?php
            if ($row['status'] != 0) {
                if ($row['role'] != 1) {
                    ?>
                    <a href="book_ride.php">
                        <div class="item <?php echo ($current_page == 'vehicle_list') ? 'active' : ''; ?>">
                            <i class="fa fa-route" aria-hidden="true"></i> &nbsp; Book a Ride
                        </div>
                    </a>

                    <a href="pre_book_vehicle.php">
                        <div class="item <?php echo ($current_page == 'prebooking') ? 'active' : ''; ?>">
                            <i class="fa fa-route" aria-hidden="true"></i> &nbsp; Pre-Booking
                        </div>
                    </a>

                    <a href="ride_status.php">
                        <div class="item <?php echo ($current_page == 'ride_status') ? 'active' : ''; ?>">
                            <i class="fa fa-location-arrow" aria-hidden="true"></i> &nbsp; Ride Status
                        </div>
                    </a>
                <?php } ?>
                <?php
                if ($row['role'] == 1) {
                    ?>
                    <a href="show_vehicle">
                        <div class="item <?php echo ($current_page == 'show_vehicle') ? 'active' : ''; ?>">
                            <i class="fa fa-car" aria-hidden="true"></i> &nbsp; Vehicle Details
                        </div>
                    </a>
                <?php } ?>

                <?php
                if ($row['role'] == 1) {

                    ?>
                    <a href="ride_request">
                        <div class="item <?php echo ($current_page == 'ride_request') ? 'active' : ''; ?>">
                            <i class="fa fa-route" aria-hidden="true"></i> &nbsp; Ride Request
                        </div>
                    </a>
                <?php } ?>

                <?php
                if ($row['role'] == 1) {
                    ?>
                    <a href="approved_ride">
                        <div class="item <?php echo ($current_page == 'approved_ride') ? 'active' : ''; ?>">
                            <i class="fa fa-route" aria-hidden="true"></i> &nbsp; Approved Ride
                        </div>
                    </a>
                <?php } ?>

                <?php if ($row['role'] == 1): ?>
                    <!-- <a href="mechanicscontacts">
                    <div class="item 
                    <?php
                    // echo ($current_page == 'mechanicscontacts') ? 'active' : '';
                    ?>
                        <i class="fa fa-phone" aria-hidden="true"></i> &nbsp; Call a Mechanics
                    </div>
                </a> -->
                <?php endif ?>



                <a href="ride_history.php">
                    <div class="item <?php echo ($current_page == 'ride_history') ? 'active' : ''; ?>">
                        <i class="fa fa-history" aria-hidden="true"></i> &nbsp; Ride History
                    </div>
                </a>

                <?php
                if ($row['role'] == 1) {
                    ?>
                    <a href="earnings">
                        <div class="item <?php echo ($current_page == 'earnings') ? 'active' : ''; ?>">
                            <i class="fa fa-dollar" aria-hidden="true"></i> &nbsp; Earnings
                        </div>
                    </a>
                <?php } ?>

                <a href="feedback">
                    <div class="item <?php echo ($current_page == 'feedback') ? 'active' : ''; ?>">
                        <i class="fa fa-commenting-o" aria-hidden="true"></i> &nbsp; Send Feedback
                    </div>
                </a>
            <?php } ?>

            <a href="emergencycontacts">
                <div class="item <?php echo ($current_page == 'emergencycontacts') ? 'active' : ''; ?>">
                    <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> &nbsp; Emergency Contacts
                </div>
            </a>

            <a href="logout">
                <div class="item">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> &nbsp; Logout
                </div>
            </a>

        </div>
    </div>
    <!-- Main Content -->
    <div class="contents-side">
        <!-- Header Section -->
        <div class="header-content">
            <a href="index">
                <!-- <span style="color:#000000;">NagarYatra</span> -->
            </a>

            <div class="notification-account-info">
                <!-- Bell Icon with Notification Badge -->
                <!-- <i class="fa fa-user" aria-hidden="true"></i> -->
                <?php if ($row['role'] == 1): ?>
                    <i class="fa fa-car" aria-hidden="true" style="font-size: 24px;"></i>
                <?php endif ?>

                <?php if ($row['role'] == 0): ?>
                    <i class="fa fa-user" aria-hidden="true" style="font-size: 24px;"></i>
                <?php endif ?>

                <?php
                $user_id = $_SESSION['id'];
                $sql = "SELECT * FROM notifications 
        WHERE user_id = ? 
        ORDER BY id DESC 
        LIMIT 3";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();

                $hasUnread = false;
                $notifications = [];

                while ($row = $result->fetch_assoc()) {
                    $notifications[] = $row;
                    if ($row['mark_as_read'] == 0) {
                        $hasUnread = true;
                    }
                }
                ?>

                <div class="notification">
                    <i id="bellIcon" class="fa fa-bell <?= $hasUnread ? 'red-bell shake' : '' ?>"
                        aria-hidden="true"></i>

                    <!-- Notification Dropdown -->
                    <div id="notifDropdown" class="dropdown" style="width: 400px; display: none;text-align:left;">
                        <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $row): ?>
                                <div class="notif-item <?= $row['mark_as_read'] == 0 ? 'unread' : '' ?>">
                                    <?= htmlspecialchars($row['message']) ?>
                                    <?php echo "<br>"; ?>
                                    <?= htmlspecialchars($row['created_at']) ?>
                                    
                                </div>
                            <?php endforeach; ?>
                            <div class="view-all">
                                <a href="view-all-notifications.php">View All Notifications</a>
                            </div>
                        <?php else: ?>
                            <div class="notif-item">No notifications.</div>
                        <?php endif; ?>
                    </div>
                </div>



                <script>
                    const bellIcon = document.getElementById('bellIcon');
                    const notifDropdown = document.getElementById('notifDropdown');

                    bellIcon.addEventListener('click', () => {
                        if (notifDropdown.style.display === 'none') {
                            notifDropdown.style.display = 'block';
                        } else {
                            notifDropdown.style.display = 'none';
                        }
                    });
                </script>

                <!-- User Profile Icon -->
                <!-- <div class="account-info">
                    <img src="../assets/logo1.png" class="profile-img" alt="NagarYatra">

                    <div class="user-menu">
                        <div class="user-header">
                            <img src="../assets/logo1.png" alt="NagarYatra">
                            <span>
                                <?php
                                echo $name;
                                ?>
                            </span>
                        </div>
                        <a href="profile" style="text-decoration:none;color:#000000;">
                            <div class="menu-item">Edit Profile</div>
                        </a>
                        <a href="change_password" style="text-decoration:none;color:#000000;">
                            <div class="menu-item">Change Password</div>
                        </a>
                        <a href="logout">
                            <div class="menu-item">Logout</div>
                        </a>

                    </div>
                </div> -->
                <div class="account-info">
                    <img src="<?php echo $image; ?>" class="profile-img" id="profileTrigger" alt="NagarYatra">

                    <div class="user-menu" id="userMenu">
                        <div class="user-header">
                            <img src="<?php echo $image ?>" alt="NagarYatra">
                            <span>
                                <?php echo $name; ?>
                            </span>
                        </div>
                        <a href="profile">
                            <div class="menu-item">Edit Profile</div>
                        </a>
                        <!-- <a href="change_password">
                            <div class="menu-item">Change Password</div>
                        </a> -->
                        <a href="logout">
                            <div class="menu-item">Logout</div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- </div> -->
        <div class="main-content">