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
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .admin-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .admin-header h1 {
        color: <?php echo $primary_blue; ?>;
        margin: 0;
    }

         .btn-add {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
        display: inline-block;
    }

    .btn-add:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
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

    .schedules-table {
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

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
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

    .btn-delete {
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
        .admin-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
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
        <h1>🚌 Manajemen Jadwal</h1>
        <a href="create.php" class="btn-add">➕ Tambah Jadwal</a>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $total_query = "SELECT COUNT(*) as total FROM schedules";
                $total_result = mysqli_query($koneksi, $total_query);
                $total = mysqli_fetch_assoc($total_result)['total'];
                echo $total;
                ?>
            </div>
            <div class="stat-label">Total Jadwal</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $active_query = "SELECT COUNT(*) as total FROM schedules WHERE status = 'active'";
                $active_result = mysqli_query($koneksi, $active_query);
                $active = mysqli_fetch_assoc($active_result)['total'];
                echo $active;
                ?>
            </div>
            <div class="stat-label">Jadwal Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $today_query = "SELECT COUNT(*) as total FROM schedules WHERE departure_date = CURDATE() AND status = 'active'";
                $today_result = mysqli_query($koneksi, $today_query);
                $today = mysqli_fetch_assoc($today_result)['total'];
                echo $today;
                ?>
            </div>
            <div class="stat-label">Beroperasi Hari Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $upcoming_query = "SELECT COUNT(*) as total FROM schedules WHERE departure_date > CURDATE() AND status = 'active'";
                $upcoming_result = mysqli_query($koneksi, $upcoming_query);
                $upcoming = mysqli_fetch_assoc($upcoming_result)['total'];
                echo $upcoming;
                ?>
            </div>
            <div class="stat-label">Jadwal Mendatang</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="search">Cari Rute</label>
                    <input type="text" id="search" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="Asal atau tujuan...">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Aktif</option>
                        <option value="cancelled" <?php echo ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Tidak Aktif</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date">Tanggal Operasional</label>
                    <input type="date" id="date" name="date" value="<?php echo $_GET['date'] ?? ''; ?>">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Schedules Table -->
    <div class="schedules-table">
        <div class="table-header">
            🚌 Daftar Jadwal Perjalanan
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Rute</th>
                        <th>Tanggal</th>
                        <th>Waktu</th>
                        <th>Bus</th>
                        <th>Harga</th>
                        <th>Kursi Tersedia</th>
                        <th>Status</th>
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
                        $where_conditions[] = "(origin LIKE ? OR destination LIKE ?)";
                        $params[] = $search;
                        $params[] = $search;
                        $types .= "ss";
                    }

                    if (!empty($_GET['status']) && in_array($_GET['status'], ['active', 'cancelled', 'completed'])) {
                        $where_conditions[] = "s.status = ?";
                        $params[] = $_GET['status'];
                        $types .= "s";
                    }

                    if (!empty($_GET['date'])) {
                        $where_conditions[] = "departure_date = ?";
                        $params[] = $_GET['date'];
                        $types .= "s";
                    }

                    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

                    // Pagination
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $per_page = 10;
                    $offset = ($page - 1) * $per_page;

                    // Count total records
                    $count_query = "SELECT COUNT(*) as total FROM schedules s LEFT JOIN routes r ON s.route_id = r.id LEFT JOIN bus_types bt ON s.bus_type_id = bt.id $where_clause";
                    if (!empty($params)) {
                        $count_stmt = mysqli_prepare($koneksi, $count_query);
                        mysqli_stmt_bind_param($count_stmt, $types, ...$params);
                        mysqli_stmt_execute($count_stmt);
                        $count_result = mysqli_stmt_get_result($count_stmt);
                        $total_records = mysqli_fetch_assoc($count_result)['total'];
                    } else {
                        $count_result = mysqli_query($koneksi, $count_query);
                        $total_records = mysqli_fetch_assoc($count_result)['total'];
                    }
                    $total_pages = ceil($total_records / $per_page);

                    // Main query
                    $query = "SELECT s.*, r.origin, r.destination, bt.name as bus_type_name, bt.capacity
                             FROM schedules s
                             LEFT JOIN routes r ON s.route_id = r.id
                             LEFT JOIN bus_types bt ON s.bus_type_id = bt.id
                             $where_clause
                             ORDER BY s.departure_date DESC, s.departure_time DESC
                             LIMIT ? OFFSET ?";

                    $stmt = mysqli_prepare($koneksi, $query);
                    $params[] = $per_page;
                    $params[] = $offset;
                    $types .= "ii";
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($schedule = mysqli_fetch_assoc($result)) {
                            // Calculate available seats
                            $booked_query = "SELECT COUNT(*) as booked FROM bookings WHERE schedule_id = ? AND booking_status IN ('confirmed', 'checked_in')";
                            $booked_stmt = mysqli_prepare($koneksi, $booked_query);
                            mysqli_stmt_bind_param($booked_stmt, "i", $schedule['id']);
                            mysqli_stmt_execute($booked_stmt);
                            $booked_result = mysqli_stmt_get_result($booked_stmt);
                            $booked = mysqli_fetch_assoc($booked_result)['booked'];
                            $available = $schedule['capacity'] - $booked;

                            $status_class = 'status-' . $schedule['status'];
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?></td>
                                <td><?php echo format_date($schedule['departure_date']); ?></td>
                                <td><?php echo format_time($schedule['departure_time']); ?> - <?php echo format_time($schedule['arrival_time']); ?></td>
                                <td><?php echo htmlspecialchars($schedule['bus_type_name']); ?></td>
                                <td><?php echo format_currency($schedule['price']); ?></td>
                                <td><?php echo $available; ?>/<?php echo $schedule['capacity']; ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($schedule['status']); ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $schedule['id']; ?>" class="btn-action btn-edit">✏️ Edit</a>
                                        <a href="occupancy.php?id=<?php echo $schedule['id']; ?>" class="btn-action btn-view">👥 Kursi</a>
                                        <a href="delete.php?id=<?php echo $schedule['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus jadwal ini?')">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <p style="color: #666; margin: 0;">Tidak ada data jadwal ditemukan</p>
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