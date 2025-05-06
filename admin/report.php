<?php
require_once '../config/connection.php';

$start_date = date("Y-m-01", strtotime("first day of last month"));
$end_date = date("Y-m-t", strtotime("last day of last month"));

// Last month data
$query = "
    SELECT
        (SELECT COUNT(*) FROM user WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date') AS user_last_month,
        (SELECT COUNT(*) FROM vehicle WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date') AS vehicle_last_month,
        (SELECT COUNT(*) FROM booking WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date') AS booking_last_month,
        (SELECT COUNT(*) FROM feedback WHERE DATE(created_at) BETWEEN '$start_date' AND '$end_date') AS feedback_last_month
";
$result = $conn->query($query);
$row = $result->fetch_assoc();

$users_last_month = $row['user_last_month'];
$vehicles_last_month = $row['vehicle_last_month'];
$bookings_last_month = $row['booking_last_month'];
$feedbacks_last_month = $row['feedback_last_month'];

// Total data
$query_total = "
    SELECT
        (SELECT COUNT(*) FROM user) AS user_total,
        (SELECT COUNT(*) FROM vehicle) AS vehicle_total,
        (SELECT COUNT(*) FROM booking) AS booking_total,
        (SELECT COUNT(*) FROM feedback) AS feedback_total
";
$result_total = $conn->query($query_total);
$row_total = $result_total->fetch_assoc();

$users_total = $row_total['user_total'];
$vehicles_total = $row_total['vehicle_total'];
$bookings_total = $row_total['booking_total'];
$feedbacks_total = $row_total['feedback_total'];

// Insert summary
$conn->query("
    INSERT INTO report (users_count, vehicles_count, bookings_count, feedback_count)
    VALUES ($users_total, $vehicles_total, $bookings_total, $feedbacks_total)
");

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" type="image/png" sizes="64x64" href="../assets/logo1.png" />
  <title>NagarYatra | Report</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  <style>
    @media print {
      body {
        width: 210mm;
        height: 297mm;
        margin: 0;
      }
    }

    body {
      max-width: 794px;
      margin: auto;
      padding: 1rem;
      border: 3px solid #234d83;
      border-radius: 10px;
      background-color: #f5f5f5;
      font-family: Arial, sans-serif;
    }

    .download-btn {
      display: flex;
      justify-content: flex-end;
      margin: 0.2rem 0;
    }

    .download-btn button {
      padding: 10px 20px;
      background-color: #234d83;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 1rem;
      transition: background-color 0.3s ease;
    }

    .download-btn button:hover {
      background-color: #1a3964;
    }

    .header {
      font-size: 1.5rem;
      text-align: center;
    }

    .title {
      color: #234d83;
      font-weight: 800;
    }

    .subject,
    .content {
      text-align: justify;
      font-size: 1.2rem;
      width: 100%;
      margin: 1rem 0;
    }

    .sub-title {
      font-weight: 800;
      margin-right: 0.5rem;
    }

    .table-body {
      display: flex;
      justify-content: center;
      /* margin: 2rem 0; */
    }

    table {
      border-collapse: separate;
      border-spacing: 0;
      /* min-width: 700px; */
      width: 100%;
      background-color: #fff;
      border: 1px solid #dee2e6;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      border-radius: 8px;
      /* overflow: hidden; */
    }

    thead {
      background-color: #234d83;
      color: #fff;
    }

    th,
    td {
      padding: 8px 12px;
      text-align: center;
      border-bottom: 1px solid #dee2e6;
    }

    tr:last-child td {
      border-bottom: none;
    }

    tr:nth-child(even) td {
      background-color: #f1f3f5;
    }

    th {
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.05em;
    }

    .charts-container {
      display: flex;
      justify-content: center;
      margin-bottom: 1rem;
    }

    .chart-box {
      width: 95%;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    canvas {
      width: 100% !important;
      height: 350px !important;
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
    }

    .note {
      margin: 1rem;
      padding: 1rem;
      font-size: 1.1rem;
    }

    .copyright {
      margin: 2px;
      padding: 2px;
      display: flex;
      justify-content: center;
      font-size: 1rem;
      border-top: 1px solid black;
    }

    #generatedAt {
      /* position: absolute; */
      /* bottom: 10px; */
      /* right: 10px; */
      font-size: 1rem;
      color: #234d83;
      /* float: right; */
    }
  </style>
</head>

<body>
  <div class="download-btn">
    <button onclick="downloadPDF()"><i class="fas fa-download"></i> Download PDF</button>
  </div>

  <div class="container" id="reportContent">
    <div class="header">
      <div>Report</div>
      <div>of</div>
      <img src="../assets/logo_report.png" alt="Logo" style="width: 150px; margin-bottom: 10px;">

    </div>
    <div id="generatedAt" style="padding-top:10px;"></div>
    <div class="subject">
      <p><span class="sub-title">Subject:</span> Comprehensive Administrative Performance Report</p>
    </div>
    <div class="content">

      <p>Dear Sir,</p>
      <p style="padding: 0px 10px;">This report was generated on <span id="date">a</span> by the Admin to provide a comprehensive analysis of the
        system's performance, efficiency, and key metrics. It offers valuable insights to support data-driven
        decision-making and future improvements.</p>


    </div>
    <div class="table-body">
      <table>
        <thead>
          <tr>
            <th>Date</th>
            <th>No. of User</th>
            <th>No. of Vehicle</th>
            <th>No. of Booking</th>
            <th>No. of Feedback</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="currentDate">Loading...</td>
            <td><?= $users_total ?></td>
            <td><?= $vehicles_total ?></td>
            <td><?= $bookings_total ?></td>
            <td><?= $feedbacks_total ?></td>
          </tr>
          <tr>
            <td id="lastMonthDate">Loading...</td>
            <td><?= $users_last_month ?></td>
            <td><?= $vehicles_last_month ?></td>
            <td><?= $bookings_last_month ?></td>
            <td><?= $feedbacks_last_month ?></td>
          </tr>
        </tbody>
      </table>
    </div>

    <h2>Comparative Analysis - Current vs Last Month</h2>

    <div class="charts-container">
      <div class="chart-box">
        <canvas id="barChart"></canvas>
      </div>
    </div>

    <div class="copyright">
      <span id="year"> </span> &copy; Copyright <strong style="padding-left: 5px;">NagarYatra</strong>

    </div>

  </div>

  <script>
    function formatDate(date) {
      const yyyy = date.getFullYear();
      const mm = String(date.getMonth() + 1).padStart(2, "0");
      const dd = String(date.getDate()).padStart(2, "0");
      return `${yyyy}-${mm}-${dd}`;
    }

    const currentDate = new Date();
    document.getElementById("currentDate").textContent = formatDate(currentDate);

    const lastMonthEnd = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0);
    document.getElementById("lastMonthDate").textContent = formatDate(lastMonthEnd);

    document.getElementById("year").textContent = new Date().getFullYear();

    function getFormattedDate() {
      const date = new Date();
      const day = date.getDate();
      const month = date.toLocaleString("default", { month: "long" });
      const year = date.getFullYear();

      const getOrdinalSuffix = (day) => {
        if (day > 3 && day < 21) return "th";
        switch (day % 10) {
          case 1: return "st";
          case 2: return "nd";
          case 3: return "rd";
          default: return "th";
        }
      };

      return `${day}${getOrdinalSuffix(day)} ${month}, ${year}`;
    }
    document.getElementById("date").innerText = getFormattedDate();
    document.getElementById("generatedAt").innerText = "Generated at: " + getFormattedDate();

    const labels = ["Users", "Vehicles", "Bookings", "Feedback"];
    const currentData = [<?= $users_total ?>, <?= $vehicles_total ?>, <?= $bookings_total ?>, <?= $feedbacks_total ?>];
    const lastMonthData = [<?= $users_last_month ?>, <?= $vehicles_last_month ?>, <?= $bookings_last_month ?>, <?= $feedbacks_last_month ?>];

    new Chart(document.getElementById("barChart"), {
      type: "bar",
      data: {
        labels: labels,
        datasets: [
          { label: "Current", data: currentData, backgroundColor: "#092448" },
          { label: "Last Month", data: lastMonthData, backgroundColor: "#234D83" },
        ]
      },
      options: {
        responsive: true,
        plugins: {
          title: { display: true, text: "Data Comparison (Current vs Last Month)" }
        },
        scales: {
          y: { beginAtZero: true }
        }
      }
    });

    // function downloadPDF() {
    //   const reportContent = document.getElementById("reportContent");
    //   const opt = {
    //     margin: 0.5,
    //     filename: "NagarYatra_Report.pdf",
    //     image: { type: "jpeg", quality: 0.98 },
    //     html2canvas: { dpi: 300, letterRendering: true },
    //     jsPDF: { unit: "mm", format: "a4", orientation: "portrait" }
    //   };
    //   html2pdf().from(reportContent).set(opt).save();
    // }
    function downloadPDF() {
      var element = document.getElementById('reportContent');
      var logo = document.createElement('img');
      // logo.src = '../assets/logo1.png'; // Use your actual logo URL or base64
      // logo.style.width = '120px';
      // logo.style.marginBottom = '5px';

      reportContent.prepend(logo);

      html2pdf().set({
        margin: [10, 4, 5, 4], // top, left, bottom, right
        filename: 'nagar_yatra_ride_summary.pdf',
        image: { type: 'jpeg', quality: 0.98 },
        html2canvas: {
          scale: 2,
          useCORS: true,
          scrollY: 0   // <-- Important: avoid content shift/cut
        },
        jsPDF: {
          unit: 'mm',
          format: 'a4',
          orientation: 'portrait'
        },
        pagebreak: { mode: ['avoid-all', 'css', 'legacy'] } // <-- handle breaking content
      }).from(element).save();

    }

  </script>
</body>

</html>