<?php
$title = "Add Vehicle Company";
$current_page = "vehicle_company";

require_once '../config/connection.php';
include_once 'master_header.php';

$name = $headquarter = "";
$global_presence = 0;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $headquarter = trim($_POST['headquarter']);
    $global_presence = isset($_POST['global_presence']) ? 1 : 0;

    // Validation
    if (empty($name)) {
        $errors['name'] = "Company name is required.";
    }
    if (empty($headquarter)) {
        $errors['headquarter'] = "Headquarter is required.";
    }

    // Insert into DB if no errors
    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO vehicle_company (name, headquarter, global_presence) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $name, $headquarter, $global_presence);

        if ($stmt->execute()) {
            echo "<script>window.location.href = 'vehicles_company.php';</script>";
            exit;
        } else {
            echo "<div class='error-msg'>Error: " . $stmt->error . "</div>";
        }

        $stmt->close();
    }
}
?>

<h2>Add Vehicle Company</h2>

<form action="" method="post">
    <div class="form-group">
        <label class="form-label" for="name">Company Name</label>
        <input type="text" class="form-input" name="name" id="name" value="<?= htmlspecialchars($name); ?>" />
        <small class="error"><?= $errors['name'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="headquarter">Headquarter</label>
        <input type="text" class="form-input" name="headquarter" id="headquarter" value="<?= htmlspecialchars($headquarter); ?>" />
        <small class="error"><?= $errors['headquarter'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label">
            <input type="checkbox" name="global_presence" value="1" <?= $global_presence ? 'checked' : ''; ?> />
            Global Presence
        </label>
    </div>

    <button type="submit" class="custom-btn">Add Company</button>
</form>

<?php include_once 'master_footer.php'; ?>
