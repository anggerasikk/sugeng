<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Akses ditolak. Anda harus login sebagai admin.');
    redirect('../signin.php');
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    set_flash('error', 'ID booking diperlukan.');
    redirect('index.php');
}

$booking_id = (int)$_GET['id'];

// Get booking data with related information
$query = "SELECT b.*, u.full_name as user_name, u.email as user_email, u.phone as user_phone,
         s.departure_date, s.departure_time, s.arrival_time,
         r.origin, r.destination, bt.name as bus_type, bt.capacity,
         TIMESTAMPDIFF(HOUR, NOW(), CONCAT(s.departure_date, ' ', s.departure_time)) as hours_until_departure
         FROM bookings b
         LEFT JOIN users u ON b.user_id = u.id
         LEFT JOIN schedules s ON b.schedule_id = s.id
         LEFT JOIN routes r ON s.route_id = r.id
         LEFT JOIN bus_types bt ON s.bus_type_id = bt.id
         WHERE b.id = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $booking_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    set_flash('error', 'Booking tidak ditemukan.');
    redirect('index.php');
}

$booking = mysqli_fetch_assoc($result);

include '../header-admin.php';
?>

<style>
    .booking-detail-content {
        margin-top: 70px;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .booking-detail-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 1000px;
        margin: 0 auto;
    }

    .booking-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .booking-code {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: 1px;
    }

    .booking-route {
        font-size: 1.2rem;
        opacity: 0.9;
        margin: 10px 0 0;
    }

    .checkin-status {
        margin-top: 20px;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .checkin-checked-in {
        background: #d4edda;
        color: #155724;
        border: 2px solid #28a745;
    }

    .checkin-not-checked-in {
        background: #fff3cd;
        color: #856404;
        border: 2px solid #ffc107;
    }

    .booking-info {
        padding: 30px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid <?php echo $primary_blue; ?>;
    }

    .info-section h3 {
        color: <?php echo $primary_blue; ?>;
        margin: 0 0 15px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #666;
    }

    .info-value {
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
        text-align: right;
    }

    .status-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-confirmed {
        background: #d4edda;
        color: #155724;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    .status-completed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .status-checked_in {
        background: #28a745;
        color: white;
    }

    .payment-status-box {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        border: 1px solid;
        border-left: 3px solid;
    }

    .payment-pending {
        background: #fff3cd;
        color: #856404;
        border-color: #ffeaa7;
        border-left-color: #ffc107;
    }

    .payment-paid {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
        border-left-color: #28a745;
    }

    .payment-cancelled {
        background: #f8d7da;
        color: #721c24;
        border-color: #f5c6cb;
        border-left-color: #dc3545;
    }

    .booking-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        padding: 20px 30px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
        font-size: 0.9rem;
    }

    .btn-checkin {
        background: #28a745;
        color: white;
    }

    .btn-checkin:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .btn-checkin:disabled {
        background: #6c757d;
        cursor: not-allowed;
        transform: none;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #545b62;
        transform: translateY(-2px);
    }

    .btn-edit {
        background: <?php echo $accent_orange; ?>;
        color: white;
    }

    .btn-edit:hover {
        background: <?php echo $primary_blue; ?>;
        transform: translateY(-2px);
    }

    .seat-info {
        background: #e9ecef;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
        text-align: center;
    }

    .seat-numbers {
        font-size: 1.2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .booking-actions {
            flex-direction: column;
        }

        .btn-action {
            text-align: center;
        }
    }
</style>

<div class="booking-detail-content">
    <div class="booking-detail-card">
        <div class="booking-header">
            <h1 class="booking-code"><?php echo htmlspecialchars($booking['booking_code']); ?></h1>
            <p class="booking-route"><?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?></p>

            <!-- Check-in Status -->
            <div class="checkin-status <?php echo $booking['booking_status'] === 'checked_in' ? 'checkin-checked-in' : 'checkin-not-checked-in'; ?>">
                <?php if ($booking['booking_status'] === 'checked_in'): ?>
                    ✅ <strong>User Sudah Check-in</strong>
                    <br>
                    <small>Waktu check-in: <?php echo format_datetime($booking['checked_in_at']); ?></small>
                <?php else: ?>
                    ❌ <strong>User Belum Check-in</strong>
                <?php endif; ?>
            </div>
        </div>

        <div class="booking-info">
            <div class="info-grid">
                <div class="info-section">
                    <h3>👤 Informasi Penumpang</h3>
                    <div class="info-item">
                        <span class="info-label">Nama:</span>
                        <span class="info-value"><?php echo htmlspecialchars($booking['passenger_name']); ?></span>
                    </div>
                    <?php if (!empty($booking['user_name'])): ?>
                        <div class="info-item">
                            <span class="info-label">User:</span>
                            <span class="info-value"><?php echo htmlspecialchars($booking['user_name']); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($booking['user_email']); ?></span>
                        </div>
                        <?php if (!empty($booking['user_phone'])): ?>
                            <div class="info-item">
                                <span class="info-label">Telepon:</span>
                                <span class="info-value"><?php echo htmlspecialchars($booking['user_phone']); ?></span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <div class="info-section">
                    <h3>🚌 Detail Perjalanan</h3>
                    <div class="info-item">
                        <span class="info-label">Rute:</span>
                        <span class="info-value"><?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal:</span>
                        <span class="info-value"><?php echo format_date($booking['departure_date']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu Berangkat:</span>
                        <span class="info-value"><?php echo format_time($booking['departure_time']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Waktu Tiba:</span>
                        <span class="info-value"><?php echo format_time($booking['arrival_time']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tipe Bus:</span>
                        <span class="info-value"><?php echo htmlspecialchars($booking['bus_type']); ?></span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>📋 Status Booking</h3>
                    <div class="info-item">
                        <span class="info-label">Status Booking:</span>
                        <span class="status-badge status-<?php echo $booking['booking_status']; ?>"><?php echo ucfirst(str_replace('_', ' ', $booking['booking_status'])); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status Pembayaran:</span>
                        <div class="payment-status-box payment-<?php echo $booking['payment_status']; ?>">
                            <?php
                            $payment_text = '';
                            $payment_icon = '';
                            if ($booking['payment_status'] == 'pending') {
                                $payment_text = 'Menunggu Pembayaran';
                                $payment_icon = '💰';
                            } elseif ($booking['payment_status'] == 'paid') {
                                $payment_text = 'Lunas';
                                $payment_icon = '✅';
                            } elseif ($booking['payment_status'] == 'cancelled') {
                                $payment_text = 'Dibatalkan';
                                $payment_icon = '❌';
                            } else {
                                $payment_text = ucfirst($booking['payment_status']);
                                $payment_icon = '⏳';
                            }
                            echo $payment_icon . ' ' . $payment_text;
                            ?>
                        </div>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jumlah Kursi:</span>
                        <span class="info-value"><?php echo $booking['seats_booked']; ?> kursi</span>
                    </div>
                    <?php if (!empty($booking['seat_numbers'])): ?>
                        <div class="seat-info">
                            <strong>Nomor Kursi:</strong>
                            <div class="seat-numbers"><?php echo htmlspecialchars($booking['seat_numbers']); ?></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="info-section">
                    <h3>💰 Informasi Pembayaran</h3>
                    <div class="info-item">
                        <span class="info-label">Total:</span>
                        <span class="info-value"><?php echo format_currency($booking['total_amount']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Dibuat:</span>
                        <span class="info-value"><?php echo format_datetime($booking['created_at']); ?></span>
                    </div>
                    <?php if ($booking['updated_at'] && $booking['updated_at'] != $booking['created_at']): ?>
                        <div class="info-item">
                            <span class="info-label">Diupdate:</span>
                            <span class="info-value"><?php echo format_datetime($booking['updated_at']); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="booking-actions">
            <?php
            // Show check-in button if conditions are met
            $can_checkin = (
                $booking['payment_status'] === 'paid' &&
                $booking['booking_status'] !== 'checked_in' &&
                $booking['booking_status'] !== 'cancelled' &&
                $booking['booking_status'] !== 'completed'
            );
            ?>

            <?php if ($can_checkin): ?>
                <button onclick="checkinBooking(<?php echo $booking['id']; ?>, '<?php echo addslashes($booking['booking_code']); ?>')"
                        class="btn-action btn-checkin"
                        id="checkinBtn">
                    ✅ Check-in User
                </button>
            <?php endif; ?>

            <a href="index.php" class="btn-action btn-back">⬅️ Kembali ke Daftar Booking</a>
        </div>
    </div>
</div>

<script>
// Loading overlay functions
function showLoading() {
    let overlay = document.getElementById('loadingOverlay');
    if (!overlay) {
        overlay = document.createElement('div');
        overlay.id = 'loadingOverlay';
        overlay.className = 'loading-overlay';
        overlay.innerHTML = '<div class="loading-spinner"></div>';
        document.body.appendChild(overlay);
    }
    overlay.style.display = 'flex';
}

function hideLoading() {
    const overlay = document.getElementById('loadingOverlay');
    if (overlay) {
        overlay.style.display = 'none';
    }
}

// Check-in booking function
function checkinBooking(bookingId, bookingCode) {
    if (!confirm(`Apakah Anda yakin ingin check-in booking ${bookingCode}?`)) {
        return;
    }

    const btn = document.getElementById('checkinBtn');
    btn.disabled = true;
    btn.textContent = '⏳ Memproses...';

    showLoading();

    const formData = new FormData();
    formData.append('booking_id', bookingId);

    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'checkin-booking.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.onload = function() {
        hideLoading();
        btn.disabled = false;
        btn.textContent = '✅ Check-in User';

        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    alert('Check-in berhasil!\n' + data.message);

                    // Reload page to show updated status
                    setTimeout(() => {
                        window.location.reload();
                    }, 1000);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (e) {
                alert('Response error: ' + xhr.responseText);
            }
        } else {
            alert('HTTP Error: ' + xhr.status + ' - ' + xhr.statusText);
        }
    };

    xhr.onerror = function() {
        hideLoading();
        btn.disabled = false;
        btn.textContent = '✅ Check-in User';
        alert('Terjadi kesalahan jaringan');
    };

    xhr.send(formData);
}

// Add loading styles if not present
if (!document.getElementById('loadingStyles')) {
    const style = document.createElement('style');
    style.id = 'loadingStyles';
    style.textContent = `
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid <?php echo $primary_blue; ?>;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
}
</script>

<?php include '../footer-admin.php'; ?>
