<?php
require_once '../config.php';
include '../header.php';

$code = $_GET['code'] ?? '';
$query = mysqli_query($koneksi, "SELECT * FROM bookings WHERE booking_code = '$code'");
$b = mysqli_fetch_assoc($query);

if (!$b) redirect('index.php');
?>

<div style="max-width: 600px; margin: 50px auto; background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    <div style="background: #001BB7; color: white; padding: 30px; text-align: center;">
        <h2 style="margin: 0;">Pembayaran Sandbox</h2>
        <p style="margin: 10px 0 0; opacity: 0.8;">Total Tagihan: <span style="font-size: 24px; font-weight: bold; color: #FF8040;"><?php echo format_currency($b['total_price']); ?></span></p>
    </div>
    
    <div style="padding: 40px; text-align: center;">
        <p style="color: #666; margin-bottom: 30px;">Silakan pilih metode pembayaran simulasi di bawah ini untuk mengonfirmasi pesanan Anda.</p>
        
        <div style="display: grid; gap: 15px;">
            <a href="success.php?code=<?php echo htmlspecialchars($code); ?>&method=VA" style="display: block; padding: 20px; border: 2px solid #001BB7; border-radius: 10px; text-decoration: none; color: #001BB7; font-weight: bold;">🏦 Virtual Account (Simulasi)</a>
            <a href="success.php?code=<?php echo htmlspecialchars($code); ?>&method=EWALLET" style="display: block; padding: 20px; border: 2px solid #001BB7; border-radius: 10px; text-decoration: none; color: #001BB7; font-weight: bold;">📱 E-Wallet (Simulasi)</a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>