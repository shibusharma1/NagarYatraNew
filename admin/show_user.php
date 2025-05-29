<?php
$title = "NagarYatra | User Profile";
$current_page = "user";

require_once '../config/connection.php';
include_once 'master_header.php';
?>

<!-- Link External CSS -->
<link rel="stylesheet" href="styles.css">
<style>
    .menu-bar{
            margin-top: -1.5rem !important;
    }
</style>
<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Query to fetch user details
    $sql = "SELECT u.name, u.email, u.phone, u.gender, u.dob,u.role, 
                   u.image AS user_image, u.address, u.status AS user_status, 
                   u.dl_number, u.dl_image, u.dl_expiry_date, 
                   u.otp, u.otp_expiry, u.is_verified
            FROM user u
            WHERE u.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        // Determine User Status
        $user_status_class = ($row['user_status'] == 1) ? "active" : "inactive";
        $user_status_text = ($row['user_status'] == 1) ? "Active" : "Blocked";

        // Determine Verification Status
        $verification_status = ($row['is_verified'] == 1) ? "<span style='color: green;'>Verified</span>" : "<span style='color: red;'>Not Verified</span>";
        ?>

        <div class="container">
            <!-- User Profile -->
            <div class="user-container">
                <div class="user-image">
                    <?php
                    $userImage = !empty($row['user_image']) && file_exists($row['user_image'])
                        ? htmlspecialchars($row['user_image'])
                        : '../assets/logo1.png'; // Change to your actual default image path
                    ?>

                    <img src="<?php echo $userImage; ?>" alt="User Image">

                    <div class="status <?php echo $user_status_class; ?>"><?php echo $user_status_text; ?></div>
                </div>

                <div class="user-info">
                    <div class="info-item">
                        <i class="fas fa-user"></i> <strong>Name:</strong> <?php echo htmlspecialchars($row['name']); ?>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-envelope"></i> <strong>Email:</strong> <?php echo htmlspecialchars($row['email']); ?>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-phone"></i> <strong>Phone:</strong> <?php echo htmlspecialchars($row['phone']); ?>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-venus-mars"></i> <strong>Gender:</strong>
                        <?php echo htmlspecialchars($row['gender']); ?>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-calendar"></i> <strong>DOB:</strong> <?php echo htmlspecialchars($row['dob']); ?>
                    </div>

                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i> <strong>Address:</strong>
                        <?php echo htmlspecialchars($row['address']); ?>
                    </div>
                    <?php
                    if ($row['role'] == 1) {
                        ?>
                        <div class="info-item">
                            <i class="fas fa-id-card"></i> <strong>DL Number:</strong>
                            <?php echo htmlspecialchars($row['dl_number']); ?>
                        </div>

                        <div class="info-item">
                            <i class="fas fa-calendar-check"></i> <strong>DL Expiry Date:</strong>
                            <?php echo htmlspecialchars($row['dl_expiry_date']); ?>
                        </div>
                    <?php } ?>

                    <div class="info-item">
                        <i class="fas fa-shield-alt"></i> <strong>Verification Status:</strong>
                        <?php echo $verification_status; ?>
                    </div>
                </div>
            </div>

            <a href="users.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Users</a>
        </div>

        <?php
    } else {
        echo "<p class='text-center text-danger'>User not found.</p>";
    }
} else {
    echo "<p class='text-center text-danger'>Invalid request.</p>";
}
include_once 'master_footer.php';
?>