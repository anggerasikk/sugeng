<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    :root {
        --primary-blue: <?php echo $primary_blue; ?>;
        --secondary-blue: <?php echo $secondary_blue; ?>;
        --accent-orange: <?php echo $accent_orange; ?>;
        --light-cream: <?php echo $light_cream; ?>;
    }

    .jadwal-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .jadwal-hero {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: var(--light-cream);
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 60px;
    }

    .jadwal-hero h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .jadwal-hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .search-section {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 60px;
    }

    .search-title {
        text-align: center;
        color: var(--primary-blue);
        font-size: 2rem;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .search-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 8px;
        color: var(--primary-blue);
        font-weight: 500;
    }

    .form-group input,
    .form-group select {
        padding: 12px;
        border: 2px solid #e1e1e1;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--accent-orange);
    }

    .btn-search {
        background: var(--accent-orange);
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-search:hover {
        background: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .jadwal-results {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 60px;
    }

    .results-header {
        background: var(--primary-blue);
        color: white;
        padding: 20px;
        font-weight: bold;
        font-size: 1.2rem;
    }

    .jadwal-item {
        border-bottom: 1px solid #eee;
        padding: 30px;
        transition: background-color 0.3s ease;
    }

    .jadwal-item:hover {
        background: #f8f9fa;
    }

    .jadwal-item:last-child {
        border-bottom: none;
    }

    .jadwal-route {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .route-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .route-cities {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-blue);
    }

    .route-arrow {
        font-size: 1.5rem;
        color: var(--accent-orange);
    }

    .jadwal-time {
        text-align: right;
        font-size: 1.2rem;
        font-weight: 500;
    }

    .jadwal-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .detail-value {
        font-weight: 500;
        color: var(--primary-blue);
    }

    .jadwal-facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .facility-tag {
        background: var(--light-cream);
        color: var(--primary-blue);
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .jadwal-price {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .price-amount {
        font-size: 1.8rem;
        font-weight: bold;
        color: var(--accent-orange);
    }

    .btn-book {
        background: var(--accent-orange);
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-book:hover {
        background: var(--primary-blue);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .no-results {
        text-align: center;
        padding: 60px 20px;
        color: #666;
    }

    .no-results h3 {
        color: var(--primary-blue);
        margin-bottom: 15px;
    }

    .popular-routes {
        margin-bottom: 60px;
    }

    .popular-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 40px;
        font-weight: bold;
    }

    .routes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .route-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 30px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .route-card:hover {
        transform: translateY(-10px);
    }

    .route-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .route-name {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 15px;
    }

    .route-desc {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .route-stats {
        display: flex;
        justify-content: space-around;
        margin-bottom: 20px;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--accent-orange);
    }

    .stat-label {
        font-size: 0.8rem;
        color: #666;
    }

    @media (max-width: 768px) {
        .jadwal-hero h1 {
            font-size: 2rem;
        }

        .search-form {
            grid-template-columns: 1fr;
        }

        .jadwal-route {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .jadwal-details {
            grid-template-columns: 1fr;
        }

        .jadwal-price {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .routes-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="jadwal-container">
    <!-- Hero Section -->
    <div class="jadwal-hero">
        <h1>🚌 Jadwal Perjalanan</h1>
        <p>Cek jadwal keberangkatan bus dan pesan tiket Anda dengan mudah</p>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <h2 class="search-title">Cari Jadwal Perjalanan</h2>
        <form method="GET" action="">
            <div class="search-form">
                <div class="form-group">
                    <label for="origin">Asal</label>
                    <select id="origin" name="origin">
                        <option value="">Pilih Kota Asal</option>
                        <option value="Jakarta" <?php echo ($_GET['origin'] ?? '') === 'Jakarta' ? 'selected' : ''; ?>>Jakarta</option>
                        <option value="Bandung" <?php echo ($_GET['origin'] ?? '') === 'Bandung' ? 'selected' : ''; ?>>Bandung</option>
                        <option value="Yogyakarta" <?php echo ($_GET['origin'] ?? '') === 'Yogyakarta' ? 'selected' : ''; ?>>Yogyakarta</option>
                        <option value="Surabaya" <?php echo ($_GET['origin'] ?? '') === 'Surabaya' ? 'selected' : ''; ?>>Surabaya</option>
                        <option value="Semarang" <?php echo ($_GET['origin'] ?? '') === 'Semarang' ? 'selected' : ''; ?>>Semarang</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination">Tujuan</label>
                    <select id="destination" name="destination">
                        <option value="">Pilih Kota Tujuan</option>
                        <option value="Jakarta" <?php echo ($_GET['destination'] ?? '') === 'Jakarta' ? 'selected' : ''; ?>>Jakarta</option>
                        <option value="Bandung" <?php echo ($_GET['destination'] ?? '') === 'Bandung' ? 'selected' : ''; ?>>Bandung</option>
                        <option value="Yogyakarta" <?php echo ($_GET['destination'] ?? '') === 'Yogyakarta' ? 'selected' : ''; ?>>Yogyakarta</option>
                        <option value="Surabaya" <?php echo ($_GET['destination'] ?? '') === 'Surabaya' ? 'selected' : ''; ?>>Surabaya</option>
                        <option value="Bali" <?php echo ($_GET['destination'] ?? '') === 'Bali' ? 'selected' : ''; ?>>Bali</option>
                        <option value="Semarang" <?php echo ($_GET['destination'] ?? '') === 'Semarang' ? 'selected' : ''; ?>>Semarang</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <input type="date" id="date" name="date" value="<?php echo $_GET['date'] ?? date('Y-m-d'); ?>" min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="form-group">
                    <button type="submit" class="btn-search">🔍 Cari Jadwal</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <?php if ($_SERVER['REQUEST_METHOD'] === 'GET' && (!empty($_GET['origin']) || !empty($_GET['destination']) || !empty($_GET['date']))) { ?>
        <div class="jadwal-results">
            <div class="results-header">
                Hasil Pencarian Jadwal
            </div>

            <?php
            // Build query
            $where_conditions = ["s.status = 'active'"];
            $params = [];
            $types = "";

            if (!empty($_GET['origin'])) {
                $where_conditions[] = "r.origin = ?";
                $params[] = $_GET['origin'];
                $types .= "s";
            }

            if (!empty($_GET['destination'])) {
                $where_conditions[] = "r.destination = ?";
                $params[] = $_GET['destination'];
                $types .= "s";
            }

            if (!empty($_GET['date'])) {
                $where_conditions[] = "s.departure_date = ?";
                $params[] = $_GET['date'];
                $types .= "s";
            }

            $where_clause = implode(" AND ", $where_conditions);
            $query = "SELECT s.*, r.origin, r.destination, r.estimated_duration, bt.name as bus_type_name, bt.facilities FROM schedules s JOIN routes r ON s.route_id = r.id JOIN bus_types bt ON s.bus_type_id = bt.id WHERE $where_clause ORDER BY s.departure_time ASC";

            $stmt = mysqli_prepare($koneksi, $query);
            if (!empty($params)) {
                mysqli_stmt_bind_param($stmt, $types, ...$params);
            }
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                while ($schedule = mysqli_fetch_assoc($result)) {
                    // Calculate available seats
                    $booked_query = "SELECT COUNT(*) as booked FROM bookings WHERE schedule_id = ? AND booking_status IN ('confirmed', 'checked_in')";
                    $booked_stmt = mysqli_prepare($koneksi, $booked_query);
                    mysqli_stmt_bind_param($booked_stmt, "i", $schedule['id']);
                    mysqli_stmt_execute($booked_stmt);
                    $booked_result = mysqli_stmt_get_result($booked_stmt);
                    $booked = mysqli_fetch_assoc($booked_result)['booked'];
                    $available = $schedule['total_seats'] - $booked;

                    $facilities = explode(',', $schedule['facilities']);
                    ?>
                    <div class="jadwal-item">
                        <div class="jadwal-route">
                            <div class="route-info">
                                <div class="route-cities"><?php echo htmlspecialchars($schedule['origin']); ?> <span class="route-arrow">→</span> <?php echo htmlspecialchars($schedule['destination']); ?></div>
                            </div>
                            <div class="jadwal-time">
                                <?php echo format_time($schedule['departure_time']); ?> - <?php echo format_time($schedule['arrival_time']); ?>
                            </div>
                        </div>

                        <div class="jadwal-details">
                            <div class="detail-item">
                                <div class="detail-label">Bus</div>
                                <div class="detail-value">Bus <?php echo htmlspecialchars($schedule['id'] ?? 'N/A'); ?> (<?php echo htmlspecialchars($schedule['bus_type_name'] ?? 'N/A'); ?>)</div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Durasi</div>
                                <div class="detail-value"><?php echo htmlspecialchars($schedule['estimated_duration'] ?? 'N/A'); ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Kursi Tersedia</div>
                                <div class="detail-value"><?php echo $available; ?>/<?php echo $schedule['total_seats']; ?></div>
                            </div>
                            <div class="detail-item">
                                <div class="detail-label">Tanggal</div>
                                <div class="detail-value"><?php echo $schedule['departure_date'] ? format_date($schedule['departure_date']) : 'N/A'; ?></div>
                            </div>
                        </div>

                        <div class="jadwal-facilities">
                            <?php foreach ($facilities as $facility) { ?>
                                <span class="facility-tag"><?php echo htmlspecialchars(trim($facility)); ?></span>
                            <?php } ?>
                        </div>

                        <div class="jadwal-price">
                            <div class="price-amount"><?php echo format_currency($schedule['price']); ?></div>
                            <a href="booking/book-detail.php?id=<?php echo $schedule['id']; ?>" class="btn-book">Pesan Sekarang</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                ?>
                <div class="no-results">
                    <h3>😔 Tidak ada jadwal ditemukan</h3>
                    <p>Coba ubah kriteria pencarian Anda atau pilih tanggal lain.</p>
                </div>
                <?php
            }
            ?>
        </div>
    <?php } ?>

    <!-- Popular Routes Section -->
    <div class="popular-routes">
        <h2 class="popular-title">Rute Populer</h2>
        <div class="routes-grid">
            <div class="route-card">
                <div class="route-icon">🚍</div>
                <h3 class="route-name">Jakarta - Bandung</h3>
                <p class="route-desc">Rute terpopuler dengan perjalanan 3 jam. Tersedia berbagai pilihan bus dari ekonomi hingga executive.</p>
                <div class="route-stats">
                    <div class="stat-item">
                        <div class="stat-number">3j</div>
                        <div class="stat-label">Durasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">150k</div>
                        <div class="stat-label">Mulai dari</div>
                    </div>
                </div>
                <a href="?origin=Jakarta&destination=Bandung" class="btn-book">Lihat Jadwal</a>
            </div>

            <div class="route-card">
                <div class="route-icon">🚌</div>
                <h3 class="route-name">Jakarta - Yogyakarta</h3>
                <p class="route-desc">Perjalanan malam yang nyaman dengan fasilitas premium. Sampai pagi hari untuk memulai aktivitas.</p>
                <div class="route-stats">
                    <div class="stat-item">
                        <div class="stat-number">8j</div>
                        <div class="stat-label">Durasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">300k</div>
                        <div class="stat-label">Mulai dari</div>
                    </div>
                </div>
                <a href="?origin=Jakarta&destination=Yogyakarta" class="btn-book">Lihat Jadwal</a>
            </div>

            <div class="route-card">
                <div class="route-icon">🚐</div>
                <h3 class="route-name">Surabaya - Bali</h3>
                <p class="route-desc">Rute wisata favorit dengan pemandangan laut yang indah. Bus premium dengan fasilitas lengkap.</p>
                <div class="route-stats">
                    <div class="stat-item">
                        <div class="stat-number">8j</div>
                        <div class="stat-label">Durasi</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">350k</div>
                        <div class="stat-label">Mulai dari</div>
                    </div>
                </div>
                <a href="?origin=Surabaya&destination=Bali" class="btn-book">Lihat Jadwal</a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>