<?php
require_once '../config.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pastikan user login sesuai SRS
    if (!isset($_SESSION['user_id'])) {
        set_flash('error', 'Silakan login untuk memesan tiket.');
        header("Location: ../signin.php");
        exit;
    }

    $user_id = $_SESSION['user_id'];
    $schedule_id = $_POST['schedule_id'];
    $travel_date = $_POST['travel_date'];
    $p_name = sanitize_input($_POST['passenger_name']);
    $p_phone = sanitize_input($_POST['passenger_phone']);
    $p_email = sanitize_input($_POST['passenger_email'] ?? '');
    $p_identity = sanitize_input($_POST['passenger_identity']);
    $num_seats = (int)$_POST['num_passengers'];
    $price_per_seat = (float)$_POST['price'];

    // Validate booking data
    $validation_errors = validate_booking_data($schedule_id, $travel_date, $p_name, $p_phone, $p_identity, $num_seats);
    if (!empty($validation_errors)) {
        set_flash('error', implode('<br>', $validation_errors));
        header("Location: book-detail.php?id=" . $schedule_id);
        exit;
    }

    $total_price = $num_seats * $price_per_seat;

    // Generate Kode Booking Unik using function
    $booking_code = generate_booking_code();

    // Mulai Transaksi Database
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Simpan ke tabel bookings
        $query_booking = "INSERT INTO bookings (booking_code, user_id, schedule_id, booking_date, travel_date,
                          passenger_name, passenger_phone, passenger_email, passenger_identity,
                          num_passengers, total_price, payment_status, booking_status)
                          VALUES (?, ?, ?, CURDATE(), ?, ?, ?, ?, ?, ?, ?, 'pending', 'active')";

        $stmt = mysqli_prepare($koneksi, $query_booking);
        mysqli_stmt_bind_param($stmt, "siisssssid", $booking_code, $user_id, $schedule_id, $travel_date,
                               $p_name, $p_phone, $p_email, $p_identity, $num_seats, $total_price);
        mysqli_stmt_execute($stmt);

        // 2. Update sisa kursi di tabel schedules
        mysqli_query($koneksi, "UPDATE schedules SET available_seats = available_seats - $num_seats WHERE id = $schedule_id");

        // Log activity
        log_activity($user_id, 'booking_created', "Booking created with code: $booking_code");

        mysqli_commit($koneksi);

        // Send confirmation email
        send_booking_confirmation_email($p_email, $booking_code, $p_name, $total_price);

        header("Location: success.php?code=" . $booking_code);
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        set_flash('error', 'Gagal memproses pesanan.');
        header("Location: book-detail.php?id=" . $schedule_id);
    }
}
