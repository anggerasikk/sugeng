<?php
require_once '../config.php';
include '../header.php';
$user_id = $_SESSION['user_id'];
$res = mysqli_query($koneksi, "SELECT b.*, s.origin, s.destination, s.bus_type, s.departure_time, s.departure_date 
                               FROM bookings b 
                               JOIN schedules s ON b.schedule_id = s.id 
                               WHERE b.user_id = '$user_id' ORDER BY b.created_at DESC");
?>
<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <h2 style="color: #001BB7; margin-bottom: 30px;">Semua Riwayat Reservasi</h2>
    <?php while($row = mysqli_fetch_assoc($res)): ?>
    <div style="background: white; margin-bottom: 20px; padding: 20px; border-radius: 10px; display: flex; justify-content: space-between; border-left: 5px solid <?php echo $row['booking_status'] == 'cancelled' ? '#dc3545' : '#FF8040'; ?>; box-shadow: 0 2px 5px rgba(0,0,0,0.05); <?php echo $row['booking_status'] == 'cancelled' ? 'opacity: 0.7;' : ''; ?>">
        <div>
            <span style="color: #888; font-size: 12px;">KODE: <?php echo $row['booking_code']; ?></span>
            <h3 style="margin: 5px 0;"><?php echo $row['origin']; ?> ➔ <?php echo $row['destination']; ?></h3>
            <p style="margin: 0; color: #666;"><?php echo format_date($row['departure_date']); ?> | <?php echo format_time($row['departure_time']); ?></p>
            <!-- Status Badge -->
            <div style="margin-top: 8px;">
                <?php
                $status_labels = [
                    'confirmed' => ['label' => 'Dikonfirmasi', 'color' => '#28a745'],
                    'checked_in' => ['label' => 'Check-in', 'color' => '#007bff'],
                    'cancelled' => ['label' => 'Dibatalkan', 'color' => '#dc3545'],
                    'completed' => ['label' => 'Selesai', 'color' => '#6c757d']
                ];
                $status = $status_labels[$row['booking_status']] ?? ['label' => ucfirst($row['booking_status']), 'color' => '#6c757d'];
                ?>
                <span style="background: <?php echo $status['color']; ?>; color: white; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: bold;">
                    <?php echo $status['label']; ?>
                </span>
            </div>
        </div>
        <div style="text-align: right;">
            <p style="font-weight: bold; color: <?php echo $row['booking_status'] == 'cancelled' ? '#dc3545' : '#001BB7'; ?>; margin-bottom: 10px;"><?php echo format_currency($row['total_amount']); ?></p>
            <a href="../booking/success.php?code=<?php echo $row['booking_code']; ?>" style="background: #F5F1DC; color: #001BB7; padding: 5px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold;">Detail</a>

            <?php
            // Check if booking is eligible for check-in
            $can_checkin = ($row['payment_status'] == 'paid' &&
                           $row['booking_status'] == 'confirmed' &&
                           $row['hours_until_departure'] <= 24 &&
                           $row['hours_until_departure'] >= 0);

            // Check if booking can be cancelled
            $can_cancel = (in_array($row['booking_status'], ['confirmed', 'checked_in']) &&
                          $row['payment_status'] == 'paid' &&
                          strtotime($row['departure_date'] . ' ' . $row['departure_time']) > time());
            ?>

            <?php if ($can_checkin): ?>
                <a href="../checkin/index.php" style="background: #28a745; color: white; padding: 5px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; margin-left: 10px;">Check-in</a>
            <?php endif; ?>

            <?php if ($can_cancel): ?>
                <a href="../cancellation/index.php" style="background: #dc3545; color: white; padding: 5px 15px; border-radius: 5px; text-decoration: none; font-size: 14px; font-weight: bold; margin-left: 10px;">Batalkan</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endwhile; ?>
</div>
<?php include '../footer.php'; ?>