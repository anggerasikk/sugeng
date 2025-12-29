<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect('../signin.php');
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    set_flash('error', 'ID pembatalan tidak valid.');
    redirect('index.php');
}

$cancellation_id = (int)$_GET['id'];

// Get cancellation details
$query = "SELECT cr.*, b.booking_code, b.total_amount, b.seats_booked, b.schedule_id, u.email, u.full_name
          FROM cancellation_requests cr
          JOIN bookings b ON cr.booking_id = b.id
          JOIN users u ON cr.user_id = u.id
          WHERE cr.id = ? AND cr.status = 'pending'";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $cancellation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$cancellation = mysqli_fetch_assoc($result);

if (!$cancellation) {
    set_flash('error', 'Pengajuan pembatalan tidak ditemukan atau sudah diproses.');
    redirect('index.php');
}

// Start transaction
mysqli_begin_transaction($koneksi);

try {
    // Update cancellation status to approved
    $update_cancellation = "UPDATE cancellation_requests SET 
                           status = 'approved', 
                           processed_by = ?, 
                           processed_at = NOW() 
                           WHERE id = ?";
    $stmt_update = mysqli_prepare($koneksi, $update_cancellation);
    mysqli_stmt_bind_param($stmt_update, "ii", $_SESSION['user_id'], $cancellation_id);
    mysqli_stmt_execute($stmt_update);

    // Update booking status to cancelled
    $update_booking = "UPDATE bookings SET 
                      booking_status = 'cancelled',
                      payment_status = 'refunded',
                      cancelled_at = NOW(),
                      cancellation_reason = ?
                      WHERE id = ?";
    $stmt_booking = mysqli_prepare($koneksi, $update_booking);
    mysqli_stmt_bind_param($stmt_booking, "si", $cancellation['reason'], $cancellation['booking_id']);
    mysqli_stmt_execute($stmt_booking);

    // Return seats to schedule
    $return_seats = "UPDATE schedules SET available_seats = available_seats + ? WHERE id = ?";
    $stmt_seats = mysqli_prepare($koneksi, $return_seats);
    mysqli_stmt_bind_param($stmt_seats, "ii", $cancellation['seats_booked'], $cancellation['schedule_id']);
    mysqli_stmt_execute($stmt_seats);

    // Log activity
    log_activity($_SESSION['user_id'], 'approve_cancellation', 
                "Approved cancellation for booking: {$cancellation['booking_code']}");

    // Send email notification (optional)
    // send_cancellation_approved_email($cancellation['email'], $cancellation['booking_code'], $cancellation['refund_amount']);

    mysqli_commit($koneksi);

    set_flash('success', 'Pengajuan pembatalan berhasil disetujui. Kursi telah dikembalikan dan dana akan diproses.');

} catch (Exception $e) {
    mysqli_rollback($koneksi);
    set_flash('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
}

redirect('index.php');
?>