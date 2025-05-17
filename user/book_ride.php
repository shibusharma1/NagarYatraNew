<?php
require_once '../config/connection.php';

$title = "NagarYatra | Vehicle Booking";
$current_page = "vehicle_list";
include_once 'master_header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $pickup = $_POST['pickup_address'] ?? '';
  $pickup_lat = $_POST['pickup_lat'] ?? '';
  $pickup_lng = $_POST['pickup_lng'] ?? '';

  $dest = $_POST['destination_address'] ?? '';
  $dest_lat = $_POST['destination_lat'] ?? '';  
  $dest_lng = $_POST['destination_lng'] ?? '';

  $distance_km = $_POST['distance_km'] ?? 0;
  $duration = $_POST['duration'] ?? '';

  $user_id = $_SESSION['id'] ?? 1;
  $category_id = $_POST['category'] ?? null;
  $otp = rand(100000, 999999); // Generate OTP

  // category to find the nearest vehicle of specific category

  // Step 1: Fetch all vehicle IDs matching the category
  $vehicleIds = [];
  $sql = "SELECT id FROM vehicle WHERE vehicle_category_id = ? AND status = 1 AND is_approved = 1";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $category_id);
  $stmt->execute();
  $result = $stmt->get_result();
  while ($row = $result->fetch_assoc()) {
    $vehicleIds[] = $row['id'];
  }

  // If no vehicles found
  if (empty($vehicleIds)) {
    // die('No vehicles found for this category.');
    echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'No Vehicles Found',
                text: 'We couldn’t find any vehicles in the selected category. Please try a different category.',
                confirmButtonColor: '#092448'
            }).then(() => {
                window.history.back(); // or redirect to another page
            });
        </script>";
    exit;

  }

  // Step 2: Fetch user (driver) details linked with these vehicles
  $placeholders = implode(',', array_fill(0, count($vehicleIds), '?'));
  $sql = "SELECT id, vehicle_id, latitude, longitude FROM user WHERE vehicle_id IN ($placeholders) AND role = 1 AND is_delete = 0 AND status = 1";

  $stmt = $conn->prepare($sql);
  $stmt->bind_param(str_repeat('i', count($vehicleIds)), ...$vehicleIds);
  $stmt->execute();
  $result = $stmt->get_result();

  $users = [];
  while ($row = $result->fetch_assoc()) {
    $users[] = $row;
  }

  // Step 3: Calculate shortest distance
  function haversineDistance($lat1, $lon1, $lat2, $lon2)
  {
    $earthRadius = 6371; // Radius in kilometers

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat / 2) * sin($dLat / 2) +
      cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
      sin($dLon / 2) * sin($dLon / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return $earthRadius * $c;
  }

  $nearest_users = []; // Array to store users within 10 km
  $nearbyUsers = []; // Optional: store full data if needed

  foreach ($users as $user) {
    $distance = haversineDistance($pickup_lat, $pickup_lng, $user['latitude'], $user['longitude']);

    if ($distance <= 20) {
      $nearest_users[] = $user['id']; // Collect user ID
      $nearbyUsers[] = array_merge($user, ['distance_km' => round($distance, 2)]); // Optional: store details
    }
  }



  $sql = "SELECT * FROM vehicle_category 
          WHERE id = $category_id";

  $result = mysqli_query($conn, $sql);
  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $per_km_cost = $row['per_km_cost'] ?? 0;
    $cost = $row['min_cost'] ?? 0;
    // echo number_format($totalEarning, 2); // Format to 2 decimal places
  } else {
    echo "Error: " . mysqli_error($conn);
  }

  if ($distance_km > 2) {
    $cost += ($distance_km - 2) * $per_km_cost;
  }
  $cost = round($cost, 2);

  $date = date('Y-m-d');
  $description = "Auto booked via user portal";

  // php mailer starts
// Email Sending Code - PHPMailer
  require '../vendor/autoload.php'; // Composer autoload for PHPMailer

  // Fetch user email and name
  $user_id = $_SESSION['id'] ?? 1;
  $user_sql = "SELECT name, email FROM user WHERE id = ?";
  $user_stmt = $conn->prepare($user_sql);
  $user_stmt->bind_param("i", $user_id);
  $user_stmt->execute();
  $user_result = $user_stmt->get_result();
  $user = $user_result->fetch_assoc();

  if ($user) {
    $name = $user['name'];
    $email = $user['email'];

    $mail = new PHPMailer\PHPMailer\PHPMailer();

    try {
      // Server settings
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'nagarctservices@gmail.com'; // Your Gmail
      $mail->Password = 'xjoa yrzu odbc nezg';        // App password
      $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;

      // Recipients
      $mail->setFrom('nagarctservices@gmail.com', 'NagarYatra');
      $mail->addAddress($email, $name); // User's email

      // Content
      $mail->isHTML(true);
      $mail->Subject = "Booking Confirmation - NagarYatra";

      $mail->Body = "
            <div style='font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #2c3e50;'>Hello, $name! 🎉</h2>
                <p>Your ride has been Booking Request is successfully with NagarYatra.</p>
                <p><b>Pickup Location:</b> $pickup</p>
                <p><b>Destination:</b> $dest</p>
                <p><b>Your OTP:</b> $otp</p>
                <p><b>Estimated Distance:</b> {$distance_km} km</p>
                <p><b>Estimated Cost:</b> Rs. {$cost}</p>
                <p style='color: #e67e22;'><i>Note: Estimated cost may differ if distance increases.</i></p>
                <p>We look forward to serving you! 🚗</p>
                <hr style='border: none; border-top: 1px solid #ddd;'>
                <p style='color: #333;'><b>Best Regards,</b><br>
                NagarYatra Team<br>
                <a href='https://www.NagarYatra.com' style='color: #3498db; text-decoration: none;'>www.NagarYatra.com</a></p>
            </div>
        ";

      $mail->AltBody = "Hello $name, Your ride from $pickup to $dest has been booked.Your OTP is { $otp }.Estimated distance: {$distance_km} km. Estimated cost: Rs. {$cost}. - NagarYatra";

      $mail->send();
      // echo 'Booking confirmation email sent successfully!';
    } catch (Exception $e) {
      echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }

  // php mailer ends
  // print_r($nearest_users);
  // exit;

  $nearest_users_str = implode(',', $nearest_users);
  echo "<pre>";
  // var_dump($nearest_users_str);
  echo "</pre>";
  $sql = "INSERT INTO booking (
    user_id, nearest_users, otp, pick_up_place, pickup_lat, pickup_lng,
    destination, destination_lat, destination_lng,
    estimated_cost, estimated_KM, estimated_ride_duration,
    booking_date, booking_description
  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

  $stmt = $conn->prepare($sql);

  if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
  }

  $stmt->bind_param(
    "isssddsssdsdss",
    $user_id,
    $nearest_users_str,
    $otp,
    $pickup,
    $pickup_lat,
    $pickup_lng,
    $dest,
    $dest_lat,
    $dest_lng,
    $cost,
    $distance_km,
    $duration,
    $date,
    $description
  );

  if ($stmt->execute()) {

    // code for notifications
    // Compose the notification message
    $message = "Dear {$name}, your vehicle booking request has been successfully sent to nearby drivers. Please wait while we find the best match for you.";

    // Escape the message to avoid SQL errors
    $escaped_message = mysqli_real_escape_string($conn, $message);

    // Build the query (handle NULL for user_id)
    if ($user_id !== NULL) {
      $sql = "INSERT INTO notifications (user_id, message) VALUES ($user_id, '$escaped_message')";
    } else {
      $sql = "INSERT INTO notifications (user_id, message) VALUES (NULL, '$escaped_message')";
    }

    // Execute the query
    if (mysqli_query($conn, $sql)) {
      // echo "Notification sent successfully!";
    } else {
      echo "Error: " . mysqli_error($conn);
    }



    echo "<script>window.location.href='ride_status.php';</script>";
    exit;
  } else {
    echo "Error: " . $stmt->error;
  }
}
?>

<section class="car-list">
  <!-- <div class="container mt-4"> -->
  <h4 class="mb-3 title">Book a Ride</h4>
  <form action="" method="post" onsubmit="return prepareBooking();">

    <!-- category -->
    <?php
    // Fetch categories for the dropdown
    $categories = [];
    $sql = "SELECT id, name, image FROM vehicle_category"; // Include image here
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
      }
    }
    ?>

    <div class="input-group mb-3">
      <div class="row">
        <?php foreach ($categories as $category): ?>
          <div class="col-md-3 mb-3">
            <div class="vehicle-option" data-id="<?= $category['id']; ?>" onclick="selectCategory(this)">
              <?php
              $image = "../admin/" . htmlspecialchars($category['image']);
              ?>
              <img src="<?php echo $image; ?>" alt="<?= htmlspecialchars($category['name']); ?>"
                style="width: 100%; height: 150px; object-fit: cover;">
              <div class="text-center mt-2"><?= htmlspecialchars($category['name']); ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>

      <!-- Hidden input to store selected category ID -->
      <input type="hidden" name="category" id="selectedCategoryId">
    </div>


    <div class="input-group mb-3">
      <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
      <input type="text" class="form-control" id="currentLocation" placeholder="Current Location">
      <button type="button" class="btn btn-theme" onclick="getCurrentLocation()"
        style="background-color: #092448;color:white;"> Use My Location</button>
    </div>

    <div class="input-group mb-3">
      <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
      <input type="text" class="form-control" id="destination" placeholder="Search Destination">
      <button type="button" class="btn btn-theme" onclick="setDestination()"
        style="background-color: #092448;color:white;">Search</button>
    </div>

    <input type="hidden" name="pickup_address" id="pickup_address">
    <input type="hidden" name="pickup_lat" id="pickup_lat">
    <input type="hidden" name="pickup_lng" id="pickup_lng">

    <input type="hidden" name="destination_address" id="destination_address">
    <input type="hidden" name="destination_lat" id="destination_lat">
    <input type="hidden" name="destination_lng" id="destination_lng">

    <input type="hidden" name="distance_km" id="distance_km">
    <input type="hidden" name="duration" id="duration">

    <div id="map" class="map-container mb-4" style="height: 400px;"></div>
    <div id="info" class="text-center fw-bold mb-3 text-primary"></div>

    <button type="submit" class="btn btn-theme w-100" style="background-color: #092448;color:white;"> <i
        class="bi bi-car-front-fill"></i> Book Ride</button>
  </form>
  <!-- </div> -->

  <script>
    let map, directionsService, directionsRenderer;
    let pickupLatLng = null;
    let destinationLatLng = null;
    let pickupMarker = null;
    let destinationMarker = null;
    let isSelectingPickup = false;
    let isSelectingDestination = false;

    function initMap() {
      const defaultLatLng = { lat: 26.455, lng: 87.270 };

      map = new google.maps.Map(document.getElementById("map"), {
        center: defaultLatLng,
        zoom: 13,
      });

      directionsService = new google.maps.DirectionsService();
      directionsRenderer = new google.maps.DirectionsRenderer({
        map,
        polylineOptions: {
          strokeColor: "#092448",
          strokeWeight: 5,
        },
        suppressMarkers: true
      });

      map.addListener("click", function (event) {
        if (isSelectingPickup) {
          setPickupLocation(event.latLng);
        } else if (isSelectingDestination) {
          setDestinationLocation(event.latLng);
        }
      });
    }

    function getCurrentLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition((position) => {
          const lat = position.coords.latitude;
          const lng = position.coords.longitude;
          setPickupLocation(new google.maps.LatLng(lat, lng));
        });
      }
    }

    async function setDestination() {
      const address = document.getElementById("destination").value;
      if (!address) return;

      const response = await fetch(`https://maps.googleapis.com/maps/api/geocode/json?address=${encodeURIComponent(address)}&key=AIzaSyDumdDv9jxmpC0yaURPXnqkk4kssB8R3C4`);
      const data = await response.json();
      if (data.results.length > 0) {
        const location = data.results[0].geometry.location;
        setDestinationLocation(new google.maps.LatLng(location.lat, location.lng));
      }
    }

    function setPickupLocation(latlng) {
      pickupLatLng = { lat: latlng.lat(), lng: latlng.lng() };

      if (pickupMarker) pickupMarker.setMap(null);
      pickupMarker = new google.maps.Marker({
        position: pickupLatLng,
        map,
        icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
        draggable: true
      });

      fetchAddress(pickupLatLng.lat, pickupLatLng.lng, "currentLocation");

      pickupMarker.addListener('dragend', function (event) {
        pickupLatLng = { lat: event.latLng.lat(), lng: event.latLng.lng() };
        fetchAddress(pickupLatLng.lat, pickupLatLng.lng, "currentLocation");
        if (pickupLatLng && destinationLatLng) showRoute();
      });

      isSelectingPickup = false;
      if (pickupLatLng && destinationLatLng) showRoute();
    }

    function setDestinationLocation(latlng) {
      destinationLatLng = { lat: latlng.lat(), lng: latlng.lng() };

      if (destinationMarker) destinationMarker.setMap(null);
      destinationMarker = new google.maps.Marker({
        position: destinationLatLng,
        map,
        icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png",
        draggable: true
      });

      fetchAddress(destinationLatLng.lat, destinationLatLng.lng, "destination");

      destinationMarker.addListener('dragend', function (event) {
        destinationLatLng = { lat: event.latLng.lat(), lng: event.latLng.lng() };
        fetchAddress(destinationLatLng.lat, destinationLatLng.lng, "destination");
        if (pickupLatLng && destinationLatLng) showRoute();
      });

      isSelectingDestination = false;
      if (pickupLatLng && destinationLatLng) showRoute();
    }

    function fetchAddress(lat, lng, inputId) {
      fetch(`https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyDumdDv9jxmpC0yaURPXnqkk4kssB8R3C4`)
        .then(res => res.json())
        .then(data => {
          const address = data.results[0]?.formatted_address || `${lat}, ${lng}`;
          document.getElementById(inputId).value = address;
        });
    }

    function showRoute() {
      const request = {
        origin: pickupLatLng,
        destination: destinationLatLng,
        travelMode: google.maps.TravelMode.DRIVING
      };

      directionsService.route(request, function (result, status) {
        if (status === "OK") {
          directionsRenderer.setDirections(result);

          const leg = result.routes[0].legs[0];
          const distanceText = leg.distance.text;
          const durationText = leg.duration.text;
          const distanceValue = parseFloat(leg.distance.value / 1000).toFixed(2);

          document.getElementById("info").innerHTML = `Distance: <strong>${distanceText}</strong> | Duration: <strong>${durationText}</strong>`;

          document.getElementById("pickup_lat").value = pickupLatLng.lat;
          document.getElementById("pickup_lng").value = pickupLatLng.lng;
          document.getElementById("pickup_address").value = document.getElementById("currentLocation").value.trim();

          document.getElementById("destination_lat").value = destinationLatLng.lat;
          document.getElementById("destination_lng").value = destinationLatLng.lng;
          document.getElementById("destination_address").value = document.getElementById("destination").value.trim();

          document.getElementById("distance_km").value = distanceValue;
          document.getElementById("duration").value = durationText;
        } else {
          document.getElementById("info").innerText = "Route not found.";
        }
      });
    }

    function prepareBooking() {
      if (!pickupLatLng || !destinationLatLng) {
        alert("Please set both pickup and destination.");
        return false;
      }
      return true;
    }

    document.getElementById("currentLocation").addEventListener("click", function () {
      isSelectingPickup = true;
      isSelectingDestination = false;
      // alert("Click on the map to select your Pickup Location 📍 or use 'My Location'");
    });

    document.getElementById("destination").addEventListener("click", function () {
      isSelectingDestination = true;
      isSelectingPickup = false;
      // alert("Click on the map to select your Destination 📍 or use Search");
    });
  </script>

  <script>
    function selectCategory(element) {
      // Remove "selected" class from all options
      document.querySelectorAll('.vehicle-option').forEach(el => el.classList.remove('selected'));

      // Add "selected" class to clicked option
      element.classList.add('selected');

      // Set selected ID into hidden input
      const categoryId = element.getAttribute('data-id');
      document.getElementById('selectedCategoryId').value = categoryId;
    }
  </script>

  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDumdDv9jxmpC0yaURPXnqkk4kssB8R3C4&callback=initMap"
    async defer></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</section>

<?php include_once 'master_footer.php'; ?>