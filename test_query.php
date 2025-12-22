<?php
require_once 'config.php';

$query = "SELECT COUNT(*) as total FROM schedules WHERE status = 'active'";
$result = mysqli_query($koneksi, $query);

if ($result) {
    $row = mysqli_fetch_assoc($result);
    echo 'Count: ' . $row['total'];
} else {
    echo 'Error: ' . mysqli_error($koneksi);
}
?>
