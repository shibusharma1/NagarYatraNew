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

                    $vehicle_stmt->bind_result($vehicle_id, $name, $phone);
                    if ($vehicle_stmt->fetch() && $vehicle_id !== null) {
                        $vehicle_stmt->close(); // Close before next query
                        // echo "Name: " . $name . "<br>";
                        // echo "Phone: " . $phone . "<br>";

                        // 3. Update vehicle_id in booking
                        $assign_stmt = $conn->prepare("UPDATE booking SET vehicle_id = ? WHERE id = ?");
                        if ($assign_stmt) {
                            $assign_stmt->bind_param("ii", $vehicle_id, $booking_id);
                            $assign_stmt->execute();
                            $assign_stmt->close();

                            // Fetching user if from booking and storing it in the notification table.
                            $sql = "SELECT user_id FROM booking WHERE booking_id = $booking_id";
                            $result = mysqli_query($conn, $sql);

                            if ($result && mysqli_num_rows($result) > 0) {
                                $row = mysqli_fetch_assoc($result);
                                $user_id = $row['user_id'];

                                // now storing the ride accepted message in notifications table
                                // Code for notifications

                                // Compose the notification message
                                $message = "Your ride has been successfully accepted by {$name}. You can contact your driver at {$phone}. They are expected to reach your location within approximately 20 minutes.";
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
            $stmt->close(); // Ensure closure
            echo "<script>alert('Failed to update booking.'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Failed to prepare update statement.'); window.history.back();</script>";
        exit;
    }
}
?>