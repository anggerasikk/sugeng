<?php
// auth/logout.php
require_once '../config.php';

// Log activity before destroying session
if (is_logged_in()) {
    $user_id = $_SESSION['user_id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $log_query = "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent) VALUES (?, 'logout', 'User logged out', ?, ?)";
    $log_stmt = mysqli_prepare($koneksi, $log_query);
    mysqli_stmt_bind_param($log_stmt, "iss", $user_id, $ip, $user_agent);
    mysqli_stmt_execute($log_stmt);
}

// Destroy session
session_unset();
session_destroy();

// Remove remember me cookie
if (isset($_COOKIE['remember_token'])) {
    setcookie('remember_token', '', time() - 3600, '/');
}

set_flash('success', 'You have been logged out successfully.');
redirect(base_url('index.php'));
?>