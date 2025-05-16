<?php
$title = "NagarYatra | Notifications";
// $current_page = "index";

include_once 'master_header.php';
require_once '../config/connection.php';


// Check if user is logged in
if (!isset($_SESSION['id'])) {
    echo "You are not logged in.";
    exit();
}

// Mark all as read (if button is clicked)
if (isset($_POST['mark_all'])) {
    $stmt = $conn->prepare("UPDATE notifications SET mark_as_read = 1 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

// Fetch all notifications
$stmt = $conn->prepare("SELECT message, mark_as_read, created_at FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$notifications = [];

while ($row = $result->fetch_assoc()) {
    $date = new DateTime($row['created_at']);
    $row['date'] = $date->format('d-m-Y');
    $row['day'] = $date->format('Y-m-d');
    $notifications[] = $row;
}

// Group notifications
$grouped = [];
$today = date('Y-m-d');
$yesterday = date('Y-m-d', strtotime('-1 day'));

foreach ($notifications as $notif) {
    if ($notif['day'] == $today) {
        $grouped['Today'][] = $notif;
    } elseif ($notif['day'] == $yesterday) {
        $grouped['Yesterday'][] = $notif;
    } else {
        $grouped[$notif['date']][] = $notif;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Notifications</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        
      
        h2 {
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
            margin-top: 40px;
            font-size: 20px;
            color: #333;
        }
        .notif-item {
            font-weight: bold;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 8px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            transition: background 0.3s;
        }
        .notif-item.unread {
            background-color: #fff8c6;
            font-weight: bold;
        }
        .notif-item:hover {
            background-color: #f0f0f0;
        }
        .timestamp {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
        }
        .mark-all-btn {
            display: inline-block;
            /* margin-bottom: 10px; */
            padding: 10px 20px;
            background-color: #092448;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
            float: right;

        
        }
        .mark-all-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <form method="POST">
        <button type="submit" name="mark_all" class="mark-all-btn">
            <i class="fa fa-check-circle"></i> Mark All as Read
        </button>
    </form>

    <?php if (!empty($grouped)): ?>
        <?php foreach ($grouped as $groupTitle => $notifs): ?>
            <h2><?= htmlspecialchars($groupTitle) ?></h2>
            <?php foreach ($notifs as $n): ?>
                <div class="notif-item <?= $n['mark_as_read'] == 0 ? 'unread' : '' ?>">
                    <?= htmlspecialchars($n['message']) ?>
                    <div class="timestamp"><?= date('h:i A', strtotime($n['created_at'])) ?></div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notifications available.</p>
    <?php endif; ?>
<!-- </div> -->
<?php
include_once 'master_footer.php';
?>
