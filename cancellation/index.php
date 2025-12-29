<?php
require_once '../config.php';
include '../header.php';
?>

<div style="max-width: 800px; margin: 50px auto; padding: 20px;">
    <div style="background: white; border-radius: 10px; border-top: 5px solid #FF8040; padding: 40px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
        <h2 style="color: #001BB7; margin-bottom: 10px;">Pengajuan Pembatalan</h2>
        <p style="color: #666; margin-bottom: 30px;">Sesuai kebijakan SRS, dana akan dikembalikan maksimal 75% setelah disetujui Admin.</p>
        
        <form action="submit.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">Kode Booking</label>
                <input type="text" name="booking_code" placeholder="SR-XXXXX" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;" required>
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold;">Alasan Pembatalan</label>
                <textarea name="reason" rows="4" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;" required></textarea>
            </div>
            <button type="submit" style="width: 100%; background: #FF8040; color: white; border: none; padding: 15px; border-radius: 5px; font-weight: bold; cursor: pointer;">Kirim Pengajuan</button>
        </form>
    </div>
</div>

<?php include '../footer.php'; ?>