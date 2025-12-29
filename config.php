  <?php
// config.php - Konfigurasi Global Sistem Bus Sugeng Rahayu

// Timezone
date_default_timezone_set('Asia/Jakarta');

// Error Reporting (Development mode)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Configuration and Start
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_secure', 0); // Set 1 jika menggunakan HTTPS
    session_start();
}

// Database Configuration
if (!defined('DB_HOST')) define('DB_HOST', 'localhost');
if (!defined('DB_USER')) define('DB_USER', 'root');
if (!defined('DB_PASS')) define('DB_PASS', '');
if (!defined('DB_NAME')) define('DB_NAME', 'sugeng');

// Site Configuration
if (!defined('SITE_NAME')) define('SITE_NAME', 'Bus Sugeng Rahayu');
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost/UAS');
if (!defined('SITE_EMAIL')) define('SITE_EMAIL', 'info@sugengrrahayu.com');

// Base URL function
if (!function_exists('base_url')) {
    function base_url($path = '') {
        return SITE_URL . '/' . ltrim($path, '/');
    }
}

// Color Palette
$primary_blue = "#001BB7";
$secondary_blue = "#0033A0";
$accent_orange = "#FF6B35";
$light_cream = "#F5F5DC";

// Pagination
if (!defined('ITEMS_PER_PAGE')) define('ITEMS_PER_PAGE', 10);

// Upload Configuration
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', 'uploads/');
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB

// Currency Format
if (!function_exists('format_currency')) {
    function format_currency($amount) {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

// Date Format (Indonesian)
if (!function_exists('format_date')) {
    function format_date($date) {
        $months = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $timestamp = strtotime($date);
        $formatted = date('d F Y', $timestamp);

        foreach ($months as $en => $id) {
            $formatted = str_replace($en, $id, $formatted);
        }

        return $formatted;
    }
}

// Time Format
if (!function_exists('format_time')) {
    function format_time($time) {
        return date('H:i', strtotime($time));
    }
}

// Authentication Functions
if (!function_exists('is_logged_in')) {
    function is_logged_in() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}

if (!function_exists('require_login')) {
    function require_login() {
        if (!is_logged_in()) {
            header("Location: " . SITE_URL . "/beranda/signin.php");
            exit;
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin() {
        if (!is_admin()) {
            header("Location: " . SITE_URL . "/beranda/index.php");
            exit;
        }
    }
}

// Flash Messages
if (!function_exists('set_flash')) {
    function set_flash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
}

if (!function_exists('get_flash')) {
    function get_flash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}

if (!function_exists('display_flash')) {
    function display_flash() {
        $flash = get_flash();
        if ($flash) {
            $class = $flash['type'] === 'success' ? 'alert-success' : 'alert-danger';
            echo "<div class='alert {$class}' style='padding: 15px; margin: 20px 0; border-radius: 5px; background: " . ($flash['type'] === 'success' ? '#d4edda' : '#f8d7da') . "; color: " . ($flash['type'] === 'success' ? '#155724' : '#721c24') . "; border: 1px solid " . ($flash['type'] === 'success' ? '#c3e6cb' : '#f5c6cb') . ";'>";
            echo "<strong>" . ($flash['type'] === 'success' ? 'Berhasil!' : 'Error!') . "</strong> " . $flash['message'];
            echo "</div>";
        }
    }
}

// CSRF Protection
if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token() {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}

// Security Functions
if (!function_exists('sanitize_input')) {
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

if (!function_exists('csrf_field')) {
    function csrf_field() {
        $token = generate_csrf_token();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
    }
}

if (!function_exists('generate_unique_code')) {
    function generate_unique_code($prefix = 'SR') {
        return $prefix . '-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 8));
    }
}

if (!function_exists('redirect')) {
    function redirect($url) {
        header("Location: " . $url);
        exit;
    }
}

// Email Configuration (placeholder for future SMTP integration)
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', 'your-email@gmail.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', 'your-app-password');



// Include additional functions
require_once 'includes/functions.php';

// Database Connection
try {
    $koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $koneksi->set_charset("utf8mb4");

    if ($koneksi->connect_error) {
        throw new Exception("Connection failed: " . $koneksi->connect_error);
    }
} catch (Exception $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Global Variables for Templates
global $primary_blue, $secondary_blue, $accent_orange, $light_cream;
?>