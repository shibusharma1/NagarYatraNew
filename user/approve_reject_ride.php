<?php
$title = "NagarYatra | Ride request";
$current_page = "ride_request";
include_once 'master_header.php';
include('../config/connection.php');
?>

<!-- Include SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
if (isset($_POST['booking_id'])) {
    $booking_id = (int) $_POST['booking_id'];
    $status_value = 2;
    if (isset($_POST['status_value'])) {
        $status_value = (int) $_POST['status_value'];
    } elseif (isset($_POST['status_id'])) {
        $status_value = (int) $_POST['status_id'];
    }

    try {
        $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE id = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ii", $status_value, $booking_id);
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }
        $stmt->close();

        if ($status_value === 3) {
            $vehicle_stmt = $conn->prepare("SELECT name, phone, vehicle_id FROM user WHERE id = ? AND is_delete = 0 AND status = 1");
            if (!$vehicle_stmt) {
                throw new Exception("Vehicle query prepare failed: " . $conn->error);
            }

            $user_id = $_SESSION['id'];
            $vehicle_stmt->bind_param("i", $user_id);
            $vehicle_stmt->execute();
            $vehicle_stmt->store_result();
            $vehicle_stmt->bind_result($name, $phone, $vehicle_id);

            if ($vehicle_stmt->fetch()) {
                $vehicle_stmt->close();

                $check_stmt = $conn->prepare("SELECT id FROM vehicle WHERE id = ?");
                $check_stmt->bind_param("i", $vehicle_id);
                $check_stmt->execute();
                $check_stmt->store_result();
                if ($check_stmt->num_rows === 0) {
                    throw new Exception("Vehicle ID not found in vehicle table.");
                }
                $check_stmt->close();

                $assign_stmt = $conn->prepare("UPDATE booking SET vehicle_id = ? WHERE id = ?");
                if (!$assign_stmt) {
                    throw new Exception("Vehicle assignment prepare failed: " . $conn->error);
                }
                $assign_stmt->bind_param("ii", $vehicle_id, $booking_id);
                $assign_stmt->execute();
                $assign_stmt->close();

                $result = mysqli_query($conn, "SELECT user_id FROM booking WHERE id = $booking_id");
                if ($result && mysqli_num_rows($result) > 0) {
                    $row = mysqli_fetch_assoc($result);
                    $booking_user_id = $row['user_id'];
                    $message = "Your ride has been successfully accepted by {$name}. You can contact your driver at {$phone}. They are expected to reach your location within approximately 20 minutes.";
                    $escaped_message = mysqli_real_escape_string($conn, $message);

                    $noti_stmt = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
                    $noti_stmt->bind_param("is", $booking_user_id, $escaped_message);
                    $noti_stmt->execute();
                    $noti_stmt->close();
                }
            } else {
                $vehicle_stmt->close();
                throw new Exception("No valid driver with vehicle found.");
            }
        }

        // $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        echo "
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'Ride status updated successfully.',
                confirmButtonColor: '#092448'
            }).then(() => {
                window.location.href = 'approved_ride.php';
            });
        </script>";
        exit;

    } catch (Exception $e) {
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '" . addslashes($e->getMessage()) . "',
                confirmButtonColor: '#092448'
            }).then(() => {
                window.history.back();
            });
        </script>";
        exit;
    }
}
?>
