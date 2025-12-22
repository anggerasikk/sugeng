<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

include '../header-admin.php';
include '../sidebar.php';
?>

<style>
    .admin-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .admin-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .admin-header h1 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
    }

    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 5px;
        font-weight: 500;
        color: <?php echo $primary_blue; ?>;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .btn-filter {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-filter:hover {
        background: <?php echo $primary_blue; ?>;
    }

    .revenue-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    .summary-number {
        font-size: 2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 5px;
    }

    .summary-label {
        color: #666;
        font-size: 0.9rem;
    }

    .revenue-chart {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .chart-container {
        height: 400px;
        position: relative;
    }

    .revenue-table {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        background: <?php echo $primary_blue; ?>;
        color: white;
        padding: 15px 20px;
        font-weight: bold;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #f8f9fa;
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .export-buttons {
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }

    .btn-export {
        background: <?php echo $primary_blue; ?>;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-export:hover {
        background: <?php echo $secondary_blue; ?>;
    }

    @media (max-width: 768px) {
        .admin-content {
            margin-left: 0;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .revenue-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-content">
    <div class="admin-header">
        <h1>💰 Laporan Pendapatan</h1>
        <p>Analisis pendapatan dan performa keuangan</p>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="period">Periode</label>
                    <select id="period" name="period">
                        <option value="daily" <?php echo ($_GET['period'] ?? 'monthly') === 'daily' ? 'selected' : ''; ?>>Harian</option>
                        <option value="weekly" <?php echo ($_GET['period'] ?? 'monthly') === 'weekly' ? 'selected' : ''; ?>>Mingguan</option>
                        <option value="monthly" <?php echo ($_GET['period'] ?? 'monthly') === 'monthly' ? 'selected' : ''; ?>>Bulanan</option>
                        <option value="yearly" <?php echo ($_GET['period'] ?? 'monthly') === 'yearly' ? 'selected' : ''; ?>>Tahunan</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_from">Tanggal Dari</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo $_GET['date_from'] ?? date('Y-m-01'); ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">Tanggal Sampai</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo $_GET['date_to'] ?? date('Y-m-t'); ?>">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Export Buttons -->
    <div class="export-buttons">
        <a href="?export=csv&<?php echo http_build_query($_GET); ?>" class="btn-export">📊 Export CSV</a>
        <a href="?export=pdf&<?php echo http_build_query($_GET); ?>" class="btn-export">📄 Export PDF</a>
    </div>

    <!-- Revenue Summary -->
    <div class="revenue-summary">
        <?php
        $period = $_GET['period'] ?? 'monthly';
        $date_from = $_GET['date_from'] ?? date('Y-m-01');
        $date_to = $_GET['date_to'] ?? date('Y-m-t');

        // Total revenue
        $total_query = "SELECT SUM(total_amount) as revenue FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE(created_at) BETWEEN ? AND ?";
        $total_stmt = mysqli_prepare($koneksi, $total_query);
        mysqli_stmt_bind_param($total_stmt, "ss", $date_from, $date_to);
        mysqli_stmt_execute($total_stmt);
        $total_result = mysqli_stmt_get_result($total_stmt);
        $total_revenue = mysqli_fetch_assoc($total_result)['revenue'] ?? 0;

        // Monthly growth (compare with previous period)
        $days_diff = (strtotime($date_to) - strtotime($date_from)) / (60 * 60 * 24);
        $prev_date_from = date('Y-m-d', strtotime($date_from) - ($days_diff * 24 * 60 * 60));
        $prev_date_to = date('Y-m-d', strtotime($date_to) - ($days_diff * 24 * 60 * 60));

        $prev_query = "SELECT SUM(total_amount) as revenue FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE(created_at) BETWEEN ? AND ?";
        $prev_stmt = mysqli_prepare($koneksi, $prev_query);
        mysqli_stmt_bind_param($prev_stmt, "ss", $prev_date_from, $prev_date_to);
        mysqli_stmt_execute($prev_stmt);
        $prev_result = mysqli_stmt_get_result($prev_stmt);
        $prev_revenue = mysqli_fetch_assoc($prev_result)['revenue'] ?? 0;

        $growth = $prev_revenue > 0 ? (($total_revenue - $prev_revenue) / $prev_revenue) * 100 : 0;

        // Average booking value
        $avg_query = "SELECT AVG(total_amount) as avg_booking FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE(created_at) BETWEEN ? AND ?";
        $avg_stmt = mysqli_prepare($koneksi, $avg_query);
        mysqli_stmt_bind_param($avg_stmt, "ss", $date_from, $date_to);
        mysqli_stmt_execute($avg_stmt);
        $avg_result = mysqli_stmt_get_result($avg_stmt);
        $avg_booking = mysqli_fetch_assoc($avg_result)['avg_booking'] ?? 0;

        // Total bookings
        $bookings_query = "SELECT COUNT(*) as total_bookings FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE(created_at) BETWEEN ? AND ?";
        $bookings_stmt = mysqli_prepare($koneksi, $bookings_query);
        mysqli_stmt_bind_param($bookings_stmt, "ss", $date_from, $date_to);
        mysqli_stmt_execute($bookings_stmt);
        $bookings_result = mysqli_stmt_get_result($bookings_stmt);
        $total_bookings = mysqli_fetch_assoc($bookings_result)['total_bookings'];
        ?>

        <div class="summary-card">
            <div class="summary-number"><?php echo format_currency($total_revenue); ?></div>
            <div class="summary-label">Total Pendapatan</div>
        </div>
        <div class="summary-card">
            <div class="summary-number"><?php echo $growth >= 0 ? '+' : ''; ?><?php echo number_format($growth, 1); ?>%</div>
            <div class="summary-label">Pertumbuhan</div>
        </div>
        <div class="summary-card">
            <div class="summary-number"><?php echo format_currency($avg_booking); ?></div>
            <div class="summary-label">Rata-rata Booking</div>
        </div>
        <div class="summary-card">
            <div class="summary-number"><?php echo $total_bookings; ?></div>
            <div class="summary-label">Total Booking</div>
        </div>
    </div>

    <!-- Revenue Chart -->
    <div class="revenue-chart">
        <h3 style="margin-bottom: 20px; color: <?php echo $primary_blue; ?>;">📈 Tren Pendapatan</h3>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Revenue Table -->
    <div class="revenue-table">
        <div class="table-header">
            📋 Detail Pendapatan per Periode
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Total Booking</th>
                        <th>Total Pendapatan</th>
                        <th>Rata-rata per Booking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Generate data based on period
                    $period_data = [];

                    if ($period === 'daily') {
                        $current_date = strtotime($date_from);
                        $end_date = strtotime($date_to);

                        while ($current_date <= $end_date) {
                            $date_str = date('Y-m-d', $current_date);
                            $period_data[$date_str] = [
                                'label' => date('d M Y', $current_date),
                                'bookings' => 0,
                                'revenue' => 0
                            ];
                            $current_date = strtotime('+1 day', $current_date);
                        }
                    } elseif ($period === 'weekly') {
                        $current_date = strtotime($date_from);
                        $end_date = strtotime($date_to);

                        while ($current_date <= $end_date) {
                            $week_start = date('Y-m-d', strtotime('monday this week', $current_date));
                            $week_end = date('Y-m-d', strtotime('sunday this week', $current_date));
                            $label = date('d M', strtotime($week_start)) . ' - ' . date('d M Y', strtotime($week_end));

                            $period_data[$week_start . '_' . $week_end] = [
                                'label' => $label,
                                'bookings' => 0,
                                'revenue' => 0
                            ];
                            $current_date = strtotime('+1 week', $current_date);
                        }
                    } elseif ($period === 'monthly') {
                        $current_date = strtotime($date_from);
                        $end_date = strtotime($date_to);

                        while ($current_date <= $end_date) {
                            $month_key = date('Y-m', $current_date);
                            $period_data[$month_key] = [
                                'label' => date('M Y', $current_date),
                                'bookings' => 0,
                                'revenue' => 0
                            ];
                            $current_date = strtotime('+1 month', $current_date);
                        }
                    } else { // yearly
                        $current_year = date('Y', strtotime($date_from));
                        $end_year = date('Y', strtotime($date_to));

                        for ($year = $current_year; $year <= $end_year; $year++) {
                            $period_data[$year] = [
                                'label' => $year,
                                'bookings' => 0,
                                'revenue' => 0
                            ];
                        }
                    }

                    // Fill data with actual values
                    foreach ($period_data as $key => &$data) {
                        if ($period === 'daily') {
                            $query = "SELECT COUNT(*) as bookings, SUM(total_amount) as revenue FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE(created_at) = ?";
                            $stmt = mysqli_prepare($koneksi, $query);
                            mysqli_stmt_bind_param($stmt, "s", $key);
                        } elseif ($period === 'weekly') {
                            list($week_start, $week_end) = explode('_', $key);
                            $query = "SELECT COUNT(*) as bookings, SUM(total_amount) as revenue FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE(created_at) BETWEEN ? AND ?";
                            $stmt = mysqli_prepare($koneksi, $query);
                            mysqli_stmt_bind_param($stmt, "ss", $week_start, $week_end);
                        } elseif ($period === 'monthly') {
                            $query = "SELECT COUNT(*) as bookings, SUM(total_amount) as revenue FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND DATE_FORMAT(created_at, '%Y-%m') = ?";
                            $stmt = mysqli_prepare($koneksi, $query);
                            mysqli_stmt_bind_param($stmt, "s", $key);
                        } else { // yearly
                            $query = "SELECT COUNT(*) as bookings, SUM(total_amount) as revenue FROM bookings WHERE booking_status IN ('confirmed', 'completed') AND YEAR(created_at) = ?";
                            $stmt = mysqli_prepare($koneksi, $query);
                            mysqli_stmt_bind_param($stmt, "s", $key);
                        }

                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);
                        $row = mysqli_fetch_assoc($result);

                        $data['bookings'] = $row['bookings'] ?? 0;
                        $data['revenue'] = $row['revenue'] ?? 0;
                    }

                    // Display table rows
                    foreach ($period_data as $data) {
                        $avg_per_booking = $data['bookings'] > 0 ? $data['revenue'] / $data['bookings'] : 0;
                        ?>
                        <tr>
                            <td><?php echo $data['label']; ?></td>
                            <td><?php echo $data['bookings']; ?></td>
                            <td><?php echo format_currency($data['revenue']); ?></td>
                            <td><?php echo format_currency($avg_per_booking); ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('revenueChart').getContext('2d');

    // Prepare chart data
    const chartData = <?php echo json_encode(array_values($period_data)); ?>;
    const labels = chartData.map(item => item.label);
    const revenues = chartData.map(item => parseFloat(item.revenue));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Pendapatan',
                data: revenues,
                borderColor: '<?php echo $primary_blue; ?>',
                backgroundColor: '<?php echo $primary_blue; ?>20',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});
</script>

<?php include '../footer-admin.php'; ?>
