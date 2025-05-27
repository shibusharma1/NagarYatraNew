<?php
$title = "NagarYatra | Edit Vehicle Company";
$current_page = "vehicle_company";

require_once '../config/connection.php';

// Check if 'id' is passed
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $company_id = $_GET['id'];

    // Fetch existing company data
    $sql = "SELECT * FROM vehicle_company WHERE id = $company_id";
    $result = mysqli_query($conn, $sql);
    $company = mysqli_fetch_assoc($result);

    if (!$company) {
        $_SESSION['vehicle_company']='succesful';
        header("Location: vehicle_company.php");
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $headquarter = trim($_POST['headquarter']);
    $global_presence = isset($_POST['global_presence']) ? 1 : 0;

    $errors = [];

    if (empty($name)) $errors['name'] = "Company name is required.";
    if (empty($headquarter)) $errors['headquarter'] = "Headquarter is required.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE vehicle_company SET name=?, headquarter=?, global_presence=? WHERE id=?");
        $stmt->bind_param("ssii", $name, $headquarter, $global_presence, $company_id);

        if ($stmt->execute()) {
            $_SESSION['vehicle_company']='succesful';
            header("Location: vehicles_company.php");
            exit;
        } else {
            echo "<div class='error-msg'>Update failed: " . $stmt->error . "</div>";
        }
    }
}

include_once 'master_header.php';
?>

<h2>Edit Vehicle Company</h2>

<form action="" method="post">
    <div class="form-group">
        <label class="form-label" for="name">Company Name</label>
        <input type="text" class="form-input" name="name" id="name" value="<?= htmlspecialchars($company['name']); ?>" />
        <small class="error"><?= $errors['name'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label" for="headquarter">Headquarter</label>
        <input type="text" class="form-input" name="headquarter" id="headquarter" value="<?= htmlspecialchars($company['headquarter']); ?>" />
        <small class="error"><?= $errors['headquarter'] ?? ''; ?></small>
    </div>

    <div class="form-group">
        <label class="form-label">
            <input type="checkbox" name="global_presence" <?= $company['global_presence'] ? 'checked' : ''; ?> />
            Global Presence
        </label>
    </div>

    <button type="submit" class="custom-btn">Update Company</button>
</form>

<?php include_once 'master_footer.php'; ?>
