<?php 
require_once '../config.php';
include '../header.php'; 
?>

<div style="max-width: 600px; margin: 80px auto; text-align: center; padding: 0 20px;">
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <div style="background: #F5F1DC; width: 80px; height: 80px; line-height: 80px; border-radius: 50%; margin: 0 auto 20px; font-size: 30px;">
            🎫
        </div>
        <h2 style="color: #001BB7; margin-bottom: 10px;">Self Check-in</h2>
        <p style="color: #666; margin-bottom: 30px;">Masukkan Kode Booking Anda untuk melakukan check-in dan mendapatkan nomor kursi.</p>

        <form action="verify.php" method="POST">
            <input type="text" name="booking_code" placeholder="Contoh: SR-12345ABC" 
                   style="width: 100%; padding: 15px; border: 2px solid #001BB7; border-radius: 10px; font-size: 18px; text-align: center; margin-bottom: 20px; text-transform: uppercase;" required>
            
            <button type="submit" style="width: 100%; background: #FF8040; color: white; border: none; padding: 15px; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer;">
                Verifikasi Tiket
            </button>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>