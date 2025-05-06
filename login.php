<?php
// when user back to this page
// session_start();
// session_unset();
// session_destroy();

?>

<?php
session_start();
require_once('config/connection.php');
$title = "NagarYatra | Login";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = stripcslashes($_POST['email']);
  $password = $_POST['password'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];
  $email = mysqli_real_escape_string($conn, $email);

  // Fetch user details
  $sql = "SELECT * FROM user WHERE  email = '$email' AND is_verified = 1";
  $sresult = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($sresult);

  if ($row && password_verify($password, $row['password'])) {
    $_SESSION['login_success'] = true;
    $_SESSION['id'] = $row['id'];
    $_SESSION['role'] = $row['role'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['vehicle_id'] = $row['vehicle_id'];
    $_SESSION['login_success'] = "Login Successful";
    // header("Location: user/hello");

    // Updating location when user logins
    $stmt = $conn->prepare("UPDATE user SET latitude = ?, longitude = ? WHERE email = ?");
    $stmt->bind_param("dds", $latitude, $longitude, $email);

    if ($stmt->execute()) {
      // echo "Location updated successfully.";
    } else {
      // echo "Failed to update location.";
    }
    header("Location: user/index.php");

    exit();
  } else {
    $_SESSION['login_error'] = true;
    $error_message = "Invalid Credentials";
  }
}
include_once "register_login_header.php";

?>
<!--Forget Password changed successfully -->
<?php if (isset($_SESSION['forget_password'])): ?>
        <script>
            const Toast1 = Swal.mixin({
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

            Toast1.fire({
                icon: "success",
                title: "Your password has been reset successful.Please check your email address."
            });
        </script>
        <?php unset($_SESSION['forget_password']); ?>
    <?php endif; ?>
<form action="" method="POST" class="sign-in-form">
  <input type="hidden" name="latitude" id="latitude">
  <input type="hidden" name="longitude" id="longitude">

  <div class="first">
    <h2 class="title">Sign in</h2>
    <div class="input-field">
      <i class="fas fa-user"></i>
      <input type="text" placeholder="Email" name="email" required>
    </div>

    <div class="input-field password-container">
      <i class="fas fa-lock"></i>
      <input type="password" id="password" placeholder="Password" name="password" required>
      <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
    </div>

    <?php if (isset($error_message)): ?>
      <p class="error"> <?= $error_message ?> </p>
    <?php endif; ?>

    <div class="remember-forget" style="display:flex;flex-direction:row;justify-content: space-between;">
      <div class="remember">
        <label><input type="checkbox"> Remember me</label>
      </div>
      <div class="forget-password">
        <a href="forget_password.php">Forgot password?</a>
      </div>
    </div>

    <button type="submit" class="btn solid">Login <i class="fas fa-arrow-right"></i></button>
    <p class="social-text">Or Sign in with social platforms</p>
    <div class="social-media">
      <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
      <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
      <a href="#" class="social-icon"><i class="fab fa-google"></i></a>
      <a href="#" class="social-icon"><i class="fab fa-linkedin-in"></i></a>
    </div>
    <p>Don't have an account? <a href="register">Register</a></p>

    <!-- Script to fetch the current location of the user when s/he logins -->
    <script>
      function fetchLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function (position) {
            document.getElementById('latitude').value = position.coords.latitude;
            document.getElementById('longitude').value = position.coords.longitude;
          }, function (error) {
            console.error("Error getting location:", error);
          });
        } else {
          console.error("Geolocation is not supported by this browser.");
        }
      }

      // Call fetchLocation() when the page loads
      window.onload = fetchLocation;
    </script>
    <?php
    include_once "register_login_footer.php";
    ?>