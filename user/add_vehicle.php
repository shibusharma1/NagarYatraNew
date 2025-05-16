<?php
$title = "NagarYatra | Add Vehicle";
$current_page = "add_vehicle";
$heading = "Become a Driver";

require_once '../config/connection.php';
include_once 'master_header.php';

$errors = []; // Array to store error messages

// Fetch companies for the dropdown
$companies = [];
$sql = "SELECT id, name FROM vehicle_company";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $companies[] = $row;
    }
}

// Fetch categories for the dropdown
$categories = [];
$sql = "SELECT id, name FROM vehicle_category";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chassis_number = trim($_POST['chassis_number']);
    $color = trim($_POST['color']);
    $vnumber = trim($_POST['vnumber']);
    $description = trim($_POST['description']);
    $company = $_POST['company'] ?? '';
    $category = $_POST['category'] ?? '';
    $bill_book_expiry_date = $_POST['bill_book_expiry_date'];
    $dl_number = $_POST['dl_number'];
    $dl_expiry_date = $_POST['dl_expiry_date'];


    // Handling file uploads
    //thumb image is the vehicle image
    $thumb_image = $_FILES['thumb_image']['name'];
    $bill_book_image = $_FILES['bill_book_image']['name'];
    $dl_image = $_FILES['dl_image']['name'];


    // Validation
    if (empty($chassis_number)) {
        $errors['chassis_number'] = "Chassis number is required.";
    }
    if (empty($color)) {
        $errors['color'] = "Color is required.";
    }
    if (empty($vnumber)) {
        $errors['vnumber'] = "Vehicle number is required.";
    }
    if (empty($company)) {
        $errors['company'] = "Please select a company.";
    }
    if (empty($category)) {
        $errors['category'] = "Please select a category.";
    }
    if (empty($bill_book_expiry_date)) {
        $errors['bill_book_expiry_date'] = "Bill book expiry date is required.";
    }
    if (empty($dl_number)) {
        $errors['dl_number'] = "Driving License Number is required.";
    }
    if (empty($dl_expiry_date)) {
        $errors['dl_expiry_date'] = "Driving License  expiry date is required.";
    }



    // If no errors, insert data
    if (empty($errors)) {
        $thumb_image_target = "../admin/uploads/" . basename($thumb_image);
        $bill_book_image_target = "../admin/uploads/" . basename($bill_book_image);
        $dl_image_target = "../admin/uploads/dl_images" . basename($dl_image);

        // Move uploaded files
        if (move_uploaded_file($_FILES['thumb_image']['tmp_name'], $thumb_image_target) && move_uploaded_file($_FILES['bill_book_image']['tmp_name'], $bill_book_image_target)) {

            // 1. Insert into vehicle table
            $insertVehicleQuery = "INSERT INTO vehicle (chassis_number, color, vehicle_number, description, vehicle_company_id, vehicle_category_id, thumb_image, bill_book_expiry_date, bill_book_image) 
                                   VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertVehicleQuery);
            $stmt->bind_param("ssssiisss", $chassis_number, $color, $vnumber, $description, $company, $category, $thumb_image_target, $bill_book_expiry_date, $bill_book_image_target);

            if ($stmt->execute()) {
                $lastInsertedVehicleId = $conn->insert_id;
                $stmt->close();

                // 2. Update user table with the new vehicle ID and set role as driver
                $updateUserQuery = "UPDATE user SET vehicle_id = ?, role = 1, dl_number = ?, dl_expiry_date = ?, dl_image  = ?  WHERE id = ?";
                $stmt = $conn->prepare($updateUserQuery);
                $stmt->bind_param("isssi", $lastInsertedVehicleId, $dl_number, $dl_expiry_date, $dl_image_target, $_SESSION['id']);

                if ($stmt->execute()) {
                    $stmt->close();

                    // Code for notifications

                    // Compose the notification message
                    $message = "Dear {$name}, your vehicle has been added successfully and is now awaiting admin approval. We will notify you once your vehicle is verified and activated.";

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

                    echo "<script> window.location.href='index.php';</script>";
                    exit();
                } else {
                    echo "<div class='error-msg'>Failed to update user: " . $stmt->error . "</div>";
                    $stmt->close();
                }
            } else {
                echo "<div class='error-msg'>Failed to insert vehicle: " . $stmt->error . "</div>";
                $stmt->close();
            }
        } else {
            echo "<div class='error-msg'>Error uploading images.</div>";
        }
    }
}
?>


<!-- Link your external CSS -->
<link rel="stylesheet" href="assets/css/vehicle-form-style.css">

<!-- Start of Form -->
<h2 class="vehicle-heading"><?php echo $heading; ?></h2>

<form class="vehicle-form-style" action="" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label for="company" class="form-label">Company/Brand</label>
                <select class="form-input" id="company" name="company">
                    <option value="">Select Company</option>
                    <?php foreach ($companies as $company): ?>
                        <option value="<?= $company['id']; ?>"><?= $company['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="error"><?php echo $errors['company'] ?? ''; ?></small>
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label for="category" class="form-label">Category</label>
                <select class="form-input" id="category" name="category">
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id']; ?>"><?= $category['name']; ?></option>
                    <?php endforeach; ?>
                </select>
                <small class="error"><?php echo $errors['category'] ?? ''; ?></small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="chassis_number" class="form-label">Chassis Number</label>
                <input type="text" class="form-input" id="chassis_number" name="chassis_number"
                    placeholder="Enter your vehicle chassis number" />
                <small class="error"><?php echo $errors['chassis_number'] ?? ''; ?></small>
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="color" class="form-label">Color</label>
                <input type="text" class="form-input" id="color" name="color" placeholder="Enter your vehicle color" />
                <small class="error"><?php echo $errors['color'] ?? ''; ?></small>
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="vnumber" class="form-label">Vehicle Number</label>
                <input type="text" class="form-input" id="vnumber" name="vnumber"
                    placeholder="Enter your vehicle number" />
                <small class="error"><?php echo $errors['vnumber'] ?? ''; ?></small>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="thumb_image" class="form-label">Thumb Image</label>
                <input type="file" class="form-input" id="thumb_image" name="thumb_image" />
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="bill_book_image" class="form-label">Bill Book Image</label>
                <input type="file" class="form-input" id="bill_book_image" name="bill_book_image" />
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="bill_book_expiry_date" class="form-label">Bill Book Expiry Date</label>
                <!-- <input type="date" class="form-input" id="bill_book_expiry_date" name="bill_book_expiry_date" /> -->
                <input type="date" class="form-input" id="bill_book_expiry_date" name="bill_book_expiry_date"
                    min="<?php echo date('Y-m-d'); ?>" />
                <small class="error"><?php echo $errors['bill_book_expiry_date'] ?? ''; ?></small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-4">
            <div class="form-group">
                <label for="dl_image" class="form-label">Driving License Image</label>
                <input type="file" class="form-input" id="dl_image" name="dl_image" />
            </div>
        </div>

        <div class="col-4">
            <div class="form-group">
                <label for="dl_number" class="form-label">Driving License Number</label>
                <input type="text" class="form-input" id="dl_number" name="dl_number" />
            </div>
        </div>
        <div class="col-4">
            <div class="form-group">
                <label for="dl_expiry_date" class="form-label">Driving License Expiry Date</label>
                <input type="date" class="form-input" id="dl_expiry_date" name="dl_expiry_date"
                    min="<?php echo date('Y-m-d'); ?>" />
                <small class="error"><?php echo $errors['dl_expiry_date'] ?? ''; ?></small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label for="description" class="form-label">Description(Optional)</label>
        <textarea id="description" class="form-input" name="description"></textarea>
    </div>
    <button type="submit" class="custom-btn">Submit</button>
</form>

<!-- Summernote Script -->
<script>
    $(document).ready(function () {
        $('#description').summernote({
            placeholder: 'Enter description...',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
    });
</script>

<?php include_once 'master_footer.php'; ?>