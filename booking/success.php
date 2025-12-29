<?php
require_once '../config.php';
require_once '../includes/functions.php';
include '../header.php';

$code = $_GET['code'] ?? '';

if (!$code) {
    set_flash('error', 'Kode booking tidak ditemukan.');
    redirect(base_url('jadwal.php'));
}

// Get booking details directly with simple query
global $koneksi;
$booking_query = "SELECT * FROM bookings WHERE booking_code = ?";
$booking_stmt = mysqli_prepare($koneksi, $booking_query);
mysqli_stmt_bind_param($booking_stmt, "s", $code);
mysqli_stmt_execute($booking_stmt);
$booking_result = mysqli_stmt_get_result($booking_stmt);
$booking = mysqli_fetch_assoc($booking_result);

if (!$booking) {
    set_flash('error', 'Booking tidak ditemukan.');
    redirect(base_url('jadwal.php'));
}

// CEK JIKA BOOKING DIBATALKAN
if ($booking['booking_status'] == 'cancelled' || $booking['payment_status'] == 'cancelled') {
    // TAMPILKAN HALAMAN BOOKING DIBATALKAN
    ?>
    <style>
        .cancelled-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .cancelled-card {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .cancelled-header {
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 2px dashed #e0e0e0;
        }

        .cancelled-icon {
            font-size: 80px;
            margin-bottom: 20px;
            color: #dc3545;
        }

        .cancelled-title {
            color: #dc3545;
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .booking-code-display {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
            border: 1px solid #f5c6cb;
        }

        .code-label {
            font-size: 0.9rem;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .code-value {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
            text-align: left;
        }

        @media (max-width: 768px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }

        .detail-box {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 8px;
            border-left: 4px solid #6c757d;
        }

        .detail-title {
            color: #495057;
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
        }

        .detail-item {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-item:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
            font-size: 1.1rem;
        }

        .refund-info {
            background: #e2e3e5;
            border: 1px solid #d6d8db;
            border-left: 4px solid #6c757d;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
            text-align: left;
        }

        .refund-title {
            color: #383d41;
            font-size: 1.2rem;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn-primary {
            background: <?php echo $accent_orange; ?>;
            color: white;
            padding: 15px 40px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            min-width: 200px;
            text-align: center;
        }

        .btn-primary:hover {
            background: #e67300;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
        }

        .btn-secondary {
            color: <?php echo $primary_blue; ?>;
            text-decoration: none;
            font-weight: 600;
            padding: 15px 40px;
            border: 2px solid <?php echo $primary_blue; ?>;
            border-radius: 8px;
            display: inline-block;
            transition: all 0.3s ease;
            min-width: 200px;
            text-align: center;
        }

        .btn-secondary:hover {
            background: <?php echo $primary_blue; ?>;
            color: white;
        }

        .cancelled-reason {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-top: 10px;
            border-left: 3px solid #dc3545;
        }

        .cancelled-reason h4 {
            color: #721c24;
            margin-bottom: 5px;
        }
    </style>

    <div class="cancelled-container">
        <?php echo display_flash(); ?>
        
        <div class="cancelled-card">
            <div class="cancelled-header">
                <div class="cancelled-icon">❌</div>
                <h1 class="cancelled-title">Booking Dibatalkan</h1>
                <p style="color: #666; font-size: 1.1rem;">Booking dengan kode berikut telah dibatalkan</p>
            </div>

            <div class="booking-code-display">
                <div class="code-label">Kode Booking:</div>
                <div class="code-value"><?php echo htmlspecialchars($code); ?></div>
            </div>

            <!-- Get schedule details -->
            <?php
            $schedule_query = "SELECT s.*, r.origin, r.destination, bt.name as bus_type, s.price FROM schedules s JOIN routes r ON s.route_id = r.id JOIN bus_types bt ON s.bus_type_id = bt.id WHERE s.id = ?";
            $schedule_stmt = mysqli_prepare($koneksi, $schedule_query);
            mysqli_stmt_bind_param($schedule_stmt, "i", $booking['schedule_id']);
            mysqli_stmt_execute($schedule_stmt);
            $schedule_result = mysqli_stmt_get_result($schedule_stmt);
            $schedule = mysqli_fetch_assoc($schedule_result);

            if (!$schedule) {
                $schedule = [
                    'origin' => 'Tidak diketahui',
                    'destination' => 'Tidak diketahui',
                    'bus_type' => 'Tidak diketahui',
                    'departure_time' => '00:00:00',
                    'arrival_time' => '00:00:00',
                    'price' => 0
                ];
            }

            // Get cancellation reason if exists
            $cancellation_query = "SELECT * FROM cancellation_requests WHERE booking_id = ?";
            $cancellation_stmt = mysqli_prepare($koneksi, $cancellation_query);
            mysqli_stmt_bind_param($cancellation_stmt, "i", $booking['id']);
            mysqli_stmt_execute($cancellation_stmt);
            $cancellation_result = mysqli_stmt_get_result($cancellation_stmt);
            $cancellation = mysqli_fetch_assoc($cancellation_result);
            ?>

            <!-- Alasan Pembatalan -->
            <?php if ($cancellation): ?>
                <div class="cancelled-reason">
                    <h4>Alasan Pembatalan:</h4>
                    <p><?php echo htmlspecialchars($cancellation['reason'] ?? '-'); ?></p>
                    <?php if (!empty($cancellation['notes'])): ?>
                        <p><strong>Catatan Admin:</strong> <?php echo htmlspecialchars($cancellation['notes']); ?></p>
                    <?php endif; ?>
                    <?php if (!empty($cancellation['processed_at'])): ?>
                        <p><small>Dibuat pada: <?php echo format_date($cancellation['created_at']); ?> 
                        <?php if ($cancellation['processed_at']): ?>
                            | Diproses pada: <?php echo format_date($cancellation['processed_at']); ?>
                        <?php endif; ?>
                        </small></p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="details-grid">
                <!-- Detail Penumpang -->
                <div class="detail-box">
                    <h3 class="detail-title">👤 Detail Penumpang</h3>
                    <div class="detail-item">
                        <div class="detail-label">Nama Lengkap</div>
                        <div class="detail-value"><?php echo htmlspecialchars($booking['passenger_name']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Jumlah Penumpang</div>
                        <div class="detail-value"><?php echo htmlspecialchars($booking['seats_booked']); ?> orang</div>
                    </div>
                </div>

                <!-- Detail Perjalanan -->
                <div class="detail-box">
                    <h3 class="detail-title">🚌 Detail Perjalanan</h3>
                    <div class="detail-item">
                        <div class="detail-label">Rute Perjalanan</div>
                        <div class="detail-value"><?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?></div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Tanggal Keberangkatan</div>
                        <div class="detail-value">
                            <?php 
                            $travel_date = !empty($booking['travel_date']) ? $booking['travel_date'] : 
                                         (!empty($schedule['operational_date']) ? $schedule['operational_date'] : '-');
                            echo format_date($travel_date);
                            ?>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total Pembayaran</div>
                        <div class="detail-value"><?php echo format_currency($booking['total_amount']); ?></div>
                    </div>
                </div>
            </div>

            <!-- Info Refund (jika ada) -->
            <?php if ($booking['payment_status'] == 'refunded' || !empty($cancellation['refund_amount'])): ?>
                <div class="refund-info">
                    <h3 class="refund-title">↩️ Status Pengembalian Dana</h3>
                    <p><strong>Status:</strong> Dana telah dikembalikan</p>
                    <p><strong>Jumlah yang dikembalikan:</strong> <?php echo format_currency($cancellation['refund_amount'] ?? $booking['total_amount']); ?></p>
                    <p><strong>Metode pengembalian:</strong> <?php echo htmlspecialchars($cancellation['refund_method'] ?? 'Transfer Bank'); ?></p>
                    <?php if (!empty($cancellation['refund_reference'])): ?>
                        <p><strong>No. Referensi:</strong> <?php echo htmlspecialchars($cancellation['refund_reference']); ?></p>
                    <?php endif; ?>
                    <p style="margin-top: 10px; font-size: 0.9rem; color: #666;">
                        Dana akan masuk ke rekening Anda dalam 3-5 hari kerja.
                    </p>
                </div>
            <?php elseif ($booking['payment_status'] == 'cancelled'): ?>
                <div class="refund-info">
                    <h3 class="refund-title">💳 Status Pembayaran</h3>
                    <p>Booking ini telah dibatalkan. Tidak ada transaksi yang diproses.</p>
                </div>
            <?php endif; ?>

            <div class="action-buttons">
                <!-- Tombol Booking Lagi -->
                <a href="<?php echo base_url('jadwal.php'); ?>" class="btn-primary">
                    🚌 Booking Lagi
                </a>
                
                <!-- Tombol Lihat Profil -->
                <a href="<?php echo base_url('user/profile.php'); ?>" class="btn-secondary">
                    👤 Kembali ke Profil
                </a>
            </div>
        </div>
    </div>

    <?php
    include '../footer.php';
    exit(); // Stop execution here, don't show success page
}

// =================================================================
// JIKA BOOKING TIDAK DIBATALKAN, TAMPILKAN HALAMAN NORMAL (SUCCESS)
// =================================================================

// Get schedule details for normal success page
$schedule_query = "SELECT s.*, r.origin, r.destination, bt.name as bus_type, s.price FROM schedules s JOIN routes r ON s.route_id = r.id JOIN bus_types bt ON s.bus_type_id = bt.id WHERE s.id = ?";
$schedule_stmt = mysqli_prepare($koneksi, $schedule_query);
mysqli_stmt_bind_param($schedule_stmt, "i", $booking['schedule_id']);
mysqli_stmt_execute($schedule_stmt);
$schedule_result = mysqli_stmt_get_result($schedule_stmt);
$schedule = mysqli_fetch_assoc($schedule_result);

// Jika tidak ada schedule, buat data default
if (!$schedule) {
    $schedule = [
        'origin' => 'Tidak diketahui',
        'destination' => 'Tidak diketahui',
        'bus_type' => 'Tidak diketahui',
        'departure_time' => '00:00:00',
        'arrival_time' => '00:00:00',
        'price' => 0
    ];
}
?>

<!-- NORMAL SUCCESS PAGE (SAMA DENGAN YANG LAMA) -->
<style>
    .success-container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .success-card {
        background: white;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        border: 1px solid #e0e0e0;
    }

    .success-header {
        text-align: center;
        margin-bottom: 40px;
        padding-bottom: 20px;
        border-bottom: 2px dashed #e0e0e0;
    }

    .success-icon {
        font-size: 80px;
        margin-bottom: 20px;
        color: #4CAF50;
    }

    .success-title {
        color: <?php echo $primary_blue; ?>;
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .booking-code-display {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 20px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 30px;
    }

    .code-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 5px;
    }

    .code-value {
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 2px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    @media (max-width: 768px) {
        .details-grid {
            grid-template-columns: 1fr;
        }
    }

    .detail-box {
        background: #f8f9fa;
        padding: 25px;
        border-radius: 8px;
        border-left: 4px solid <?php echo $accent_orange; ?>;
    }

    .detail-title {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.3rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #e0e0e0;
    }

    .detail-item {
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }

    .detail-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .detail-label {
        font-weight: 600;
        color: #555;
        margin-bottom: 5px;
    }

    .detail-value {
        color: #333;
        font-size: 1.1rem;
    }

    .status-box {
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        text-align: center;
    }

    .status-box.pending {
        background: #fff3cd;
        border: 1px solid #ffeaa7;
        border-left: 4px solid #ffc107;
    }

    .status-box.uploaded {
        background: #d4edda;
        border: 1px solid #c3e6cb;
        border-left: 4px solid #28a745;
    }

    .status-title {
        font-size: 1.2rem;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .status-box.pending .status-title {
        color: #856404;
    }

    .status-box.uploaded .status-title {
        color: #155724;
    }

    .status-value {
        font-weight: 600;
        font-size: 1.1rem;
        display: inline-block;
        padding: 5px 15px;
        background: white;
        border-radius: 20px;
        margin-left: 10px;
    }

    .status-box.pending .status-value {
        color: #856404;
    }

    .status-box.uploaded .status-value {
        color: #155724;
    }

    .action-buttons {
        text-align: center;
        margin-top: 30px;
        display: flex;
        justify-content: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .btn-primary {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 15px 40px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        display: inline-block;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        min-width: 200px;
        text-align: center;
    }

    .btn-primary:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    .btn-secondary {
        color: <?php echo $primary_blue; ?>;
        text-decoration: none;
        font-weight: 600;
        padding: 15px 40px;
        border: 2px solid <?php echo $primary_blue; ?>;
        border-radius: 8px;
        display: inline-block;
        transition: all 0.3s ease;
        min-width: 200px;
        text-align: center;
    }

    .btn-secondary:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }
</style>

<div class="success-container">
    <?php echo display_flash(); ?>
    
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">✅</div>
            <h1 class="success-title">Booking Berhasil!</h1>
            <p style="color: #666; font-size: 1.1rem;">Simpan kode booking untuk tracking status</p>
        </div>

        <div class="booking-code-display">
            <div class="code-label">Kode Booking Anda:</div>
            <div class="code-value"><?php echo htmlspecialchars($code); ?></div>
        </div>

        <div class="details-grid">
            <!-- Detail Penumpang -->
            <div class="detail-box">
                <h3 class="detail-title">👤 Detail Penumpang</h3>
                <div class="detail-item">
                    <div class="detail-label">Nama Lengkap</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['passenger_name']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Telepon</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['passenger_phone']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['passenger_email'] ?: '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Identitas</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['passenger_id_number']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Jumlah Penumpang</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['seats_booked']); ?> orang</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Kursi</div>
                    <div class="detail-value"><?php echo htmlspecialchars($booking['seat_numbers'] ?: '-'); ?></div>
                </div>
            </div>

            <!-- Detail Perjalanan -->
            <div class="detail-box">
                <h3 class="detail-title">🚌 Detail Perjalanan</h3>
                <div class="detail-item">
                    <div class="detail-label">Rute Perjalanan</div>
                    <div class="detail-value"><?php echo htmlspecialchars($schedule['origin'] . ' → ' . $schedule['destination']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Kelas Bus</div>
                    <div class="detail-value"><?php echo htmlspecialchars(ucfirst($schedule['bus_type'])); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Berangkat</div>
                    <div class="detail-value"><?php echo format_time($schedule['departure_time']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tiba</div>
                    <div class="detail-value"><?php echo format_time($schedule['arrival_time']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Nomor Bus</div>
                    <div class="detail-value"><?php echo htmlspecialchars($schedule['bus_number'] ?? '-'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Total Pembayaran</div>
                    <div class="detail-value"><?php echo format_currency($booking['total_amount']); ?></div>
                </div>
            </div>
        </div>

        <!-- Status Box (berdasarkan status pembayaran) -->
        <?php if ($booking['payment_status'] == 'pending' && !empty($booking['payment_proof'])): ?>
            <!-- Sudah upload bukti pembayaran -->
            <div class="status-box uploaded">
                <h3 class="status-title">📤 Bukti Pembayaran Telah Diupload</h3>
                <p style="color: #155724; margin: 0;">
                    Status: 
                    <span class="status-value">Menunggu Verifikasi</span>
                </p>
                <p style="color: #155724; margin: 15px 0 0 0;">
                    Pembayaran Anda sedang diverifikasi oleh admin. E-Ticket akan dikirim setelah pembayaran terverifikasi.
                </p>
            </div>
        <?php elseif ($booking['payment_status'] == 'pending'): ?>
            <!-- Belum upload bukti pembayaran -->
            <div class="status-box pending">
                <h3 class="status-title">💰 Menunggu Pembayaran</h3>
                <p style="color: #856404; margin: 0;">
                    Status: 
                    <span class="status-value">Belum Bayar</span>
                </p>
                <p style="color: #856404; margin: 15px 0 0 0;">
                    Silakan lakukan pembayaran untuk menyelesaikan booking Anda.
                </p>
            </div>
        <?php elseif ($booking['payment_status'] == 'paid'): ?>
            <!-- Sudah dibayar -->
            <div class="status-box uploaded">
                <h3 class="status-title">✅ Pembayaran Terverifikasi</h3>
                <p style="color: #155724; margin: 0;">
                    Status: 
                    <span class="status-value">Siap Berangkat</span>
                </p>
                <p style="color: #155724; margin: 15px 0 0 0;">
                    E ticket sudah tersedia, <a href="<?php echo base_url('user/e-ticket.php?code=' . urlencode($code)); ?>" style="color: #007bff; text-decoration: underline;">klik di sini</a>.
                </p>
            </div>
        <?php endif; ?>

        <div class="action-buttons">
            <!-- Tombol Booking Lagi -->
            <a href="<?php echo base_url('jadwal.php'); ?>" class="btn-primary">
                🚌 Booking Lagi
            </a>
            
            <!-- Tombol Batalkan Pesanan (jika eligible) -->
            <?php if (in_array($booking['booking_status'], ['pending', 'active']) && $booking['payment_status'] == 'paid' && strtotime($schedule['departure_date'] . ' ' . $schedule['departure_time']) > time()): ?>
                <a href="<?php echo base_url('cancellation/index.php'); ?>" class="btn-secondary" style="background: #dc3545; color: white; border-color: #dc3545;" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                    ❌ Batalkan Pesanan
                </a>
            <?php endif; ?>
            
            <!-- Tombol Lihat Profil -->
            <a href="<?php echo base_url('user/profile.php'); ?>" class="btn-secondary">
                👤 Lihat di Profil
            </a>
        </div>

        <!-- Info tambahan untuk yang sudah upload bukti -->
        <?php if ($booking['payment_status'] == 'pending' && !empty($booking['payment_proof'])): ?>
            <div style="text-align: center; margin-top: 20px; color: #666; font-size: 0.9rem;">
                <p>📧 Cek email secara berkala untuk informasi verifikasi pembayaran.</p>
                <p>⏱️ Verifikasi biasanya memakan waktu 1-24 jam.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include '../footer.php'; ?>