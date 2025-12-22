<?php
require_once 'config.php';

// Simulate the count_query with status filter
$where_clause = "WHERE status = ?";
$count_query = "SELECT COUNT(*) as total FROM schedules s LEFT JOIN routes r ON s.route_id = r.id LEFT JOIN bus_types bt ON s.bus_type_id = bt.id $where_clause";

echo "Query: $count_query\n";

$stmt = mysqli_prepare($koneksi, $count_query);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $status);
    $status = 'active';
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    echo "Count: " . $row['total'] . "\n";
} else {
    echo "Error: " . mysqli_error($koneksi) . "\n";
}
?>
