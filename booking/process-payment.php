<?php
require_once '../config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu');
    redirect(base_url('signin.php'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(base_url('jadwal.php'));
}

// Verify CSRF
if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Invalid security token');
    redirect(base_url('jadwal.php'));
}

// Check if booking data exists in session
if (!isset($_SESSION['pending_booking'])) {
    set_flash('error', 'Data booking tidak ditemukan');
    redirect(base_url('jadwal.php'));
}

// Define generate_booking_code() function if not exists
if (!function_exists('generate_booking_code')) {
    function generate_booking_code() {
        return 'SR' . date('Ymd') . strtoupper(substr(md5(time()), 0, 6));
    }
}

$booking_data = $_SESSION['pending_booking'];

// Validate file upload
if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    set_flash('error', 'Gagal upload bukti pembayaran');
    redirect(base_url('booking/confirm-booking.php'));
}

$file = $_FILES['payment_proof'];

// Validate file type (simple way)
$allowed_extensions = ['jpg', 'jpeg', 'png'];
$file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

if (!in_array($file_extension, $allowed_extensions)) {
    set_flash('error', 'Format file harus JPG, JPEG, atau PNG');
    redirect(base_url('booking/confirm-booking.php'));
}

// Validate file size (2MB)
if ($file['size'] > 2 * 1024 * 1024) {
    set_flash('error', 'Ukuran file maksimal 2MB');
    redirect(base_url('booking/confirm-booking.php'));
}

// Create upload directory if not exists
$upload_dir = '../uploads/payment_proofs/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$new_filename = 'payment_' . time() . '_' . uniqid() . '.' . $file_extension;
$upload_path = $upload_dir . $new_filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
    set_flash('error', 'Gagal menyimpan file');
    redirect(base_url('booking/confirm-booking.php'));
}

// Start transaction
mysqli_begin_transaction($koneksi);

try {
    // Generate booking code
    $booking_code = generate_booking_code();
    $user_id = $_SESSION['user_id'];
    
    // SESUAIKAN DENGAN STRUKTUR TABEL bookings
    // Hitung: ada 12 tanda ? dalam query
    $insert_query = "INSERT INTO bookings (
        booking_code, 
        user_id, 
        schedule_id,
        passenger_name, 
        passenger_phone, 
        passenger_email, 
        passenger_id_number,
        seats_booked, 
        seat_numbers, 
        total_amount,
        payment_status, 
        booking_status,
        payment_method, 
        payment_proof,
        checkin_status,
        notes
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending', 'pending', 'transfer', ?, 'not_checked_in', ?)";
    
    // Note untuk booking
    $origin = $booking_data['schedule']['origin'] ?? 'Unknown';
    $destination = $booking_data['schedule']['destination'] ?? 'Unknown';
    $notes = "Booking dari " . $origin . " ke " . $destination . 
             " pada " . $booking_data['travel_date'] . 
             " untuk " . $booking_data['num_passengers'] . " penumpang";
    
    $insert_stmt = mysqli_prepare($koneksi, $insert_query);
    
    // Debug: Tampilkan informasi untuk troubleshooting
    error_log("Booking Data: " . print_r($booking_data, true));
    error_log("Notes: " . $notes);
    error_log("Filename: " . $new_filename);
    
    // Binding parameter: 12 parameter (sesuai dengan 12 tanda ?)
    // s=string, i=integer, d=double/decimal
    mysqli_stmt_bind_param($insert_stmt, "siissssisdss", 
        $booking_code,                    // booking_code (string)
        $user_id,                         // user_id (integer)
        $booking_data['schedule_id'],     // schedule_id (integer)
        $booking_data['passenger_name'],  // passenger_name (string)
        $booking_data['passenger_phone'], // passenger_phone (string)
        $booking_data['passenger_email'], // passenger_email (string)
        $booking_data['passenger_identity'], // passenger_id_number (string)
        $booking_data['num_passengers'],  // seats_booked (integer)
        $booking_data['selected_seats'],  // seat_numbers (string)
        $booking_data['total_price'],     // total_amount (double/decimal)
        $new_filename,                    // payment_proof (string)
        $notes                            // notes (string)
    );
    
    if (!mysqli_stmt_execute($insert_stmt)) {
        throw new Exception('Gagal menyimpan booking: ' . mysqli_error($koneksi));
    }
    
    $booking_id = mysqli_insert_id($koneksi);
    
    // Update available seats
    $seats_booked = $booking_data['num_passengers'];
    $schedule_id = $booking_data['schedule_id'];
    
    $update_query = "UPDATE schedules SET available_seats = available_seats - ? WHERE id = ?";
    $update_stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($update_stmt, "ii", $seats_booked, $schedule_id);
    
    if (!mysqli_stmt_execute($update_stmt)) {
        throw new Exception('Gagal update kursi: ' . mysqli_error($koneksi));
    }
    
    // Commit transaction
    mysqli_commit($koneksi);
    
    // Clear session data
    unset($_SESSION['pending_booking']);
    
    // Set success message
    set_flash('success', 'Booking berhasil! Kode Booking: ' . $booking_code . '. Pembayaran sedang diverifikasi.');
    
    // Redirect to booking success page
    redirect(base_url('booking/success.php?code=' . $booking_code));
    
} catch (Exception $e) {
    // Rollback on error
    mysqli_rollback($koneksi);
    
    // Delete uploaded file
    if (file_exists($upload_path)) {
        unlink($upload_path);
    }
    
    set_flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
    redirect(base_url('booking/confirm-booking.php'));
}
?>