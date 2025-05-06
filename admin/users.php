<?php
$title = "NagarYatra | Users";
$current_page = "user";

include_once 'master_header.php';
require_once '../config/connection.php';

// Pagination logic
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Get role and order values, trimming spaces to prevent issues
$filter_role = isset($_GET['role']) ? trim($_GET['role']) : 'all';
$order_by = isset($_GET['order']) ? trim($_GET['order']) : 'name';

$where_clause = "WHERE is_delete = 0";

if ($filter_role === 'passenger') {
  $where_clause .= " AND role = 0";
} elseif ($filter_role === 'driver') {
  $where_clause .= " AND role = 1";
}

// Fetch user data
$sql = "SELECT id, name, role, email, phone, status, gender, address, vehicle_id, is_verified, created_at
        FROM user
        $where_clause
        ORDER BY created_at DESC
        LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Get total number of records for pagination
$sql_count = "SELECT COUNT(*) AS total FROM user $where_clause";
$result_count = mysqli_query($conn, $sql_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $records_per_page);
?>

<div class="table-heading">
  <div class="heading-2">
    <h2>Users</h2>
  </div>
  <div class="filters">
    <select id="roleFilter" class="filter-dropdown">
      <option value="all">All</option>
      <option value="passenger">Passenger</option>
      <option value="driver">Driver</option>
    </select>
    <select id="sortBy" class="filter-dropdown">
      <option value="name">Sort by Name</option>
      <option value="email">Sort by Email</option>
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
            <!-- <a href="delete_user.php?id=<?php
            // echo $row['id']; 
            ?>"
              onclick="return confirm('Are you sure you want to delete this user?');" class="action-btn">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a> -->
            <a href="delete_user.php?id=<?php echo $row['id']; ?>" class="action-btn delete-user"
              data-id="<?php echo $row['id']; ?>">
              <i class="fa fa-trash-o" aria-hidden="true"></i>
            </a>
            <script>
              document.addEventListener("DOMContentLoaded", function () {
                const deleteLinks = document.querySelectorAll(".delete-user");

                deleteLinks.forEach(link => {
                  link.addEventListener("click", function (e) {
                    e.preventDefault(); // prevent default link behavior
                    const userId = this.getAttribute("data-id");
                    const url = `delete_user.php?id=${userId}`;

                    Swal.fire({
                      title: "Are you sure?",
                      text: "You won't be able to revert this!",
                      icon: "warning",
                      showCancelButton: true,
                      confirmButtonColor: "#d33",
                      cancelButtonColor: "#3085d6",
                      confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                      if (result.isConfirmed) {
                        window.location.href = url;
                      }
                    });
                  });
                });
              });
            </script>

          </td>
        </tr>
        <?php
      }
    } else {
      echo "<tr><td colspan='9'>No users available.</td></tr>";
    }
    ?>
  </tbody>
</table>

<!-- Pagination -->
<div class="pagination">
  <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&role=<?php echo $filter_role; ?>&order=<?php echo $order_by; ?>"
      class="prev">&laquo; Previous</a>
  <?php endif; ?>

  <?php
  // Display page numbers
  for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a href='?page=$i&role=$filter_role&order=$order_by' class='page-num " . ($i == $page ? 'active' : '') . "'>$i</a>";
  }
  ?>

  <?php if ($page < $total_pages): ?>
    <a href="?page=<?php echo $page + 1; ?>&role=<?php echo $filter_role; ?>&order=<?php echo $order_by; ?>"
      class="next">Next &raquo;</a>
  <?php endif; ?>
</div>

<script>
  document.getElementById('roleFilter').addEventListener('change', function () {
    let role = this.value;
    window.location.href = '?role=' + role + '&order=' + document.getElementById('sortBy').value;
  });

  document.getElementById('sortBy').addEventListener('change', function () {
    let order = this.value;
    window.location.href = '?role=' + document.getElementById('roleFilter').value + '&order=' + order;
  });

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