<?php
require_once '../config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu untuk melihat konfirmasi check-in.');
    redirect('../beranda/signin.php');
}

// Check if checkin success data exists
if (!isset($_SESSION['checkin_success'])) {
    set_flash('error', 'Data check-in tidak ditemukan.');
    redirect('index.php');
}

$checkin_data = $_SESSION['checkin_success'];
unset($_SESSION['checkin_success']); // Clear the session data

include '../header.php';
?>

<div style="max-width: 800px; margin: 80px auto; text-align: center; padding: 0 20px;">
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <!-- Success Icon -->
        <div style="background: #28a745; width: 80px; height: 80px; line-height: 80px; border-radius: 50%; margin: 0 auto 20px; font-size: 30px; color: white;">
            ✓
        </div>

        <h2 style="color: #001BB7; margin-bottom: 10px;">Check-in Berhasil!</h2>
        <p style="color: #666; margin-bottom: 30px;">Selamat! Anda telah berhasil melakukan check-in online.</p>

        <!-- Booking Code -->
        <div style="background: #F5F1DC; padding: 20px; border-radius: 10px; margin-bottom: 20px;">
            <h3 style="color: #001BB7; margin-bottom: 10px;">Kode Booking</h3>
            <div style="font-size: 24px; font-weight: bold; color: #FF8040;"><?php echo htmlspecialchars($checkin_data['booking_code']); ?></div>
        </div>

        <!-- Trip Details -->
        <div style="background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 20px; text-align: left;">
            <h3 style="color: #001BB7; margin-bottom: 15px; text-align: center;">Detail Perjalanan</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div>
                    <strong>Rute:</strong><br>
                    <?php echo htmlspecialchars($checkin_data['route']); ?>
                </div>
                <div>
                    <strong>Tanggal Keberangkatan:</strong><br>
                    <?php echo date('d M Y', strtotime($checkin_data['departure_date'])); ?>
                </div>
                <div>
                    <strong>Waktu Keberangkatan:</strong><br>
                    <?php echo htmlspecialchars($checkin_data['departure_time']); ?>
                </div>
                <div>
                    <strong>Tipe Bus:</strong><br>
                    <?php echo htmlspecialchars($checkin_data['bus_type']); ?>
                </div>
            </div>
        </div>

        <!-- Seat Numbers -->
        <div style="background: #e9ecef; padding: 20px; border-radius: 10px; margin-bottom: 30px;">
            <h3 style="color: #001BB7; margin-bottom: 15px;">Nomor Kursi Anda</h3>
            <div style="display: flex; flex-wrap: wrap; gap: 10px; justify-content: center;">
                <?php foreach ($checkin_data['seat_numbers'] as $seat): ?>
                    <div style="background: #28a745; color: white; padding: 10px 15px; border-radius: 5px; font-weight: bold;">
                        <?php echo htmlspecialchars($seat); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Instructions -->
        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 20px; border-radius: 10px; margin-bottom: 30px; text-align: left;">
            <h4 style="color: #856404; margin-bottom: 10px;">📋 Instruksi Penting:</h4>
            <ul style="color: #856404; margin: 0; padding-left: 20px;">
                <li>Tunjukkan kode booking dan nomor kursi ini saat naik bus</li>
                <li>Datang ke terminal minimal 30 menit sebelum keberangkatan</li>
                <li>Siapkan KTP/SIM untuk verifikasi identitas</li>
                <li>Email konfirmasi telah dikirim ke alamat email Anda</li>
            </ul>
        </div>

        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
            <a href="index.php" style="background: #6c757d; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Check-in Lagi
            </a>
            <a href="../user/history.php" style="background: #001BB7; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Riwayat Booking
            </a>
            <a href="../index.php" style="background: #FF8040; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
