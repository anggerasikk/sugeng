<?php
// Ensure session is started for AJAX requests
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../../config.php';
require_once '../../koneksi.php';
require_once '../../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
        // Response JSON untuk AJAX
        header('Content-Type: application/json');
        ob_clean();
        echo json_encode(['success' => false, 'message' => 'Akses ditolak. User ID: ' . ($_SESSION['user_id'] ?? 'null') . ', Role: ' . ($_SESSION['user_role'] ?? 'null')]);
        exit;
    } else {
        set_flash('error', 'Akses ditolak.');
        redirect(base_url('signin.php'));
    }
}

// Cek apakah ini request AJAX atau regular request
$is_ajax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';

// GET method untuk verifikasi
$booking_id = intval($_GET['id'] ?? ($_POST['booking_id'] ?? 0));

if (!$booking_id) {
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'ID booking tidak valid']);
        exit;
    } else {
        set_flash('error', 'ID booking tidak valid');
        redirect('index.php');
    }
}

// Ambil data booking terlebih dahulu untuk logging
$booking_query = "SELECT b.*, u.email, u.full_name, u.id as user_id, 
                 r.origin, r.destination, s.departure_date, s.departure_time
                 FROM bookings b 
                 JOIN users u ON b.user_id = u.id 
                 JOIN schedules s ON b.schedule_id = s.id
                 JOIN routes r ON s.route_id = r.id
                 WHERE b.id = ?";
$booking_stmt = mysqli_prepare($koneksi, $booking_query);
mysqli_stmt_bind_param($booking_stmt, "i", $booking_id);
mysqli_stmt_execute($booking_stmt);
$booking_result = mysqli_stmt_get_result($booking_stmt);
$booking = mysqli_fetch_assoc($booking_result);

if (!$booking) {
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Booking tidak ditemukan']);
        exit;
    } else {
        set_flash('error', 'Booking tidak ditemukan');
        redirect('index.php');
    }
}

// Check action type (verifikasi atau tolak)
$action = $_GET['action'] ?? ($_POST['action'] ?? 'verify');
$reason = $_POST['reason'] ?? '';

if ($action === 'verify') {
    // VERIFIKASI PEMBAYARAN
    // Cek dulu status saat ini
    $current_status_query = "SELECT payment_status FROM bookings WHERE id = ?";
    $current_stmt = mysqli_prepare($koneksi, $current_status_query);
    mysqli_stmt_bind_param($current_stmt, "i", $booking_id);
    mysqli_stmt_execute($current_stmt);
    $current_result = mysqli_stmt_get_result($current_stmt);
    $current_status = mysqli_fetch_assoc($current_result)['payment_status'];
    
    // Jika sudah paid, jangan update lagi tapi beri response berbeda
    if ($current_status === 'paid') {
        $message = "Pembayaran booking {$booking['booking_code']} sudah diverifikasi sebelumnya.";
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => $message, 
                'already_verified' => true,
                'booking_code' => $booking['booking_code']
            ]);
            exit;
        } else {
            set_flash('info', $message);
            redirect('index.php');
        }
    }
    
    // Start transaction
    mysqli_begin_transaction($koneksi);
    
    try {
        // Update status booking
        $update_query = "UPDATE bookings SET 
                         payment_status = 'paid',
                         booking_status = 'confirmed',
                         payment_verified_at = NOW(),
                         verified_by = ?,
                         updated_at = NOW()
                         WHERE id = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        if (!$update_stmt) {
            throw new Exception('Prepare failed: ' . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($update_stmt, "ii", $_SESSION['user_id'], $booking_id);

        if (!mysqli_stmt_execute($update_stmt)) {
            throw new Exception('Execute failed: ' . mysqli_stmt_error($update_stmt));
        }
        
        // Update status verified user jika belum terverifikasi
        $update_user_query = "UPDATE users SET verified = TRUE, updated_at = NOW() WHERE id = ? AND verified = FALSE";
        $update_user_stmt = mysqli_prepare($koneksi, $update_user_query);
        mysqli_stmt_bind_param($update_user_stmt, "i", $booking['user_id']);
        mysqli_stmt_execute($update_user_stmt);
        
        // Log activity
        log_activity($_SESSION['user_id'], 'verify_payment', 
                    "Verified payment for booking: {$booking['booking_code']} (ID: {$booking_id})");
        
        // Kirim email notifikasi ke user
        if (function_exists('send_booking_confirmation_email')) {
            send_booking_confirmation_email(
                $booking['email'],
                $booking['booking_code'],
                $booking['passenger_name'],
                $booking['total_amount']
            );
        }
        
        // Commit transaction
        mysqli_commit($koneksi);
        
        $success_message = "Pembayaran booking <strong>{$booking['booking_code']}</strong> berhasil diverifikasi.";
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode([
                'success' => true, 
                'message' => $success_message,
                'booking_code' => $booking['booking_code'],
                'booking_id' => $booking_id,
                'new_status' => 'paid'
            ]);
            exit;
        } else {
            set_flash('success', $success_message);
        }
        
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        
        $error_message = 'Gagal memverifikasi pembayaran: ' . $e->getMessage();
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => $error_message]);
            exit;
        } else {
            set_flash('error', $error_message);
        }
    }
}
elseif ($action === 'reject') {
    // TOLAK PEMBAYARAN
    
    // Cek dulu status saat ini
    $current_status_query = "SELECT payment_status FROM bookings WHERE id = ?";
    $current_stmt = mysqli_prepare($koneksi, $current_status_query);
    mysqli_stmt_bind_param($current_stmt, "i", $booking_id);
    mysqli_stmt_execute($current_stmt);
    $current_result = mysqli_stmt_get_result($current_stmt);
    $current_status = mysqli_fetch_assoc($current_result)['payment_status'];
    
    // Jika sudah cancelled, jangan update lagi
    if ($current_status === 'cancelled') {
        $message = "Pembayaran booking {$booking['booking_code']} sudah ditolak sebelumnya.";
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => $message, 
                'already_cancelled' => true,
                'booking_code' => $booking['booking_code']
            ]);
            exit;
        } else {
            set_flash('info', $message);
            redirect('index.php');
        }
    }
    
    // Cek apakah sudah ada alasan
    if (empty($reason) && !$is_ajax) {
        // Tampilkan form alasan (kode HTML Anda, tetap sama)
        // ...
        exit;
    }
    
    // Start transaction
    mysqli_begin_transaction($koneksi);
    
    try {
        // Update status booking
        $update_query = "UPDATE bookings SET 
                         payment_status = 'cancelled',
                         booking_status = 'cancelled',
                         cancellation_reason = ?,
                         cancelled_by = ?,
                         cancelled_at = NOW(),
                         updated_at = NOW()
                         WHERE id = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($update_stmt, "sii", $reason, $_SESSION['user_id'], $booking_id);

        if (!mysqli_stmt_execute($update_stmt)) {
            throw new Exception('Gagal update status booking');
        }
        
        // Update available seats (kembalikan kursi)
        $seats_update = "UPDATE schedules 
                        SET available_seats = available_seats + ? 
                        WHERE id = ?";
        $seats_stmt = mysqli_prepare($koneksi, $seats_update);
        mysqli_stmt_bind_param($seats_stmt, "ii", $booking['seats_booked'], $booking['schedule_id']);
        mysqli_stmt_execute($seats_stmt);
        
        // Log activity
        log_activity($_SESSION['user_id'], 'reject_payment', 
                    "Rejected payment for booking: {$booking['booking_code']} (ID: {$booking_id}) - Reason: {$reason}");
        
        // Simpan ke cancellation_requests untuk tracking
        $cancellation_query = "INSERT INTO cancellation_requests 
                              (booking_id, user_id, reason, status, created_by, created_at) 
                              VALUES (?, ?, ?, 'approved', ?, NOW())";
        $cancellation_stmt = mysqli_prepare($koneksi, $cancellation_query);
        $cancellation_status = 'approved';
        mysqli_stmt_bind_param($cancellation_stmt, "iissi", 
            $booking_id, 
            $booking['user_id'], 
            $reason, 
            $cancellation_status,
            $_SESSION['user_id']
        );
        mysqli_stmt_execute($cancellation_stmt);
        
        // Commit transaction
        mysqli_commit($koneksi);
        
        $success_message = "Pembayaran booking <strong>{$booking['booking_code']}</strong> berhasil ditolak.";
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true, 
                'message' => $success_message,
                'booking_code' => $booking['booking_code'],
                'booking_id' => $booking_id,
                'new_status' => 'cancelled'
            ]);
            exit;
        } else {
            set_flash('success', $success_message);
        }
        
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        
        $error_message = 'Gagal menolak pembayaran: ' . $e->getMessage();
        
        if ($is_ajax) {
            header('Content-Type: application/json');
            ob_clean();
            echo json_encode(['success' => false, 'message' => $error_message]);
            exit;
        } else {
            set_flash('error', $error_message);
        }
    }
} else {
    $error_message = 'Aksi tidak valid';
    
    if ($is_ajax) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $error_message]);
        exit;
    } else {
        set_flash('error', $error_message);
    }
}

// Hanya redirect jika bukan AJAX request
if (!$is_ajax) {
    // Clear cache headers untuk memastikan halaman refresh
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    
    redirect('index.php');
}
?>