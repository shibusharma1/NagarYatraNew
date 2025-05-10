<?php
$title = "Add Vehicle Category";
$current_page = "vehicles_category";

require_once '../config/connection.php';
include_once 'master_header.php';

$name = $fuel_type = "";
$seats = $min_cost = $per_km_cost = 0;
$image = null;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $seats = (int) $_POST['seats'];
    $min_cost = (int) $_POST['min_cost'];
    $per_km_cost = (int) $_POST['per_km_cost'];
    $fuel_type = trim($_POST['fuel_type']);

    // Validate required fields
    if (empty($name))
        $errors['name'] = "Name is required.";
    if (empty($fuel_type))
        $errors['fuel_type'] = "Fuel type is required.";
    if ($seats <= 0)
        $errors['seats'] = "Seats must be greater than 0.";

        

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
        $errors['image'] = "Image is required.";
    }

    // Insert into DB if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO vehicle_category (name, image, seats, min_cost, per_km_cost, Fuel_type) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiis", $name, $image, $seats, $min_cost, $per_km_cost, $fuel_type);

        if ($stmt->execute()) {
            // header("Location: vehicles_category.php");
            echo "<script>window.location.href = 'vehicles_category.php';</script>";
            exit();

            exit();
        } else {
            echo "<div class='error-msg'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>

<h2>Add Vehicle Category</h2>

<form action="" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label class="form-label" for="name">Category Name</label>
        <input type="text" class="form-input" name="name" id="name" value="<?= htmlspecialchars($name); ?>" />
        <small class="error"><?= $errors['name'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="seats">Seats</label>
        <input type="number" class="form-input" name="seats" id="seats" value="<?= htmlspecialchars($seats); ?>" />
        <small class="error"><?= $errors['seats'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="min_cost">Minimum Cost (Rs)</label>
        <input type="number" class="form-input" name="min_cost" id="min_cost"
            value="<?= htmlspecialchars($min_cost); ?>" />
        <small class="error"><?= $errors['min_cost'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="per_km_cost">Per KM Cost (Rs)</label>
        <input type="number" class="form-input" name="per_km_cost" id="per_km_cost"
            value="<?= htmlspecialchars($per_km_cost); ?>" />
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
                <option value="<?= $option ?>" <?= ($fuel_type === $option) ? 'selected' : '' ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select>
        <small class="error"><?= $errors['fuel_type'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="image">Image</label>
        <input type="file" class="form-input" name="image" id="image" accept="image/*" onchange="previewImage(event)" />
        <br />
        <img id="preview" src="<?= $image ? '../' . htmlspecialchars($image) : ''; ?>"
            style="max-height: 100px; margin-top: 10px;" alt="Category Image" />
        <small class="error"><?= $errors['image'] ?? ''; ?></small>
    </div>

    <button type="submit" class="custom-btn">Add Category</button>
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