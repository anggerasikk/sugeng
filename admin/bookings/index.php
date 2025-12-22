<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

include '../header-admin.php';
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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
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

    .bookings-table {
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

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    .btn-edit {
        background: <?php echo $accent_orange; ?>;
        color: white;
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .page-link {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        color: <?php echo $primary_blue; ?>;
        text-decoration: none;
        border-radius: 3px;
    }

    .page-link.active {
        background: <?php echo $primary_blue; ?>;
        color: white;
        border-color: <?php echo $primary_blue; ?>;
    }

    .page-link:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    @media (max-width: 768px) {
        .admin-content {
            margin-left: 0;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="admin-content">
    <div class="admin-header">
        <h1>📋 Manajemen Booking</h1>
        <p>Kelola semua booking tiket bus</p>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $total_query = "SELECT COUNT(*) as total FROM bookings";
                $total_result = mysqli_query($koneksi, $total_query);
                $total = mysqli_fetch_assoc($total_result)['total'];
                echo $total;
                ?>
            </div>
            <div class="stat-label">Total Booking</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $confirmed_query = "SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'confirmed'";
                $confirmed_result = mysqli_query($koneksi, $confirmed_query);
                $confirmed = mysqli_fetch_assoc($confirmed_result)['total'];
                echo $confirmed;
                ?>
            </div>
            <div class="stat-label">Booking Dikonfirmasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $pending_query = "SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'pending'";
                $pending_result = mysqli_query($koneksi, $pending_query);
                $pending = mysqli_fetch_assoc($pending_result)['total'];
                echo $pending;
                ?>
            </div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $today_query = "SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at) = CURDATE()";
                $today_result = mysqli_query($koneksi, $today_query);
                $today = mysqli_fetch_assoc($today_result)['total'];
                echo $today;
                ?>
            </div>
            <div class="stat-label">Booking Hari Ini</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="search">Cari Booking Code / Nama</label>
                    <input type="text" id="search" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="Masukkan kode booking atau nama...">
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
                    <label for="date_from">Tanggal Dari</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">Tanggal Sampai</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bookings-table">
        <div class="table-header">
            📋 Daftar Booking
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Penumpang</th>
                        <th>Rute</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Build query with filters
                    $where_conditions = [];
                    $params = [];
                    $types = "";

                    if (!empty($_GET['search'])) {
                        $search = "%" . mysqli_real_escape_string($koneksi, $_GET['search']) . "%";
                        $where_conditions[] = "(b.booking_code LIKE ? OR u.full_name LIKE ?)";
                        $params[] = $search;
                        $params[] = $search;
                        $types .= "ss";
                    }

                    if (!empty($_GET['status'])) {
                        $where_conditions[] = "b.booking_status = ?";
                        $params[] = $_GET['status'];
                        $types .= "s";
                    }

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

                    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

                    // Pagination
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $per_page = 10;
                    $offset = ($page - 1) * $per_page;

                    // Count total records
                    $count_query = "SELECT COUNT(*) as total FROM bookings b
                                   LEFT JOIN users u ON b.user_id = u.id
                                   LEFT JOIN schedules s ON b.schedule_id = s.id
                                   LEFT JOIN routes r ON s.route_id = r.id
                                   $where_clause";
                    $count_stmt = mysqli_prepare($koneksi, $count_query);
                    if (!empty($params)) {
                        mysqli_stmt_bind_param($count_stmt, $types, ...$params);
                    }
                    mysqli_stmt_execute($count_stmt);
                    $count_result = mysqli_stmt_get_result($count_stmt);
                    $total_records = mysqli_fetch_assoc($count_result)['total'];
                    $total_pages = ceil($total_records / $per_page);

                    // Main query
                    $query = "SELECT b.*, u.full_name as user_name, r.origin, r.destination, s.departure_time, s.departure_date, b.booking_status as status
                             FROM bookings b
                             LEFT JOIN users u ON b.user_id = u.id
                             LEFT JOIN schedules s ON b.schedule_id = s.id
                             LEFT JOIN routes r ON s.route_id = r.id
                             $where_clause
                             ORDER BY b.created_at DESC
                             LIMIT ? OFFSET ?";

                    $stmt = mysqli_prepare($koneksi, $query);
                    $params[] = $per_page;
                    $params[] = $offset;
                    $types .= "ii";
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($booking = mysqli_fetch_assoc($result)) {
                            $status_class = 'status-' . $booking['status'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['booking_code']); ?></td>
                                <td><?php echo htmlspecialchars($booking['passenger_name']); ?></td>
                                <td><?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?></td>
                                <td><?php echo format_date($booking['departure_date']); ?><br>
                                    <small><?php echo format_time($booking['departure_time']); ?></small></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                <td><?php echo format_currency($booking['total_amount']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="detail.php?id=<?php echo $booking['id']; ?>" class="btn-action btn-view">👁️ Lihat</a>
                                        <?php if ($booking['status'] === 'pending') { ?>
                                            <a href="cancel-admin.php?id=<?php echo $booking['id']; ?>" class="btn-action btn-cancel" onclick="return confirm('Yakin ingin membatalkan booking ini?')">❌ Batal</a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 40px;">
                                <p style="color: #666; margin: 0;">Tidak ada data booking ditemukan</p>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1) { ?>
            <div class="pagination">
                <?php
                $query_string = $_GET;
                unset($query_string['page']);

                for ($i = 1; $i <= $total_pages; $i++) {
                    $query_string['page'] = $i;
                    $url = '?' . http_build_query($query_string);
                    $active_class = $i === $page ? 'active' : '';
                    echo "<a href='$url' class='page-link $active_class'>$i</a>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<script>
// Auto-submit form on filter change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>

<?php include '../footer-admin.php'; ?>