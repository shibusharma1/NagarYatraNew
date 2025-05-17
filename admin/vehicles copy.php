<?php
$title = "NagarYatra | Vehicles";
$current_page = "vehicle";

include_once 'master_header.php';
require_once '../config/connection.php';

// Fetch categories for dropdown
$category_query = "SELECT id, name FROM vehicle_category";
$category_result = mysqli_query($conn, $category_query);

// Filters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$search_keyword = isset($_GET['search']) ? trim($_GET['search']) : '';

// Pagination
$records_per_page = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Build WHERE clause
$where_clauses = ["v.is_delete = 0"];
if ($status_filter !== '') {
    $where_clauses[] = "v.is_approved = " . (int)$status_filter;
}
if ($category_filter !== '') {
    $where_clauses[] = "v.vehicle_category_id = " . (int)$category_filter;
}
if ($search_keyword !== '') {
    $search = mysqli_real_escape_string($conn, $search_keyword);
    $where_clauses[] = "(v.vehicle_number LIKE '%$search%' OR v.chassis_number LIKE '%$search%' OR v.description LIKE '%$search%')";
}
$where_sql = implode(" AND ", $where_clauses);

// Get data
$sql = "SELECT v.id, v.chassis_number, v.color, v.vehicle_number, v.vehicle_category_id, v.description, 
               v.bill_book_expiry_date, v.thumb_image, v.bill_book_image, vc.name AS company_name, 
               vvc.name AS category_name, v.is_approved, v.created_at
        FROM vehicle v
        LEFT JOIN vehicle_company vc ON v.vehicle_company_id = vc.id
        LEFT JOIN vehicle_category vvc ON v.vehicle_category_id = vvc.id
        WHERE $where_sql
        ORDER BY v.created_at DESC
        LIMIT $records_per_page OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Total count for pagination
$sql_count = "SELECT COUNT(*) AS total FROM vehicle v WHERE $where_sql";
$result_count = mysqli_query($conn, $sql_count);
$total_rows = mysqli_fetch_assoc($result_count)['total'];
$total_pages = ceil($total_rows / $records_per_page);
?>

<div class="table-heading" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap;">
  <form method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
    <select name="status">
      <option value="">All Status</option>
      <option value="1" <?= $status_filter === '1' ? 'selected' : '' ?>>Approved</option>
      <option value="0" <?= $status_filter === '0' ? 'selected' : '' ?>>Disapproved</option>
    </select>

    <select name="category">
      <option value="">All Categories</option>
      <?php
      // Re-run category query as result was consumed
      $category_result = mysqli_query($conn, $category_query);
      while ($cat = mysqli_fetch_assoc($category_result)) { ?>
        <option value="<?= $cat['id']; ?>" <?= $category_filter == $cat['id'] ? 'selected' : '' ?>>
          <?= htmlspecialchars($cat['name']); ?>
        </option>
      <?php } ?>
    </select>

    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search_keyword); ?>">
    <button type="submit">Filter</button>
  </form>

  <div class="add-button">
    <a href="vehicle_form">
      <button><i class="fa fa-plus" aria-hidden="true"></i> Add</button>
    </a>
  </div>
</div>

<!-- Table -->
<div class="table-container">
  <table border="1" cellpadding="10" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th>#</th>
        <th>Vehicle Number</th>
        <th>Chassis No.</th>
        <th>Company</th>
        <th>Category</th>
        <th>Color</th>
        <th>Description</th>
        <th>Bill Book Expiry</th>
        <th>Status</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (mysqli_num_rows($result) > 0) {
        $count = $offset + 1;
        while ($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $count++; ?></td>
            <td><?= htmlspecialchars($row['vehicle_number']); ?></td>
            <td><?= htmlspecialchars($row['chassis_number']); ?></td>
            <td><?= htmlspecialchars($row['company_name']); ?></td>
            <td><?= htmlspecialchars($row['category_name']); ?></td>
            <td><?= htmlspecialchars($row['color']); ?></td>
            <td><?= htmlspecialchars($row['description']); ?></td>
            <td><?= htmlspecialchars($row['bill_book_expiry_date']); ?></td>
            <td>
              <?php if ($row['is_approved'] == 1): ?>
                <span style="color: green;">Approved</span>
              <?php else: ?>
                <span style="color: red;">Disapproved</span>
              <?php endif; ?>
            </td>
            <td><?= date("Y-m-d", strtotime($row['created_at'])); ?></td>
            <td>
              <a href="vehicle_form?id=<?= $row['id']; ?>">Edit</a>
              |
              <a href="vehicle_delete.php?id=<?= $row['id']; ?>" onclick="return confirm('Delete this vehicle?')">Delete</a>
            </td>
          </tr>
      <?php }
      } else {
        echo "<tr><td colspan='11' style='text-align:center;'>No vehicles found.</td></tr>";
      } ?>
    </tbody>
  </table>
</div>

<!-- Pagination -->
<div class="pagination" style="margin-top: 20px;">
  <?php
  $base_url = strtok($_SERVER["REQUEST_URI"], '?');
  $query_params = $_GET;
  if ($page > 1) {
    $query_params['page'] = $page - 1;
    echo "<a href='$base_url?" . http_build_query($query_params) . "'>&laquo; Prev</a> ";
  }

  for ($i = 1; $i <= $total_pages; $i++) {
    $query_params['page'] = $i;
    $active_class = ($i == $page) ? "style='font-weight: bold;'" : "";
    echo "<a href='$base_url?" . http_build_query($query_params) . "' $active_class>$i</a> ";
  }

  if ($page < $total_pages) {
    $query_params['page'] = $page + 1;
    echo "<a href='$base_url?" . http_build_query($query_params) . "'>Next &raquo;</a>";
  }
  ?>
</div>

<?php include_once 'master_footer.php'; ?>
