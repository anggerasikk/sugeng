<?php
require_once 'config.php';

try {
    global $koneksi;

    echo "Checking schedules table schema:\n";
    $result = $koneksi->query('DESCRIBE schedules');
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . ' - ' . $row['Type'] . ' - ' . $row['Null'] . ' - ' . $row['Default'] . "\n";
    }

    echo "\nTesting count query with status filter:\n";
    $count_query = "SELECT COUNT(*) as total FROM schedules WHERE status = ?";
    $stmt = mysqli_prepare($koneksi, $count_query);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $status);
        $status = 'active';
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count = mysqli_fetch_assoc($result)['total'];
        echo "Count query works. Count: $count\n";
    } else {
        echo "Error preparing count query: " . mysqli_error($koneksi) . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
