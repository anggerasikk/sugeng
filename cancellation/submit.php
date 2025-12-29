<?php
require_once '../config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu.');
    header("Location: ../beranda/signin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Token keamanan tidak valid!');
        header("Location: index.php");
        exit;
    }

    $booking_code = sanitize_input($_POST['booking_code']);
    $reason = sanitize_input($_POST['reason']);
    $user_id = $_SESSION['user_id'];

    // Cari data booking milik user yang login
    $q = mysqli_query($koneksi, "SELECT * FROM bookings WHERE booking_code = '$booking_code' AND user_id = '$user_id'");
    $data = mysqli_fetch_assoc($q);

    if ($data) {
        // Cek apakah booking masih bisa dibatalkan (belum completed atau cancelled)
        if ($data['booking_status'] == 'completed' || $data['booking_status'] == 'cancelled') {
            set_flash('error', 'Booking ini sudah tidak bisa dibatalkan.');
            header("Location: index.php");
            exit;
        }

        // Cek apakah sudah ada pengajuan pembatalan
        $check_existing = mysqli_query($koneksi, "SELECT id FROM cancellation_requests WHERE booking_id = '{$data['id']}'");
        if (mysqli_num_rows($check_existing) > 0) {
            set_flash('error', 'Pengajuan pembatalan untuk booking ini sudah pernah diajukan.');
            header("Location: index.php");
            exit;
        }

        $booking_id = $data['id'];
        $refund_amount = $data['total_amount'] * 0.75; // Sesuai aturan SRS

        $insert = "INSERT INTO cancellation_requests (booking_id, user_id, reason, refund_amount, status, created_at) 
                   VALUES ('$booking_id', '$user_id', '$reason', '$refund_amount', 'pending', NOW())";
        
        if (mysqli_query($koneksi, $insert)) {
            // Log activity
            log_activity($user_id, 'cancel_request', "Cancellation request for booking: $booking_code");
            
            // Redirect to success page
            $message = 'Pengajuan pembatalan berhasil dikirim. Menunggu persetujuan admin.';
            header("Location: success.php?message=" . urlencode($message) . "&code=" . urlencode($booking_code));
            exit;
        } else {
            // Debug: show error
            die("Database error: " . mysqli_error($koneksi));
        }
    } else {
        set_flash('error', 'Kode booking tidak ditemukan atau bukan milik Anda.');
        header("Location: index.php");
        exit;
    }
}