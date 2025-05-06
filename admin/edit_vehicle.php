<?php
$title = "NagarYatra | Edit Vehicle";
$current_page = "vehicles";

require_once '../config/connection.php';
//include_once 'master_header.php';

// Check if 'id' is passed in URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $vehicle_id = $_GET['id'];

    // Fetch vehicle details from the database
    $sql = "SELECT * FROM vehicle WHERE id = $vehicle_id";
    $result = mysqli_query($conn, $sql);
    $vehicle = mysqli_fetch_assoc($result);

    // If the vehicle doesn't exist, redirect to the vehicle list
    if (!$vehicle) {
        header("Location: vehicles");
        exit;
    }
}

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

// Handle form submission to update vehicle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $chassis_number  = trim($_POST['chassis_number']);
    $color = trim($_POST['color']);
    $vnumber = trim($_POST['vnumber']);
    $description = trim($_POST['description']);
    $company = $_POST['company'] ?? '';
    $category = $_POST['category'] ?? '';
    $status = $_POST['status'] ?? 'inactive';  // Default to inactive if not selected

    // Validation
    $errors = [];
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

    // If no errors, update the record
    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE vehicle SET chassis_number = ?, color = ?, vehicle_number = ?, description = ?, vehicle_company_id = ?, vehicle_category_id = ? WHERE id = ?");
        $stmt->bind_param("ssssiis", $chassis_number, $color, $vnumber, $description, $company, $category, $vehicle_id);

        if ($stmt->execute()) {
            echo "<div class='success-msg'>Vehicle updated successfully!</div>";
           // header("Location: vehicle");
           header("Location: vehicles");
           exit;
           ob_end_flush(); // End buffering and send output
        } else {
            echo "<div class='error-msg'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>
<?php
include_once 'master_header.php';
?>

<h2>Edit Vehicle</h2>

<form action="" method="post">
    <div class="form-group">
        <label for="company" class="form-label">Company/Brand</label>
        <select class="form-input" id="company" name="company">
            <option value="">Select Company</option>
            <?php foreach ($companies as $company): ?>
                <option value="<?= $company['id']; ?>" <?= $company['id'] == $vehicle['vehicle_company_id'] ? 'selected' : ''; ?>>
                    <?= $company['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small class="error"><?php echo $errors['company'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="category" class="form-label">Category</label>
        <select class="form-input" id="category" name="category">
            <option value="">Select Category</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id']; ?>" <?= $category['id'] == $vehicle['vehicle_category_id'] ? 'selected' : ''; ?>>
                    <?= $category['name']; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <small class="error"><?php echo $errors['category'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="chassis_number" class="form-label">Chassis Number</label>
        <input type="text" class="form-input" id="chassis_number" name="chassis_number" value="<?= htmlspecialchars($vehicle['chassis_number']); ?>" />
        <small class="error"><?php echo $errors['chassis_number'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="color" class="form-label">Color</label>
        <input type="text" class="form-input" id="color" name="color" value="<?= htmlspecialchars($vehicle['color']); ?>" />
        <small class="error"><?php echo $errors['color'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="vnumber" class="form-label">Vehicle Number</label>
        <input type="text" class="form-input" id="vnumber" name="vnumber" value="<?= htmlspecialchars($vehicle['vehicle_number']); ?>" />
        <small class="error"><?php echo $errors['vnumber'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label for="description" class="form-label">Description</label>
        <textarea id="description" class="form-input" name="description"><?= htmlspecialchars($vehicle['description']); ?></textarea>
    </div>

    <!-- <div class="form-group">
        <label for="status" class="form-label">Status</label>
        <select class="form-input" id="status" name="status">
            <option value="active" <?= $vehicle['status'] == 'active' ? 'selected' : ''; ?>>Active</option>
            <option value="inactive" <?= $vehicle['status'] == 'inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>
    </div> -->

    <button type="submit" class="custom-btn">Update</button>
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
