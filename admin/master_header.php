<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login");
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
    <link rel="stylesheet" href="../css/admin_feedback_form.css">
    <link rel="stylesheet" href="../css/feedback.css">
    <link rel="stylesheet" href="../css/show_vehicle.css">
    <link rel="stylesheet" href="../css/users.css">
    <link rel="stylesheet" href="../css/show_user.css">
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
    <!-- Sweetalert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">

    <!-- for bargraph and charts -->
    <!-- Include Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Include Bootstrap for grid layout -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">





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
            /* margin-top: 1.1rem; */
            border-right: 2px solid #092448;
        }

        .menu-bar .logo-img {
            padding-top: 18px;
        }
        a{
            text-decoration: none !important;
        }
        .heading-2 h2{
            color: #092448;
        } 
    </style>

</head>

<body style="padding: 0;">
    <!-- Sidebar Menu -->

    <div class="menu-bar">

        <a href="index">
            <div class="logo-img">
                <img src="../assets/logo1.png" alt="NagarYatra">
            </div>
        </a>

        <div class="menu-items">

            <a href="index">
                <div class="item <?php echo ($current_page == 'index') ? 'active' : ''; ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> &nbsp; Dashboard
                </div>
            </a>

            <a href="users">
                <div class="item <?php echo ($current_page == 'user') ? 'active' : ''; ?>">
                    <i class="fa fa-users" aria-hidden="true"></i> &nbsp; Users
                </div>
            </a>

            <a href="vehicles_company">
                <div class="item <?php echo ($current_page == 'vehicle_company') ? 'active' : ''; ?>">
                    <i class="fa fa-id-card-o" aria-hidden="true"></i> &nbsp; Vehicles Company
                </div>
            </a>

            <a href="vehicles_category">
                <div class="item <?php echo ($current_page == 'vehicles_category') ? 'active' : ''; ?>">
                    <i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp; Vehicles Category
                </div>
            </a>

            <a href="vehicles">
                <div class="item <?php echo ($current_page == 'vehicle') ? 'active' : ''; ?>">
                    <i class="fa fa-car" aria-hidden="true"></i>&nbsp; Vehicles
                </div>
            </a>

            <a href="booking">
                <div class="item <?php echo ($current_page == 'booking') ? 'active' : ''; ?>">
                    <i class="fa fa-ticket" aria-hidden="true"></i> &nbsp; Booking
                </div>
            </a>


          
            <a href="feedbacks">
                <div class="item <?php echo ($current_page == 'feedback') ? 'active' : ''; ?>">
                    <i class="fa fa-commenting-o" aria-hidden="true"></i> &nbsp; Feedback
                </div>
            </a>
            <a href="report" target="_blank">
                <div class="item <?php echo ($current_page == 'report') ? 'active' : ''; ?>">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> &nbsp; Report
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
        <div class="header-content px-4">
            <a href="index">
                <!-- <span style="color:#000000;">NagarYatra</span> -->
            </a>

            <div class="notification-account-info">
                <!-- Bell Icon with Notification Badge -->
                <!-- <div class="notification">-->
                    <!-- <i class="fa fa-bell" aria-hidden="true"></i>  -->
                    <i class="fa fa-user-circle" aria-hidden="true"></i>  <!-- Good for profile/admin -->

                    <!-- <sup class="badge">3</sup> -->
                    <!-- <div class="dropdown">
                        <div class="notif-item">Notification 1</div>
                        <div class="notif-item">Notification 2</div>
                        <div class="notif-item">Notification 3</div>
                    </div>
                </div> -->
                <!-- User Profile Icon -->
                <div class="account-info" id="profileTrigger">
                    <!-- <i class="fa fa-user-circle" aria-hidden="true"></i> -->
                    <img src="../assets/logo1.png" class="profile-img" alt="NagarYatra">


                    <div id="user-menu" class="user-menu">
                        <div class="user-header">
                            <img src="../assets/logo1.png" alt="NagarYatra">
                            <span>
                                <?php
                                echo "Admin";
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
                </div>
            </div>
        </div>
        <!-- </div> -->
        <div class="main-content">