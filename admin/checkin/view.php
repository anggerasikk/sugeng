<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Check if admin is logged in
if (!is_admin_logged_in()) {
    redirect('../../auth/login.php');
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    set_flash('error', 'ID booking tidak ditemukan.');
    redirect('index.php');
}

$booking_id = (int)$_GET['id'];

// Get booking details with check-in information
$query = "SELECT b.*, u.name as user_name, u.email, u.phone,
         s.departure_date, s.departure_time, s.arrival_time,
         r.origin, r.destination, bt.name as bus_type, bt.capacity
         FROM bookings b
         JOIN users u ON b.user_id = u.id
         JOIN schedules s ON b.schedule_id = s.id
         JOIN routes r ON s.route_id = r.id
         JOIN bus_types bt ON s.bus_type_id = bt.id
         WHERE b.id = ? AND b.booking_status = 'checked_in'";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    set_flash('error', 'Data check-in tidak ditemukan.');
    redirect('index.php');
}

$booking = mysqli_fetch_assoc($result);

include '../header-admin.php';
include '../sidebar.php';
?>

<div class="main-content">
    <div class="content-header">
        <h1>Detail Check-in</h1>
        <p>Informasi lengkap check-in penumpang</p>
    </div>

    <div class="row">
        <!-- Check-in Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Check-in</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Kode Booking</label>
                            <span class="booking-code"><?php echo htmlspecialchars($booking['booking_code']); ?></span>
                        </div>

                        <div class="info-item">
                            <label>Status Check-in</label>
                            <span class="status-badge checked-in">Sudah Check-in</span>
                        </div>

                        <div class="info-item">
                            <label>Waktu Check-in</label>
                            <span><?php echo date('d/m/Y H:i:s', strtotime($booking['checked_in_at'])); ?></span>
                        </div>

                        <div class="info-item">
                            <label>Nomor Kursi</label>
                            <span class="seat-numbers"><?php echo htmlspecialchars($booking['seat_numbers']); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trip Details -->
            <div class="card">
                <div class="card-header">
                    <h3>Detail Perjalanan</h3>
                </div>
                <div class="card-body">
                    <div class="trip-details">
                        <div class="route-info">
                            <h4><?php echo htmlspecialchars($booking['origin']); ?> → <?php echo htmlspecialchars($booking['destination']); ?></h4>
                            <p class="bus-type"><?php echo htmlspecialchars($booking['bus_type']); ?> (Kapasitas: <?php echo $booking['capacity']; ?> penumpang)</p>
                        </div>

                        <div class="schedule-info">
                            <div class="schedule-item">
                                <label>Tanggal Keberangkatan</label>
                                <span><?php echo date('d F Y', strtotime($booking['departure_date'])); ?></span>
                            </div>

                            <div class="schedule-item">
                                <label>Waktu Keberangkatan</label>
                                <span><?php echo date('H:i', strtotime($booking['departure_time'])); ?></span>
                            </div>

                            <div class="schedule-item">
                                <label>Waktu Kedatangan</label>
                                <span><?php echo date('H:i', strtotime($booking['arrival_time'])); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Passenger Information -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Penumpang</h3>
                </div>
                <div class="card-body">
                    <div class="passenger-info">
                        <div class="passenger-avatar">
                            <span><?php echo strtoupper(substr($booking['user_name'], 0, 1)); ?></span>
                        </div>

                        <div class="passenger-details">
                            <h4><?php echo htmlspecialchars($booking['user_name']); ?></h4>
                            <p><i class="fas fa-envelope"></i> <?php echo htmlspecialchars($booking['email']); ?></p>
                            <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($booking['phone']); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="card">
                <div class="card-header">
                    <h3>Informasi Booking</h3>
                </div>
                <div class="card-body">
                    <div class="booking-info">
                        <div class="info-row">
                            <span>Status Pembayaran</span>
                            <span class="status-badge <?php echo $booking['payment_status'] === 'paid' ? 'paid' : 'pending'; ?>">
                                <?php echo $booking['payment_status'] === 'paid' ? 'Lunas' : 'Pending'; ?>
                            </span>
                        </div>

                        <div class="info-row">
                            <span>Total Pembayaran</span>
                            <span class="price">Rp <?php echo number_format($booking['total_amount'], 0, ',', '.'); ?></span>
                        </div>

                        <div class="info-row">
                            <span>Jumlah Penumpang</span>
                            <span><?php echo $booking['passenger_count']; ?> orang</span>
                        </div>

                        <div class="info-row">
                            <span>Waktu Booking</span>
                            <span><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-header">
                    <h3>Aksi</h3>
                </div>
                <div class="card-body">
                    <div class="action-buttons">
                        <a href="index.php" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
                        </a>

                        <button onclick="printCheckin()" class="btn btn-primary btn-block">
                            <i class="fas fa-print"></i> Cetak Konfirmasi
                        </button>

                        <button onclick="sendNotification()" class="btn btn-info btn-block">
                            <i class="fas fa-envelope"></i> Kirim Notifikasi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.info-item label {
    font-weight: 600;
    color: #666;
    font-size: 0.9rem;
}

.info-item span {
    font-size: 1rem;
    color: #333;
}

.booking-code {
    font-size: 1.2rem !important;
    font-weight: bold !important;
    color: #001BB7 !important;
}

.seat-numbers {
    font-size: 1.1rem !important;
    font-weight: bold !important;
    color: #28a745 !important;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.checked-in {
    background: #28a745;
    color: white;
}

.status-badge.paid {
    background: #28a745;
    color: white;
}

.status-badge.pending {
    background: #ffc107;
    color: #212529;
}

.trip-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.route-info h4 {
    margin: 0 0 5px 0;
    color: #001BB7;
}

.bus-type {
    color: #666;
    margin: 0;
}

.schedule-info {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 15px;
}

.schedule-item {
    text-align: center;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
}

.schedule-item label {
    display: block;
    font-size: 0.8rem;
    color: #666;
    margin-bottom: 5px;
}

.schedule-item span {
    font-size: 1.1rem;
    font-weight: 600;
    color: #001BB7;
}

.passenger-info {
    text-align: center;
}

.passenger-avatar {
    width: 60px;
    height: 60px;
    background: #001BB7;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
    margin: 0 auto 15px;
}

.passenger-details h4 {
    margin: 0 0 10px 0;
    color: #001BB7;
}

.passenger-details p {
    margin: 5px 0;
    color: #666;
}

.booking-info {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.info-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #eee;
}

.info-row:last-child {
    border-bottom: none;
}

.price {
    font-weight: bold;
    color: #28a745;
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-block {
    width: 100%;
}

@media (max-width: 768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }

    .schedule-info {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function printCheckin() {
    window.print();
}

function sendNotification() {
    if (confirm('Kirim notifikasi konfirmasi check-in ke penumpang?')) {
        // Here you would implement the notification sending logic
        alert('Notifikasi berhasil dikirim!');
    }
}
</script>

<?php include '../footer-admin.php'; ?>
