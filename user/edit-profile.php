<?php
require_once '../config.php';
include '../header.php';

$user_id = $_SESSION['user_id'];
$user = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$user_id'"));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($koneksi, $_POST['name']);
    $phone = mysqli_real_escape_string($koneksi, $_POST['phone']);
    
    mysqli_query($koneksi, "UPDATE users SET name = '$name', phone = '$phone' WHERE id = '$user_id'");
    set_flash('success', 'Profil berhasil diperbarui!');
    header("Location: profile.php");
    exit;
}
?>

<div style="max-width: 600px; margin: 50px auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.1);">
    <h2 style="color: #001BB7; margin-bottom: 25px;">Ubah Data Diri</h2>
    <form action="" method="POST">
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Nama Lengkap</label>
            <input type="text" name="name" value="<?php echo $user['name']; ?>" style="width:100%; padding:10px; border:1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Email (Tidak bisa diubah)</label>
            <input type="email" value="<?php echo $user['email']; ?>" disabled style="width:100%; padding:10px; background: #f9f9f9; border:1px solid #ddd; border-radius: 5px;">
        </div>
        <div style="margin-bottom: 25px;">
            <label style="display:block; margin-bottom: 5px; font-weight: bold;">Nomor Telepon</label>
            <input type="text" name="phone" value="<?php echo $user['phone']; ?>" style="width:100%; padding:10px; border:1px solid #ddd; border-radius: 5px;">
        </div>
        <button type="submit" style="width:100%; background: #FF8040; color: white; border: none; padding: 12px; border-radius: 5px; font-weight: bold; cursor: pointer;">Simpan Perubahan</button>
        <a href="profile.php" style="display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none;">Batal</a>
    </form>
</div>
<?php include '../footer.php'; ?>