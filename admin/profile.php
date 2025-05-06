<?php
// session_start();
// Example user data (Fetch from database in real implementation)
$user = [
    "name" => "John Doe",
    "email" => "abc@gmail.com",
    "profile_image" => "../assets/logo1.png" // Change to actual path
];
// include_once 'master_header.php';
$title = "Edit User";
$current_page = "user";
include_once 'master_header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
    <!-- <style>
        .container {
            width: 50%;
            margin: 50px auto;
        }

        .profile-card {
            background: white;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        .profile-img {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .profile-actions button {
            background: green;
            color: white;
            border: none;
            padding: 10px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }

        .profile-actions button:hover {
            background: darkgreen;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 20px;
            width: 300px;
            border-radius: 10px;
            text-align: center;
        }

        .close {
            float: right;
            cursor: pointer;
            font-size: 20px;
        }
    </style> -->
<style>
    <style>
    :root {
        --primary-color: #2c3e50;
        --secondary-color: #3498db;
        --accent-color: #27ae60;
        --text-color: #34495e;
        --shadow: 0 10px 20px rgba(0,0,0,0.1);
    }

    .container {
        max-width: 600px;
        margin: 50px auto;
        padding: 20px;
    }

    .profile-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        padding: 40px 30px;
        border-radius: 20px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
    }

    .profile-header {
        position: relative;
        margin-bottom: 30px;
    }

    .profile-img {
        width: 200px !important;
        height: 200px !important;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: var(--shadow);
        transition: transform 0.3s ease;
    }

    .profile-img:hover {
        transform: scale(1.05);
    }

    .profile-card h2 {
        color: var(--primary-color);
        margin: 20px 0 10px;
        font-size: 2.2em;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .profile-card p {
        color: #7f8c8d;
        font-size: 1.1em;
        margin-bottom: 25px;
    }

    .profile-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .profile-actions button {
        background: linear-gradient(135deg, var(--accent-color) 0%, #219a52 100%);
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-actions button:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(39, 174, 96, 0.3);
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.4);
        backdrop-filter: blur(4px);
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-content {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: var(--shadow);
        width: 90%;
        max-width: 400px;
        position: relative;
        animation: modalSlideIn 0.3s ease;
    }

    .close {
        position: absolute;
        top: 15px;
        right: 20px;
        font-size: 24px;
        color: #95a5a6;
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .close:hover {
        color: var(--primary-color);
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    form label {
        font-weight: 500;
        color: var(--primary-color);
        text-align: left;
        margin-bottom: -8px;
    }

    form input {
        padding: 12px;
        border: 2px solid #ecf0f1;
        border-radius: 8px;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    form input:focus {
        border-color: var(--secondary-color);
        outline: none;
    }

    form button[type="submit"] {
        background: var(--secondary-color);
        color: white;
        padding: 12px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    form button[type="submit"]:hover {
        background: #2980b9;
        transform: translateY(-2px);
    }

    .profile-image{
        border-radius: 50%;
        border: 2px solid #092448;
        
    }
    @keyframes modalSlideIn {
        from { transform: translateY(-20px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @media (max-width: 768px) {
        .container {
            width: 90%;
            margin: 30px auto;
        }
        
        .profile-img {
            width: 140px;
            height: 140px;
        }
        
        .profile-actions {
            flex-direction: column;
        }
    }
</style>
</style>
</head>

<body>

    <div class="container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="<?php echo $user['profile_image']; ?>" alt="Profile" class="profile-image" width="200px" height="200px">
                <p style="color:green;"><?php echo "Active" ?></p>
                <h2><?php echo $user['name']; ?></h2>
                <p><?php echo $user['email']; ?></p>
            </div>

            <div class="profile-actions">
                <button onclick="openEditModal()">Edit Profile</button>
                <button onclick="openPasswordModal()">Change Password</button>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Profile</h2>
            <form action="update_profile.php" method="POST">
                <label>Name:</label>
                <input type="text" name="name" value="<?php echo $user['name']; ?>" required>

                <label>Email:</label>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" required>

                <label>Profile Image:</label>
                <input type="file" name="profile_image">

                <button type="submit">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="passwordModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closePasswordModal()">&times;</span>
            <h2>Change Password</h2>
            <form action="change_password.php" method="POST">
                <label>Old Password:</label>
                <input type="password" name="old_password" required>

                <label>New Password:</label>
                <input type="password" name="new_password" required>

                <label>Confirm Password:</label>
                <input type="password" name="confirm_password" required>

                <button type="submit">Update Password</button>
            </form>
        </div>
    </div>

    <!-- <script src="script.js"></script> -->
    <script>
        function openEditModal() {
            document.getElementById("editProfileModal").style.display = "flex";
        }

        function closeEditModal() {
            document.getElementById("editProfileModal").style.display = "none";
        }

        function openPasswordModal() {
            document.getElementById("passwordModal").style.display = "flex";
        }

        function closePasswordModal() {
            document.getElementById("passwordModal").style.display = "none";
        }

    </script>
<?php
include_once 'master_footer.php';

?>