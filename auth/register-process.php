<?php
require_once '../config.php';
require_once '../koneksi.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF Protection
    if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
        set_flash('error', 'Token keamanan tidak valid!');
        header("Location: ../beranda/signup.php");
        exit;
    }

    $fullname = sanitize_input($_POST['fullname']);
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $terms = isset($_POST['terms']) ? true : false;

    // Validation
    $errors = [];

    if (empty($fullname) || strlen($fullname) < 2) {
        $errors[] = 'Nama lengkap minimal 2 karakter.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Format email tidak valid.';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Konfirmasi password tidak cocok!';
    }

    if (!$terms) {
        $errors[] = 'Anda harus menyetujui syarat dan ketentuan.';
    }

    if (!empty($errors)) {
        set_flash('error', implode('<br>', $errors));
        header("Location: ../beranda/signup.php");
        exit;
    }

    // Check if email already exists
    $check_email = mysqli_query($koneksi, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check_email) > 0) {
        set_flash('error', 'Email sudah digunakan!');
        header("Location: ../beranda/signup.php");
        exit;
    }

    // Generate unique username from fullname
    $base_username = strtolower(str_replace(' ', '_', $fullname));
    $username = $base_username;
    $counter = 1;
    while (mysqli_num_rows(mysqli_query($koneksi, "SELECT id FROM users WHERE username = '$username'")) > 0) {
        $username = $base_username . '_' . $counter;
        $counter++;
    }

    // Hash Password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert into Database
    $query = "INSERT INTO users (username, full_name, email, password, role, status, created_at)
              VALUES ('$username', '$fullname', '$email', '$hashed_password', 'user', 'active', NOW())";

    if (mysqli_query($koneksi, $query)) {
        // Log activity
        $user_id = mysqli_insert_id($koneksi);
        log_activity($user_id, 'user_registered', "User registered with email: $email");

        set_flash('success', 'Registrasi berhasil! Silakan login.');
        header("Location: ../beranda/signin.php");
    } else {
        // Debug: echo error
        echo "Database Error: " . mysqli_error($koneksi);
        exit;
    }
}
