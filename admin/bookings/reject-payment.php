<?php
require_once '../../config.php';

if (!is_admin()) {
    set_flash('error', 'Akses ditolak.');
    redirect(base_url('signin.php'));
}

$booking_id = intval($_GET['id'] ?? 0);

if (!$booking_id) {
    set_flash('error', 'ID booking tidak valid');
    redirect('index.php');
}

// Update status pembayaran menjadi cancelled
$update_query = "UPDATE bookings SET 
                 payment_status = 'cancelled',
                 booking_status = 'cancelled',
                 notes = CONCAT(IFNULL(notes, ''), '\n[Ditolak oleh admin: ', NOW(), ']'),
                 updated_at = NOW()
                 WHERE id = ?";
$update_stmt = mysqli_prepare($koneksi, $update_query);
mysqli_stmt_bind_param($update_stmt, "i", $booking_id);

if (mysqli_stmt_execute($update_stmt)) {
    // Update kursi tersedia
    $booking_query = "SELECT schedule_id, seats_booked FROM bookings WHERE id = ?";
    $booking_stmt = mysqli_prepare($koneksi, $booking_query);
    mysqli_stmt_bind_param($booking_stmt, "i", $booking_id);
    mysqli_stmt_execute($booking_stmt);
    $booking_result = mysqli_stmt_get_result($booking_stmt);
    $booking = mysqli_fetch_assoc($booking_result);
    
    if ($booking) {
        $update_seats_query = "UPDATE schedules SET available_seats = available_seats + ? WHERE id = ?";
        $update_seats_stmt = mysqli_prepare($koneksi, $update_seats_query);
        mysqli_stmt_bind_param($update_seats_stmt, "ii", $booking['seats_booked'], $booking['schedule_id']);
        mysqli_stmt_execute($update_seats_stmt);
    }
    
    set_flash('success', 'Pembayaran berhasil ditolak dan kursi dikembalikan.');
} else {
    set_flash('error', 'Gagal menolak pembayaran: ' . mysqli_error($koneksi));
}

redirect('index.php');
?>