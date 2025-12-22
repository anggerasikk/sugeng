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

    .report-summary {
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

    .report-table {
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

    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
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

        .report-summary {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-content">
    <div class="admin-header">
        <h1>📊 Laporan Booking</h1>
        <p>Laporan detail semua aktivitas booking</p>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="date_from">Tanggal Dari</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo $_GET['date_from'] ?? date('Y-m-01'); ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">Tanggal Sampai</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo $_GET['date_to'] ?? date('Y-m-t'); ?>">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="cancelled" <?php echo ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        <option value="completed" <?php echo ($_GET['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
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

    <!-- Report Summary -->
    <div class="report-summary">
        <?php
        $where_conditions = [];
        $params = [];
        $types = "";

        if (!empty($_GET['date_from'])) {
            $where_conditions[] = "DATE(b.created_at) >= ?";
            $params[] = $_GET['date_from'];
            $types .= "s";
        }

        if (!empty($_GET['date_to'])) {
            $where_conditions[] = "DATE(b.created_at) <= ?";
            $params[] = $_GET['date_to'];
            $types .= "s";
        }

        if (!empty($_GET['status'])) {
            $where_conditions[] = "b.booking_status = ?";
            $params[] = $_GET['status'];
            $types .= "s";
        }

        $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

        // Total bookings
        $total_query = "SELECT COUNT(*) as total FROM bookings b $where_clause";
        $total_stmt = mysqli_prepare($koneksi, $total_query);
        if (!empty($params)) {
            mysqli_stmt_bind_param($total_stmt, $types, ...$params);
        }
        mysqli_stmt_execute($total_stmt);
        $total_result = mysqli_stmt_get_result($total_stmt);
        $total = mysqli_fetch_assoc($total_result)['total'];

        // Total revenue
        $revenue_query = "SELECT SUM(total_amount) as revenue FROM bookings b $where_clause AND b.booking_status IN ('confirmed', 'completed')";
        $revenue_stmt = mysqli_prepare($koneksi, $revenue_query);
        if (!empty($params)) {
            mysqli_stmt_bind_param($revenue_stmt, $types, ...$params);
        }
        mysqli_stmt_execute($revenue_stmt);
        $revenue_result = mysqli_stmt_get_result($revenue_stmt);
        $revenue = mysqli_fetch_assoc($revenue_result)['revenue'] ?? 0;

        // Confirmed bookings
        $confirmed_query = "SELECT COUNT(*) as confirmed FROM bookings b $where_clause AND b.booking_status = 'confirmed'";
        $confirmed_stmt = mysqli_prepare($koneksi, $confirmed_query);
        if (!empty($params)) {
            mysqli_stmt_bind_param($confirmed_stmt, $types, ...$params);
        }
        mysqli_stmt_execute($confirmed_stmt);
        $confirmed_result = mysqli_stmt_get_result($confirmed_stmt);
        $confirmed = mysqli_fetch_assoc($confirmed_result)['confirmed'];

        // Cancelled bookings
        $cancelled_query = "SELECT COUNT(*) as cancelled FROM bookings b $where_clause AND b.booking_status = 'cancelled'";
        $cancelled_stmt = mysqli_prepare($koneksi, $cancelled_query);
        if (!empty($params)) {
            mysqli_stmt_bind_param($cancelled_stmt, $types, ...$params);
        }
        mysqli_stmt_execute($cancelled_stmt);
        $cancelled_result = mysqli_stmt_get_result($cancelled_stmt);
        $cancelled = mysqli_fetch_assoc($cancelled_result)['cancelled'];
        ?>

        <div class="summary-card">
            <div class="summary-number"><?php echo $total; ?></div>
            <div class="summary-label">Total Booking</div>
        </div>
        <div class="summary-card">
            <div class="summary-number"><?php echo format_currency($revenue); ?></div>
            <div class="summary-label">Total Pendapatan</div>
        </div>
        <div class="summary-card">
            <div class="summary-number"><?php echo $confirmed; ?></div>
            <div class="summary-label">Booking Dikonfirmasi</div>
        </div>
        <div class="summary-card">
            <div class="summary-number"><?php echo $cancelled; ?></div>
            <div class="summary-label">Booking Dibatalkan</div>
        </div>
    </div>

    <!-- Report Table -->
    <div class="report-table">
        <div class="table-header">
            📋 Detail Booking
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Kode Booking</th>
                        <th>Nama Penumpang</th>
                        <th>Rute</th>
                        <th>Status</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT b.*, u.full_name as user_name, r.origin, r.destination, s.departure_date
                             FROM bookings b
                             LEFT JOIN users u ON b.user_id = u.id
                             LEFT JOIN schedules s ON b.schedule_id = s.id
                             LEFT JOIN routes r ON s.route_id = r.id
                             $where_clause
                             ORDER BY b.created_at DESC";

                    $stmt = mysqli_prepare($koneksi, $query);
                    if (!empty($params)) {
                        mysqli_stmt_bind_param($stmt, $types, ...$params);
                    }
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($booking = mysqli_fetch_assoc($result)) {
                            $status_class = 'status-' . $booking['booking_status'];
                            ?>
                            <tr>
                                <td><?php echo format_date($booking['created_at']); ?></td>
                                <td><?php echo htmlspecialchars($booking['booking_code']); ?></td>
                                <td><?php echo htmlspecialchars($booking['passenger_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($booking['booking_status']); ?></span></td>
                                <td><?php echo format_currency($booking['total_amount']); ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <p style="color: #666; margin: 0;">Tidak ada data booking untuk periode yang dipilih</p>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer-admin.php'; ?>
