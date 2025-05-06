<?php
$title = "NagarYatra | Vehicle Companies";
$current_page = "vehicle_company";

include_once 'master_header.php';
require_once '../config/connection.php';

// Fetch all vehicle companies
$sql = "SELECT * FROM vehicle_company";
$result = mysqli_query($conn, $sql);
?>

<div class="table-heading">
  <div class="heading-2">
    <h2>Vehicle Companies</h2>
  </div>
  <div class="add-button">
    <a href="add_vehicle_company.php">
      <button><i class="fa fa-plus" aria-hidden="true"></i> Add Company</button>
    </a>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Headquarter</th>
      <th>Global Presence</th>
      <th>Created At</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td><?php echo htmlspecialchars($row['headquarter']); ?></td>
          <td><?php echo $row['global_presence'] ? 'Yes' : 'No'; ?></td>
          <td><?php echo htmlspecialchars($row['created_at']); ?></td>
          <td>
            <!-- Edit Action -->
            <a href="edit_vehicle_company.php?id=<?php echo $row['id']; ?>" class="action-btn">
              <i class="fa fa-edit" aria-hidden="true"></i>
            </a>

            <!-- Delete Action (Confirmation dialog) -->
            <!-- <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['id']; ?>)" class="action-btn">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a> -->
            <a href="javascript:void(0);" onclick="confirmDelete(<?= $row['id']; ?>)" class="action-btn">
              <i class="fa fa-trash"></i>
            </a>
            <!-- SweetAlert2 CDN -->
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
              function confirmDelete(id) {
                Swal.fire({
                  title: 'Are you sure?',
                  text: "This will delete the company permanently!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#d33',
                  cancelButtonColor: '#3085d6',
                  confirmButtonText: 'Yes, delete it',
                  cancelButtonText: 'Cancel'
                }).then((result) => {
                  if (result.isConfirmed) {
                    window.location.href = 'delete_vehicle_company.php?id=' + id;
                  }
                });
              }
            </script>

          </td>
        </tr>
        <?php
      }
    } else {
      echo "<tr><td colspan='6'>No vehicle companies available.</td></tr>";
    }
    ?>
  </tbody>
</table>

<?php include_once 'master_footer.php'; ?>