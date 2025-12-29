<?php
require_once '../config.php';
require_once '../koneksi.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Token keamanan tidak valid!');
        header("Location: ../beranda/signin.php");
        exit;
    }

    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) ? true : false;

    // Validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        set_flash('error', 'Format email tidak valid.');
        header("Location: ../beranda/signin.php");
        exit;
    }

    if (empty($password)) {
        set_flash('error', 'Password tidak boleh kosong.');
        header("Location: ../beranda/signin.php");
        exit;
    }

    // Query to find user by email
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email' AND status = 'active'");

    if (mysqli_num_rows($query) == 1) {
        $user = mysqli_fetch_assoc($query);

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_role'] = $user['role'];

            // Remember me functionality
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (30 * 24 * 60 * 60), '/', '', true, true); // 30 days
                // Store token in database (assuming you have a remember_tokens table)
                mysqli_query($koneksi, "INSERT INTO remember_tokens (user_id, token, expires_at) VALUES ('{$user['id']}', '$token', DATE_ADD(NOW(), INTERVAL 30 DAY))");
            }

            // Log activity
            log_activity($user['id'], 'user_login', "User logged in with email: $email");

            // Redirect based on role
            if ($user['role'] == 'admin') {
                header("Location: ../admin/index.php");
            } else {
                header("Location: ../beranda/index.php");
            }
            exit;
        } else {
            // Debug
            echo "Password salah untuk email: $email";
            exit;
        }
    } else {
        // Debug
        echo "Email tidak ditemukan atau akun tidak aktif: $email";
        exit;
    }
}
