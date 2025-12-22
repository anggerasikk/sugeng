<?php
require_once 'config.php';

try {
    global $koneksi;

    echo "Checking schedules table schema:\n";
    $result = $koneksi->query('DESCRIBE schedules');
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . ' - ' . $row['Type'] . ' - ' . $row['Null'] . ' - ' . $row['Default'] . "\n";
    }

    echo "\nChecking if status column exists in WHERE clause:\n";
    $test_query = "SELECT COUNT(*) as total FROM schedules WHERE status = 'active'";
    $test_result = $koneksi->query($test_query);
    if ($test_result) {
        $count = $test_result->fetch_assoc()['total'];
        echo "Status column works in WHERE clause. Count: $count\n";
    } else {
        echo "Error with status column: " . $koneksi->error . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
