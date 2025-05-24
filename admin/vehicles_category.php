<?php
$title = "NagarYatra | Vehicle Category";
$current_page = "vehicles_category";

include_once 'master_header.php';
require_once '../config/connection.php';

// Fetch all vehicle categories
$sql = "SELECT * FROM vehicle_category WHERE 1";
$result = mysqli_query($conn, $sql);
?>
<?php if (isset($_SESSION['vehicle_category'])): ?>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });

    Toast.fire({
      icon: "success",
      title: "vehicle Category Added Successfully"
    });
  </script>
  <?php unset($_SESSION['vehicle_category']); ?>
<?php endif; ?>

<!-- edit -->
 
<?php 
if (isset($_SESSION['edit_vehicle_category'])) {
  echo "<script>console.log('Edit vehicle category session is set');</script>";
}
if (isset($_SESSION['edit_vehicle_category'])): ?>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast1.onmouseenter = Swal.stopTimer;
        toast1.onmouseleave = Swal.resumeTimer;
      }
    });

    Toast.fire({
      icon: "success",
      title: "vehicle Category Updated Successfully"
    });
  </script>
  <?php unset($_SESSION['edit_vehicle_category']); ?>
<?php endif; ?>
<div class="table-heading">
  <div class="heading-2">
    <h2>Vehicle Categories</h2>
  </div>
  <div class="add-button">
    <a href="add_vehicles_category.php">
      <button><i class="fa fa-plus" aria-hidden="true"></i> Add Category</button>
    </a>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Image</th>
      <th>Seats</th>
      <th>Min Cost (Rs)</th>
      <th>Per KM Cost (Rs)</th>
      <th>Fuel Type</th>
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
          <td>
            <?php if (!empty($row['image'])): ?>
              <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Image" style="height: 60px;background:transparent;" />
            <?php else: ?>
              N/A
            <?php endif; ?>
          </td>
          <td><?php echo $row['seats']; ?></td>
          <td><?php echo $row['min_cost']; ?></td>
          <td><?php echo $row['per_km_cost']; ?></td>
          <td><?php echo htmlspecialchars($row['Fuel_type']); ?></td>
          <td>
            <!-- Edit Action -->
            <a href="edit_vehicle_category.php?id=<?php echo $row['id']; ?>" class="action-btn">
              <i class="fa fa-edit" aria-hidden="true"></i>
            </a>

            <!-- Delete Action (Confirmation dialog) -->
            <a href="delete_vehicle_category.php?id=<?php echo $row['id']; ?>" 
   class="action-btn delete-btn" 
   data-id="<?php echo $row['id']; ?>">
   <i class="fa fa-trash-o" aria-hidden="true"></i>
</a>
</td>
        </tr>
        <?php
      }
    } else {
      echo "<tr><td colspan='8'>No vehicle categories available.</td></tr>";
    }
    ?>
    
  </tbody>
</table>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const deleteButtons = document.querySelectorAll(".delete-btn");

  deleteButtons.forEach(button => {
    button.addEventListener("click", function(e) {
      e.preventDefault(); // stop the link from navigating
      const url = this.getAttribute("href");

      Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
      }).then((result) => {
        if (result.isConfirmed) {
          // Redirect to delete URL
          window.location.href = url;
        }
      });
    });
  });
});
</script>


<?php if (isset($_SESSION['delete_vehicle_category'])): ?>
  <script>
    const Toast = Swal.mixin({
      toast: true,
      position: "top-end",
      showConfirmButton: false,
      timer: 2500,
      timerProgressBar: true,
      didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
      }
    });

    Toast.fire({
      icon: "success",
      title: "Vehicle Category Deleted Successfully"
    });
  </script>
  <?php unset($_SESSION['delete_vehicle_category']); ?>
<?php endif; ?>

<?php include_once 'master_footer.php'; ?>
