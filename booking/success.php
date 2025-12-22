<?php
require_once '../config.php';
require_once '../includes/functions.php';
include '../header.php';

$code = $_GET['code'] ?? '';

if (!$code) {
    header("Location: index.php");
    exit;
}

// Get booking details
$booking = get_booking_by_code($code);
if (!$booking) {
    set_flash('error', 'Booking tidak ditemukan.');
    header("Location: index.php");
    exit;
}

// Get schedule details
$schedule = get_schedule_by_id($booking['schedule_id']);
?>

<div style="max-width: 800px; margin: 40px auto; padding: 0 20px;">
    <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="font-size: 60px; color: #001BB7;">🎫</div>
            <h1 style="color: #001BB7; margin-bottom: 10px;">Booking Berhasil Dibuat!</h1>
            <p style="color: #666; font-size: 16px;">Kode Booking: <strong style="color: #FF8040; font-size: 18px;"><?php echo htmlspecialchars($code); ?></strong></p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px;">
            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h3 style="color: #001BB7; margin-bottom: 15px;">Detail Penumpang</h3>
                <p><strong>Nama:</strong> <?php echo htmlspecialchars($booking['passenger_name']); ?></p>
                <p><strong>Telepon:</strong> <?php echo htmlspecialchars($booking['passenger_phone']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['passenger_email'] ?: 'Tidak ada'); ?></p>
                <p><strong>Identitas:</strong> <?php echo htmlspecialchars($booking['passenger_identity']); ?></p>
                <p><strong>Jumlah Kursi:</strong> <?php echo $booking['num_passengers']; ?></p>
            </div>

            <div style="background: #f8f9fa; padding: 20px; border-radius: 8px;">
                <h3 style="color: #001BB7; margin-bottom: 15px;">Detail Perjalanan</h3>
                <p><strong>Rute:</strong> <?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?></p>
                <p><strong>Tanggal:</strong> <?php echo format_date($booking['travel_date']); ?></p>
                <p><strong>Bus:</strong> <?php echo htmlspecialchars($schedule['bus_type_name']); ?></p>
                <p><strong>Total Harga:</strong> <?php echo format_currency($booking['total_price']); ?></p>
            </div>
        </div>

        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <h4 style="color: #856404; margin-bottom: 10px;">Status Pembayaran: <strong><?php echo ucfirst($booking['payment_status']); ?></strong></h4>
            <p style="color: #856404; margin: 0;">Silakan lakukan pembayaran untuk menyelesaikan booking Anda.</p>
        </div>

        <div style="text-align: center;">
            <a href="payment.php?code=<?php echo urlencode($code); ?>" style="background: #FF8040; color: white; padding: 15px 30px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 16px; display: inline-block;">
                Lanjutkan ke Pembayaran
            </a>
            <a href="../user/profile.php" style="margin-left: 15px; color: #666; text-decoration: none;">Lihat di Profil</a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
