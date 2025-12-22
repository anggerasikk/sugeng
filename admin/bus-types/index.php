<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

include '../header-admin.php';
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

    .bus-types-table {
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

    .bus-type-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: <?php echo $primary_blue; ?>;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
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

    .capacity-info {
        background: #f8f9fa;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        color: <?php echo $primary_blue; ?>;
        display: inline-block;
    }

    @media (max-width: 768px) {
        .admin-content {
            margin-left: 0;
        }
    }
</style>

<div class="admin-content">
    <div class="admin-header">
        <h1>🚌 Manajemen Tipe Bus</h1>
        <p>Kelola berbagai tipe bus yang tersedia</p>
    </div>

    <!-- Bus Types Table -->
    <div class="bus-types-table">
        <div class="table-header">
            📋 Daftar Tipe Bus
            <a href="add.php" class="btn-add">➕ Tambah Tipe Bus</a>
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Tipe Bus</th>
                        <th>Kapasitas</th>
                        <th>Fasilitas</th>
                        <th>Harga Tambahan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT * FROM bus_types ORDER BY created_at DESC";
                    $result = mysqli_query($koneksi, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($bus_type = mysqli_fetch_assoc($result)) {
                            $status_class = $bus_type['is_active'] ? 'status-active' : 'status-inactive';
                            $status_text = $bus_type['is_active'] ? 'Aktif' : 'Tidak Aktif';

                            // Parse facilities
                            $facilities = json_decode($bus_type['facilities'], true);
                            $facilities_text = '';
                            if ($facilities) {
                                $facility_names = [];
                                if (isset($facilities['ac'])) $facility_names[] = 'AC';
                                if (isset($facilities['wifi'])) $facility_names[] = 'WiFi';
                                if (isset($facilities['toilet'])) $facility_names[] = 'Toilet';
                                if (isset($facilities['entertainment'])) $facility_names[] = 'Entertainment';
                                if (isset($facilities['reclining_seats'])) $facility_names[] = 'Kursi Reclining';
                                $facilities_text = implode(', ', $facility_names);
                            }
                            ?>
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 15px;">
                                        <div class="bus-type-icon">🚌</div>
                                        <div>
                                            <div style="font-weight: 500;"><?php echo htmlspecialchars($bus_type['name']); ?></div>
                                            <div style="font-size: 0.8rem; color: #666;"><?php echo htmlspecialchars($bus_type['description']); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="capacity-info"><?php echo $bus_type['capacity']; ?> penumpang</span>
                                </td>
                                <td><?php echo htmlspecialchars($facilities_text); ?></td>
                                <td><?php echo format_currency($bus_type['additional_price']); ?></td>
                                <td><span class="<?php echo $status_class; ?>"><?php echo $status_text; ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $bus_type['id']; ?>" class="btn-action btn-edit">✏️ Edit</a>
                                        <a href="delete.php?id=<?php echo $bus_type['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Yakin ingin menghapus tipe bus ini?')">🗑️ Hapus</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 40px;">
                                <p style="color: #666; margin: 0;">Belum ada data tipe bus</p>
                                <a href="add.php" class="btn-add" style="margin-top: 10px; display: inline-block;">Tambah Tipe Bus Pertama</a>
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
