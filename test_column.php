<?php
require_once 'config.php';

try {
    global $koneksi;

    // Test query to check if status column exists
    $result = $koneksi->query("SELECT status FROM schedules LIMIT 1");
    if ($result) {
        echo "Status column exists and is accessible.\n";
        $row = $result->fetch_assoc();
        echo "Sample status value: " . ($row ? $row['status'] : 'No data') . "\n";
    } else {
        echo "Error: " . $koneksi->error . "\n";
    }

    $koneksi->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
