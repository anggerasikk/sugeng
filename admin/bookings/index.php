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

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 5px;
        font-weight: 500;
        color: <?php echo $primary_blue; ?>;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .btn-filter {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-filter:hover {
        background: <?php echo $primary_blue; ?>;
    }

    /* STYLE STATUS PEMBAYARAN SAMA SEPERTI PROFILE */
    .payment-status-box {
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 5px;
        min-width: 120px;
        text-align: center;
        border: 1px solid;
        border-left: 3px solid;
    }

    .payment-pending-uploaded {
        background: #d4edda;
        color: #155724;
        border-color: #c3e6cb;
        border-left-color: #28a745;
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

    .payment-refunded {
        background: #e2e3e5;
        color: #383d41;
        border-color: #d6d8db;
        border-left-color: #6c757d;
    }

    .status-icon {
        margin-right: 5px;
        font-size: 1rem;
    }

    .payment-actions {
        margin-top: 5px;
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .payment-action-btn {
        padding: 4px 8px;
        border: none;
        border-radius: 3px;
        font-size: 0.75rem;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-verify {
        background: #28a745;
        color: white;
    }

    .btn-verify:hover {
        background: #218838;
        transform: translateY(-2px);
    }

    .btn-cancel {
        background: #dc3545;
        color: white;
    }

    .btn-cancel:hover {
        background: #c82333;
        transform: translateY(-2px);
    }

    .btn-refund {
        background: #6c757d;
        color: white;
    }

    .btn-refund:hover {
        background: #5a6268;
        transform: translateY(-2px);
    }

    .bookings-table {
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

    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
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

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
        padding: 20px;
    }

    .page-link {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        color: <?php echo $primary_blue; ?>;
        text-decoration: none;
        border-radius: 3px;
        transition: all 0.3s;
    }

    .page-link.active {
        background: <?php echo $primary_blue; ?>;
        color: white;
        border-color: <?php echo $primary_blue; ?>;
    }

    .page-link:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .admin-content {
            margin-left: 0;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .payment-actions {
            flex-direction: column;
        }
    }
    
    /* Loading overlay */
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
    
    /* Success/Error Messages */
    .message {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 15px;
        display: none;
    }
    
    .message.success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .message.error {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-cancel-booking {
        background: #dc3545;
        color: white;
    }

    .btn-cancel-booking:hover {
        background: #c82333;
        transform: translateY(-1px);
    }

    .btn-view {
        background: #007bff;
        color: white;
    }

    .btn-view:hover {
        background: #0056b3;
        transform: translateY(-1px);
    }
</style>

<div id="loadingOverlay" class="loading-overlay">
    <div class="loading-spinner"></div>
</div>

<div class="admin-content">
    <div class="admin-header">
        <h1>📋 Manajemen Booking</h1>
        <p>Kelola semua booking tiket bus</p>
    </div>
    
    <!-- Message Container -->
    <div id="messageContainer"></div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $total_query = "SELECT COUNT(*) as total FROM bookings";
                $total_result = mysqli_query($koneksi, $total_query);
                $total = mysqli_fetch_assoc($total_result)['total'];
                echo $total;
                ?>
            </div>
            <div class="stat-label">Total Booking</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $confirmed_query = "SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'confirmed'";
                $confirmed_result = mysqli_query($koneksi, $confirmed_query);
                $confirmed = mysqli_fetch_assoc($confirmed_result)['total'];
                echo $confirmed;
                ?>
            </div>
            <div class="stat-label">Booking Dikonfirmasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $pending_query = "SELECT COUNT(*) as total FROM bookings WHERE booking_status = 'pending'";
                $pending_result = mysqli_query($koneksi, $pending_query);
                $pending = mysqli_fetch_assoc($pending_result)['total'];
                echo $pending;
                ?>
            </div>
            <div class="stat-label">Menunggu Konfirmasi</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $today_query = "SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at) = CURDATE()";
                $today_result = mysqli_query($koneksi, $today_query);
                $today = mysqli_fetch_assoc($today_result)['total'];
                echo $today;
                ?>
            </div>
            <div class="stat-label">Booking Hari Ini</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="search">Cari Booking Code / Nama</label>
                    <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" placeholder="Masukkan kode booking atau nama...">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="cancelled" <?php echo ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        <option value="completed" <?php echo ($_GET['status'] ?? '') === 'completed' ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="date_from">Tanggal Dari</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo htmlspecialchars($_GET['date_from'] ?? ''); ?>">
                </div>
                <div class="filter-group">
                    <label for="date_to">Tanggal Sampai</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo htmlspecialchars($_GET['date_to'] ?? ''); ?>">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                    <?php if (!empty($_GET)): ?>
                        <a href="index.php" class="btn-filter" style="margin-top: 10px; background: #6c757d; text-align: center; text-decoration: none; display: block;">
                            🔄 Reset
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <!-- Bookings Table -->
    <div class="bookings-table">
        <div class="table-header">
            📋 Daftar Booking (Total: <?php echo $total; ?>)
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Penumpang</th>
                        <th>Rute</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="bookingsTableBody">
                    <?php
                    // Build query with filters
                    $where_conditions = [];
                    $params = [];
                    $types = "";

                    if (!empty($_GET['search'])) {
                        $search = "%" . mysqli_real_escape_string($koneksi, $_GET['search']) . "%";
                        $where_conditions[] = "(b.booking_code LIKE ? OR u.full_name LIKE ? OR b.passenger_name LIKE ?)";
                        $params[] = $search;
                        $params[] = $search;
                        $params[] = $search;
                        $types .= "sss";
                    }

                    if (!empty($_GET['status'])) {
                        $where_conditions[] = "b.booking_status = ?";
                        $params[] = $_GET['status'];
                        $types .= "s";
                    }

                    if (!empty($_GET['date_from'])) {
                        $where_conditions[] = "DATE(b.created_at) >= ?";
                        $params[] = $_GET['date_from'];
                        $types .= "s";
                    }

                    if (!empty($_GET['date_to'])) {
                        $where_conditions[] = "DATE(b.created_at) <= ?";
                        $params[] = $_GET['date_to'];
                        $types .= "s";
                    }

                    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

                    // Pagination
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $per_page = 10;
                    $offset = ($page - 1) * $per_page;

                    // Count total records
                    $count_query = "SELECT COUNT(*) as total FROM bookings b
                                   LEFT JOIN users u ON b.user_id = u.id
                                   LEFT JOIN schedules s ON b.schedule_id = s.id
                                   LEFT JOIN routes r ON s.route_id = r.id
                                   $where_clause";
                    $count_stmt = mysqli_prepare($koneksi, $count_query);
                    if (!empty($params)) {
                        mysqli_stmt_bind_param($count_stmt, $types, ...$params);
                    }
                    mysqli_stmt_execute($count_stmt);
                    $count_result = mysqli_stmt_get_result($count_stmt);
                    $total_records = mysqli_fetch_assoc($count_result)['total'];
                    $total_pages = ceil($total_records / $per_page);

                    // Main query
                    $query = "SELECT b.*, u.full_name as user_name, r.origin, r.destination, 
                             s.departure_time, s.departure_date, b.booking_status as status,
                             b.payment_status, b.payment_proof, b.seat_numbers
                             FROM bookings b
                             LEFT JOIN users u ON b.user_id = u.id
                             LEFT JOIN schedules s ON b.schedule_id = s.id
                             LEFT JOIN routes r ON s.route_id = r.id
                             $where_clause
                             ORDER BY b.created_at DESC
                             LIMIT ? OFFSET ?";

                    $stmt = mysqli_prepare($koneksi, $query);
                    
                    // Tambahkan parameter untuk LIMIT dan OFFSET
                    $limit_params = $params;
                    $limit_params[] = $per_page;
                    $limit_params[] = $offset;
                    $limit_types = $types . "ii";
                    
                    mysqli_stmt_bind_param($stmt, $limit_types, ...$limit_params);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($booking = mysqli_fetch_assoc($result)) {
                            $status_class = 'status-' . $booking['status'];
                            
                            // Tentukan class dan teks untuk status pembayaran
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
                            ?>
                            <tr id="booking-<?php echo $booking['id']; ?>">
                                <td>
                                    <strong><?php echo htmlspecialchars($booking['booking_code']); ?></strong>
                                    <br>
                                    <small style="color: #666; font-size: 0.8rem;">
                                        <?php echo format_date($booking['created_at']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($booking['passenger_name']); ?>
                                    <?php if (!empty($booking['user_name'])): ?>
                                        <br>
                                        <small style="color: #666; font-size: 0.8rem;">
                                            User: <?php echo htmlspecialchars($booking['user_name']); ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($booking['origin'] . ' → ' . $booking['destination']); ?></td>
                                <td>
                                    <?php echo format_date($booking['departure_date']); ?>
                                    <br>
                                    <small style="color: #666;">
                                        <?php echo format_time($booking['departure_time']); ?>
                                    </small>
                                </td>
                                <td>
                                    <span class="status-badge <?php echo $status_class; ?>">
                                        <?php echo ucfirst($booking['status']); ?>
                                    </span>
                                </td>
                                <td id="payment-cell-<?php echo $booking['id']; ?>">
                                    <div class="payment-status-box <?php echo $payment_class; ?>">
                                        <span class="status-icon"><?php echo $payment_icon; ?></span>
                                        <?php echo $payment_text; ?>
                                    </div>
                                    
                                    <?php if ($booking['payment_status'] == 'pending' && !empty($booking['payment_proof'])): ?>
                                        <div class="payment-actions">
                                            <!-- Tombol verifikasi dengan AJAX -->
                                            <button onclick="verifyPayment(<?php echo $booking['id']; ?>, '<?php echo addslashes($booking['booking_code']); ?>')" 
                                               class="payment-action-btn btn-verify">
                                                ✅ Verifikasi
                                            </button>
                                            <button onclick="rejectPayment(<?php echo $booking['id']; ?>, '<?php echo addslashes($booking['booking_code']); ?>')" 
                                               class="payment-action-btn btn-cancel">
                                                ❌ Tolak
                                            </button>
                                            <?php if (!empty($booking['payment_proof'])): ?>
                                                <a href="<?php echo base_url('uploads/payment_proofs/' . $booking['payment_proof']); ?>" 
                                                   target="_blank"
                                                   class="payment-action-btn"
                                                   style="background: #007bff; color: white;">
                                                    👁️ Bukti
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    <?php elseif ($booking['payment_status'] == 'paid'): ?>
                                        <div class="payment-actions">
                                            <span style="color: #28a745; font-size: 0.8rem;">
                                                ✅ Telah diverifikasi
                                            </span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo format_currency($booking['total_amount']); ?></strong>
                                    <br>
                                    <small style="color: #666; font-size: 0.8rem;">
                                        <?php echo $booking['seats_booked']; ?> kursi
                                        <?php if (!empty($booking['seat_numbers'])): ?>
                                            (<?php echo htmlspecialchars($booking['seat_numbers']); ?>)
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <?php if (in_array($booking['status'], ['pending', 'confirmed']) && $booking['payment_status'] == 'paid'): ?>
                                            <button onclick="cancelBooking(<?php echo $booking['id']; ?>, '<?php echo addslashes($booking['booking_code']); ?>')" 
                                               class="btn-action btn-cancel-booking" 
                                               title="Batalkan Booking">
                                                ❌ Batalkan
                                            </button>
                                        <?php endif; ?>
                                        <a href="view.php?id=<?php echo $booking['id']; ?>" class="btn-action btn-view" title="Lihat Detail">
                                            👁️ Lihat
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 40px;">
                                <div style="color: #666; margin: 0;">
                                    <p style="font-size: 1.2rem; margin-bottom: 10px;">📭 Tidak ada data booking ditemukan</p>
                                    <p style="font-size: 0.9rem;">Coba gunakan filter yang berbeda atau reset filter</p>
                                </div>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1) { ?>
            <div class="pagination">
                <?php
                // Previous page
                if ($page > 1) {
                    $query_string = $_GET;
                    $query_string['page'] = $page - 1;
                    $prev_url = '?' . http_build_query($query_string);
                    echo "<a href='$prev_url' class='page-link'>← Sebelumnya</a>";
                }

                // Page numbers
                $query_string = $_GET;
                
                for ($i = 1; $i <= $total_pages; $i++) {
                    $query_string['page'] = $i;
                    $url = '?' . http_build_query($query_string);
                    $active_class = $i === $page ? 'active' : '';
                    
                    // Show limited page numbers
                    if ($i == 1 || $i == $total_pages || ($i >= $page - 2 && $i <= $page + 2)) {
                        echo "<a href='$url' class='page-link $active_class'>$i</a>";
                    } elseif ($i == $page - 3 || $i == $page + 3) {
                        echo "<span class='page-link'>...</span>";
                    }
                }

                // Next page
                if ($page < $total_pages) {
                    $query_string['page'] = $page + 1;
                    $next_url = '?' . http_build_query($query_string);
                    echo "<a href='$next_url' class='page-link'>Selanjutnya →</a>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<script>
// Define base URL for AJAX requests (not used currently)
// const baseUrl = '<?php echo base_url(); ?>';

// Show loading overlay
function showLoading() {
    document.getElementById('loadingOverlay').style.display = 'flex';
}

// Hide loading when page is loaded
window.addEventListener('load', function() {
    document.getElementById('loadingOverlay').style.display = 'none';
});

// Auto-submit form on filter change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        showLoading();
        this.closest('form').submit();
    });
});

// Add loading to date inputs
document.querySelectorAll('input[type="date"]').forEach(input => {
    input.addEventListener('change', function() {
        showLoading();
        this.closest('form').submit();
    });
});

// Show message
function showMessage(type, text) {
    const container = document.getElementById('messageContainer');
    container.innerHTML = `<div class="message ${type}">${text}</div>`;
    container.firstElementChild.style.display = 'block';
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        container.firstElementChild.style.display = 'none';
    }, 3000);
}

// Update payment status in table
function updatePaymentStatus(bookingId, newStatus) {
    const paymentCell = document.getElementById(`payment-cell-${bookingId}`);
    let newHtml = '';
    let statusText = '';
    let statusClass = '';
    let statusIcon = '';
    
    if (newStatus === 'paid') {
        statusText = 'Lunas';
        statusClass = 'payment-paid';
        statusIcon = '✅';
        newHtml = `
            <div class="payment-status-box ${statusClass}">
                <span class="status-icon">${statusIcon}</span>
                ${statusText}
            </div>
            <div class="payment-actions">
                <span style="color: #28a745; font-size: 0.8rem;">
                    ✅ Telah diverifikasi
                </span>
            </div>
        `;
    } else if (newStatus === 'cancelled') {
        statusText = 'Dibatalkan';
        statusClass = 'payment-cancelled';
        statusIcon = '❌';
        newHtml = `
            <div class="payment-status-box ${statusClass}">
                <span class="status-icon">${statusIcon}</span>
                ${statusText}
            </div>
        `;
    } else if (newStatus === 'refunded') {
        statusText = 'Dikembalikan';
        statusClass = 'payment-refunded';
        statusIcon = '↩️';
        newHtml = `
            <div class="payment-status-box ${statusClass}">
                <span class="status-icon">${statusIcon}</span>
                ${statusText}
            </div>
        `;
    }
    
    paymentCell.innerHTML = newHtml;
    
    // Update status badge to confirmed if payment is verified
    if (newStatus === 'paid') {
        const statusBadge = document.querySelector(`#booking-${bookingId} .status-badge`);
        if (statusBadge) {
            statusBadge.className = 'status-badge status-confirmed';
            statusBadge.textContent = 'Confirmed';
        }
    }
}
// Verify payment with AJAX
function verifyPayment(bookingId, bookingCode) {
    if (!confirm(`Verifikasi pembayaran booking ${bookingCode}?`)) {
        return;
    }
    
    showLoading();
    
    const xhr = new XMLHttpRequest();
    xhr.open('GET', `verify-payment.php?id=${bookingId}&action=verify`, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.withCredentials = true;
    
    xhr.onload = function() {
        document.getElementById('loadingOverlay').style.display = 'none';
        
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    showMessage('success', data.message);
                    updatePaymentUI(bookingId, 'paid');
                } else {
                    showMessage('error', data.message || 'Terjadi kesalahan');
                }
            } catch (e) {
                showMessage('error', 'Response bukan JSON valid: ' + xhr.responseText);
            }
        } else {
            showMessage('error', 'HTTP Error: ' + xhr.status + ' - ' + xhr.statusText);
        }
    };
    
    xhr.onerror = function() {
        document.getElementById('loadingOverlay').style.display = 'none';
        showMessage('error', 'Terjadi kesalahan jaringan');
    };
    
    xhr.send();
}

// Reject payment with AJAX
function rejectPayment(bookingId, bookingCode) {
    const reason = prompt(`Alasan menolak pembayaran booking ${bookingCode}:`, 'Bukti pembayaran tidak valid');
    
    if (reason === null) {
        return; // User cancelled
    }
    
    if (!reason.trim()) {
        alert('Silakan masukkan alasan penolakan');
        return;
    }
    
    showLoading();
    
    const formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('action', 'reject');
    formData.append('reason', reason);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'verify-payment.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.withCredentials = true;
    
    xhr.onload = function() {
        document.getElementById('loadingOverlay').style.display = 'none';
        
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    showMessage('success', data.message);
                    updatePaymentUI(bookingId, 'cancelled');
                } else {
                    showMessage('error', data.message || 'Terjadi kesalahan');
                }
            } catch (e) {
                showMessage('error', 'Response bukan JSON valid: ' + xhr.responseText);
            }
        } else {
            showMessage('error', 'HTTP Error: ' + xhr.status + ' - ' + xhr.statusText);
        }
    };
    
    xhr.onerror = function() {
        document.getElementById('loadingOverlay').style.display = 'none';
        showMessage('error', 'Terjadi kesalahan jaringan');
    };
    
    xhr.send(formData);
}

// Function to update UI after payment status change
function updatePaymentUI(bookingId, newStatus) {
    const paymentCell = document.getElementById(`payment-cell-${bookingId}`);
    const bookingRow = document.getElementById(`booking-${bookingId}`);
    
    if (!paymentCell || !bookingRow) return;
    
    if (newStatus === 'paid') {
        // Update payment status box
        const statusBox = paymentCell.querySelector('.payment-status-box');
        if (statusBox) {
            statusBox.className = 'payment-status-box payment-paid';
            statusBox.innerHTML = '<span class="status-icon">✅</span>Lunas';
        }
        
        // Update booking status badge
        const statusBadge = bookingRow.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'status-badge status-confirmed';
            statusBadge.textContent = 'Confirmed';
        }
        
        // Remove action buttons and replace with "Telah diverifikasi"
        const actionsDiv = paymentCell.querySelector('.payment-actions');
        if (actionsDiv) {
            actionsDiv.innerHTML = '<span style="color: #28a745; font-size: 0.8rem;">✅ Telah diverifikasi</span>';
        }
        
    } else if (newStatus === 'cancelled') {
        // Update payment status box
        const statusBox = paymentCell.querySelector('.payment-status-box');
        if (statusBox) {
            statusBox.className = 'payment-status-box payment-cancelled';
            statusBox.innerHTML = '<span class="status-icon">❌</span>Dibatalkan';
        }
        
        // Update booking status badge
        const statusBadge = bookingRow.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.className = 'status-badge status-cancelled';
            statusBadge.textContent = 'Cancelled';
        }
        
        // Remove action buttons
        const actionsDiv = paymentCell.querySelector('.payment-actions');
        if (actionsDiv) {
            actionsDiv.remove();
        }
    }
}

// Cancel booking with AJAX
function cancelBooking(bookingId, bookingCode) {
    const reason = prompt(`Alasan membatalkan booking ${bookingCode}:`, 'Dibatalkan oleh admin');
    
    if (reason === null) {
        return; // User cancelled
    }
    
    if (!reason.trim()) {
        alert('Silakan masukkan alasan pembatalan');
        return;
    }
    
    showLoading();
    
    const formData = new FormData();
    formData.append('booking_id', bookingId);
    formData.append('reason', reason);
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'cancel-booking.php', true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.withCredentials = true;
    
    xhr.onload = function() {
        document.getElementById('loadingOverlay').style.display = 'none';
        
        if (xhr.status === 200) {
            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    showMessage('success', data.message);
                    // Reload page to update table
                    setTimeout(() => {
                        window.location.reload();
                    }, 1500);
                } else {
                    showMessage('error', data.message || 'Terjadi kesalahan');
                }
            } catch (e) {
                showMessage('error', 'Response bukan JSON valid: ' + xhr.responseText);
            }
        } else {
            showMessage('error', 'HTTP Error: ' + xhr.status + ' - ' + xhr.statusText);
        }
    };
    
    xhr.onerror = function() {
        document.getElementById('loadingOverlay').style.display = 'none';
        showMessage('error', 'Terjadi kesalahan jaringan');
    };
    
    xhr.send(formData);
}
</script>

<?php include '../footer-admin.php'; ?>