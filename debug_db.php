<?php
require_once 'config.php';

try {
    global $koneksi;

    echo "Database Connection Test:\n";
    echo "Host: " . DB_HOST . "\n";
    echo "Database: " . DB_NAME . "\n";
    echo "Connected: " . ($koneksi->ping() ? "Yes" : "No") . "\n\n";

    echo "Tables in database:\n";
    $result = $koneksi->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        echo "- " . $row[0] . "\n";
    }

    echo "\nChecking schedules table:\n";
    $result = $koneksi->query("DESCRIBE schedules");
    while ($row = $result->fetch_assoc()) {
        echo $row['Field'] . " - " . $row['Type'] . " - " . $row['Null'] . " - " . $row['Default'] . "\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
