<?php
require_once '../config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu');
    redirect(base_url('signin.php'));
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect(base_url('jadwal.php'));
}

// Verify CSRF token (pastikan function ini ada di functions.php)
if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token($token) {
        return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
    }
}

if (!verify_csrf_token($_POST['csrf_token'] ?? '')) {
    set_flash('error', 'Invalid security token');
    redirect(base_url('jadwal.php'));
}

// Fungsi clean_input alternatif jika tidak ada
if (!function_exists('clean_input')) {
    function clean_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        return $data;
    }
}

// Get form data
$schedule_id = intval($_POST['schedule_id'] ?? 0);
$travel_date = clean_input($_POST['travel_date'] ?? '');
$num_passengers = intval($_POST['num_passengers'] ?? 1);
$selected_seats = clean_input($_POST['selected_seats'] ?? '');
$passenger_name = clean_input($_POST['passenger_name'] ?? '');
$passenger_identity = clean_input($_POST['passenger_identity'] ?? '');
$passenger_phone = clean_input($_POST['passenger_phone'] ?? '');

// Validate
if (!$schedule_id || !$selected_seats || !$passenger_name) {
    set_flash('error', 'Data tidak lengkap');
    redirect(base_url('jadwal.php'));
}

// Get schedule details
$query = "SELECT * FROM schedules WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $schedule_id);
mysqli_stmt_execute($stmt);
$schedule = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$schedule) {
    set_flash('error', 'Jadwal tidak ditemukan');
    redirect(base_url('jadwal.php'));
}

$total_price = $schedule['price'] * $num_passengers;

// Store booking data in session for payment processing
$_SESSION['pending_booking'] = [
    'schedule_id' => $schedule_id,
    'travel_date' => $travel_date,
    'num_passengers' => $num_passengers,
    'selected_seats' => $selected_seats,
    'passenger_name' => $passenger_name,
    'passenger_identity' => $passenger_identity,
    'passenger_phone' => $passenger_phone,
    'passenger_email' => $_SESSION['user_email'],
    'total_price' => $total_price,
    'schedule' => $schedule,
    'origin' => $schedule['origin'],
    'destination' => $schedule['destination']
];

// Fungsi generate_booking_code jika tidak ada
if (!function_exists('generate_booking_code')) {
    function generate_booking_code() {
        return 'SR' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    }
}

// Fungsi csrf_field jika tidak ada
if (!function_exists('csrf_field')) {
    function csrf_field() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return '<input type="hidden" name="csrf_token" value="' . $_SESSION['csrf_token'] . '">';
    }
}

include '../header.php';
?>

<!-- CSS dan HTML TETAP SAMA seperti file Anda -->
<!-- ... kode CSS dan HTML yang sudah ada ... -->

<style>
    .payment-container {
        max-width: 900px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .payment-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: center;
    }

    .payment-header h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .booking-code-box {
        background: rgba(255,255,255,0.2);
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }

    .booking-code {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        color: <?php echo $accent_orange; ?>;
    }

    .payment-content {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .payment-info {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .section-title {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.3rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid <?php echo $accent_orange; ?>;
    }

    .bank-card {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        padding: 25px;
        border-radius: 12px;
        color: white;
        margin-bottom: 20px;
    }

    .bank-logo {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 20px;
    }

    .bank-number {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 3px;
        margin: 15px 0;
        font-family: 'Courier New', monospace;
    }

    .bank-name {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .payment-notes {
        background: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .payment-notes h4 {
        color: #856404;
        margin-bottom: 10px;
    }

    .payment-notes ul {
        margin-left: 20px;
        color: #856404;
    }

    .payment-notes li {
        margin-bottom: 8px;
    }

    .upload-section {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .upload-area {
        border: 3px dashed #e0e0e0;
        border-radius: 12px;
        padding: 40px;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
        background: #f8f9fa;
    }

    .upload-area:hover {
        border-color: <?php echo $accent_orange; ?>;
        background: <?php echo $light_cream; ?>;
    }

    .upload-area.dragover {
        border-color: <?php echo $accent_orange; ?>;
        background: <?php echo $light_cream; ?>;
    }

    .upload-icon {
        font-size: 4rem;
        margin-bottom: 15px;
        color: <?php echo $primary_blue; ?>;
    }

    .file-input {
        display: none;
    }

    .upload-label {
        display: block;
        cursor: pointer;
    }

    .file-preview {
        margin-top: 20px;
        display: none;
    }

    .preview-image {
        max-width: 100%;
        border-radius: 8px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .file-info {
        margin-top: 10px;
        padding: 10px;
        background: #e3f2fd;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .summary-box {
        background: <?php echo $light_cream; ?>;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #ddd;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-total {
        font-size: 1.3rem;
        font-weight: 700;
        color: <?php echo $accent_orange; ?>;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 2px solid #ddd;
    }

    .btn-submit {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 768px) {
        .payment-content {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="payment-container">
    <?php echo display_flash(); ?>

    <div class="payment-header">
        <h1>💳 Informasi Pembayaran</h1>
        <p>Silakan transfer ke rekening di bawah ini</p>
        <div class="booking-code-box">
            <div style="font-size: 0.9rem; opacity: 0.9;">Kode Booking Anda:</div>
            <div class="booking-code"><?php echo generate_booking_code(); ?></div>
        </div>
    </div>

    <form id="paymentForm" action="process-payment.php" method="POST" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="payment-content">
            <!-- Left Column: Payment Info -->
            <div>
                <div class="payment-info">
                    <h3 class="section-title">🏦 Transfer ke Rekening</h3>

                    <div class="bank-card">
                        <div class="bank-logo">🏦 BANK MANDIRI</div>
                        <div class="bank-number">1234 5678 9012 3456</div>
                        <div class="bank-name">a.n. PT SUGENG RAHAYU TRANSPORT</div>
                    </div>

                    <div class="bank-card" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                        <div class="bank-logo">🏦 BANK BCA</div>
                        <div class="bank-number">9876 5432 1098 7654</div>
                        <div class="bank-name">a.n. PT SUGENG RAHAYU TRANSPORT</div>
                    </div>

                    <div class="bank-card" style="background: linear-gradient(135deg, #27ae60, #229954);">
                        <div class="bank-logo">🏦 BANK BNI</div>
                        <div class="bank-number">5555 6666 7777 8888</div>
                        <div class="bank-name">a.n. PT SUGENG RAHAYU TRANSPORT</div>
                    </div>
                </div>

                <div class="payment-notes">
                    <h4>⚠️ Penting!</h4>
                    <ul>
                        <li>Transfer sesuai <strong>TOTAL NOMINAL</strong> yang tertera</li>
                        <li>Upload bukti transfer yang <strong>JELAS</strong> dan <strong>LENGKAP</strong></li>
                        <li>Booking akan dikonfirmasi dalam <strong>max 1x24 jam</strong></li>
                        <li>E-Ticket akan dikirim setelah pembayaran terverifikasi</li>
                        <li>Simpan kode booking untuk tracking status</li>
                    </ul>
                </div>
            </div>

            <!-- Right Column: Upload & Summary -->
            <div>
                <div class="upload-section">
                    <h3 class="section-title">📤 Upload Bukti Pembayaran</h3>

                    <div class="summary-box">
                        <div class="summary-item">
                            <span>Rute:</span>
                            <strong><?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?></strong>
                        </div>
                        <div class="summary-item">
                            <span>Tanggal:</span>
                            <strong><?php echo format_date($travel_date); ?></strong>
                        </div>
                        <div class="summary-item">
                            <span>Kursi:</span>
                            <strong><?php echo htmlspecialchars($selected_seats); ?></strong>
                        </div>
                        <div class="summary-item">
                            <span>Penumpang:</span>
                            <strong><?php echo $num_passengers; ?> orang</strong>
                        </div>
                        <div class="summary-item">
                            <span>Harga per Kursi:</span>
                            <strong><?php echo format_currency($schedule['price']); ?></strong>
                        </div>
                        <div class="summary-item summary-total">
                            <span>TOTAL BAYAR:</span>
                            <strong><?php echo format_currency($total_price); ?></strong>
                        </div>
                    </div>

                    <div class="upload-area" id="uploadArea">
                        <label for="payment_proof" class="upload-label">
                            <div class="upload-icon">📸</div>
                            <h4>Klik atau Drag & Drop</h4>
                            <p>Upload bukti transfer (JPG, PNG, max 2MB)</p>
                        </label>
                        <input type="file" name="payment_proof" id="payment_proof" class="file-input" accept="image/*" required>
                    </div>

                    <div class="file-preview" id="filePreview">
                        <img id="previewImage" class="preview-image" alt="Preview">
                        <div class="file-info" id="fileInfo"></div>
                    </div>

                    <button type="submit" class="btn-submit" id="btnSubmit" disabled style="margin-top: 20px;">
                        ⏳ Upload file terlebih dahulu
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const uploadArea = document.getElementById('uploadArea');
const fileInput = document.getElementById('payment_proof');
const filePreview = document.getElementById('filePreview');
const previewImage = document.getElementById('previewImage');
const fileInfo = document.getElementById('fileInfo');
const btnSubmit = document.getElementById('btnSubmit');

// Click to upload
uploadArea.addEventListener('click', () => {
    fileInput.click();
});

// File input change
fileInput.addEventListener('change', handleFileSelect);

// Drag & drop
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        fileInput.files = files;
        handleFileSelect();
    }
});

function handleFileSelect() {
    const file = fileInput.files[0];
    
    if (!file) return;
    
    // Validate file type
    if (!file.type.startsWith('image/')) {
        alert('Hanya file gambar yang diperbolehkan');
        fileInput.value = '';
        return;
    }
    
    // Validate file size (2MB)
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran file maksimal 2MB');
        fileInput.value = '';
        return;
    }
    
    // Preview image
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImage.src = e.target.result;
        filePreview.style.display = 'block';
        uploadArea.style.display = 'none';
        
        // Show file info
        fileInfo.innerHTML = `
            <strong>File:</strong> ${file.name}<br>
            <strong>Size:</strong> ${(file.size / 1024).toFixed(2)} KB<br>
            <button type="button" onclick="removeFile()" style="margin-top:10px; background:#f44336; color:white; border:none; padding:8px 15px; border-radius:5px; cursor:pointer;">
                🗑️ Hapus
            </button>
        `;
        
        // Enable submit button
        btnSubmit.disabled = false;
        btnSubmit.textContent = '✅ Kirim Bukti Pembayaran';
    };
    reader.readAsDataURL(file);
}

function removeFile() {
    fileInput.value = '';
    filePreview.style.display = 'none';
    uploadArea.style.display = 'block';
    btnSubmit.disabled = true;
    btnSubmit.textContent = '⏳ Upload file terlebih dahulu';
}

// Form submit
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    if (!fileInput.files[0]) {
        e.preventDefault();
        alert('Harap upload bukti pembayaran');
        return false;
    }
    
    btnSubmit.disabled = true;
    btnSubmit.textContent = '⏳ Sedang diproses...';
});
</script>

<?php include '../footer.php'; ?>