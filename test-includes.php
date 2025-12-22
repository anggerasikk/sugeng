<?php
echo "<h1>Test Include Files</h1>";

// Test config.php
try {
    require_once 'config.php';
    echo "<p style='color: green;'>✅ config.php loaded successfully</p>";
    echo "<p>Site Name: " . SITE_NAME . "</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ config.php error: " . $e->getMessage() . "</p>";
}

// Test header.php
try {
    ob_start();
    include 'header.php';
    $header_content = ob_get_clean();
    echo "<p style='color: green;'>✅ header.php loaded successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ header.php error: " . $e->getMessage() . "</p>";
}

// Test footer.php
try {
    ob_start();
    include 'footer.php';
    $footer_content = ob_get_clean();
    echo "<p style='color: green;'>✅ footer.php loaded successfully</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ footer.php error: " . $e->getMessage() . "</p>";
}

// Test koneksi database
try {
    if (isset($koneksi) && $koneksi->ping()) {
        echo "<p style='color: green;'>✅ Database connection successful</p>";

        // Cek tabel
        $result = $koneksi->query("SHOW TABLES");
        $tables = [];
        while ($row = $result->fetch_array()) {
            $tables[] = $row[0];
        }
        echo "<p>Tabel yang ada: " . implode(', ', $tables) . "</p>";
    } else {
        echo "<p style='color: red;'>❌ Database connection failed</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database error: " . $e->getMessage() . "</p>";
}

echo "<br><a href='index.php'>Kembali ke Home</a>";
?>