<?php
$title = "NagarYatra | Feedback";
$current_page = "feedback";

include_once 'master_header.php';
require_once '../config/connection.php';

// Pagination logic
$records_per_page = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Fetch feedback data with associated user info, excluding deleted feedback
$sql = "SELECT f.id, f.subject, f.message, f.status, f.created_at, u.name, u.role, u.email
        FROM feedback f
        LEFT JOIN user u ON f.user_id = u.id
        WHERE f.is_delete = 0
        ORDER BY f.created_at DESC
        LIMIT $records_per_page OFFSET $offset";

$result = mysqli_query($conn, $sql);

// Get total number of records for pagination
$sql_count = "SELECT COUNT(*) AS total FROM feedback WHERE is_delete = 0";
$result_count = mysqli_query($conn, $sql_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $records_per_page);
?>

<div class="table-heading">
  <div class="heading-2">
    <h2>Feedback</h2>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>S.N</th>
      <th>Name</th>
      <th>Role</th>
      <th>Email</th>
      <th>Subject</th>
      <th>Message</th>
      <th>Status</th>
    </tr>
  </thead>
  <tbody>
    <?php
    if (mysqli_num_rows($result) > 0) {
      $sn = $offset + 1;
      while ($row = mysqli_fetch_assoc($result)) {
        $role_text = ($row['role'] == 1) ? '<i class="fa fa-car"></i>' : '<i class="fa fa-user"></i>';
        $status_text = ($row['status'] == 1) ? '1' : '0';
    ?>
    <tr>
      <td><?php echo $sn++; ?></td>
      <td><?php echo htmlspecialchars($row['name']); ?></td>
      <td><?php echo $role_text; ?></td>
      <td><?php echo htmlspecialchars($row['email']); ?></td>
      <td><?php echo htmlspecialchars($row['subject']); ?></td>
      <td><?php echo htmlspecialchars($row['message']); ?></td>
      <td>
      <?php if ($row['status'] == 1): ?>
              <!-- If status is 1, show Active and Deactivate button -->
              
                <button type="" class="btn-active">Reviewed</button>
             
              <!-- script for confirmation -->
              <script>
                document.querySelectorAll('.block-form').forEach(form => {
                  form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Stop form from submitting

                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Do you really want to change feedback status?",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Yes!',
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
              <form method="POST" action="update_status_feedback.php" style="display:inline;" class="block-form12">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="status" value="1">
                <button type="submit" class="btn-inactive" style="background-color:#FFA500;">Resolve</button>
              </form>
              <!-- script for confirmation -->
              <script>
                document.querySelectorAll('.block-form1').forEach(form => {
                  form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Stop form from submitting

                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Do you really want to Change Feedback Status?",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Yes',
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
    </tr>
    <?php
      }
    } else {
      echo "<tr><td colspan='7'>No feedback available.</td></tr>";
    }
    ?>
  </tbody>
</table>

<!-- Pagination -->
<div class="pagination">
  <?php
  if ($page > 1) {
    echo "<a href='?page=" . ($page - 1) . "'>&laquo; Previous</a>";
  }

  for ($i = 1; $i <= $total_pages; $i++) {
    if ($i == $page) {
      echo "<a class='active' href='?page=$i'>$i</a>";
    } else {
      echo "<a href='?page=$i'>$i</a>";
    }
  }

  if ($page < $total_pages) {
    echo "<a href='?page=" . ($page + 1) . "'>Next &raquo;</a>";
  }
  ?>
</div>

<?php include_once 'master_footer.php'; ?>
