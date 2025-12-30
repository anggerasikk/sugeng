<?php
require_once 'config.php';

$query = "SELECT id, name, capacity FROM bus_types";
$result = mysqli_query($koneksi, $query);

if ($result) {
    echo "Current bus types capacities:\n";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . ", Name: " . $row['name'] . ", Capacity: " . $row['capacity'] . "\n";
    }
} else {
    echo "Error fetching data: " . mysqli_error($koneksi);
}
?>
