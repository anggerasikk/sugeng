<?php
require_once 'config.php';

echo "<h1>Setup Database Bus Sugeng Rahayu</h1>";

try {
    // Baca file SQL
    $sql = file_get_contents('database_schema.sql');

    if (!$sql) {
        throw new Exception("Tidak dapat membaca file database_schema.sql");
    }

    // Split SQL commands
    $commands = array_filter(array_map('trim', explode(';', $sql)));

    $success_count = 0;
    $error_count = 0;

    foreach ($commands as $command) {
        if (empty($command) || strpos($command, '--') === 0) continue;

        if ($koneksi->query($command) === TRUE) {
            $success_count++;
        } else {
            echo "<p style='color: red;'>Error executing: " . $command . "<br>Error: " . $koneksi->error . "</p>";
            $error_count++;
        }
    }

    echo "<h2 style='color: green;'>✅ Setup Selesai!</h2>";
    echo "<p>Query berhasil: $success_count</p>";
    echo "<p>Query gagal: $error_count</p>";

    if ($error_count == 0) {
        echo "<p style='color: green; font-weight: bold;'>🎉 Database berhasil di-setup! Silakan akses website.</p>";
        echo "<a href='index.php' style='background: #001BB7; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Ke Website</a>";
    }

} catch (Exception $e) {
    echo "<h2 style='color: red;'>❌ Error: " . $e->getMessage() . "</h2>";
}

$koneksi->close();
?>