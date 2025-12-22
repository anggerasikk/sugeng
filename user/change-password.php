<?php
require_once '../config.php';
include '../header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $old_pass = $_POST['old_password'];
    $new_pass = $_POST['new_password'];
    
    $user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT password FROM users WHERE id = '$user_id'"));
    
    if (password_verify($old_pass, $user['password'])) {
        $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
        mysqli_query($koneksi, "UPDATE users SET password = '$hashed' WHERE id = '$user_id'");
        set_flash('success', 'Password berhasil diganti!');
        header("Location: profile.php");
    } else {
        set_flash('error', 'Password lama salah!');
    }
}
?>

<div style="max-width: 500px; margin: 50px auto; background: white; padding: 30px; border-radius: 12px; border-top: 5px solid #001BB7;">
    <h2 style="color: #001BB7; margin-bottom: 20px;">Ganti Password</h2>
    <form action="" method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px;">Password Lama</label>
            <input type="password" name="old_password" required style="width:100%; padding:10px; border:1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 20px;">
            <label style="display:block; margin-bottom: 5px;">Password Baru</label>
            <input type="password" name="new_password" required minlength="8" style="width:100%; padding:10px; border:1px solid #ddd; border-radius: 5px;">
        </div>
        <button type="submit" style="width:100%; background: #001BB7; color: white; border: none; padding: 12px; border-radius: 5px; font-weight: bold; cursor: pointer;">Update Password</button>
    </form>
</div>
<?php include '../footer.php'; ?>