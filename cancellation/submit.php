<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_code = mysqli_real_escape_string($koneksi, $_POST['booking_code']);
    $reason = mysqli_real_escape_string($koneksi, $_POST['reason']);

    // Cari data booking
    $q = mysqli_query($koneksi, "SELECT * FROM bookings WHERE booking_code = '$booking_code'");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        $booking_id = $data['id'];
        $refund_amount = $data['total_price'] * 0.75; // Sesuai aturan SRS

        $insert = "INSERT INTO cancellations (booking_id, reason, refund_amount, status) 
                   VALUES ('$booking_id', '$reason', '$refund_amount', 'pending')";
        
        if (mysqli_query($koneksi, $insert)) {
            // Update status booking menjadi 'cancelled'
            mysqli_query($koneksi, "UPDATE bookings SET booking_status = 'cancelled' WHERE id = '$booking_id'");
            set_flash('success', 'Pengajuan pembatalan berhasil dikirim. Menunggu persetujuan admin.');
        }
    } else {
        set_flash('error', 'Kode booking tidak ditemukan.');
    }
    header("Location: index.php");
}