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
    $reason = sanitize_input($_POST['reason'] ?? '');

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
    $query = "SELECT b.*, s.schedule_id FROM bookings b LEFT JOIN schedules s ON b.schedule_id = s.id WHERE b.id = ?";
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

    // Start transaction
    mysqli_begin_transaction($koneksi);

    try {
        // Update booking status to cancelled
        $update_booking = "UPDATE bookings SET 
                          booking_status = 'cancelled',
                          payment_status = 'refunded',
                          cancelled_at = NOW(),
                          cancellation_reason = ?
                          WHERE id = ?";
        $stmt_booking = mysqli_prepare($koneksi, $update_booking);
        mysqli_stmt_bind_param($stmt_booking, "si", $reason, $booking_id);
        mysqli_stmt_execute($stmt_booking);

        // Return seats to schedule
        $return_seats = "UPDATE schedules SET available_seats = available_seats + ? WHERE id = ?";
        $stmt_seats = mysqli_prepare($koneksi, $return_seats);
        mysqli_stmt_bind_param($stmt_seats, "ii", $booking['seats_booked'], $booking['schedule_id']);
        mysqli_stmt_execute($stmt_seats);

        // Log activity
        log_activity($_SESSION['user_id'], 'admin_cancel_booking', 
                    "Admin cancelled booking: {$booking['booking_code']} - Reason: {$reason}");

        mysqli_commit($koneksi);

        $success_message = "Booking <strong>{$booking['booking_code']}</strong> berhasil dibatalkan.";

        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode([
                'success' => true, 
                'message' => $success_message,
                'booking_code' => $booking['booking_code'],
                'booking_id' => $booking_id
            ]);
            exit;
        } else {
            set_flash('success', $success_message);
        }

    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        
        $error_message = 'Gagal membatalkan booking: ' . $e->getMessage();
        
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