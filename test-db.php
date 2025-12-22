<?php
require_once 'koneksi.php';

if ($koneksi) {
    echo "<h2 style='color: green;'>✅ Koneksi Database Berhasil!</h2>";

    // Cek apakah database ada
    $result = mysqli_query($koneksi, "SHOW TABLES");
    if ($result) {
        $tables = mysqli_fetch_all($result, MYSQLI_ASSOC);
        echo "<h3>Tabel yang ada di database 'sugeng':</h3>";
        echo "<ul>";
        foreach ($tables as $table) {
            echo "<li>" . reset($table) . "</li>";
        }
        echo "</ul>";
    } else {
        echo "<h3 style='color: red;'>❌ Database 'sugeng' belum memiliki tabel!</h3>";
        echo "<p>Silakan import schema database terlebih dahulu.</p>";
    }
} else {
    echo "<h2 style='color: red;'>❌ Koneksi Database Gagal!</h2>";
    echo "<p>Error: " . mysqli_connect_error() . "</p>";
}
?>