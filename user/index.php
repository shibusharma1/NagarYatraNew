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

// Set $userId correctly based on role
if ($_SESSION['role'] == 0) {
    $userId = $_SESSION['id']; // Normal user
    $sql = "SELECT * 
            FROM booking 
            WHERE user_id = ? AND status == 5 
            ORDER BY id DESC";
} else {
    $userId = $_SESSION['vehicle_id']; // Driver or vehicle owner
    $sql = "SELECT * 
            FROM booking 
            WHERE vehicle_id = ? 
            ORDER BY id DESC";
}

// Pagination setup
$limit = 5; // items per page
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
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

<h2 style="color:#092448;">Dashboard</h2>
<!-- <img src="https://upload.wikimedia.org/wikipedia/commons/5/5b/Pathao_Logo.png" alt="Pathao" class="logo"> -->
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
<!-- <div class="icon-menu"></div> -->
</header>
<div class="recent_ride"
    style="max-width: 100%; margin: 30px auto; background: #f9f9f9; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.05); padding: 20px;">
    <h3 style="text-align: left; color: #092448; font-size: 24px; margin-bottom: 20px;">Your Recent Ride</h3>

    <?php if ($result && $result->num_rows > 0): ?>
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


<!-- <div class="service">
        <img src="../admin/uploads/bike.png" alt="Bike" title="Comfortable for 1 riders, secure with locks and GPS, and highly convenient for quick city travel.">
        <div>Bike</div>
    </div>
    <div class="service">
        <img src="../admin/uploads/car.png" alt="car" title="Spacious for 4 passengers, safe with seatbelts and airbags, and convenient for comfortable long or short trips.">
        <div>Car</div>
    </div>
    <div class="service">
        <img src="../admin/uploads/auto.png" alt="Auto" title="Ideal for 2–3 passengers, offers moderate safety, and convenient for short city rides.">
        <div>Auto</div>
    </div>
    <div class="service">
        <img src="../admin/uploads/microbus.png" alt="Micro Bus" title="Comfortable for 8–15 passengers, ensures good security, and perfect for group travel.">
        <div>Micro Bus</div>
    </div> -->
<!-- </div> -->
<?php
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
<?php } ?>
<!-- Invite Friends Section -->
<div class="invite-section">
    <div class="invite-content">
        <h2>Invite Friends & Get Discount</h2>
        <p>Invite your friends to join the vehicle booking system and get exciting discounts on your next ride!</p>

        <!-- Invite Code Input (to share with friends) -->
        <div class="invite-code">
            <label for="inviteCode">Your Referral Code:</label>
            <input type="text" id="inviteCode" value="ABCD1234" readonly class="input-field">
            <button onclick="copyInviteCode()" class="copy-btn">Copy Code</button>
        </div>

        <!-- Invite Friend Button -->
        <div class="invite-btn-container">
            <button onclick="sendInvite()" class="invite-btn">Invite Friends</button>
        </div>

        <!-- <p class="terms">By inviting, you agree to our <a href="terms_and_conditions.php">terms and conditions</a>.</p> -->
    </div>
</div>
<script>
    // Function to copy the referral code to clipboard
    function copyInviteCode() {
        var inviteCode = document.getElementById("inviteCode");
        inviteCode.select();
        inviteCode.setSelectionRange(0, 99999); // For mobile devices
        document.execCommand("copy");
        // alert("Referral code copied to clipboard!");
    }

    // Function to handle sending invites (just a placeholder for now)
    function sendInvite() {
        // In a real-world scenario, you'd send the invite via email or a messaging API
        alert("Invite sent to your friends! They will receive a referral link and you will earn discounts.");
    }
</script>


<?php
include_once 'master_footer.php';
?>