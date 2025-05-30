<?php
$title = "NagarYatra | Ride request";
$current_page = "ride_request";
include_once 'master_header.php';
include('../config/connection.php');

// Handle form submission to approve/reject ride
if (isset($_POST['booking_id'])) {
    $booking_id = (int) $_POST['booking_id'];

    // Determine the status value from form input
    $status_value = 2; // Default: pending
    if (isset($_POST['status_value'])) {
        $status_value = (int) $_POST['status_value'];
    } elseif (isset($_POST['status_id'])) {
        $status_value = (int) $_POST['status_id'];
    }

    // 1. Update booking status
    $stmt = $conn->prepare("UPDATE booking SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("ii", $status_value, $booking_id);

        if ($stmt->execute()) {
            $stmt->close(); // Close to avoid sync issues

            // 2. If status = approved (3), assign vehicle
            if ($status_value === 3) {
                $vehicle_stmt = $conn->prepare("SELECT name, phone, vehicle_id FROM user WHERE id = ? AND is_delete = 0 AND status = 1");
                if ($vehicle_stmt) {
                    $user_id = $_SESSION['id'];
                    $vehicle_stmt->bind_param("i", $user_id);
                    $vehicle_stmt->execute();
                    $vehicle_stmt->store_result();

                    $vehicle_stmt->bind_result($name, $phone, $vehicle_id); // ✅ Corrected order

                    if ($vehicle_stmt->fetch() && $vehicle_id !== null) {
                        $vehicle_stmt->close();

                        // ✅ Check if vehicle exists in vehicle table
                        $check_vehicle = $conn->prepare("SELECT id FROM vehicle WHERE id = ?");
                        $check_vehicle->bind_param("i", $vehicle_id);
                        $check_vehicle->execute();
                        $check_vehicle->store_result();
                        if ($check_vehicle->num_rows === 0) {
                            echo "<script>alert('Invalid vehicle assigned.'); window.history.back();</script>";
                            exit;
                        }
                        $check_vehicle->close();

                        // ✅ Now update booking with valid vehicle_id
                        $assign_stmt = $conn->prepare("UPDATE booking SET vehicle_id = ? WHERE id = ?");
                        if ($assign_stmt) {
                            $assign_stmt->bind_param("ii", $vehicle_id, $booking_id);
                            $assign_stmt->execute();
                            $assign_stmt->close();

                            // ✅ Get user_id from booking
                            $sql = "SELECT user_id FROM booking WHERE id = $booking_id";
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $ride_user_id = $row['user_id'];

                                // ✅ Compose and store notification
                                $message = "Your ride has been successfully accepted by {$name}. You can contact your driver at {$phone}. They are expected to reach your location within approximately 20 minutes.";
                                $escaped_message = mysqli_real_escape_string($conn, $message);

                                $notif_sql = "INSERT INTO notifications (user_id, message) VALUES (?, ?)";
                                $notif_stmt = $conn->prepare($notif_sql);
                                $notif_stmt->bind_param("is", $ride_user_id, $escaped_message);
                                $notif_stmt->execute();
                                $notif_stmt->close();
                            }
                        }
                    } else {
                        $vehicle_stmt->close(); // Always close
                    }
                }
            }

            // 4. Redirect back with same pagination (if any)
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            echo "<script>window.location.href='ride_request.php?page={$page}';</script>";
            exit;
        } else {
            $stmt->close();
            echo "<script>alert('Failed to update booking.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Failed to prepare update statement.'); window.history.back();</script>";
        exit;
    }
}
?>
