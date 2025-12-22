<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

// Get schedule ID from URL
$schedule_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$schedule_id) {
    set_flash('error', 'ID jadwal tidak valid.');
    redirect('index.php');
}

// Get schedule data
$query = "SELECT s.*, r.origin, r.destination, bt.name as bus_type_name
         FROM schedules s
         LEFT JOIN routes r ON s.route_id = r.id
         LEFT JOIN bus_types bt ON s.bus_type_id = bt.id
         WHERE s.id = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $schedule_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    set_flash('error', 'Jadwal tidak ditemukan.');
    redirect('index.php');
}

$schedule = mysqli_fetch_assoc($result);

// Check if schedule has bookings
$booking_check = "SELECT COUNT(*) as booking_count FROM bookings WHERE schedule_id = ? AND booking_status IN ('confirmed', 'checked_in', 'pending')";
$booking_stmt = mysqli_prepare($koneksi, $booking_check);
mysqli_stmt_bind_param($booking_stmt, "i", $schedule_id);
mysqli_stmt_execute($booking_stmt);
$booking_result = mysqli_stmt_get_result($booking_stmt);
$booking_count = mysqli_fetch_assoc($booking_result)['booking_count'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && verify_csrf_token($_POST['csrf_token'])) {
    if (isset($_POST['confirm_delete'])) {
        // Check again for bookings before deletion
        $final_check = "SELECT COUNT(*) as booking_count FROM bookings WHERE schedule_id = ? AND booking_status IN ('confirmed', 'checked_in', 'pending')";
        $final_stmt = mysqli_prepare($koneksi, $final_check);
        mysqli_stmt_bind_param($final_stmt, "i", $schedule_id);
        mysqli_stmt_execute($final_stmt);
        $final_result = mysqli_stmt_get_result($final_stmt);
        $final_booking_count = mysqli_fetch_assoc($final_result)['final_booking_count'];

        if ($final_booking_count > 0) {
            set_flash('error', 'Tidak dapat menghapus jadwal yang masih memiliki booking aktif.');
            redirect('index.php');
        }

        // Delete schedule
        $delete_query = "DELETE FROM schedules WHERE id = ?";
        $delete_stmt = mysqli_prepare($koneksi, $delete_query);
        mysqli_stmt_bind_param($delete_stmt, "i", $schedule_id);

        if (mysqli_stmt_execute($delete_stmt)) {
            set_flash('success', 'Jadwal berhasil dihapus.');
        } else {
            set_flash('error', 'Gagal menghapus jadwal. Silakan coba lagi.');
        }
    }

    redirect('index.php');
}

include '../header-admin.php';
?>

<style>
    .delete-schedule-content {
        margin-left: 0;
        padding: 20px;
    }

    .delete-schedule-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .delete-schedule-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 30px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .delete-schedule-header h1 {
        margin: 0 0 10px 0;
        font-size: 1.8rem;
    }

    .delete-warning {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-radius: 8px;
        padding: 20px;
        margin: 20px;
        text-align: center;
    }

    .warning-icon {
        font-size: 3rem;
        color: #856404;
        margin-bottom: 10px;
    }

    .warning-text {
        color: #856404;
        font-weight: 500;
        margin: 0;
    }

    .schedule-details {
        background: #f8f9fa;
        padding: 20px;
        margin: 20px;
        border-radius: 8px;
        border-left: 4px solid <?php echo $primary_blue; ?>;
    }

    .schedule-details h3 {
        margin: 0 0 15px 0;
        color: <?php echo $primary_blue; ?>;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #eee;
    }

    .detail-label {
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    .detail-value {
        color: #333;
    }

    .booking-alert {
        background: #f8d7da;
        border: 1px solid #f5c6cb;
        border-radius: 8px;
        padding: 20px;
        margin: 20px;
        text-align: center;
    }

    .booking-alert .alert-icon {
        font-size: 2rem;
        color: #721c24;
        margin-bottom: 10px;
    }

    .booking-alert .alert-text {
        color: #721c24;
        font-weight: 500;
        margin: 0 0 10px 0;
    }

    .booking-count {
        font-size: 1.2rem;
        font-weight: bold;
        color: #721c24;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-delete:disabled {
        background: #6c757d;
        cursor: not-allowed;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s;
    }

    .btn-cancel:hover {
        background: #545b62;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-delete,
        .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="delete-schedule-content">
    <div class="delete-schedule-card">
        <div class="delete-schedule-header">
            <h1>🗑️ Hapus Jadwal</h1>
        </div>

        <div class="delete-warning">
            <div class="warning-icon">⚠️</div>
            <p class="warning-text">Apakah Anda yakin ingin menghapus jadwal ini?</p>
            <p style="color: #856404; margin: 10px 0 0 0; font-size: 0.9rem;">
                Tindakan ini tidak dapat dibatalkan dan akan menghapus jadwal secara permanen.
            </p>
        </div>

        <div class="schedule-details">
            <h3>Detail Jadwal</h3>
            <div class="detail-grid">
                <div class="detail-item">
                    <span class="detail-label">Rute:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Tanggal:</span>
                    <span class="detail-value"><?php echo format_date($schedule['departure_date']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Waktu:</span>
                    <span class="detail-value"><?php echo format_time($schedule['departure_time']); ?> - <?php echo format_time($schedule['arrival_time']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Bus:</span>
                    <span class="detail-value"><?php echo htmlspecialchars($schedule['bus_type_name']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Harga:</span>
                    <span class="detail-value"><?php echo format_currency($schedule['price']); ?></span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Status:</span>
                    <span class="detail-value"><?php echo ucfirst($schedule['status']); ?></span>
                </div>
            </div>
        </div>

        <?php if ($booking_count > 0) { ?>
            <div class="booking-alert">
                <div class="alert-icon">🚫</div>
                <p class="alert-text">Tidak dapat menghapus jadwal ini!</p>
                <p class="booking-count">Masih ada <?php echo $booking_count; ?> booking aktif pada jadwal ini.</p>
                <p style="color: #721c24; margin: 10px 0 0 0; font-size: 0.9rem;">
                    Harap batalkan semua booking terlebih dahulu sebelum menghapus jadwal.
                </p>
            </div>
        <?php } ?>

        <form method="POST" action="" class="form-actions">
            <?php echo csrf_field(); ?>
            <button type="submit" name="confirm_delete" class="btn-delete" <?php echo ($booking_count > 0) ? 'disabled' : ''; ?>>
                🗑️ Ya, Hapus Jadwal
            </button>
            <a href="index.php" class="btn-cancel">❌ Batal</a>
        </form>
    </div>
</div>

<?php include '../footer-admin.php'; ?>
