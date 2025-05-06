<?php
$title = "NagarYatra | Booking";
$current_page = "booking";
include_once 'master_header.php';
include('../config/connection.php');

// Fetch all bookings
$sql = "SELECT * FROM booking ORDER BY id DESC";
$result = $conn->query($sql);
$bookings = [];
while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f4f4; }
        .container { max-width: 1100px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #092448; margin-bottom: 20px; font-weight: 600; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a { color: #092448; padding: 8px 16px; text-decoration: none; border: 1px solid #092448; margin: 0 2px; border-radius: 8px; }
        .pagination a.active, .pagination a:hover { background-color: #092448; color: white; }
        a { text-decoration: none; }
    </style>
</head>
<body>
<!-- <div class="container"> -->
    <div class="row mb-1 mt-4">
    <div class="col-md-3">
    <h2 style="margin-left:-6rem;font-size:24px;">Bookings</h2>
        </div>
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by pickup or destination">
        </div>
        <div class="col-md-3">
            <select id="statusFilter" class="form-control">
                <option value="">All Status</option>
                <option value="1">Cancelled</option>
                <option value="3">Success</option>
                <option value="4">Rejected</option>
                <option value="5">Completed</option>
            </select>
        </div>
    </div>
    <div id="bookingData"></div>
<!-- </div> -->

<script>
    const bookings = <?= json_encode($bookings) ?>;
    const statusMap = {1: "Cancelled", 3: "Success", 4: "Rejected", 5: "Completed"};

    const ITEMS_PER_PAGE = 10;
    let currentPage = 1;

    function renderBookings() {
        const keyword = document.getElementById("searchInput").value.toLowerCase();
        const status = document.getElementById("statusFilter").value;

        const filtered = bookings.filter(b => {
            const matchKeyword = b.pick_up_place.toLowerCase().includes(keyword) || b.destination.toLowerCase().includes(keyword);
            const matchStatus = status === "" || b.status === status;
            return matchKeyword && matchStatus;
        });

        const totalPages = Math.ceil(filtered.length / ITEMS_PER_PAGE);
        const start = (currentPage - 1) * ITEMS_PER_PAGE;
        const pageData = filtered.slice(start, start + ITEMS_PER_PAGE);

        let html = "<table class='table table-bordered'><thead><tr>";
        html += "<th style='color:white;'>S.N</th><th style='color:white;text-align:center;'>Pickup</th><th style='color:white;text-align:center;'>Destination</th><th style='color:white;'>Date</th><th style='color:white;'>Status</th><th style='color:white;'>Action</th>";
        html += "</tr></thead><tbody>";

        if (pageData.length === 0) {
            html += "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
        } else {
            pageData.forEach((b, index) => {
                const sn = start + index + 1;
                const statusText = statusMap[b.status] || "Unknown";
                const date = new Date(b.booking_date).toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
                html += `<tr>
                    <td>${sn}</td>
                    <td>${b.pick_up_place}</td>
                    <td>${b.destination}</td>
                    <td>${date}</td>
                    <td>${statusText}</td>
                    <td><a href='generate_ride_summary.php?id=${b.id}' target='_blank' class='btn btn-sm btn-success' style="background:#092448;"><i class='fas fa-download'></i></a></td>
                </tr>`;
            });
        }

        html += "</tbody></table><div class='pagination'>";
        for (let i = 1; i <= totalPages; i++) {
            const active = i === currentPage ? "active" : "";
            html += `<a href='#' class='page-link ${active}' data-page='${i}'>${i}</a>`;
        }
        html += "</div>";

        document.getElementById("bookingData").innerHTML = html;

        // Add click events
        document.querySelectorAll(".page-link").forEach(link => {
            link.addEventListener("click", e => {
                e.preventDefault();
                currentPage = parseInt(e.target.getAttribute("data-page"));
                renderBookings();
            });
        });
    }

    document.getElementById("searchInput").addEventListener("input", () => {
        currentPage = 1;
        renderBookings();
    });

    document.getElementById("statusFilter").addEventListener("change", () => {
        currentPage = 1;
        renderBookings();
    });

    // Initial render
    renderBookings();
</script>
</body>
</html>
