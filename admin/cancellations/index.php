<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../signin.php');
}

include '../header-admin.php';

// Get cancellation statistics
$query_stats = "SELECT 
    COUNT(*) as total,
    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
    SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved,
    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
    FROM cancellation_requests";

$result_stats = mysqli_query($koneksi, $query_stats);
$stats = mysqli_fetch_assoc($result_stats);

$total_cancellations = $stats['total'] ?? 0;
$pending_cancellations = $stats['pending'] ?? 0;
$approved_cancellations = $stats['approved'] ?? 0;
$rejected_cancellations = $stats['rejected'] ?? 0;
?>

<style>
    .main-content {
        margin-top: 70px;
        padding: 20px;
        min-height: calc(100vh - 70px);
        max-width: 100%;
        overflow-x: hidden;
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

    .btn-primary {
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

    .btn-primary:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    /* Statistics Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border-left: 5px solid;
        transition: all 0.3s ease;
        position: relative;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 80px;
        height: 80px;
        background: rgba(0,0,0,0.03);
        border-radius: 50%;
        transform: translate(30px, -30px);
        z-index: 1;
    }

    .stat-card.total {
        border-left-color: #001BB7;
        background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
    }

    .stat-card.pending {
        border-left-color: #ffc107;
        background: linear-gradient(135deg, #fffef8 0%, #ffffff 100%);
        position: relative;
    }

    .stat-card.approved {
        border-left-color: #28a745;
        background: linear-gradient(135deg, #f8fff9 0%, #ffffff 100%);
    }

    .stat-card.rejected {
        border-left-color: #dc3545;
        background: linear-gradient(135deg, #fff8f8 0%, #ffffff 100%);
    }

    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
        position: relative;
        z-index: 3;
    }

    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }

    .stat-badge {
        background: currentColor;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
        font-weight: bold;
        position: absolute;
        top: 20px;
        right: 20px;
        z-index: 2;
    }

    .stat-title {
        font-size: 0.95rem;
        color: #555;
        font-weight: 600;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: #222;
    }

    .stat-desc {
        font-size: 0.9rem;
        color: #777;
        font-weight: 500;
    }

    /* Filters Section */
    .filters-section {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        margin-bottom: 30px;
        clear: both;
    }

    .filters-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }

    .filters-icon {
        font-size: 1.5rem;
        color: #001BB7;
    }

    .filters-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #001BB7;
        margin: 0;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-weight: 600;
        color: #001BB7;
        margin-bottom: 8px;
        font-size: 0.95rem;
    }

    .filter-input,
    .filter-select {
        padding: 12px 16px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #fafbfc;
    }

    .filter-input:focus,
    .filter-select:focus {
        outline: none;
        border-color: #001BB7;
        background: white;
        box-shadow: 0 0 0 3px rgba(0,27,183,0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #FF8040 0%, #ff6b35 100%);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(255,128,64,0.3);
    }

    /* Table Styles */
    .data-table {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .table-header {
        background: linear-gradient(135deg, #001BB7 0%, #0033CC 100%);
        color: white;
        padding: 25px 30px;
        font-weight: 600;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .table-container {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 18px 20px;
        text-align: left;
        border-bottom: 1px solid #f0f2f5;
    }

    th {
        background: #f8f9fa;
        font-weight: 600;
        color: #001BB7;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    tr:hover {
        background: #f8f9fa;
    }

    td {
        color: #333;
        border-bottom: 1px solid #f0f2f5;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
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

    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .btn-action {
        padding: 8px 16px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s ease;
    }

    .btn-view {
        background: #001BB7;
        color: white;
    }

    .btn-approve {
        background: #28a745;
        color: white;
    }

    .btn-reject {
        background: #dc3545;
        color: white;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-title {
        font-size: 1.3rem;
        font-weight: 600;
        margin-bottom: 10px;
        color: #333;
    }

    .empty-text {
        font-size: 1rem;
        color: #666;
    }

    /* Pagination */
    .pagination {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 30px 0;
        flex-wrap: wrap;
    }

    .page-link {
        padding: 10px 16px;
        border: 2px solid #e1e5e9;
        background: white;
        color: #001BB7;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .page-link:hover,
    .page-link.active {
        background: #001BB7;
        color: white;
        border-color: #001BB7;
        transform: translateY(-2px);
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

        .btn-primary {
            justify-content: center;
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .stat-card {
            padding: 20px;
            min-height: auto;
        }

        .stat-value {
            font-size: 2rem;
        }

        .filters-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .filters-section {
            padding: 20px;
        }

        .data-table {
            margin: 0 -15px;
        }

        .table-container {
            font-size: 0.9rem;
        }

        th, td {
            padding: 12px 15px;
            white-space: nowrap;
        }

        .action-buttons {
            flex-direction: column;
            gap: 6px;
            min-width: 120px;
        }

        .btn-action {
            justify-content: center;
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .pagination {
            gap: 5px;
        }

        .page-link {
            padding: 8px 12px;
            font-size: 0.9rem;
        }
    }

    @media (max-width: 480px) {
        .main-content {
            padding: 10px;
        }

        .page-header {
            padding: 15px;
            margin-bottom: 15px;
        }

        .page-title {
            font-size: 1.4rem;
        }

        .page-subtitle {
            font-size: 0.9rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-value {
            font-size: 1.5rem;
        }

        .filters-section {
            padding: 15px;
            margin-bottom: 20px;
        }

        .filters-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .data-table {
            margin: 0 -10px;
        }

        th, td {
            padding: 10px 12px;
            font-size: 0.85rem;
        }

        .action-buttons {
            flex-direction: column;
            gap: 4px;
            min-width: 100px;
        }

        .btn-action {
            padding: 5px 10px;
            font-size: 0.75rem;
        }

        .pagination {
            gap: 3px;
            margin: 20px 0;
        }

        .page-link {
            padding: 6px 10px;
            font-size: 0.8rem;
        }
    }
</style>

<div class="main-content">
    <div class="page-header">
        <div class="header-content">
            <div class="header-text">
                <h1 class="page-title">⚠️ Manajemen Pembatalan</h1>
                <p class="page-subtitle">Sistem approval untuk menerima atau menolak pengajuan pembatalan tiket bus</p>
            </div>
            <div class="header-actions">
                <a href="index.php" class="btn-primary">
                    <span>🔄</span> Refresh
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-header">
                <span class="stat-icon">📊</span>
            </div>
            <div class="stat-title">Total Pengajuan</div>
            <div class="stat-value"><?php echo number_format($total_cancellations); ?></div>
            <div class="stat-desc">semua pengajuan pembatalan</div>
        </div>

        <div class="stat-card pending">
            <div class="stat-header">
                <span class="stat-icon">⏳</span>
                <span class="stat-badge"><?php echo $pending_cancellations; ?></span>
            </div>
            <div class="stat-title">Menunggu Approval</div>
            <div class="stat-value"><?php echo number_format($pending_cancellations); ?></div>
            <div class="stat-desc">perlu ditinjau</div>
        </div>

        <div class="stat-card approved">
            <div class="stat-header">
                <span class="stat-icon">✅</span>
            </div>
            <div class="stat-title">Disetujui</div>
            <div class="stat-value"><?php echo number_format($approved_cancellations); ?></div>
            <div class="stat-desc">pembatalan disetujui</div>
        </div>

        <div class="stat-card rejected">
            <div class="stat-header">
                <span class="stat-icon">❌</span>
            </div>
            <div class="stat-title">Ditolak</div>
            <div class="stat-value"><?php echo number_format($rejected_cancellations); ?></div>
            <div class="stat-desc">pembatalan ditolak</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <div class="filters-header">
            <span class="filters-icon">🔍</span>
            <h3 class="filters-title">Filter Pengajuan</h3>
        </div>
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label" for="search">Cari</label>
                    <input type="text" id="search" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="Kode booking atau nama penumpang..." class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="status">Status</label>
                    <select id="status" name="status" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo ($_GET['status'] ?? '') === 'pending' ? 'selected' : ''; ?>>Menunggu</option>
                        <option value="approved" <?php echo ($_GET['status'] ?? '') === 'approved' ? 'selected' : ''; ?>>Disetujui</option>
                        <option value="rejected" <?php echo ($_GET['status'] ?? '') === 'rejected' ? 'selected' : ''; ?>>Ditolak</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="date_from">Tanggal Dari</label>
                    <input type="date" id="date_from" name="date_from" value="<?php echo $_GET['date_from'] ?? ''; ?>" class="filter-input">
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="date_to">Tanggal Sampai</label>
                    <input type="date" id="date_to" name="date_to" value="<?php echo $_GET['date_to'] ?? ''; ?>" class="filter-input">
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">
                        <span>🔍</span> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Cancellations Table -->
    <div class="data-table">
        <div class="table-header">
            <span>⚠️</span>
            Daftar Pengajuan Pembatalan
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama Penumpang</th>
                        <th>Tanggal Keberangkatan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Alasan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Build query with filters
                    $where_conditions = [];
                    $params = [];
                    $types = "";

                    if (!empty($_GET['search'])) {
                        $search = "%" . mysqli_real_escape_string($koneksi, $_GET['search']) . "%";
                        $where_conditions[] = "(cr.booking_code LIKE ? OR u.full_name LIKE ?)";
                        $params[] = $search;
                        $params[] = $search;
                        $types .= "ss";
                    }

                    if (!empty($_GET['status'])) {
                        $where_conditions[] = "cr.status = ?";
                        $params[] = $_GET['status'];
                        $types .= "s";
                    }

                    if (!empty($_GET['date_from'])) {
                        $where_conditions[] = "DATE(cr.created_at) >= ?";
                        $params[] = $_GET['date_from'];
                        $types .= "s";
                    }

                    if (!empty($_GET['date_to'])) {
                        $where_conditions[] = "DATE(cr.created_at) <= ?";
                        $params[] = $_GET['date_to'];
                        $types .= "s";
                    }

                    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

                    // Pagination
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $per_page = 10;
                    $offset = ($page - 1) * $per_page;

                    // Count total records
                    $count_query = "SELECT COUNT(*) as total FROM cancellation_requests cr
                                   LEFT JOIN bookings b ON cr.booking_id = b.id
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
                    $query = "SELECT cr.*, b.booking_code, b.total_amount, u.full_name,
                             CONCAT(r.origin, ' → ', r.destination) as route,
                             s.departure_time, s.departure_date
                             FROM cancellation_requests cr
                             LEFT JOIN bookings b ON cr.booking_id = b.id
                             LEFT JOIN users u ON b.user_id = u.id
                             LEFT JOIN schedules s ON b.schedule_id = s.id
                             LEFT JOIN routes r ON s.route_id = r.id
                             $where_clause
                             ORDER BY cr.created_at DESC
                             LIMIT ? OFFSET ?";

                    $stmt = mysqli_prepare($koneksi, $query);
                    $params[] = $per_page;
                    $params[] = $offset;
                    $types .= "ii";
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($cancellation = mysqli_fetch_assoc($result)) {
                            $status_colors = [
                                'pending' => 'background: #fff3cd; color: #856404;',
                                'approved' => 'background: #d4edda; color: #155724;',
                                'rejected' => 'background: #f8d7da; color: #721c24;'
                            ];
                            $status_style = $status_colors[$cancellation['status']] ?? 'background: #e2e3e5; color: #383d41;';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cancellation['booking_code']); ?></td>
                                <td><?php echo htmlspecialchars($cancellation['full_name'] ?? 'N/A'); ?></td>
                                <td><?php echo format_date($cancellation['departure_date']); ?><br><small><?php echo format_time($cancellation['departure_time']); ?></small></td>
                                <td><?php echo format_date($cancellation['created_at']); ?></td>
                                <td><?php echo htmlspecialchars(substr($cancellation['reason'], 0, 50)) . (strlen($cancellation['reason']) > 50 ? '...' : ''); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo $cancellation['status']; ?>">
                                        <?php
                                        $status_labels = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'];
                                        echo $status_labels[$cancellation['status']] ?? ucfirst($cancellation['status']);
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view.php?id=<?php echo $cancellation['id']; ?>" class="btn-action btn-view">
                                            <span>👁️</span> Lihat Detail
                                        </a>
                                        <?php if ($cancellation['status'] === 'pending') { ?>
                                            <a href="approve.php?id=<?php echo $cancellation['id']; ?>" class="btn-action btn-approve" onclick="return confirm('Terima pengajuan pembatalan ini?')">
                                                <span>✅</span> Terima
                                            </a>
                                            <a href="reject.php?id=<?php echo $cancellation['id']; ?>" class="btn-action btn-reject" onclick="return confirm('Tolak pengajuan pembatalan ini?')">
                                                <span>❌</span> Tolak
                                            </a>
                                        <?php } ?>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="7" class="empty-state">
                                <div class="empty-icon">📋</div>
                                <div class="empty-title">Tidak ada pengajuan pembatalan</div>
                                <div class="empty-text">Belum ada pengajuan pembatalan yang sesuai dengan kriteria filter</div>
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
                $query_string = $_GET;
                unset($query_string['page']);

                for ($i = 1; $i <= $total_pages; $i++) {
                    $query_string['page'] = $i;
                    $url = '?' . http_build_query($query_string);
                    $active_class = $i === $page ? 'active' : '';
                    echo "<a href='$url' class='page-link $active_class'>$i</a>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<script>
// Auto-submit form on filter change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>

<?php include '../footer-admin.php'; ?>
