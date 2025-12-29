<?php
require_once '../config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'error' => 'User not logged in',
        'dates' => [],
        'reservations' => [],
        'cancellations' => []
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Get data for last 30 days for the specific user
$dates = [];
$reservations = [];
$cancellations = [];

for ($i = 29; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $dates[] = date('d/m', strtotime($date));

    // Count reservations (bookings created) for this user
    $res_query = "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = '$date' AND user_id = '$user_id'";
    $res_result = mysqli_query($koneksi, $res_query);
    $res_count = mysqli_fetch_assoc($res_result)['count'];
    $reservations[] = (int)$res_count;

    // Count cancellations (cancellation requests) for this user
    $cancel_query = "SELECT COUNT(*) as count FROM cancellation_requests WHERE DATE(created_at) = '$date' AND user_id = '$user_id'";
    $cancel_result = mysqli_query($koneksi, $cancel_query);
    $cancel_count = mysqli_fetch_assoc($cancel_result)['count'];
    $cancellations[] = (int)$cancel_count;
}

echo json_encode([
    'dates' => $dates,
    'reservations' => $reservations,
    'cancellations' => $cancellations
]);
?>
