<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect(base_url('signin.php'));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && verify_csrf_token($_POST['csrf_token'])) {
    $route_id = sanitize_input($_POST['route_id']);
    $bus_type_id = sanitize_input($_POST['bus_type_id']);
    $departure_date = sanitize_input($_POST['departure_date']);
    $departure_time = sanitize_input($_POST['departure_time']);
    $arrival_time = sanitize_input($_POST['arrival_time']);
    $price = sanitize_input($_POST['price']);
    $status = sanitize_input($_POST['status']);

    // Validate input
    $errors = [];

    if (empty($route_id)) {
        $errors[] = 'Rute harus dipilih.';
    }

    if (empty($bus_type_id)) {
        $errors[] = 'Tipe bus harus dipilih.';
    }

    if (empty($departure_date)) {
        $errors[] = 'Tanggal keberangkatan harus diisi.';
    }

    if (empty($departure_time)) {
        $errors[] = 'Waktu keberangkatan harus diisi.';
    }

    if (empty($arrival_time)) {
        $errors[] = 'Waktu kedatangan harus diisi.';
    }

    if (empty($price) || !is_numeric($price) || $price <= 0) {
        $errors[] = 'Harga harus diisi dengan angka positif.';
    }

    if (!in_array($status, ['active', 'cancelled'])) {
        $errors[] = 'Status tidak valid.';
    }

    // Check if schedule already exists for same route, date, and time
    if (empty($errors)) {
        $check_query = "SELECT id FROM schedules WHERE route_id = ? AND departure_date = ? AND departure_time = ?";
        $check_stmt = mysqli_prepare($koneksi, $check_query);
        mysqli_stmt_bind_param($check_stmt, "iss", $route_id, $departure_date, $departure_time);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);

        if (mysqli_num_rows($check_result) > 0) {
            $errors[] = 'Jadwal untuk rute, tanggal, dan waktu yang sama sudah ada.';
        }
    }

    if (empty($errors)) {
        // Insert schedule
        $insert_query = "INSERT INTO schedules (route_id, bus_type_id, departure_date, departure_time, arrival_time, price, status, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = mysqli_prepare($koneksi, $insert_query);
        mysqli_stmt_bind_param($stmt, "iisssds", $route_id, $bus_type_id, $departure_date, $departure_time, $arrival_time, $price, $status);

        if (mysqli_stmt_execute($stmt)) {
            $new_schedule_id = mysqli_insert_id($koneksi);
            set_flash('success', 'Jadwal berhasil ditambahkan.');
            redirect('index.php');
        } else {
            $errors[] = 'Gagal menambahkan jadwal. Silakan coba lagi.';
        }
    }

    if (!empty($errors)) {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../header-admin.php';
?>

<style>
    .create-schedule-content {
        margin-left: 0;
        padding: 20px;
    }

    .create-schedule-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 800px;
        margin: 0 auto;
    }

    .create-schedule-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .create-schedule-header h1 {
        margin: 0;
        font-size: 1.8rem;
    }

    .create-schedule-form {
        padding: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    .form-group input,
    .form-group select {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: <?php echo $primary_blue; ?>;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn-submit {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: <?php echo $primary_blue; ?>;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s;
    }

    .btn-cancel:hover {
        background: #545b62;
    }

    .route-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-top: 10px;
        display: none;
    }

    .route-info.show {
        display: block;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .create-schedule-form {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="create-schedule-content">
    <div class="create-schedule-card">
        <div class="create-schedule-header">
            <h1>🚌 Tambah Jadwal Baru</h1>
        </div>

        <form method="POST" action="" class="create-schedule-form">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="route_id">Rute *</label>
                    <select id="route_id" name="route_id" required>
                        <option value="">Pilih Rute</option>
                        <?php
                        $routes = mysqli_query($koneksi, "SELECT * FROM routes ORDER BY origin, destination");
                        while ($route = mysqli_fetch_assoc($routes)) {
                            $selected = ($_POST['route_id'] ?? '') == $route['id'] ? 'selected' : '';
                            echo "<option value='{$route['id']}' $selected>{$route['origin']} → {$route['destination']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="bus_type_id">Tipe Bus *</label>
                    <select id="bus_type_id" name="bus_type_id" required>
                        <option value="">Pilih Tipe Bus</option>
                        <?php
                        $bus_types = mysqli_query($koneksi, "SELECT * FROM bus_types ORDER BY name");
                        while ($bus_type = mysqli_fetch_assoc($bus_types)) {
                            $selected = ($_POST['bus_type_id'] ?? '') == $bus_type['id'] ? 'selected' : '';
                            echo "<option value='{$bus_type['id']}' $selected>{$bus_type['name']} (Kapasitas: {$bus_type['capacity']})</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="departure_date">Tanggal Keberangkatan *</label>
                    <input type="date" id="departure_date" name="departure_date" value="<?php echo htmlspecialchars($_POST['departure_date'] ?? ''); ?>" required min="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="departure_time">Waktu Keberangkatan *</label>
                    <input type="time" id="departure_time" name="departure_time" value="<?php echo htmlspecialchars($_POST['departure_time'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="arrival_time">Waktu Kedatangan *</label>
                    <input type="time" id="arrival_time" name="arrival_time" value="<?php echo htmlspecialchars($_POST['arrival_time'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="price">Harga (Rp) *</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" required min="0" step="1000" placeholder="0">
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" <?php echo (($_POST['status'] ?? 'active') === 'active' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="cancelled" <?php echo (($_POST['status'] ?? '') === 'cancelled' ? 'selected' : ''); ?>>Tidak Aktif</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Simpan Jadwal</button>
                <a href="index.php" class="btn-cancel">❌ Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-format price input
document.getElementById('price').addEventListener('input', function(e) {
    // Remove non-numeric characters except decimal point
    let value = this.value.replace(/[^\d]/g, '');
    this.value = value;
});

// Update arrival time validation based on departure time
document.getElementById('departure_time').addEventListener('change', function() {
    const arrivalInput = document.getElementById('arrival_time');
    if (this.value) {
        arrivalInput.min = this.value;
    } else {
        arrivalInput.removeAttribute('min');
    }
});
</script>

<?php include '../footer-admin.php'; ?>
