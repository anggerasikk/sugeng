kan fu<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../signin.php');
}

include '../header-admin.php';

// Get cancellation ID from URL
$cancellation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($cancellation_id <= 0) {
    set_flash('error', 'ID pembatalan tidak valid.');
    redirect('index.php');
}

// Get cancellation details with related data
$query = "SELECT cr.*, b.booking_code, b.total_amount, b.payment_status, b.booking_status,
         u.full_name, u.email, u.phone,
         CONCAT(r.origin, ' → ', r.destination) as route,
         s.departure_time, s.departure_date, bt.name as bus_type,
         p.full_name as processed_by_name
         FROM cancellation_requests cr
         LEFT JOIN bookings b ON cr.booking_id = b.id
         LEFT JOIN users u ON cr.user_id = u.id
         LEFT JOIN schedules s ON b.schedule_id = s.id
         LEFT JOIN routes r ON s.route_id = r.id
         LEFT JOIN bus_types bt ON s.bus_type_id = bt.id
         LEFT JOIN users p ON cr.processed_by = p.id
         WHERE cr.id = ?";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $cancellation_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    set_flash('error', 'Data pembatalan tidak ditemukan.');
    redirect('index.php');
}

$cancellation = mysqli_fetch_assoc($result);
?>

<style>
    .main-content {
        margin-top: 70px;
        padding: 20px;
        min-height: calc(100vh - 70px);
        max-width: 1200px;
        margin-left: auto;
        margin-right: auto;
    }

    .page-header {
        background: linear-gradient(135deg, #001BB7 0%, #0033CC 100%);
        color: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        margin-bottom: 30px;
        position: relative;
        min-height: 120px;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        transform: translate(50px, -50px);
        z-index: 1;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        z-index: 2;
    }

    .header-text {
        flex: 1;
    }

    .page-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    .page-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin: 0;
        font-weight: 400;
    }

    .header-actions {
        margin-left: 30px;
    }

    .btn-back {
        background: rgba(255,255,255,0.2);
        color: white;
        border: 2px solid rgba(255,255,255,0.3);
        padding: 12px 24px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .btn-back:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Detail Cards */
    .detail-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
        margin-bottom: 30px;
    }

    .detail-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #001BB7 0%, #0033CC 100%);
        color: white;
        padding: 20px 25px;
        font-weight: 600;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-content {
        padding: 25px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .info-item {
        margin-bottom: 20px;
    }

    .info-label {
        font-weight: 600;
        color: #001BB7;
        margin-bottom: 5px;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        color: #333;
        font-size: 1rem;
        line-height: 1.4;
    }

    .status-badge {
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: inline-block;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-approved {
        background: #d4edda;
        color: #155724;
    }

    .status-rejected {
        background: #f8d7da;
        color: #721c24;
    }

    .refund-status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .refund-status-processed {
        background: #cce5ff;
        color: #004085;
    }

    .refund-status-completed {
        background: #d4edda;
        color: #155724;
    }

    /* Action Panel */
    .action-panel {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
    }

    .action-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #001BB7;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-action {
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .btn-approve {
        background: #28a745;
        color: white;
    }

    .btn-approve:hover {
        background: #218838;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(40,167,69,0.3);
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-reject:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(220,53,69,0.3);
    }

    .btn-disabled {
        background: #6c757d;
        color: white;
        cursor: not-allowed;
    }

    .btn-disabled:hover {
        transform: none;
        box-shadow: none;
    }

    /* Reason Section */
    .reason-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 30px;
    }

    .reason-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #001BB7;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .reason-content {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #001BB7;
        color: #333;
        line-height: 1.6;
    }

    /* Admin Notes */
    .notes-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        padding: 25px;
        margin-bottom: 30px;
    }

    .notes-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #001BB7;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notes-content {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid #ffc107;
        color: #333;
        line-height: 1.6;
        font-style: italic;
    }

    .no-notes {
        color: #666;
        font-style: italic;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .main-content {
            padding: 15px;
        }

        .page-header {
            padding: 20px;
            margin-bottom: 20px;
            min-height: auto;
        }

        .page-header::before {
            width: 100px;
            height: 100px;
            transform: translate(30px, -30px);
        }

        .header-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }

        .page-title {
            font-size: 1.6rem;
        }

        .page-subtitle {
            font-size: 1rem;
        }

        .header-actions {
            margin-left: 0;
            align-self: stretch;
        }

        .btn-back {
            justify-content: center;
            width: 100%;
        }

        .detail-container {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .info-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .action-panel {
            padding: 20px;
        }

        .reason-section,
        .notes-section {
            padding: 20px;
        }
    }
</style>

<div class="main-content">
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">👁️ Detail Pembatalan</h1>
                <p class="page-subtitle">Kode Booking: <?php echo htmlspecialchars($cancellation['booking_code']); ?></p>
            </div>
            <div class="header-actions">
                <a href="index.php" class="btn-back">
                    <span>⬅️</span> Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Cancellation Reason -->
    <div class="reason-section">
        <h3 class="reason-title">
            <span>📝</span> Alasan Pembatalan
        </h3>
        <div class="reason-content">
            <?php echo nl2br(htmlspecialchars($cancellation['reason'])); ?>
        </div>
    </div>

    <!-- Admin Notes (if any) -->
    <?php if (!empty($cancellation['admin_notes'])) { ?>
    <div class="notes-section">
        <h3 class="notes-title">
            <span>📋</span> Catatan Admin
        </h3>
        <div class="notes-content">
            <?php echo nl2br(htmlspecialchars($cancellation['admin_notes'])); ?>
        </div>
    </div>
    <?php } ?>

    <div class="detail-container">
        <!-- Main Details -->
        <div class="detail-card">
            <div class="card-header">
                <span>📋</span> Informasi Pembatalan
            </div>
            <div class="card-content">
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Status Pembatalan</div>
                        <div class="info-value">
                            <span class="status-badge status-<?php echo $cancellation['status']; ?>">
                                <?php
                                $status_labels = [
                                    'pending' => 'Menunggu Approval',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak'
                                ];
                                echo $status_labels[$cancellation['status']] ?? ucfirst($cancellation['status']);
                                ?>
                            </span>
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Tanggal Pengajuan</div>
                        <div class="info-value"><?php echo format_date($cancellation['created_at']); ?></div>
                    </div>

                    <?php if ($cancellation['processed_at']) { ?>
                    <div class="info-item">
                        <div class="info-label">Tanggal Diproses</div>
                        <div class="info-value"><?php echo format_date($cancellation['processed_at']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Diproses Oleh</div>
                        <div class="info-value"><?php echo htmlspecialchars($cancellation['processed_by_name'] ?? 'N/A'); ?></div>
                    </div>
                    <?php } ?>

                    <?php if ($cancellation['refund_amount'] > 0) { ?>
                    <div class="info-item">
                        <div class="info-label">Jumlah Refund</div>
                        <div class="info-value"><?php echo format_currency($cancellation['refund_amount']); ?></div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Status Refund</div>
                        <div class="info-value">
                            <span class="status-badge refund-status-<?php echo $cancellation['refund_status']; ?>">
                                <?php
                                $refund_labels = [
                                    'pending' => 'Menunggu',
                                    'processed' => 'Diproses',
                                    'completed' => 'Selesai'
                                ];
                                echo $refund_labels[$cancellation['refund_status']] ?? ucfirst($cancellation['refund_status']);
                                ?>
                            </span>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Action Panel -->
        <div class="action-panel">
            <h3 class="action-title">
                <span>⚡</span> Aksi
            </h3>

            <?php if ($cancellation['status'] === 'pending') { ?>
                <a href="approve.php?id=<?php echo $cancellation['id']; ?>" class="btn-action btn-approve" onclick="return confirm('Apakah Anda yakin ingin MENYETUJUI pengajuan pembatalan ini?')">
                    <span>✅</span> Setujui Pembatalan
                </a>

                <a href="reject.php?id=<?php echo $cancellation['id']; ?>" class="btn-action btn-reject" onclick="return confirm('Apakah Anda yakin ingin MENOLAK pengajuan pembatalan ini?')">
                    <span>❌</span> Tolak Pembatalan
                </a>
            <?php } else { ?>
                <button class="btn-action btn-disabled" disabled>
                    <span>🔒</span> Sudah Diproses
                </button>
            <?php } ?>
        </div>
    </div>

    <!-- Booking Details -->
    <div class="detail-card">
        <div class="card-header">
            <span>🎫</span> Detail Booking
        </div>
        <div class="card-content">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Kode Booking</div>
                    <div class="info-value"><?php echo htmlspecialchars($cancellation['booking_code']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Nama Penumpang</div>
                    <div class="info-value"><?php echo htmlspecialchars($cancellation['full_name']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($cancellation['email']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Telepon</div>
                    <div class="info-value"><?php echo htmlspecialchars($cancellation['phone'] ?? 'N/A'); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Rute</div>
                    <div class="info-value"><?php echo htmlspecialchars($cancellation['route']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Tanggal Keberangkatan</div>
                    <div class="info-value"><?php echo format_date($cancellation['departure_date']); ?> pukul <?php echo format_time($cancellation['departure_time']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Tipe Bus</div>
                    <div class="info-value"><?php echo htmlspecialchars($cancellation['bus_type']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Total Pembayaran</div>
                    <div class="info-value"><?php echo format_currency($cancellation['total_amount']); ?></div>
                </div>

                <div class="info-item">
                    <div class="info-label">Status Pembayaran</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo $cancellation['payment_status']; ?>">
                            <?php
                            $payment_labels = [
                                'pending' => 'Menunggu',
                                'paid' => 'Lunas',
                                'cancelled' => 'Dibatalkan',
                                'refunded' => 'Direfund'
                            ];
                            echo $payment_labels[$cancellation['payment_status']] ?? ucfirst($cancellation['payment_status']);
                            ?>
                        </span>
                    </div>
                </div>

                <div class="info-item">
                    <div class="info-label">Status Booking</div>
                    <div class="info-value">
                        <span class="status-badge status-<?php echo $cancellation['booking_status']; ?>">
                            <?php
                            $booking_labels = [
                                'confirmed' => 'Dikonfirmasi',
                                'checked_in' => 'Check-in',
                                'cancelled' => 'Dibatalkan',
                                'completed' => 'Selesai'
                            ];
                            echo $booking_labels[$cancellation['booking_status']] ?? ucfirst($cancellation['booking_status']);
                            ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer-admin.php'; ?>
