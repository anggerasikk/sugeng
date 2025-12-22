<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

include '../header-admin.php';

// Get schedule ID
$schedule_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$schedule_id) {
    set_flash('error', 'ID jadwal tidak valid.');
    redirect(base_url('admin/schedules/index.php'));
}

// Get schedule details
$schedule_query = "SELECT s.*, r.origin, r.destination, bt.name as bus_type_name
                  FROM schedules s
                  LEFT JOIN routes r ON s.route_id = r.id
                  LEFT JOIN bus_types bt ON s.bus_type_id = bt.id
                  WHERE s.id = ?";
$schedule_stmt = mysqli_prepare($koneksi, $schedule_query);
mysqli_stmt_bind_param($schedule_stmt, "i", $schedule_id);
mysqli_stmt_execute($schedule_stmt);
$schedule_result = mysqli_stmt_get_result($schedule_stmt);
$schedule = mysqli_fetch_assoc($schedule_result);

if (!$schedule) {
    set_flash('error', 'Jadwal tidak ditemukan.');
    redirect(base_url('admin/schedules/index.php'));
}

// Get bookings for this schedule
$bookings_query = "SELECT b.*, u.full_name as user_name, b.booking_status as status
                  FROM bookings b
                  LEFT JOIN users u ON b.user_id = u.id
                  WHERE b.schedule_id = ? AND b.booking_status IN ('confirmed', 'checked_in')
                  ORDER BY b.created_at ASC";
$bookings_stmt = mysqli_prepare($koneksi, $bookings_query);
mysqli_stmt_bind_param($bookings_stmt, "i", $schedule_id);
mysqli_stmt_execute($bookings_stmt);
$bookings_result = mysqli_stmt_get_result($bookings_stmt);

// Calculate occupancy
$total_booked = 0;
while ($booking = mysqli_fetch_assoc($bookings_result)) {
    $total_booked += $booking['seats_booked'];
}
$available = $schedule['total_seats'] - $total_booked;

// Reset result pointer
mysqli_data_seek($bookings_result, 0);
?>

<style>
    .admin-content {
        padding: 20px;
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

    .schedule-info {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .info-item {
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        text-align: center;
    }

    .info-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 1.2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
    }

    .occupancy-table {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        background: <?php echo $primary_blue; ?>;
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 1.1rem;
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

    .status-badge {
        padding: 4px 8px;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-checked_in {
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

    .btn-back {
        background: #6c757d;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }

    .btn-back:hover {
        background: #5a6268;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="admin-content">
    <a href="index.php" class="btn-back">← Kembali ke Daftar Jadwal</a>

    <div class="admin-header">
        <h1>👥 Okupansi Kursi</h1>
        <p><?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?> - <?php echo format_date($schedule['departure_date']); ?> <?php echo format_time($schedule['departure_time']); ?></p>
    </div>

    <div class="schedule-info">
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Total Kursi</div>
                <div class="info-value"><?php echo $schedule['total_seats']; ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Kursi Terisi</div>
                <div class="info-value"><?php echo $total_booked; ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Kursi Tersedia</div>
                <div class="info-value"><?php echo $available; ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Tingkat Okupansi</div>
                <div class="info-value"><?php echo $schedule['total_seats'] > 0 ? round(($total_booked / $schedule['total_seats']) * 100, 1) : 0; ?>%</div>
            </div>
        </div>
    </div>

    <div class="occupancy-table">
        <div class="table-header">
            📋 Daftar Penumpang
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Penumpang</th>
                        <th>Jumlah Kursi</th>
                        <th>Status</th>
                        <th>Total Bayar</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($bookings_result) > 0) {
                        while ($booking = mysqli_fetch_assoc($bookings_result)) {
                            $status_class = 'status-' . str_replace('_', '-', $booking['status']);
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($booking['booking_code']); ?></td>
                                <td><?php echo htmlspecialchars($booking['passenger_name']); ?></td>
                                <td><?php echo $booking['seats_booked']; ?> kursi</td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst(str_replace('_', ' ', $booking['status'])); ?></span></td>
                                <td><?php echo format_currency($booking['total_amount']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <!-- <a href="../bookings/detail.php?id=<?php echo $booking['id']; ?>" class="btn-action btn-view">👁️ Lihat Detail</a> -->
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <div style="color: #666;">
                                    <p>🚫 Belum ada booking untuk jadwal ini</p>
                                </div>
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