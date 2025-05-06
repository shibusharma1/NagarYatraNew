<?php
require_once '../config/connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['status'])) {
    $user_id = intval($_POST['user_id']);
    $status = intval($_POST['status']);

    $sql = "UPDATE vehicle SET is_approved = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $status, $user_id);

    if ($stmt->execute()) {
        header("Location: vehicles.php"); // Redirect back to users list
        exit();
    } else {
        echo "Error updating status: " . $conn->error;
    }

    $stmt->close();
}
?>
