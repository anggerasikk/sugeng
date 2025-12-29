<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        header('Content-Type: application/json');
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Akses ditolak.']);
        exit;
    } else {
        set_flash('error', 'Akses ditolak.');
        redirect('../signin.php');
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $booking_id = intval($_POST['booking_id'] ?? 0);

    if (!$booking_id) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'ID booking tidak valid']);
            exit;
        } else {
            set_flash('error', 'ID booking tidak valid');
            redirect('index.php');
        }
    }

    // Get booking details
    $query = "SELECT b.*, s.departure_date, s.departure_time, s.arrival_time,
             r.origin, r.destination, bt.name as bus_type, bt.capacity,
             TIMESTAMPDIFF(HOUR, NOW(), CONCAT(s.departure_date, ' ', s.departure_time)) as hours_until_departure
             FROM bookings b
             JOIN schedules s ON b.schedule_id = s.id
             JOIN routes r ON s.route_id = r.id
             JOIN bus_types bt ON s.bus_type_id = bt.id
             WHERE b.id = ?";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $booking = mysqli_fetch_assoc($result);

    if (!$booking) {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Booking tidak ditemukan']);
            exit;
        } else {
            set_flash('error', 'Booking tidak ditemukan');
            redirect('index.php');
        }
    }

    // Check if already checked in
    if ($booking['booking_status'] === 'checked_in') {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'User sudah melakukan check-in untuk booking ini.']);
            exit;
        } else {
            set_flash('error', 'User sudah melakukan check-in untuk booking ini.');
            redirect('index.php');
        }
    }

    // Check if booking is eligible for check-in
    if ($booking['payment_status'] !== 'paid') {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Booking belum dibayar.']);
            exit;
        } else {
            set_flash('error', 'Booking belum dibayar.');
            redirect('index.php');
        }
    }

    if ($booking['booking_status'] === 'cancelled') {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => 'Booking telah dibatalkan.']);
            exit;
        } else {
            set_flash('error', 'Booking telah dibatalkan.');
            redirect('index.php');
        }
    }

    // Start transaction
    mysqli_begin_transaction($koneksi);

    try {
        // Generate seat numbers (random available seats)
        $available_seats = range(1, $booking['capacity']);
        shuffle($available_seats);
        $assigned_seats = array_slice($available_seats, 0, $booking['seats_booked']);
        $seat_numbers = implode(',', $assigned_seats);

        // Update booking status to checked_in
        $update_query = "UPDATE bookings SET booking_status = 'checked_in', seat_numbers = ?, checked_in_at = NOW() WHERE id = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($update_stmt, "si", $seat_numbers, $booking_id);
        mysqli_stmt_execute($update_stmt);

        // Log activity
        log_activity($_SESSION['user_id'], 'admin_checkin_booking', "Admin checked in booking: {$booking['booking_code']}");

        mysqli_commit($koneksi);

        $success_message = "Check-in berhasil untuk booking <strong>{$booking['booking_code']}</strong>. Nomor kursi: " . $seat_numbers;

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode([
                'success' => true,
                'message' => $success_message,
                'booking_code' => $booking['booking_code'],
                'booking_id' => $booking_id,
                'seat_numbers' => $seat_numbers
            ]);
            exit;
        } else {
            set_flash('success', $success_message);
        }

    } catch (Exception $e) {
        mysqli_rollback($koneksi);

        $error_message = 'Gagal melakukan check-in: ' . $e->getMessage();

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => $error_message]);
            exit;
        } else {
            set_flash('error', $error_message);
        }
    }
} else {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        header('Content-Type: application/json');
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Method tidak diizinkan']);
        exit;
    } else {
        set_flash('error', 'Method tidak diizinkan');
    }
}

// Redirect if not AJAX
if (!(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {
    redirect('index.php');
}
?>
