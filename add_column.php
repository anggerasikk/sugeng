<?php
require_once 'config.php';

try {
    global $koneksi;

    // Check if status column exists
    $result = $koneksi->query("SHOW COLUMNS FROM schedules LIKE 'status'");
    if ($result->num_rows == 0) {
        // Add the status column
        $sql = "ALTER TABLE schedules ADD COLUMN status ENUM('active', 'cancelled', 'completed') DEFAULT 'active' AFTER total_seats";
        if ($koneksi->query($sql) === TRUE) {
            echo "Status column added successfully.\n";
        } else {
            echo "Error adding column: " . $koneksi->error . "\n";
        }
    } else {
        echo "Status column already exists.\n";
    }

    $koneksi->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
