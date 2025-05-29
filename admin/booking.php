<?php
$title = "NagarYatra | Booking";
$current_page = "booking";
include_once 'master_header.php';
include('../config/connection.php');

// Fetch all bookings
$sql = "SELECT * FROM booking WHERE status != 2 ORDER BY id DESC";
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
        body {
            background: #f4f4f4;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #092448;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            color: #092448;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #092448;
            margin: 0 2px;
            border-radius: 8px;
        }

        .pagination a.active,
        .pagination a:hover {
            background-color: #092448;
            color: white;
        }

        a {
            text-decoration: none;
        }
        .stars:hover{
            font-size:24.5px !important;
        }
    </style>
</head>

<body>

    <!-- <div class="container"> -->
    <div class="row mb-1 mt-4">
        <div class="col-md-3">
            <h2 style="margin-left:-7rem;font-size:24px;">Bookings</h2>
        </div>
        <div class="col-md-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by pickup or destination">
        </div>
        <div class="col-md-2">
            <input type="date" id="fromDate" class="form-control" placeholder="From Date">
        </div>
        <div class="col-md-2">
            <input type="date" id="toDate" class="form-control" placeholder="To Date">
        </div>
        <div class="col-md-2">
            <select id="statusFilter" class="form-control">
                <option value="">All Status</option>
                <option value="1">Cancelled</option>
                <option value="3">Accepted</option>
                <option value="4">Rejected</option>
                <option value="5">Completed</option>
                <option value="6">Cancelled</option>
            </select>
        </div>
    </div>

    <div id="bookingData"></div>
    <!-- </div> -->

    <script>
        const bookings = <?= json_encode($bookings) ?>;
        const statusMap = {
            1: { text: "Cancelled(P)", badge: "danger" },
            3: { text: "Accepted", badge: "primary" },
            4: { text: "Rejected", badge: "secondary" },
            5: { text: "Completed", badge: "success" },
            6: { text: "Cancelled(D)", badge: "danger" },
        };

        const ITEMS_PER_PAGE = 10;
        let currentPage = 1;

        function renderBookings() {
            const keyword = document.getElementById("searchInput").value.toLowerCase();
            const status = document.getElementById("statusFilter").value;
            const fromDate = document.getElementById("fromDate").value;
            const toDate = document.getElementById("toDate").value;

            const filtered = bookings.filter(b => {
                const matchKeyword = b.pick_up_place.toLowerCase().includes(keyword) || b.destination.toLowerCase().includes(keyword);
                const matchStatus = status === "" || b.status === status;
                const bookingDate = new Date(b.booking_date);
                let matchFrom = true, matchTo = true;
                if (fromDate) matchFrom = bookingDate >= new Date(fromDate);
                if (toDate) matchTo = bookingDate <= new Date(toDate);
                return matchKeyword && matchStatus && matchFrom && matchTo;
            });

            const totalPages = Math.ceil(filtered.length / ITEMS_PER_PAGE);
            const start = (currentPage - 1) * ITEMS_PER_PAGE;
            const pageData = filtered.slice(start, start + ITEMS_PER_PAGE);

            let html = "<table class='table table-bordered'><thead><tr>";
            html += "<th style='color:white;'>S.N</th><th style='color:white;text-align:center;'>Pickup</th><th style='color:white;text-align:center;'>Destination</th><th style='color:white;'>Date</th><th style='color:white;'>Rating</th><th style='color:white;'>Booking Status </th><th style='color:white;'>Action</th>";
            html += "</tr></thead><tbody>";

            // code for rating stars
            function getStarHTML(rating) {
                rating = parseInt(rating) || 0; // Ensure it's a number
                let stars = '';
                for (let i = 1; i <= 5; i++) {
                    if (i <= rating) {
                        stars += `<span class='stars' style="color: gold;font-size:24px;">★</span>`;
                    } else {
                        stars += `<span class='stars' style="color: #ccc;font-size:20px;">☆</span>`;
                    }
                }
                return stars;
            }


            if (pageData.length === 0) {
                html += "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
            } else {
                pageData.forEach((b, index) => {
                    const sn = start + index + 1;
                    const statusInfo = statusMap[b.status] || { text: "N/A", badge: "#9248" };
                    const date = new Date(b.booking_date).toLocaleDateString("en-GB", { day: "2-digit", month: "short", year: "numeric" });
                    html += `<tr>
                    <td>${sn}</td>
                    <td>${b.pick_up_place}</td>
                    <td>${b.destination}</td>
                    <td>${date}</td>
                    <td>${getStarHTML(b.rating)}</td>
                    <td><span class="badge bg-${statusInfo.badge}" style='line-height:2 !important;
                        border: none !important;
                        padding: 0.3rem !important;
                        font-size: 0.8rem !important;
                        font-weight: 500 !important;
                        border-radius: 8px !important;
                        cursor: pointer !important;
                        transition: all 0.3s ease !important;
                        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);'>${statusInfo.text}</span></td>
                    <td><a href='generate_ride_summary.php?id=${b.id}' target='_blank' class='btn btn-sm btn-success' style="background:#092448;"><i class='fas fa-download'></i></a></td>
                </tr>`;
                });
            }

            html += "</tbody></table><div class='pagination'>";

            // Pagination Logic: Prev + 3 buttons + Next
            if (totalPages > 1) {
                // Previous Button
                if (currentPage > 1) {
                    html += `<a href='#' class='page-link' data-page='${currentPage - 1}'>Prev</a>`;
                }

                // Calculate visible pages
                let startPage = Math.max(1, currentPage - 1);
                let endPage = Math.min(totalPages, startPage + 2);
                if (endPage - startPage < 2) {
                    startPage = Math.max(1, endPage - 2);
                }

                for (let i = startPage; i <= endPage; i++) {
                    const active = i === currentPage ? "active" : "";
                    html += `<a href='#' class='page-link ${active}' data-page='${i}'>${i}</a>`;
                }

                // Next Button
                if (currentPage < totalPages) {
                    html += `<a href='#' class='page-link' data-page='${currentPage + 1}'>Next</a>`;
                }
            }

            html += "</div>";

            document.getElementById("bookingData").innerHTML = html;

            document.querySelectorAll(".page-link").forEach(link => {
                link.addEventListener("click", e => {
                    e.preventDefault();
                    currentPage = parseInt(e.target.getAttribute("data-page"));
                    renderBookings();
                });
            });
        }

        ["searchInput", "statusFilter", "fromDate", "toDate"].forEach(id => {
            document.getElementById(id).addEventListener("input", () => {
                currentPage = 1;
                renderBookings();
            });
        });

        renderBookings();
    </script>
</body>

</html>