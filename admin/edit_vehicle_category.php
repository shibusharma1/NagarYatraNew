<?php
$title = "NagarYatra | Edit Vehicle Category";
$current_page = "vehicles_category";

require_once '../config/connection.php';

// Check if 'id' is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $category_id = $_GET['id'];

    // Fetch existing category data
    $sql = "SELECT * FROM vehicle_category WHERE id = $category_id";
    $result = mysqli_query($conn, $sql);
    $category = mysqli_fetch_assoc($result);

    if (!$category) {
        
        header("Location: vehicles_category.php");
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $seats = trim($_POST['seats']);
    $min_cost = trim($_POST['min_cost']);
    $per_km_cost = trim($_POST['per_km_cost']);
    $fuel_type = trim($_POST['fuel_type']);

    $errors = [];

    if (empty($name)) $errors['name'] = "Name is required.";
    if (empty($seats) || !is_numeric($seats)) $errors['seats'] = "Valid seat count required.";
    if (!is_numeric($min_cost)) $errors['min_cost'] = "Minimum cost must be a number.";
    if (!is_numeric($per_km_cost)) $errors['per_km_cost'] = "Per km cost must be a number.";
    if (empty($fuel_type)) $errors['fuel_type'] = "Fuel type is required.";

    $image = $category['image']; // Existing image

    // Handle image upload if new one is selected
    // if (isset($_FILES['image']) && $_FILES['image']['name']) {
    //     $target_dir = "uploads/Vehicle_category/";
    //     // $image_path = "uploads/vehicle_category/";
    //     $target_file = $target_dir . basename($_FILES["image"]["name"]);
    //     move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    //     $image = $_FILES["image"]["name"];
    // }
       // Image Upload
    if (!empty($_FILES['image']['name'])) {
        $image_path = "uploads/vehicle_category/";
        if (!is_dir($image_path))
            mkdir($image_path, 0777, true);

        $unique_name = uniqid() . "_" . basename($_FILES['image']['name']);
        $image = $image_path . $unique_name;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
            $errors['image'] = "Failed to upload image.";
        }
    } else {
        // $errors['image'] = "Image is required.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE vehicle_category SET name=?, image=?, seats=?, min_cost=?, per_km_cost=?, Fuel_type=? WHERE id=?");
        $stmt->bind_param("ssiiisi", $name, $image, $seats, $min_cost, $per_km_cost, $fuel_type, $category_id);

        if ($stmt->execute()) {
            $_SESSION['edit_vehicle_category']='succesful';
            header("Location: vehicles_category.php");
            exit;
        } else {
            echo "<div class='error-msg'>Update failed: " . $stmt->error . "</div>";
        }
    }
}
include_once 'master_header.php';
?>

<h2>Edit Vehicle Category</h2>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label class="form-label" for="name">Category Name</label>
        <input type="text" class="form-input" name="name" id="name" value="<?= htmlspecialchars($category['name']); ?>" />
        <small class="error"><?= $errors['name'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="seats">Seats</label>
        <input type="number" class="form-input" name="seats" id="seats" value="<?= htmlspecialchars($category['seats']); ?>" />
        <small class="error"><?= $errors['seats'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="min_cost">Minimum Cost (Rs)</label>
        <input type="number" class="form-input" name="min_cost" id="min_cost" value="<?= htmlspecialchars($category['min_cost']); ?>" />
        <small class="error"><?= $errors['min_cost'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="per_km_cost">Per KM Cost (Rs)</label>
        <input type="number" class="form-input" name="per_km_cost" id="per_km_cost" value="<?= htmlspecialchars($category['per_km_cost']); ?>" />
        <small class="error"><?= $errors['per_km_cost'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="fuel_type">Fuel Type</label>
        <select class="form-input" name="fuel_type" id="fuel_type">
            <option value="">Select Fuel Type</option>
            <?php
            $fuel_options = ["Petrol", "Diesel", "Electric", "CNG"];
            foreach ($fuel_options as $option):
            ?>
                <option value="<?= $option ?>" <?= $category['Fuel_type'] == $option ? 'selected' : '' ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select>
        <small class="error"><?= $errors['fuel_type'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="image">Image</label>
        <input type="file" class="form-input" name="image" id="image" accept="image/*" onchange="previewImage(event)" />
        <br />
        <img id="preview" src="<?= $category['image']; ?>" style="max-height: 100px; margin-top: 10px;" alt="Category Image" />
    </div>

    <button type="submit" class="custom-btn">Update Category</button>
</form>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function () {
            const output = document.getElementById('preview');
            output.src = reader.result;
        };
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<?php include_once 'master_footer.php'; ?>
