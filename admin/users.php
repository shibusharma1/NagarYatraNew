<?php
$title = "NagarYatra | Users";
$current_page = "user";

include_once 'master_header.php';
require_once '../config/connection.php';

// Pagination logic
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get role, order, and status values
$filter_role = isset($_GET['role']) ? trim($_GET['role']) : 'all';
$order_by = isset($_GET['order']) ? trim($_GET['order']) : 'name';
$filter_status = isset($_GET['status']) ? trim($_GET['status']) : 'all';

$where_clause = "WHERE is_delete = 0";

if ($filter_role === 'passenger') {
  $where_clause .= " AND role = 0";
} elseif ($filter_role === 'driver') {
  $where_clause .= " AND role = 1";
}

if ($filter_status === 'active') {
  $where_clause .= " AND status = 1";
} elseif ($filter_status === 'blocked') {
  $where_clause .= " AND status = 0";
}

// Fetch user data
$sql = "SELECT id, name, role, email, phone, status, gender, address, vehicle_id, is_verified, created_at
        FROM user
        $where_clause
        ORDER BY $order_by ASC
        LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Get total number of records for pagination
$sql_count = "SELECT COUNT(*) AS total FROM user $where_clause";
$result_count = mysqli_query($conn, $sql_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $records_per_page);
?>
<?php if (isset($_SESSION['user_updated'])): ?>
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
      title: "User updated successfully"
    });
  </script>
  <?php unset($_SESSION['user_updated']); ?>
<?php endif; ?>


<div class="table-heading">
  <div class="heading-2">
    <h2>Users</h2>
  </div>
  <div class="filters">
    <select id="roleFilter" class="filter-dropdown">
      <option value="all" <?php echo $filter_role === 'all' ? 'selected' : ''; ?>>All</option>
      <option value="passenger" <?php echo $filter_role === 'passenger' ? 'selected' : ''; ?>>Passenger</option>
      <option value="driver" <?php echo $filter_role === 'driver' ? 'selected' : ''; ?>>Driver</option>
    </select>
    <select id="statusFilter" class="filter-dropdown">
      <option value="all" <?php echo $filter_status === 'all' ? 'selected' : ''; ?>>All Status</option>
      <option value="active" <?php echo $filter_status === 'active' ? 'selected' : ''; ?>>Active</option>
      <option value="blocked" <?php echo $filter_status === 'blocked' ? 'selected' : ''; ?>>Blocked</option>
    </select>
    <select id="sortBy" class="filter-dropdown">
      <option value="name" <?php echo $order_by === 'name' ? 'selected' : ''; ?>>Sort by Name</option>
      <option value="email" <?php echo $order_by === 'email' ? 'selected' : ''; ?>>Sort by Email</option>
    </select>
    <input type="text" id="searchInput" class="search-input" placeholder="Search..." style="display: none;">
    <button id="searchBtn" class="search-btn">
      <i class="fa fa-search"></i> Search
    </button>
    <a href="user_form.php" class="add-button">
      <button class="add-user-btn">
        <i class="fa fa-plus" aria-hidden="true"></i> Add User
      </button>
    </a>
  </div>
</div>

<table>
  <thead>
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Role</th>
      <th>Email</th>
      <th>Phone</th>
      <th>Status</th>
      <th>Gender</th>
      <th>Address</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody id="userTable">
    <?php
    if (mysqli_num_rows($result) > 0) {
      $sn = $offset + 1;
      while ($row = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
          <td><?php echo $sn++; ?></td>
          <td><?php echo htmlspecialchars($row['name']); ?></td>
          <td>
            <?php if ($row['role'] == 1) { ?>
              <i class="fa fa-car"></i>
            <?php } else { ?>
              <i class="fa fa-user"></i>
            <?php } ?>
          </td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['phone']); ?></td>
          <td>
            <?php if ($row['status'] == 1): ?>
              <!-- If status is 1, show Active and Deactivate button -->
              <form method="POST" action="update_status.php" style="display:inline;" class="block-form">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="status" value="0">
                <button type="submit" class="btn-active">Active</button>
              </form>
              <!-- script for confirmation -->
              <script>
                document.querySelectorAll('.block-form').forEach(form => {
                  form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Stop form from submitting

                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Do you really want to block this user?",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Yes, block user!',
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
              <form method="POST" action="update_status.php" style="display:inline;" class="block-form1">
                <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="status" value="1">
                <button type="submit" class="btn-inactive">Blocked</button>
              </form>
              <!-- script for confirmation -->
              <script>
                document.querySelectorAll('.block-form1').forEach(form => {
                  form.addEventListener('submit', function (e) {
                    e.preventDefault(); // Stop form from submitting

                    Swal.fire({
                      title: 'Are you sure?',
                      text: "Do you really want to unblock this user?",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#d33',
                      cancelButtonColor: '#3085d6',
                      confirmButtonText: 'Yes, unblock user!',
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
          <td><?php echo htmlspecialchars($row['gender']); ?></td>
          <td><?php echo htmlspecialchars($row['address']); ?></td>
          <td>
            <a href="show_user.php?id=<?php echo $row['id']; ?>" class="action-btn">
              <i class="fa fa-eye" aria-hidden="true"></i>
            </a>
            <a href="edit_user.php?id=<?php echo $row['id']; ?>" class="action-btn">
              <i class="fa fa-edit" aria-hidden="true"></i>
            </a>
            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="action-btn delete-user"
              data-id="<?php echo $row['id']; ?>">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a>
            <script>
              document.querySelectorAll('.delete-user').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                  e.preventDefault(); // Stop the default link behavior
                  const url = this.getAttribute('href');

                  Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you really want to delete this user?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel'
                  }).then((result) => {
                    if (result.isConfirmed) {
                      window.location.href = url; // Redirect to delete URL
                    }
                  });
                });
              });
            </script>

          </td>
        </tr>
        <?php
      }
    } else {
      echo "<tr><td colspan='9'><img src='../assets/no_data.svg' alt='No data Found'class='no_image'/></td></tr>";
    }
    ?>
  </tbody>
</table>

<!-- Pagination -->
<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&role=<?php echo $filter_role; ?>&status=<?php echo $filter_status; ?>&order=<?php echo $order_by; ?>"
      class="prev">&laquo; Previous</a>
  <?php endif; ?>

  <?php
  for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i&role=$filter_role&status=$filter_status&order=$order_by' class='page-num " . ($i == $page ? 'active' : '') . "'>$i</a>";
  }
  ?>

  <?php if ($page < $total_pages): ?>
    <a href="?page=<?php echo $page + 1; ?>&role=<?php echo $filter_role; ?>&status=<?php echo $filter_status; ?>&order=<?php echo $order_by; ?>"
      class="next">Next &raquo;</a>
  <?php endif; ?>
</div>

<script>
  document.getElementById('roleFilter').addEventListener('change', updateFilters);
  document.getElementById('statusFilter').addEventListener('change', updateFilters);
  document.getElementById('sortBy').addEventListener('change', updateFilters);

  function updateFilters() {
    let role = document.getElementById('roleFilter').value;
    let status = document.getElementById('statusFilter').value;
    let order = document.getElementById('sortBy').value;
    window.location.href = '?role=' + role + '&status=' + status + '&order=' + order;
  }

  document.getElementById('searchBtn').addEventListener('click', function () {
    document.getElementById('searchInput').style.display = 'block';
  });

  document.getElementById('searchInput').addEventListener('input', function () {
    let searchVal = this.value.toLowerCase();
    document.querySelectorAll('#userTable tr').forEach(row => {
      row.style.display = row.innerText.toLowerCase().includes(searchVal) ? '' : 'none';
    });
  });
</script>

<?php
include_once 'master_footer.php';
?>