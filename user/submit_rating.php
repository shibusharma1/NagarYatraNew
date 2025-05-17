<?php
require '../config/connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = isset($_POST['rating']) ? floatval($_POST['rating']) : null;
    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    $description = isset($_POST['experience_description']) ? trim($_POST['experience_description']) : '';

if (!is_null($rating) && $rating > 0 && $booking_id > 0) {
        $stmt = $conn->prepare("UPDATE booking SET rating = ?, remarks = ? WHERE id = ? AND status = 5 AND rating IS NULL");

        if ($stmt) {
            $stmt->bind_param("dsi", $rating, $description, $booking_id);

            if ($stmt->execute()) {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Thank you!',
                        text: 'Your feedback has been submitted.',
                        confirmButtonColor: '#092448'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>";
                exit;
            } else {
                echo "
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Failed to save rating. Please try again.',
                        confirmButtonColor: '#092448'
                    }).then(() => {
                        window.location.href = 'index.php';
                    });
                </script>";
            }

            $stmt->close();
        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Failed to prepare statement.',
                    confirmButtonColor: '#092448'
                }).then(() => {
                    window.location.href = 'index.php';
                });
            </script>";
        }
    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Invalid Input',
                text: 'Please check your submitted data.',
                confirmButtonColor: '#092448'
            }).then(() => {
                window.location.href = 'index.php';
            });
        </script>";
    }
} else {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Invalid Request',
            text: 'Request method not allowed.',
            confirmButtonColor: '#092448'
        }).then(() => {
            window.location.href = 'index.php';
        });
    </script>";
}
exit;
