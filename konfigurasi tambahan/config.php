<?php
// config.php - Konfigurasi Global Sistem

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

// Database Configuration (gunakan dari koneksi.php)
require_once __DIR__ . '/../koneksi.php';

// Site Configuration
if (!defined('SITE_NAME')) define('SITE_NAME', 'Sugeng Rahayu');
if (!defined('SITE_URL')) define('SITE_URL', 'http://localhost/UAS'); // Sesuaikan dengan URL Anda
if (!defined('ADMIN_EMAIL')) define('ADMIN_EMAIL', 'admin@sugengrahayu.com');

// Upload Configuration
if (!defined('UPLOAD_PATH')) define('UPLOAD_PATH', __DIR__ . '/uploads/');
if (!defined('MAX_FILE_SIZE')) define('MAX_FILE_SIZE', 5242880); // 5MB
if (!defined('ALLOWED_IMAGE_TYPES')) define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/jpg']);

// Pagination
if (!defined('ITEMS_PER_PAGE')) define('ITEMS_PER_PAGE', 10);

// Booking Configuration
if (!defined('BOOKING_CODE_PREFIX')) define('BOOKING_CODE_PREFIX', 'SR');
if (!defined('CANCELLATION_REFUND_PERCENTAGE')) define('CANCELLATION_REFUND_PERCENTAGE', 80); // 80% refund

// Email Configuration (untuk fitur email nantinya)
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'smtp.gmail.com');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 587);
if (!defined('SMTP_USER')) define('SMTP_USER', 'your-email@gmail.com');
if (!defined('SMTP_PASS')) define('SMTP_PASS', 'your-password');
if (!defined('SMTP_FROM')) define('SMTP_FROM', 'noreply@sugengrahayu.com');
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'Sugeng Rahayu');

// Color Palette (dari header.php)
$primary_blue = "#001BB7";
$secondary_blue = "#0046FF";
$accent_orange = "#FF8040";
$light_cream = "#F5F1DC";

// Helper Functions
function base_url($path = '') {
    return SITE_URL . '/' . ltrim($path, '/');
}

function asset_url($path = '') {
    return base_url('assets/' . ltrim($path, '/'));
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['role']) && in_array($_SESSION['role'], ['admin', 'staff']);
}

function get_logged_in_user() {
    if (!is_logged_in()) {
        return null;
    }
    
    global $koneksi;
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    return mysqli_fetch_assoc($result);
}

// CSRF Protection
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

// Security Functions
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function sanitize_email($email) {
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Flash Messages
function set_flash($type, $message) {
    $_SESSION['flash'][$type] = $message;
}

function get_flash($type) {
    if (isset($_SESSION['flash'][$type])) {
        $message = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $message;
    }
    return null;
}

function display_flash() {
    $types = ['success', 'error', 'warning', 'info'];
    $output = '';
    
    foreach ($types as $type) {
        $message = get_flash($type);
        if ($message) {
            $color = [
                'success' => '#4CAF50',
                'error' => '#f44336',
                'warning' => '#ff9800',
                'info' => '#2196F3'
            ][$type];
            
            $output .= "
            <div style='background: {$color}; color: white; padding: 15px; margin: 20px 0; border-radius: 5px; text-align: center;'>
                {$message}
            </div>";
        }
    }
    
    return $output;
}

// Date & Time Helpers
function format_date($date) {
    return date('d F Y', strtotime($date));
}

function format_datetime($datetime) {
    return date('d F Y H:i', strtotime($datetime));
}

function format_time($time) {
    return date('H:i', strtotime($time));
}

function format_currency($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

// Generate Booking Code
function generate_booking_code() {
    return BOOKING_CODE_PREFIX . date('Ymd') . strtoupper(substr(uniqid(), -6));
}

// Password Helper
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}
?>