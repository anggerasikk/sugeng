<?php
require_once '../config.php';
include '../header.php';

if (!is_logged_in()) {
    header("Location: ../signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];
// Ambil Data User
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'"));
// Ambil Riwayat Booking
$bookings = mysqli_query($koneksi, "SELECT b.*, r.origin, r.destination FROM bookings b JOIN schedules s ON b.schedule_id = s.id JOIN routes r ON s.route_id = r.id WHERE b.user_id = '$user_id' ORDER BY b.created_at DESC");
?>

<div style="max-width: 1100px; margin: 40px auto; padding: 0 20px; min-height: 70vh;">
    <div style="display: flex; gap: 30px;">
        <div style="width: 250px;">
            <div style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="width: 80px; height: 80px; background: #001BB7; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-size: 30px;">
                        <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                    </div>
                    <h4 style="margin: 0; color: #333;"><?php echo $user['full_name']; ?></h4>
                    <p style="font-size: 12px; color: #777;"><?php echo $user['email']; ?></p>
                </div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 15px 0;">
                <a href="edit-profile.php" style="display: block; text-decoration: none; color: #333; padding: 10px 0; font-weight: bold;">👤 Edit Profil</a>
                <a href="change-password.php" style="display: block; text-decoration: none; color: #333; padding: 10px 0;">🔑 Ganti Password</a>
                <a href="<?php echo base_url('auth/logout.php'); ?>" style="display: block; text-decoration: none; color: #f44336; padding: 10px 0; margin-top: 20px;">🚪 Keluar</a>
            </div>
        </div>

        <div style="flex: 1;">
            <h2 style="color: #001BB7; margin-bottom: 20px;">Dashboard Penumpang</h2>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                <div style="background: #001BB7; color: white; padding: 20px; border-radius: 10px;">
                    <p style="margin: 0; opacity: 0.8;">Total Perjalanan</p>
                    <h2 style="margin: 5px 0 0;"><?php echo mysqli_num_rows($bookings); ?></h2>
                </div>
                <div style="background: #FF8040; color: white; padding: 20px; border-radius: 10px;">
                    <p style="margin: 0; opacity: 0.8;">Status Akun</p>
                    <h2 style="margin: 5px 0 0; text-transform: capitalize;"><?php echo $user['status']; ?></h2>
                </div>
            </div>

            <div style="background: white; border-radius: 10px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
                <h3 style="margin-bottom: 20px; color: #333;">Riwayat Reservasi Terakhir</h3>
                <table width="100%" style="border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; border-bottom: 2px solid #eee;">
                            <th style="padding: 10px 0;">Kode</th>
                            <th>Rute</th>
                            <th>Tgl Perjalanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($b = mysqli_fetch_assoc($bookings)): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px 0; font-weight: bold; color: #001BB7;"><?php echo $b['booking_code']; ?></td>
                            <td><?php echo $b['origin']; ?> - <?php echo $b['destination']; ?></td>
                            <td><?php echo format_date($b['travel_date']); ?></td>
                            <td>
                                <span style="padding: 4px 10px; border-radius: 20px; font-size: 11px; background: <?php echo $b['booking_status'] == 'active' ? '#e3f2fd' : '#ffebee'; ?>; color: <?php echo $b['booking_status'] == 'active' ? '#1976d2' : '#c62828'; ?>;">
                                    <?php echo strtoupper($b['booking_status']); ?>
                                </span>
                            </td>
                            <td><a href="../booking/detail.php?code=<?php echo $b['booking_code']; ?>" style="color: #FF8040; text-decoration: none; font-size: 13px; font-weight: bold;">Detail</a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include '../footer.php'; ?>