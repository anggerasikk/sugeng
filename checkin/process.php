<?php
require_once '../config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_code = sanitize_input($_POST['booking_code']);

    // Validate booking code format
    if (!preg_match('/^SR-[A-Z0-9]{8}$/', $booking_code)) {
        set_flash('error', 'Format kode booking tidak valid.');
        header("Location: index.php");
        exit;
    }

    // Get booking details with schedule info
    $booking_query = mysqli_query($koneksi, "
        SELECT b.*, s.departure_date, s.departure_time, s.origin, s.destination,
               TIMESTAMPDIFF(HOUR, NOW(), CONCAT(s.departure_date, ' ', s.departure_time)) as hours_until_departure
        FROM bookings b
        JOIN schedules s ON b.schedule_id = s.id
        WHERE b.booking_code = '$booking_code'
    ");

    if (mysqli_num_rows($booking_query) == 0) {
        set_flash('error', 'Kode booking tidak ditemukan.');
        header("Location: index.php");
        exit;
    }

    $booking = mysqli_fetch_assoc($booking_query);

    // Check if booking is eligible for check-in
    if ($booking['payment_status'] !== 'paid') {
        set_flash('error', 'Booking belum dibayar. Silakan lakukan pembayaran terlebih dahulu.');
        header("Location: index.php");
        exit;
    }

    if ($booking['booking_status'] === 'cancelled') {
        set_flash('error', 'Booking telah dibatalkan.');
        header("Location: index.php");
        exit;
    }

    if ($booking['booking_status'] === 'checked_in') {
        set_flash('error', 'Anda sudah melakukan check-in untuk booking ini.');
        header("Location: index.php");
        exit;
    }

    // Check if check-in is allowed (within 24 hours before departure)
    if ($booking['hours_until_departure'] > 24) {
        set_flash('error', 'Check-in hanya dapat dilakukan maksimal 24 jam sebelum keberangkatan.');
        header("Location: index.php");
        exit;
    }

    if ($booking['hours_until_departure'] < 0) {
        set_flash('error', 'Waktu keberangkatan sudah lewat.');
        header("Location: index.php");
        exit;
    }

    // Start transaction for check-in process
    mysqli_begin_transaction($koneksi);

    try {
        // Generate seat numbers for passengers
        $seat_numbers = [];
        for ($i = 1; $i <= $booking['num_passengers']; $i++) {
            // Simple seat assignment - in production, you'd have a proper seat map
            $seat_numbers[] = 'A' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        // Update booking status to checked_in
        mysqli_query($koneksi, "
            UPDATE bookings
            SET booking_status = 'checked_in',
                checked_in_at = NOW(),
                seat_numbers = '" . implode(',', $seat_numbers) . "'
            WHERE id = {$booking['id']}
        ");

        // Log activity
        log_activity($booking['user_id'], 'check_in', "Checked in for booking: $booking_code");

        // Send check-in confirmation email
        send_checkin_confirmation_email($booking['passenger_email'], $booking_code, $booking['passenger_name'], $seat_numbers, $booking);

        mysqli_commit($koneksi);

        set_flash('success', 'Check-in berhasil! Nomor kursi Anda: ' . implode(', ', $seat_numbers));
        header("Location: success.php?code=" . $booking_code);
        exit;

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        set_flash('error', 'Terjadi kesalahan saat check-in. Silakan coba lagi.');
        header("Location: index.php");
        exit;
    }
}
