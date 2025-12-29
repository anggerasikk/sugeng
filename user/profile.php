<?php
require_once '../config.php';
include '../header.php';

if (!is_logged_in()) {
    header("Location: ../signin.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil Data User
$user_query = "SELECT * FROM users WHERE id = ?";
$user_stmt = mysqli_prepare($koneksi, $user_query);
mysqli_stmt_bind_param($user_stmt, "i", $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user = mysqli_fetch_assoc($user_result);

// Ambil Riwayat Booking dengan query yang sesuai
$bookings_query = "
    SELECT
        b.*,
        r.origin,
        r.destination,
        s.departure_time,
        s.arrival_time,
        s.bus_type,
        s.departure_date
    FROM bookings b
    JOIN schedules s ON b.schedule_id = s.id
    JOIN routes r ON s.route_id = r.id
    WHERE b.user_id = ?
    ORDER BY b.created_at DESC
";

$bookings_stmt = mysqli_prepare($koneksi, $bookings_query);
mysqli_stmt_bind_param($bookings_stmt, "i", $user_id);
mysqli_stmt_execute($bookings_stmt);
$bookings_result = mysqli_stmt_get_result($bookings_stmt);

// Hitung total booking
$total_bookings_query = "SELECT COUNT(*) as total FROM bookings WHERE user_id = ?";
$total_stmt = mysqli_prepare($koneksi, $total_bookings_query);
mysqli_stmt_bind_param($total_stmt, "i", $user_id);
mysqli_stmt_execute($total_stmt);
$total_result = mysqli_stmt_get_result($total_stmt);
$total_data = mysqli_fetch_assoc($total_result);
$total_bookings = $total_data['total'];
?>

<style>
    .profile-container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 20px;
        min-height: 70vh;
    }

    .profile-layout {
        display: flex;
        gap: 30px;
    }

    @media (max-width: 768px) {
        .profile-layout {
            flex-direction: column;
        }
    }

    .sidebar {
        width: 250px;
        flex-shrink: 0;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
        }
    }

    .user-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        text-align: center;
        margin-bottom: 20px;
    }

    .user-avatar {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 36px;
        font-weight: 600;
    }

    .user-name {
        color: #333;
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .user-email {
        color: #777;
        font-size: 0.9rem;
        margin-bottom: 10px;
    }

    .verification-badge {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .verification-badge:not(.unverified) {
        background: #d4edda;
        color: #155724;
    }

    .verification-badge.unverified {
        background: #fff3cd;
        color: #856404;
    }

    .sidebar-menu {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .menu-item {
        display: block;
        text-decoration: none;
        color: #333;
        padding: 12px 15px;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .menu-item:hover {
        background: #f8f9fa;
        color: <?php echo $primary_blue; ?>;
        transform: translateX(5px);
    }

    .menu-item.logout {
        color: #f44336;
        margin-top: 15px;
        border-top: 1px solid #eee;
        padding-top: 15px;
    }

    .content {
        flex: 1;
    }

    .content-title {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.8rem;
        margin-bottom: 25px;
        font-weight: 600;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        text-align: center;
    }

    .stat-card.primary {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
    }

    .stat-card.secondary {
        background: linear-gradient(135deg, <?php echo $accent_orange; ?>, #ff9933);
        color: white;
    }

    .stat-label {
        font-size: 0.9rem;
        opacity: 0.9;
        margin-bottom: 8px;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .bookings-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .card-title {
        color: #333;
        font-size: 1.3rem;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .bookings-table {
        width: 100%;
        border-collapse: collapse;
    }

    .bookings-table th {
        text-align: left;
        padding: 15px 0;
        border-bottom: 2px solid #f0f0f0;
        color: #666;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .bookings-table td {
        padding: 18px 0;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }

    .bookings-table tr:hover {
        background: #fafafa;
    }

    .booking-code {
        font-weight: 700;
        color: <?php echo $primary_blue; ?>;
        font-family: 'Courier New', monospace;
    }

    .route-info {
        font-weight: 500;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-pending {
        background: #fff3cd;
        color: #856404;
    }

    .badge-active {
        background: #d4edda;
        color: #155724;
    }

    .badge-confirmed {
        background: #d1ecf1;
        color: #0c5460;
    }

    .badge-cancelled {
        background: #f8d7da;
        color: #721c24;
    }

    /* STYLE STATUS PEMBAYARAN SAMA SEPERTI DI SUCCESS.PHP */
    .payment-status-box {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-top: 5px;
        min-width: 120px;
        text-align: center;
    }

    .payment-pending-uploaded {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-left: 3px solid #28a745;
    }

    .payment-pending {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
        border-left: 3px solid #ffc107;
    }

    .payment-paid {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        border-left: 3px solid #28a745;
    }

    .payment-cancelled {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
        border-left: 3px solid #dc3545;
    }

    .payment-refunded {
        background: #e2e3e5;
        color: #383d41;
        border: 1px solid #d6d8db;
        border-left: 3px solid #6c757d;
    }

    .status-icon {
        margin-right: 5px;
        font-size: 1rem;
    }

    .action-link {
        color: <?php echo $accent_orange; ?>;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 8px 15px;
        border: 2px solid <?php echo $accent_orange; ?>;
        border-radius: 6px;
        display: inline-block;
        transition: all 0.3s ease;
    }

    .action-link:hover {
        background: <?php echo $accent_orange; ?>;
        color: white;
    }

    .no-bookings {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }

    .no-bookings-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    /* Chart Section */
    .chart-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
    }

    .chart-title {
        color: #333;
        font-size: 1.3rem;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f0f0f0;
    }

    .chart-container {
        height: 400px;
        width: 100%;
    }
</style>

<div class="profile-container">
    <div class="profile-layout">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-card">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                </div>
                <h3 class="user-name"><?php echo htmlspecialchars($user['full_name']); ?></h3>
                <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                <?php if ($user['verified']): ?>
                    <div class="verification-badge">
                        ✅ Terverifikasi
                    </div>
                <?php else: ?>
                    <div class="verification-badge unverified">
                        ⏳ Belum Terverifikasi
                    </div>
                <?php endif; ?>
            </div>

            <div class="sidebar-menu">
                <a href="../index.php" class="menu-item">
                    🏠 Beranda
                </a>
                <a href="index.php" class="menu-item">
                    📊 Dashboard
                </a>
                <a href="edit-profile.php" class="menu-item">
                    👤 Edit Profil
                </a>
                <a href="change-password.php" class="menu-item">
                    🔑 Ganti Password
                </a>
                <a href="<?php echo base_url('auth/logout.php'); ?>" class="menu-item logout">
                    🚪 Keluar
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="content">
            <h1 class="content-title">Dashboard Penumpang</h1>
            
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card primary">
                    <div class="stat-label">Total Pesanan</div>
                    <div class="stat-value"><?php echo $total_bookings; ?></div>
                </div>
                
                <div class="stat-card secondary">
                    <div class="stat-label">Status Akun</div>
                    <div class="stat-value" style="text-transform: capitalize; font-size: 1.5rem;">
                        <?php echo htmlspecialchars($user['status']); ?>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="chart-section">
                <h3 class="chart-title">📈 Tren Reservasi & Pembatalan (30 Hari Terakhir)</h3>
                <div class="chart-container">
                    <div id="reservationCancellationChart"></div>
                </div>
            </div>

            <!-- Bookings Table -->
            <div class="bookings-card">
                <h3 class="card-title">Riwayat Reservasi</h3>
                
                <?php if (mysqli_num_rows($bookings_result) > 0): ?>
                    <table class="bookings-table">
                        <thead>
                            <tr>
                                <th width="15%">Kode Booking</th>
                                <th width="25%">Rute</th>
                                <th width="15%">Tanggal</th>
                                <th width="25%">Status Pembayaran</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($booking = mysqli_fetch_assoc($bookings_result)): 
                                // Tentukan class dan teks berdasarkan status pembayaran
                                $payment_class = '';
                                $payment_text = '';
                                $payment_icon = '';
                                
                                if ($booking['payment_status'] == 'pending' && !empty($booking['payment_proof'])) {
                                    // Sudah upload bukti pembayaran
                                    $payment_class = 'payment-pending-uploaded';
                                    $payment_text = 'Menunggu Verifikasi';
                                    $payment_icon = '📤';
                                } elseif ($booking['payment_status'] == 'pending') {
                                    // Belum upload bukti pembayaran
                                    $payment_class = 'payment-pending';
                                    $payment_text = 'Menunggu Pembayaran';
                                    $payment_icon = '💰';
                                } elseif ($booking['payment_status'] == 'paid') {
                                    // Sudah dibayar
                                    $payment_class = 'payment-paid';
                                    $payment_text = 'Lunas';
                                    $payment_icon = '✅';
                                } elseif ($booking['payment_status'] == 'cancelled') {
                                    // Dibatalkan
                                    $payment_class = 'payment-cancelled';
                                    $payment_text = 'Dibatalkan';
                                    $payment_icon = '❌';
                                } elseif ($booking['payment_status'] == 'refunded') {
                                    // Dikembalikan
                                    $payment_class = 'payment-refunded';
                                    $payment_text = 'Dikembalikan';
                                    $payment_icon = '↩️';
                                } else {
                                    // Default
                                    $payment_class = 'payment-pending';
                                    $payment_text = ucfirst($booking['payment_status']);
                                    $payment_icon = '⏳';
                                }
                                
                                // Tentukan teks tambahan
                                $additional_text = '';
                                if ($booking['payment_status'] == 'pending' && !empty($booking['payment_proof'])) {
                                    $additional_text = '<div style="font-size: 0.75rem; margin-top: 3px; color: #0c5460;">Bukti pembayaran telah diupload</div>';
                                } elseif ($booking['payment_status'] == 'pending') {
                                    $additional_text = '<div style="font-size: 0.75rem; margin-top: 3px; color: #856404;">Silakan lakukan pembayaran</div>';
                                }
                            ?>
                            <tr>
                                <td>
                                    <div class="booking-code"><?php echo htmlspecialchars($booking['booking_code']); ?></div>
                                </td>
                                <td>
                                    <div class="route-info">
                                        <?php echo htmlspecialchars($booking['origin']); ?> → 
                                        <?php echo htmlspecialchars($booking['destination']); ?>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #666; margin-top: 5px;">
                                        <span style="font-weight: 600;"><?php echo format_time($booking['departure_time']); ?></span> - 
                                        <?php echo format_time($booking['arrival_time']); ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    // Cek apakah ada travel_date di booking, jika tidak gunakan departure_date
                                    $travel_date = !empty($booking['travel_date']) ? $booking['travel_date'] : $booking['departure_date'];
                                    echo format_date($travel_date);
                                    ?>
                                </td>
                                <td>
                                    <div class="payment-status-box <?php echo $payment_class; ?>">
                                        <span class="status-icon"><?php echo $payment_icon; ?></span>
                                        <?php echo $payment_text; ?>
                                    </div>
                                    <?php echo $additional_text; ?>
                                    
                                    <?php if ($booking['payment_status'] == 'pending' && empty($booking['payment_proof'])): ?>
                                        <div style="margin-top: 8px;">
                                            <a href="../booking/confirm-booking.php?code=<?php echo urlencode($booking['booking_code']); ?>" 
                                               class="action-link" 
                                               style="font-size: 0.8rem; padding: 5px 10px;">
                                                💳 Lanjutkan Pembayaran
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="../booking/success.php?code=<?php echo urlencode($booking['booking_code']); ?>" class="action-link">
                                        Detail
                                    </a>
                                    <?php 
                                    $travel_date = !empty($booking['travel_date']) ? $booking['travel_date'] : $booking['departure_date'];
                                    if (in_array($booking['booking_status'], ['pending', 'active']) && $booking['payment_status'] == 'paid' && strtotime($travel_date . ' ' . $booking['departure_time']) > time()): 
                                    ?>
                                        <br><br>
                                        <a href="../cancellation/index.php" class="action-link" style="background: #dc3545; color: white; border-color: #dc3545;" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                            ❌ Batalkan
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="no-bookings">
                        <div class="no-bookings-icon">🚌</div>
                        <h3>Belum Ada Pesanan</h3>
                        <p>Mulai pesan tiket bus Anda sekarang!</p>
                        <a href="<?php echo base_url('jadwal.php'); ?>" class="action-link" style="margin-top: 15px;">
                            🎫 Pesan Tiket Sekarang
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Chart JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Fetch chart data from the API
    fetch('chart_data.php')
        .then(response => response.json())
        .then(data => {
            const options = {
                series: [{
                    name: 'Reservasi',
                    data: data.reservations
                }, {
                    name: 'Pembatalan',
                    data: data.cancellations
                }],
                chart: {
                    type: 'line',
                    height: 350,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['<?php echo $primary_blue; ?>', '<?php echo $accent_orange; ?>'],
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                title: {
                    text: undefined
                },
                grid: {
                    borderColor: '#f1f1f1',
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: data.dates,
                    title: {
                        text: 'Tanggal'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah'
                    },
                    min: 0
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                },
                tooltip: {
                    shared: true,
                    intersect: false,
                    y: {
                        formatter: function (y) {
                            if (typeof y !== "undefined") {
                                return y + " item";
                            }
                            return y;
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#reservationCancellationChart"), options);
            chart.render();
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            document.querySelector("#reservationCancellationChart").innerHTML =
                '<div style="text-align: center; padding: 50px; color: #666;">' +
                '<div style="font-size: 3rem; margin-bottom: 20px;">📊</div>' +
                '<h3>Gagal Memuat Grafik</h3>' +
                '<p>Silakan refresh halaman atau coba lagi nanti.</p>' +
                '</div>';
        });
});
</script>

<?php include '../footer.php'; ?>
