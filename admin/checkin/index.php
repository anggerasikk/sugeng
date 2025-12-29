<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!is_admin_logged_in()) {
    redirect('../../auth/login.php');
}

include '../header-admin.php';
include '../sidebar.php';

// Get check-in statistics
$query = "SELECT
    COUNT(*) as total_checkins,
    COUNT(CASE WHEN DATE(checked_in_at) = CURDATE() THEN 1 END) as today_checkins,
    COUNT(CASE WHEN booking_status = 'checked_in' THEN 1 END) as active_checkins
    FROM bookings WHERE booking_status = 'checked_in'";

$result = mysqli_query($koneksi, $query);
$stats = mysqli_fetch_assoc($result);

// Get recent check-ins
$recent_query = "SELECT b.*, u.name as user_name, s.departure_date, s.departure_time,
                r.origin, r.destination
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN schedules s ON b.schedule_id = s.id
                JOIN routes r ON s.route_id = r.id
                WHERE b.booking_status = 'checked_in'
                ORDER BY b.checked_in_at DESC LIMIT 10";

$recent_result = mysqli_query($koneksi, $recent_query);
?>

<div class="main-content">
    <div class="content-header">
        <h1>Check-in Management</h1>
        <p>Kelola dan pantau aktivitas check-in online penumpang</p>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">🎫</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['total_checkins']); ?></h3>
                <p>Total Check-in</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">📅</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['today_checkins']); ?></h3>
                <p>Check-in Hari Ini</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">✅</div>
            <div class="stat-content">
                <h3><?php echo number_format($stats['active_checkins']); ?></h3>
                <p>Check-in Aktif</p>
            </div>
        </div>
    </div>

    <!-- Recent Check-ins Table -->
    <div class="card">
        <div class="card-header">
            <h3>Check-in Terbaru</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Kode Booking</th>
                            <th>Penumpang</th>
                            <th>Rute</th>
                            <th>Tanggal Keberangkatan</th>
                            <th>Waktu Check-in</th>
                            <th>Nomor Kursi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($checkin = mysqli_fetch_assoc($recent_result)): ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($checkin['booking_code']); ?></strong></td>
                            <td><?php echo htmlspecialchars($checkin['user_name']); ?></td>
                            <td><?php echo htmlspecialchars($checkin['origin'] . ' → ' . $checkin['destination']); ?></td>
                            <td><?php echo date('d/m/Y', strtotime($checkin['departure_date'])); ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($checkin['checked_in_at'])); ?></td>
                            <td><?php echo htmlspecialchars($checkin['seat_numbers']); ?></td>
                            <td>
                                <a href="view.php?id=<?php echo $checkin['id']; ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Check-in Search -->
    <div class="card">
        <div class="card-header">
            <h3>Cari Check-in</h3>
        </div>
        <div class="card-body">
            <form method="GET" class="search-form">
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="booking_code">Kode Booking</label>
                        <input type="text" class="form-control" id="booking_code" name="booking_code"
                               value="<?php echo isset($_GET['booking_code']) ? htmlspecialchars($_GET['booking_code']) : ''; ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="date_from">Dari Tanggal</label>
                        <input type="date" class="form-control" id="date_from" name="date_from"
                               value="<?php echo isset($_GET['date_from']) ? htmlspecialchars($_GET['date_from']) : ''; ?>">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="date_to">Sampai Tanggal</label>
                        <input type="date" class="form-control" id="date_to" name="date_to"
                               value="<?php echo isset($_GET['date_to']) ? htmlspecialchars($_GET['date_to']) : ''; ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block">Cari</button>
                    </div>
                </div>
            </form>

            <?php if (isset($_GET['booking_code']) || isset($_GET['date_from'])): ?>
            <div class="search-results">
                <?php
                $search_query = "SELECT b.*, u.name as user_name, s.departure_date, s.departure_time,
                               r.origin, r.destination
                               FROM bookings b
                               JOIN users u ON b.user_id = u.id
                               JOIN schedules s ON b.schedule_id = s.id
                               JOIN routes r ON s.route_id = r.id
                               WHERE b.booking_status = 'checked_in'";

                $params = [];
                $types = "";

                if (!empty($_GET['booking_code'])) {
                    $search_query .= " AND b.booking_code LIKE ?";
                    $params[] = "%" . $_GET['booking_code'] . "%";
                    $types .= "s";
                }

                if (!empty($_GET['date_from'])) {
                    $search_query .= " AND DATE(b.checked_in_at) >= ?";
                    $params[] = $_GET['date_from'];
                    $types .= "s";
                }

                if (!empty($_GET['date_to'])) {
                    $search_query .= " AND DATE(b.checked_in_at) <= ?";
                    $params[] = $_GET['date_to'];
                    $types .= "s";
                }

                $search_query .= " ORDER BY b.checked_in_at DESC";

                $stmt = mysqli_prepare($koneksi, $search_query);
                if (!empty($params)) {
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                }
                mysqli_stmt_execute($stmt);
                $search_result = mysqli_stmt_get_result($stmt);
                ?>

                <h4>Hasil Pencarian (<?php echo mysqli_num_rows($search_result); ?> hasil)</h4>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Kode Booking</th>
                                <th>Penumpang</th>
                                <th>Rute</th>
                                <th>Tanggal Check-in</th>
                                <th>Nomor Kursi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($result = mysqli_fetch_assoc($search_result)): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($result['booking_code']); ?></strong></td>
                                <td><?php echo htmlspecialchars($result['user_name']); ?></td>
                                <td><?php echo htmlspecialchars($result['origin'] . ' → ' . $result['destination']); ?></td>
                                <td><?php echo date('d/m/Y H:i', strtotime($result['checked_in_at'])); ?></td>
                                <td><?php echo htmlspecialchars($result['seat_numbers']); ?></td>
                                <td>
                                    <a href="view.php?id=<?php echo $result['id']; ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 20px;
}

.stat-icon {
    font-size: 2.5rem;
    opacity: 0.8;
}

.stat-content h3 {
    margin: 0;
    font-size: 2rem;
    color: #001BB7;
}

.stat-content p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 0.9rem;
}

.search-form {
    margin-bottom: 20px;
}

.search-results {
    margin-top: 20px;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
}

.btn-sm {
    padding: 5px 10px;
    font-size: 0.8rem;
}
</style>

<?php include '../footer-admin.php'; ?>
