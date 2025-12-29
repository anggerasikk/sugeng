<?php
require_once '../config.php';

$booking_code = $_GET['code'] ?? '';

if (empty($booking_code)) {
    redirect(base_url('user/profile.php'));
}

// Get booking details
$query = "SELECT b.*, s.origin, s.destination, s.departure_time, s.bus_type 
          FROM bookings b 
          JOIN schedules s ON b.schedule_id = s.id 
          WHERE b.booking_code = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $booking_code);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$booking = mysqli_fetch_assoc($result);

if (!$booking) {
    set_flash('error', 'Booking tidak ditemukan');
    redirect(base_url('user/profile.php'));
}

include '../header.php';
?>

<style>
    .waiting-container {
        max-width: 800px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .waiting-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 40px;
        text-align: center;
    }

    .status-icon {
        width: 100px;
        height: 100px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }

    .status-icon::before {
        content: '⏳';
        font-size: 50px;
    }

    .card-header h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .card-header p {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    .card-body {
        padding: 40px;
    }

    .booking-code-box {
        background: <?php echo $light_cream; ?>;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
        border: 2px dashed <?php echo $accent_orange; ?>;
        text-align: center;
    }

    .booking-code-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 10px;
    }

    .booking-code {
        font-size: 2rem;
        font-weight: 700;
        color: <?php echo $accent_orange; ?>;
        letter-spacing: 2px;
    }

    .info-section {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #ddd;
    }

    .info-item:last-child {
        border-bottom: none;
    }

    .info-label {
        color: #666;
    }

    .info-value {
        font-weight: 600;
        color: #333;
    }

    .status-info {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
    }

    .status-info h4 {
        color: #856404;
        margin-bottom: 15px;
    }

    .status-info ul {
        margin-left: 20px;
        color: #856404;
    }

    .status-info li {
        margin-bottom: 8px;
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .btn {
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .btn-primary {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    .btn-primary:hover {
        background: <?php echo $secondary_blue; ?>;
        transform: translateY(-2px);
    }

    .btn-secondary {
        background: white;
        color: <?php echo $primary_blue; ?>;
        border: 2px solid <?php echo $primary_blue; ?>;
    }

    .btn-secondary:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    @media (max-width: 768px) {
        .action-buttons {
            grid-template-columns: 1fr;
        }

        .card-header {
            padding: 30px 20px;
        }

        .card-body {
            padding: 30px 20px;
        }

        .booking-code {
            font-size: 1.5rem;
        }
    }
</style>

<div class="waiting-container">
    <div class="waiting-card">
        <div class="card-header">
            <div class="status-icon"></div>
            <h1>Menunggu Verifikasi</h1>
            <p>Pembayaran Anda sedang diverifikasi oleh admin</p>
        </div>

        <div class="card-body">
            <div class="booking-code-box">
                <div class="booking-code-label">Kode Booking Anda:</div>
                <div class="booking-code"><?php echo htmlspecialchars($booking['booking_code']); ?></div>
                <p style="margin-top: 15px; color: #666; font-size: 0.9rem;">
                    Simpan kode ini untuk tracking status booking
                </p>
            </div>

            <div class="status-info">
                <h4>📋 Status Pembayaran: PENDING</h4>
                <ul>
                    <li>Bukti pembayaran Anda sudah kami terima</li>
                    <li>Admin kami akan memverifikasi dalam <strong>max 1x24 jam</strong></li>
                    <li>Anda akan mendapat notifikasi via email setelah diverifikasi</li>
                    <li>E-Ticket akan tersedia di menu "Riwayat Reservasi"</li>
                    <li>Cek status booking Anda secara berkala</li>
                </ul>
            </div>

            <div class="info-section">
                <h4 style="color: <?php echo $primary_blue; ?>; margin-bottom: 15px;">Detail Pemesanan</h4>
                
                <div class="info-item">
                    <span class="info-label">Nama Penumpang:</span>
                    <span class="info-value"><?php echo htmlspecialchars($booking['passenger_name']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Rute:</span>
                    <span class="info-value"><?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Tanggal Keberangkatan:</span>
                    <span class="info-value"><?php echo format_date($booking['travel_date']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Waktu Keberangkatan:</span>
                    <span class="info-value"><?php echo format_time($booking['departure_time']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Kelas Bus:</span>
                    <span class="info-value"><?php echo ucfirst($booking['bus_type']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Kursi:</span>
                    <span class="info-value"><?php echo htmlspecialchars($booking['seat_numbers']); ?></span>
                </div>

                <div class="info-item">
                    <span class="info-label">Jumlah Penumpang:</span>
                    <span class="info-value"><?php echo $booking['num_passengers']; ?> orang</span>
                </div>

                <div class="info-item" style="border-top: 2px solid <?php echo $accent_orange; ?>; padding-top: 15px; margin-top: 10px;">
                    <span class="info-label" style="font-size: 1.1rem; font-weight: 600;">Total Dibayar:</span>
                    <span class="info-value" style="font-size: 1.2rem; color: <?php echo $accent_orange; ?>;"><?php echo format_currency($booking['total_price']); ?></span>
                </div>
            </div>

            <div class="action-buttons">
                <a href="../user/profile.php" class="btn btn-primary">
                    👤 Ke Profil Saya
                </a>
                <a href="../index.php" class="btn btn-secondary">
                    🏠 Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>