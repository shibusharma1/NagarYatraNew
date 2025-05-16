<?php
require_once '../config/connection.php';

$title = "NagarYatra | Earnings";
$current_page = "earnings";
include_once 'master_header.php';

if (!isset($_SESSION['id'])) {
    echo "You are not logged in.";
    exit();
}

$userId = $_SESSION['vehicle_id'];
$filter = $_GET['filter'] ?? '7days';

switch ($filter) {
    case 'today':
        $startDate = date('Y-m-d');
        $endDate = $startDate;
        break;
    case 'month':
        $startDate = date('Y-m-01');
        $endDate = date('Y-m-t');
        break;
    case 'year':
        $startDate = date('Y-01-01');
        $endDate = date('Y-12-31');
        break;
    case '7days':
    default:
        $startDate = date('Y-m-d', strtotime('-6 days'));
        $endDate = date('Y-m-d');
        break;
}

$sql = "SELECT DATE(created_at) AS earning_date, SUM(estimated_cost) AS earnings
    FROM booking
    WHERE vehicle_id = ? AND is_delete = 0 AND status = 5 AND DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY earning_date
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $userId, $startDate, $endDate);
$stmt->execute();
$result = $stmt->get_result();

$earning_data = [];
while ($row = $result->fetch_assoc()) {
    $earning_data[$row['earning_date']] = $row['earnings'];
}

$labels = [];
$earnings = [];
$total_earnings = 0;

$period = new DatePeriod(
    new DateTime($startDate),
    new DateInterval('P1D'),
    (new DateTime($endDate))->modify('+1 day')
);

foreach ($period as $date) {
    $d = $date->format('Y-m-d');
    $labels[] = $d;
    $earn = round($earning_data[$d] ?? 0, 2);
    $earnings[] = $earn < 0 ? 0 : $earn;
    $total_earnings += $earn > 0 ? $earn : 0;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Earnings Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --theme-color: #092448;
            --bg-color: #f9f9f9;
        }
        body {
            background-color: var(--bg-color);
            font-family: 'Segoe UI', sans-serif;
        }
        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        }
        h2 {
            text-align: center;
            color: var(--theme-color);
            margin-bottom: 30px;
            font-weight: 700;
            font-size: 1.8rem;
        }
        .filter-group {
            text-align: center;
            margin-bottom: 25px;
        }
        .filter-btn {
            margin: 5px;
            border-color: var(--theme-color);
            color: var(--theme-color);
        }
        .filter-btn:hover,
        .filter-btn.active {
            background-color: var(--theme-color) !important;
            color: white !important;
        }
        .total-earnings {
            text-align: right;
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--theme-color);
            margin-top: 30px;
        }
        .chart-container {
            padding: 10px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Earnings Summary</h2>

    <div class="filter-group">
        <a href="?filter=today" class="btn btn-outline-primary filter-btn <?= $filter == 'today' ? 'active' : '' ?>">Today</a>
        <a href="?filter=7days" class="btn btn-outline-primary filter-btn <?= $filter == '7days' ? 'active' : '' ?>">Last 7 Days</a>
        <a href="?filter=month" class="btn btn-outline-primary filter-btn <?= $filter == 'month' ? 'active' : '' ?>">This Month</a>
        <a href="?filter=year" class="btn btn-outline-primary filter-btn <?= $filter == 'year' ? 'active' : '' ?>">This Year</a>
    </div>

    <div class="chart-container">
        <canvas id="earningChart" height="100"></canvas>
    </div>

    <div class="total-earnings">
        Total Earnings: ₹<?= number_format($total_earnings, 2) ?>
    </div>
</div>

<script>
    const labels = <?= json_encode($labels); ?>;
    const earnings = <?= json_encode($earnings); ?>;
    const themeColor = '#092448';

    const ctx = document.getElementById('earningChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(9, 36, 72, 0.4)');
    gradient.addColorStop(1, 'rgba(9, 36, 72, 0.02)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.map(label => {
                const date = new Date(label);
                return date.toLocaleDateString('en-IN', { day: 'numeric', month: 'short' });
            }),
            datasets: [{
                label: 'Earnings (₹)',
                data: earnings,
                fill: true,
                backgroundColor: gradient,
                borderColor: themeColor,
                tension: 0.3,
                pointBackgroundColor: themeColor,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.raw < 0 ? '' : '₹' + context.raw.toFixed(2);
                        }
                    }
                },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: (value) => '₹' + value,
                        color: themeColor
                    },
                    grid: {
                        color: '#eee'
                    }
                },
                x: {
                    ticks: { color: themeColor },
                    grid: { color: '#f5f5f5' }
                }
            }
        }
    });
</script>
<?php include_once 'master_footer.php'; ?>

