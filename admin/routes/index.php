<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

include '../header-admin.php';
include '../sidebar.php';
?>

<style>
    .admin-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .admin-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .admin-header h1 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
    }

    .routes-table {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        background: <?php echo $primary_blue; ?>;
        color: white;
        padding: 15px 20px;
        font-weight: bold;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-add {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .btn-add:hover {
        background: <?php echo $primary_blue; ?>;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #f8f9fa;
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
    }

    .btn-edit {
        background: <?php echo $accent_orange; ?>;
        color: white;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .status-active {
        color: #28a745;
        font-weight: 500;
    }

    .status-inactive {
        color: #6c757d;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .admin-content {
            margin-left: 0;
        }
    }
</style>

<div class="admin-content">
    <div class="admin-header">
        <h1>🛣️ Manajemen Rute</h1>
        <p>Kelola semua rute perjalanan bus</p>
    </div>

    <!-- Routes Table -->
    <div class="routes-table">
        <div class="table-header">
            📋 Daftar Rute
            <a href="add.php" class="btn-add">➕ Tambah Rute</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Jarak (km)</th>
                        <th>Estimasi (jam)</th>
                        <th>Harga Dasar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM routes ORDER BY created_at DESC";
                    $result = mysqli_query($koneksi, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($route = mysqli_fetch_assoc($result)) {
                            $status_class = $route['is_active'] ? 'status-active' : 'status-inactive';
                            $status_text = $route['is_active'] ? 'Aktif' : 'Tidak Aktif';
                            ?>
                            <tr>
                                <td><?php echo $route['id']; ?></td>
                                <td><?php echo htmlspecialchars($route['origin']); ?></td>
                                <td><?php echo htmlspecialchars($route['destination']); ?></td>
                                <td><?php echo $route['distance_km']; ?> km</td>
                                <td><?php echo $route['estimated_hours']; ?> jam</td>
                                <td><?php echo format_currency($route['base_price']); ?></td>
                                <td><span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $route['id']; ?>" class="btn-action btn-edit">✏️ Edit</a>
                                        <a href="delete.php?id=<?php echo $route['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus rute ini?')">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <p style="color: #666; margin: 0;">Belum ada data rute</p>
                                <a href="add.php" class="btn-add" style="margin-top: 10px; display: inline-block;">Tambah Rute Pertama</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../footer-admin.php'; ?>
