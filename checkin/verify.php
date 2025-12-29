<?php
require_once '../config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu untuk melakukan check-in.');
    redirect('../beranda/signin.php');
}

// Get booking code from POST data
$booking_code = isset($_POST['booking_code']) ? sanitize_input($_POST['booking_code']) : '';

if (empty($booking_code)) {
    set_flash('error', 'Kode booking tidak boleh kosong.');
    redirect('index.php');
}

// Verify booking exists and belongs to current user
$query = "SELECT b.*, s.departure_date, s.departure_time, s.arrival_time,
         r.origin, r.destination, bt.name as bus_type, bt.capacity,
         TIMESTAMPDIFF(HOUR, NOW(), CONCAT(s.departure_date, ' ', s.departure_time)) as hours_until_departure
         FROM bookings b
         JOIN schedules s ON b.schedule_id = s.id
         JOIN routes r ON s.route_id = r.id
         JOIN bus_types bt ON s.bus_type_id = bt.id
         WHERE b.booking_code = ? AND b.user_id = ? AND b.payment_status = 'paid' AND (b.booking_status IN ('confirmed', '', 'active', 'checked_in'))";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "si", $booking_code, $_SESSION['user_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    set_flash('error', 'Kode booking tidak ditemukan atau tidak valid untuk check-in.');
    redirect('index.php');
}

$booking = mysqli_fetch_assoc($result);

// Check if already checked in
if ($booking['booking_status'] === 'checked_in') {
    set_flash('error', 'Anda sudah melakukan cek in.');
    redirect('index.php');
}

// Check if departure time is within 24 hours
if ($booking['hours_until_departure'] > 24) {
    set_flash('error', 'Check-in hanya dapat dilakukan maksimal 24 jam sebelum keberangkatan.');
    redirect('index.php');
}

// Check if departure time has passed
if ($booking['hours_until_departure'] < 0) {
    set_flash('error', 'Waktu keberangkatan sudah lewat. Check-in tidak dapat dilakukan.');
    redirect('index.php');
}

// Generate seat numbers (random available seats)
$available_seats = range(1, $booking['capacity']);
shuffle($available_seats);
$assigned_seats = array_slice($available_seats, 0, $booking['seats_booked']);

// Update booking status to checked_in and assign seats
$seat_numbers = implode(',', $assigned_seats);
$update_query = "UPDATE bookings SET booking_status = 'checked_in', seat_numbers = ?, checked_in_at = NOW() WHERE id = ?";
$update_stmt = mysqli_prepare($koneksi, $update_query);
mysqli_stmt_bind_param($update_stmt, "si", $seat_numbers, $booking['id']);

if (mysqli_stmt_execute($update_stmt)) {
    // Send confirmation email (placeholder for future implementation)
    // send_checkin_email($booking['email'], $booking_code, $assigned_seats);

    // Redirect to success page with booking details
    $_SESSION['checkin_success'] = [
        'booking_code' => $booking_code,
        'seat_numbers' => $assigned_seats,
        'route' => $booking['origin'] . ' → ' . $booking['destination'],
        'departure_date' => $booking['departure_date'],
        'departure_time' => $booking['departure_time'],
        'bus_type' => $booking['bus_type']
    ];

    redirect('success.php');
} else {
    set_flash('error', 'Terjadi kesalahan saat melakukan check-in. Silakan coba lagi.');
    redirect('index.php');
}

// If we reach here, include the header and footer (though this shouldn't happen due to redirects above)
include '../header.php';
?>

<?php include '../footer.php'; ?>
