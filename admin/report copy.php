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
        font-size: 2rem;
        /* padding-top: 1rem; */
        text-align: center;
      }
      .title {
        color: #234d83;
        font-weight: 800;
        /* margin-top: -1rem; */
      }
      .subject, .content {
        text-align: justify;
        font-size: 1.2rem;
        margin: 1rem 0;
      }
      .sub-title {
        font-weight: 800;
        margin-right: 0.5rem;
      }
      .table-body {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
      }
      table {
        border-collapse: separate;
        border-spacing: 0;
        min-width: 700px;
        background-color: #fff;
        border: 1px solid #dee2e6;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
      }
      thead {
        background-color: #234d83;
        color: #fff;
      }
      th, td {
        padding: 10px 15px;
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
        justify-content: space-between;
        gap: 5px;
        margin-bottom: 0.6rem;
      }
      .chart-box {
        width: 48%;
        background: #fff;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
        <div class="title">NagarYatra</div>
      </div>
      <div class="subject">
        <p><span class="sub-title">Subject:</span> Comprehensive Administrative Performance Report</p>
      </div>
      <div class="content">
        <p>Dear Sir,</p>
        <p>This report was generated on <span id="date">a</span> by the Admin to provide a comprehensive analysis of the system's performance, efficiency, and key metrics. It offers valuable insights to support data-driven decision-making and future improvements.</p>
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
              <td>10</td>
              <td>20</td>
              <td>20</td>
              <td>40</td>
            </tr>
            <tr>
              <td id="lastMonthDate">Loading...</td>
              <td>5</td>
              <td>15</td>
              <td>10</td>
              <td>35</td>
            </tr>
          </tbody>
        </table>
      </div>

      <h2>Comparative Analysis - Current vs Last Month</h2>

      <div class="charts-container">
        <div class="chart-box">
          <canvas id="barChart"></canvas>
        </div>
        <div class="chart-box">
          <canvas id="lineChart"></canvas>
        </div>
      </div>

      <!-- <div class="note">
        <strong>Note:</strong> This report contains confidential information and is intended solely for administrative use by authorized personnel. Unauthorized access, disclosure, or distribution is strictly prohibited.
      </div> -->
      <div class="copyright">
        <span id="year"></span> &copy; Copyright<strong style="padding-left: 5px;"> NagarYatra</strong>
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

      const labels = ["Users", "Vehicles", "Bookings", "Feedback"];
      const currentData = [10, 20, 20, 40];
      const lastMonthData = [5, 15, 10, 35];

      new Chart(document.getElementById("barChart"), {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            { label: "Current", data: currentData, backgroundColor: "rgba(54, 162, 235, 0.7)" },
            { label: "Last Month", data: lastMonthData, backgroundColor: "rgba(255, 99, 132, 0.7)" },
          ]
        },
        options: {
          responsive: true,
          plugins: {
            title: { display: true, text: "Bar Graph - Data Comparison" },
            legend: { position: "top" }
          },
          scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
      });

      new Chart(document.getElementById("lineChart"), {
        type: "line",
        data: {
          labels: labels,
          datasets: [
            { label: "Current", data: currentData, borderColor: "rgba(54, 162, 235, 1)", backgroundColor: "rgba(54, 162, 235, 0.2)", fill: true, tension: 0.4 },
            { label: "Last Month", data: lastMonthData, borderColor: "rgba(255, 99, 132, 1)", backgroundColor: "rgba(255, 99, 132, 0.2)", fill: true, tension: 0.4 },
          ]
        },
        options: {
          responsive: true,
          plugins: {
            title: { display: true, text: "Line Graph - Data Comparison" },
            legend: { position: "top" }
          },
          scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
      });

      function downloadPDF() {
        const element = document.getElementById("reportContent");
        html2pdf().from(element).set({
          margin: 0.5,
          filename: 'NagarYatra-Report.pdf',
          image: { type: 'png', quality: 0.98 },
          html2canvas: { scale: 2 },
          jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        }).save();
      }
    </script>
  </body>
</html>