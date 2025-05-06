<?php
$title = "NagarYatra | Vehicles";
$current_page = "vehicle";

include_once 'master_header.php';
require_once '../config/connection.php';

// Pagination logic
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1; // Get current page, default to 1
$offset = ($page - 1) * $records_per_page;

// Fetch vehicle data with the necessary columns
$sql = "SELECT v.id, v.chassis_number, v.color, v.vehicle_number, v.description, v.bill_book_expiry_date, 
               v.thumb_image, v.bill_book_image, vc.name AS company_name,v.is_approved, v.created_at
        FROM vehicle v
        LEFT JOIN vehicle_company vc ON v.vehicle_company_id = vc.id
        WHERE is_delete = 0
        ORDER BY v.created_at DESC
        LIMIT $records_per_page OFFSET $offset";


$result = mysqli_query($conn, $sql);

// Get total number of records for pagination
$sql_count = "SELECT COUNT(*) AS total FROM vehicle";
$result_count = mysqli_query($conn, $sql_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $records_per_page);

?>

<div class="table-heading">
  <div class="heading-2">
    <h2>Vehicles</h2>
  </div>
  <div class="add-button">
    <a href="vehicle_form">
      <button>
        <i class="fa fa-plus" aria-hidden="true"></i> Add
      </button>
    </a>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Driver Name</th>
      <th>Company</th>
      <th>Chassis Number</th>
      <th>Color</th>
      <th>Vehicle Number</th>
      <th>Description</th>
      <th>Bill Expiry Date</th>
      <th>Approved Status</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
      $sn = $offset + 1; // Start serial number from current offset
      while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td><?php echo $sn++; ?></td>

          <td>

            <?php
            // Assuming $row['id'] contains the vehicle_id
            $vehicle_id = $row['id']; // Get the vehicle_id from the row
        
            // Prepare the query to fetch the driver's name
            $sql = "SELECT name FROM user WHERE vehicle_id = ?";

            // Prepare and execute the query
            if ($stmt = $conn->prepare($sql)) {
              $stmt->bind_param("i", $vehicle_id); // Bind the vehicle_id to the prepared statement
              $stmt->execute();
              $stmt->bind_result($driver_name); // Bind the result to $driver_name
        
              if ($stmt->fetch()) {
                // If a driver is found
                echo htmlspecialchars($driver_name);
              } else {
                // If no driver is found for this vehicle
                echo "No driver assigned";
              }

              $stmt->close(); // Close the statement
            } else {
              echo "Error in query preparation: " . $conn->error; // Error handling
            }
            ?>

          </td>
          <td><?php echo htmlspecialchars($row['company_name']); ?></td>
          <td><?php echo htmlspecialchars($row['chassis_number']); ?></td>
          <td><?php echo htmlspecialchars($row['color']); ?></td>
          <td><?php echo htmlspecialchars($row['vehicle_number']); ?></td>

          <td>
            <?php
            $plainText = strip_tags($row['description']); // Remove HTML tags
            $shortText = substr($plainText, 0, 50); // Get first 100 characters
            echo $shortText . (strlen($plainText) > 50 ? '...' : '');
            ?>


          </td>
          <td><?php echo htmlspecialchars($row['bill_book_expiry_date']); ?></td>
          
          <td>
            <?php if ($row['is_approved'] == 1): ?>
              <!-- If status is 1, show Active and Deactivate button -->
              <form method="POST" action="is_approved.php" style="display:inline;" class="block-form">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="status" value="0">
                <button type="submit" class="btn-active">Approved</button>
              </form>
              <!-- script for confirmation -->
              <script>
                document.querySelectorAll('.block-form').forEach(form => {
                  form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Stop form from submitting

                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Do you really want to disapprove this vehicle?",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Yes, disapprove user!',
                      cancelButtonText: 'Cancel'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        form.submit(); // Submit form if confirmed
                      }
                    });
                  });
                });
              </script>
            <?php else: ?>
              <!-- If status is not 1, show Inactive and Activate button -->
              <form method="POST" action="is_approved.php" style="display:inline;" class="block-form1">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="status" value="1">
                <button type="submit" class="btn-inactive">Disapproved</button>
              </form>
              <!-- script for confirmation -->
              <script>
                document.querySelectorAll('.block-form1').forEach(form => {
                  form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Stop form from submitting

                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Do you really want to approve this vehicle?",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Yes, approve user!',
                      cancelButtonText: 'Cancel'
                    }).then((result) => {
                      if (result.isConfirmed) {
                        form.submit(); // Submit form if confirmed
                      }
                    });
                  });
                });
              </script>

            <?php endif; ?>
          </td>



          <td>
            <!-- show Action -->
            <a href="show_vehicle.php?id=<?php echo $row['id']; ?>" class="action-btn">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </a>

            <!-- Edit Action -->
            <a href="edit_vehicle.php?id=<?php echo $row['id']; ?>" class="action-btn">
              <i class="fa fa-edit" aria-hidden="true"></i>
            </a>

            <!-- Delete Action (Confirmation dialog) -->
            <a href="delete_vehicle.php?id=<?php echo $row['id']; ?>"
              onclick="return confirm('Are you sure you want to delete this vehicle?');" class="action-btn">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a>
          </td>
        </tr>
        <?php
      }
    } else {
      echo "<tr><td colspan='9'>No vehicles available.</td></tr>";
    }
    ?>
  </tbody>
</table>

<!-- Pagination -->
<div class="pagination">
  <?php
  // Display previous page link
  if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "'>&laquo; Previous</a>";
  }

  // Display page number links
  for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
      echo "<a class='active' href='?page=$i'>$i</a>";
    } else {
      echo "<a href='?page=$i'>$i</a>";
    }
  }

  // Display next page link
  if ($page < $total_pages) {
    echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
  }
  ?>
</div>

<?php
include_once 'master_footer.php';
?>