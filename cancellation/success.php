<?php
require_once '../config.php';
include '../header.php';

$message = $_GET['message'] ?? 'Pengajuan pembatalan berhasil dikirim.';
$booking_code = $_GET['code'] ?? '';
?>

<div style="max-width: 600px; margin: 50px auto; padding: 20px;">
    <div style="background: white; border-radius: 10px; border-top: 5px solid #28a745; padding: 40px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); text-align: center;">
        <div style="font-size: 60px; margin-bottom: 20px;">✅</div>
        <h2 style="color: #28a745; margin-bottom: 20px;">Pengajuan Berhasil!</h2>
        <p style="color: #666; font-size: 1.1rem; margin-bottom: 30px;"><?php echo htmlspecialchars($message); ?></p>
        
        <?php if ($booking_code): ?>
        <div style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 30px;">
            <p style="margin: 0; color: #333;"><strong>Kode Booking:</strong> <?php echo htmlspecialchars($booking_code); ?></p>
        </div>
        <?php endif; ?>

        <div style="margin-top: 30px;">
            <a href="index.php" style="background: #007bff; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: bold; margin-right: 10px;">Kembali</a>
            <a href="<?php echo base_url('user/profile.php'); ?>" style="background: #28a745; color: white; padding: 12px 30px; border-radius: 5px; text-decoration: none; font-weight: bold;">Lihat Profil</a>
        </div>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; color: #666; font-size: 0.9rem;">
            <p>📧 Admin akan memproses pengajuan Anda dalam 1-2 hari kerja.</p>
            <p>💰 Jika disetujui, dana akan dikembalikan 75% ke rekening Anda.</p>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>