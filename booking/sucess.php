<?php
require_once '../config.php';
include '../header.php';

$code = $_GET['code'] ?? '';
$method = $_GET['method'] ?? '';
// Update status menjadi PAID karena ini sandbox
mysqli_query($koneksi, "UPDATE bookings SET payment_status = 'paid' WHERE booking_code = '$code'");
?>

<div style="max-width: 600px; margin: 80px auto; text-align: center;">
    <div style="font-size: 80px; color: #4CAF50;">✅</div>
    <h1 style="color: #001BB7;">Pembayaran Berhasil!</h1>
    <p style="color: #666; font-size: 18px;">Kode Booking Anda: <strong style="color: #FF8040;"><?php echo htmlspecialchars($code); ?></strong></p>
    <p style="color: #666; font-size: 16px;">Metode Pembayaran: <strong><?php echo htmlspecialchars($method); ?></strong></p>
    <div style="margin-top: 30px;">
        <a href="../user/profile.php" style="background: #001BB7; color: white; padding: 12px 25px; border-radius: 5px; text-decoration: none; font-weight: bold;">Lihat E-Tiket</a>
        <a href="../index.php" style="margin-left: 10px; color: #666; text-decoration: none;">Kembali ke Beranda</a>
    </div>
</div>

<?php include '../footer.php'; ?>