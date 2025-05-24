<?php
$title = "NagarYatra | Add Vehicle";
$current_page = "vehicles";
$heading = "Add Vehicle";


require_once '../config/connection.php';

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

    // Handling file uploads
    $thumb_image = $_FILES['thumb_image']['name'];
    $bill_book_image = $_FILES['bill_book_image']['name'];

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

    // If no errors, insert data
    if (empty($errors)) {
        // File upload logic for thumb_image and bill_book_image
        $thumb_image_target = "uploads/" . basename($thumb_image);
        $bill_book_image_target = "uploads/" . basename($bill_book_image);

 


        // Move uploaded files to the "uploads" directory
        if (move_uploaded_file($_FILES['thumb_image']['tmp_name'], $thumb_image_target) && move_uploaded_file($_FILES['bill_book_image']['tmp_name'], $bill_book_image_target)) {
            // Prepare and execute the insert query
            
            $stmt = $conn->prepare("INSERT INTO vehicle (chassis_number, color, vehicle_number, description, vehicle_company_id, vehicle_category_id, thumb_image, bill_book_expiry_date, bill_book_image) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssiisss", $chassis_number, $color, $vnumber, $description, $company, $category, $thumb_image_target, $bill_book_expiry_date, $bill_book_image_target);

            if ($stmt->execute()) {
                echo "<div class='success-msg'>Vehicle added successfully!</div>";
                $_SESSION['vehicle_added'] = true;
                header("Location: vehicles");

            } else {
                echo "<div class='error-msg'>Error: " . $stmt->error . "</div>";
            }

            $stmt->close();
        } else {
            echo "<div class='error-msg'>Error uploading images.</div>";
        }
    }
}
include_once 'master_header.php';
?>

<h2><?php echo $heading; ?></h2>

<form action="" method="post" enctype="multipart/form-data">
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

    <div class="form-group">
        <label for="chassis_number" class="form-label">Chassis Number</label>
        <input type="text" class="form-input" id="chassis_number" name="chassis_number"
            placeholder="Enter your vehicle chassis number" />
        <small class="error"><?php echo $errors['chassis_number'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-input" id="color" name="color" placeholder="Enter your vehicle color" />
        <small class="error"><?php echo $errors['color'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="vnumber" class="form-label">Vehicle Number</label>
        <input type="text" class="form-input" id="vnumber" name="vnumber" placeholder="Enter your vehicle number" />
        <small class="error"><?php echo $errors['vnumber'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" class="form-input" name="description"></textarea>
    </div>

    <div class="form-group">
        <label for="bill_book_expiry_date" class="form-label">Bill Book Expiry Date</label>
        <input type="date" class="form-input" id="bill_book_expiry_date" name="bill_book_expiry_date" />
        <small class="error"><?php echo $errors['bill_book_expiry_date'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="thumb_image" class="form-label">Thumb Image</label>
        <input type="file" class="form-input" id="thumb_image" name="thumb_image" />
    </div>

    <div class="form-group">
        <label for="bill_book_image" class="form-label">Bill Book Image</label>
        <input type="file" class="form-input" id="bill_book_image" name="bill_book_image" />
    </div>

    <button type="submit" class="custom-btn">Submit</button>
</form>

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

<?php
include_once 'master_footer.php';
?>