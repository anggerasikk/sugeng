<?php
require_once '../config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu untuk melihat riwayat check-in.');
    redirect('../beranda/signin.php');
}

include '../header.php';

// Get user's check-in history
$query = "SELECT b.*, s.departure_date, s.departure_time, s.arrival_time,
         r.origin, r.destination, bt.name as bus_type,
         TIMESTAMPDIFF(HOUR, NOW(), CONCAT(s.departure_date, ' ', s.departure_time)) as hours_until_departure
         FROM bookings b
         JOIN schedules s ON b.schedule_id = s.id
         JOIN routes r ON s.route_id = r.id
         JOIN bus_types bt ON s.bus_type_id = bt.id
         WHERE b.user_id = ? AND b.booking_status = 'checked_in'
         ORDER BY b.checked_in_at DESC";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<div style="max-width: 1000px; margin: 80px auto; padding: 0 20px;">
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 40px rgba(0,0,0,0.1);">
        <!-- Header -->
        <div style="text-align: center; margin-bottom: 30px;">
            <div style="background: #F5F1DC; width: 80px; height: 80px; line-height: 80px; border-radius: 50%; margin: 0 auto 20px; font-size: 30px;">
                📋
            </div>
            <h2 style="color: #001BB7; margin-bottom: 10px;">Riwayat Check-in Online</h2>
            <p style="color: #666;">Daftar semua check-in yang telah Anda lakukan</p>
        </div>

        <?php if (mysqli_num_rows($result) > 0): ?>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                    <thead>
                        <tr style="background: #001BB7; color: white;">
                            <th style="padding: 15px; text-align: left; border-radius: 10px 0 0 0;">Kode Booking</th>
                            <th style="padding: 15px; text-align: left;">Rute</th>
                            <th style="padding: 15px; text-align: left;">Tanggal & Waktu</th>
                            <th style="padding: 15px; text-align: left;">Tipe Bus</th>
                            <th style="padding: 15px; text-align: left;">Nomor Kursi</th>
                            <th style="padding: 15px; text-align: left; border-radius: 0 10px 0 0;">Waktu Check-in</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($booking = mysqli_fetch_assoc($result)): ?>
                            <tr style="border-bottom: 1px solid #eee;">
                                <td style="padding: 15px; font-weight: bold; color: #FF8040;">
                                    <?php echo htmlspecialchars($booking['booking_code']); ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php echo date('d M Y', strtotime($booking['departure_date'])); ?><br>
                                    <small style="color: #666;"><?php echo htmlspecialchars($booking['departure_time']); ?></small>
                                </td>
                                <td style="padding: 15px;">
                                    <?php echo htmlspecialchars($booking['bus_type']); ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php if (!empty($booking['seat_numbers'])): ?>
                                        <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                            <?php foreach (explode(',', $booking['seat_numbers']) as $seat): ?>
                                                <span style="background: #28a745; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                                    <?php echo htmlspecialchars(trim($seat)); ?>
                                                </span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <span style="color: #666;">-</span>
                                    <?php endif; ?>
                                </td>
                                <td style="padding: 15px;">
                                    <?php echo $booking['checked_in_at'] ? date('d M Y H:i', strtotime($booking['checked_in_at'])) : '-'; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 60px 20px; background: #f8f9fa; border-radius: 10px; margin-top: 20px;">
                <div style="font-size: 48px; margin-bottom: 20px;">🎫</div>
                <h3 style="color: #001BB7; margin-bottom: 10px;">Belum Ada Riwayat Check-in</h3>
                <p style="color: #666; margin-bottom: 30px;">Anda belum melakukan check-in online sebelumnya.</p>
                <a href="index.php" style="background: #FF8040; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                    Lakukan Check-in Sekarang
                </a>
            </div>
        <?php endif; ?>

        <!-- Action Buttons -->
        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" style="background: #001BB7; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-right: 10px;">
                Check-in Baru
            </a>
            <a href="../user/history.php" style="background: #6c757d; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold; margin-right: 10px;">
                Riwayat Booking
            </a>
            <a href="../index.php" style="background: #FF8040; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: bold;">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
