<?php
require_once '../config.php';
include '../header.php';
$user_id = $_SESSION['user_id'];
$res = mysqli_query($koneksi, "SELECT b.*, s.origin, s.destination, s.bus_type, s.departure_time 
                               FROM bookings b 
                               JOIN schedules s ON b.schedule_id = s.id 
                               WHERE b.user_id = '$user_id' ORDER BY b.created_at DESC");
?>
<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <h2 style="color: #001BB7; margin-bottom: 30px;">Semua Riwayat Reservasi</h2>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
    <div style="background: white; margin-bottom: 20px; padding: 20px; border-radius: 10px; display: flex; justify-content: space-between; border-left: 5px solid #FF8040; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <div>
            <span style="color: #888; font-size: 12px;">KODE: <?php echo $row['booking_code']; ?></span>
            <h3 style="margin: 5px 0;"><?php echo $row['origin']; ?> ➔ <?php echo $row['destination']; ?></h3>
            <p style="margin: 0; color: #666;"><?php echo format_date($row['travel_date']); ?> | <?php echo format_time($row['departure_time']); ?></p>
        </div>
        <div style="text-align: right;">
            <p style="font-weight: bold; color: #001BB7; margin-bottom: 10px;"><?php echo format_currency($row['total_price']); ?></p>
            <a href="../booking/detail.php?code=<?php echo $row['booking_code']; ?>" style="background: #F5F1DC; color: #001BB7; padding: 5px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold;">E-Tiket</a>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php include '../footer.php'; ?>