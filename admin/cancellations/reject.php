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
$query = "SELECT cr.*, b.booking_code, u.email, u.full_name
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

// Update cancellation status to rejected
$update_query = "UPDATE cancellation_requests SET 
                status = 'rejected', 
                processed_by = ?, 
                processed_at = NOW() 
                WHERE id = ?";

$stmt_update = mysqli_prepare($koneksi, $update_query);
mysqli_stmt_bind_param($stmt_update, "ii", $_SESSION['user_id'], $cancellation_id);

if (mysqli_stmt_execute($stmt_update)) {
    // Log activity
    log_activity($_SESSION['user_id'], 'reject_cancellation', 
                "Rejected cancellation for booking: {$cancellation['booking_code']}");

    // Send email notification (optional)
    // send_cancellation_rejected_email($cancellation['email'], $cancellation['booking_code']);

    set_flash('success', 'Pengajuan pembatalan berhasil ditolak.');
} else {
    set_flash('error', 'Gagal memproses pengajuan.');
}

redirect('index.php');
?>