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

    .armada-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .hero-section {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: var(--light-cream);
        padding: 80px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .hero-section h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .hero-section p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .filter-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        margin-bottom: 60px;
        text-align: center;
    }

    .filter-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .filter-btn {
        background: #f8f9fa;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
        padding: 12px 25px;
        border-radius: 25px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
    }

    .filter-btn:hover, .filter-btn.active {
        background: var(--primary-blue);
        color: white;
    }

    .armada-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
        margin-bottom: 80px;
    }

    .bus-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
        opacity: 1;
        transform: scale(1);
    }

    .bus-card.hidden {
        opacity: 0;
        transform: scale(0.8);
        pointer-events: none;
    }

    .bus-card:hover {
        transform: translateY(-10px);
    }

    .bus-image {
        height: 250px;
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    .bus-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .bus-placeholder {
        font-size: 4rem;
        color: var(--primary-blue);
        opacity: 0.5;
    }

    .bus-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: var(--accent-orange);
        color: white;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .bus-content {
        padding: 30px;
    }

    .bus-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .bus-type {
        color: var(--accent-orange);
        font-weight: 500;
        margin-bottom: 20px;
        display: inline-block;
    }

    .bus-features {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 25px;
    }

    .feature-item {
        display: flex;
        align-items: center;
        font-size: 0.9rem;
        color: #666;
    }

    .feature-item i {
        margin-right: 8px;
        color: var(--primary-blue);
        width: 16px;
    }

    .bus-specs {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    .spec-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .spec-item:last-child {
        margin-bottom: 0;
    }

    .spec-label {
        color: #666;
    }

    .spec-value {
        font-weight: 500;
        color: var(--primary-blue);
    }

    .bus-price {
        text-align: center;
        margin-bottom: 20px;
    }

    .price-label {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .price-amount {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--accent-orange);
    }

    .btn-book {
        width: 100%;
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 15px;
        border-radius: 10px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }

    .btn-book:hover {
        background: var(--secondary-blue);
        transform: translateY(-2px);
    }

    .stats-section {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: var(--light-cream);
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 80px;
        text-align: center;
    }

    .stats-title {
        font-size: 2.5rem;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .stat-item {
        padding: 30px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 10px;
        display: block;
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .maintenance-section {
        margin-bottom: 80px;
    }

    .maintenance-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 60px;
        font-weight: bold;
    }

    .maintenance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .maintenance-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .maintenance-card:hover {
        transform: translateY(-10px);
    }

    .maintenance-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .maintenance-title-card {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 15px;
        font-weight: bold;
    }

    .maintenance-description {
        color: #666;
        line-height: 1.6;
    }

    .safety-section {
        background: #f8f9fa;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .safety-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 40px;
        font-weight: bold;
    }

    .safety-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .safety-item {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .safety-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .safety-title-item {
        font-size: 1.2rem;
        color: var(--primary-blue);
        margin-bottom: 10px;
        font-weight: bold;
    }

    .safety-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .cta-section {
        background: linear-gradient(135deg, <?php echo $accent_orange; ?>, #ff6b35);
        color: white;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
    }

    .cta-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .cta-section p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .btn-cta {
        background: white;
        color: var(--accent-orange);
        padding: 15px 40px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-cta:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .filter-buttons {
            flex-direction: column;
            align-items: center;
        }

        .armada-grid {
            grid-template-columns: 1fr;
        }

        .bus-features {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .maintenance-grid {
            grid-template-columns: 1fr;
        }

        .safety-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="armada-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>🚌 Armada Modern</h1>
        <p>Pilih armada terbaik untuk perjalanan Anda. Semua bus kami dilengkapi dengan fasilitas modern dan standar keselamatan tertinggi.</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <h2 style="color: <?php echo $primary_blue; ?>; margin-bottom: 30px; font-size: 2rem;">Pilih Tipe Armada</h2>
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">Semua Armada</button>
            <button class="filter-btn" data-filter="premium">Premium</button>
            <button class="filter-btn" data-filter="executive">Executive</button>
            <button class="filter-btn" data-filter="vip">VIP</button>
            <button class="filter-btn" data-filter="regular">Regular</button>
        </div>
    </div>

    <!-- Armada Grid -->
    <div class="armada-grid">
        <!-- Premium Bus -->
        <div class="bus-card" data-type="premium">
            <div class="bus-image">
                <div class="bus-placeholder">🚌</div>
                <div class="bus-badge">Premium</div>
            </div>
            <div class="bus-content">
                <h3 class="bus-title">Sugeng Rahayu Premium</h3>
                <span class="bus-type">Premium Class</span>

                <div class="bus-features">
                    <div class="feature-item"><i class="fas fa-wifi"></i> Free WiFi</div>
                    <div class="feature-item"><i class="fas fa-utensils"></i> Makanan & Minuman</div>
                    <div class="feature-item"><i class="fas fa-tv"></i> Entertainment</div>
                    <div class="feature-item"><i class="fas fa-snowflake"></i> AC</div>
                    <div class="feature-item"><i class="fas fa-bed"></i> Reclining Seats</div>
                    <div class="feature-item"><i class="fas fa-plug"></i> Power Outlet</div>
                </div>

                <div class="bus-specs">
                    <div class="spec-item">
                        <span class="spec-label">Kapasitas</span>
                        <span class="spec-value">36 Penumpang</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Tahun</span>
                        <span class="spec-value">2023</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Fasilitas Toilet</span>
                        <span class="spec-value">Ya</span>
                    </div>
                </div>

                <div class="bus-price">
                    <div class="price-label">Mulai dari</div>
                    <div class="price-amount">Rp 150.000</div>
                </div>

                <a href="booking/index.php" class="btn-book">Pesan Sekarang</a>
            </div>
        </div>

        <!-- Executive Bus -->
        <div class="bus-card" data-type="executive">
            <div class="bus-image">
                <div class="bus-placeholder">🚌</div>
                <div class="bus-badge">Executive</div>
            </div>
            <div class="bus-content">
                <h3 class="bus-title">Sugeng Rahayu Executive</h3>
                <span class="bus-type">Executive Class</span>

                <div class="bus-features">
                    <div class="feature-item"><i class="fas fa-wifi"></i> Free WiFi</div>
                    <div class="feature-item"><i class="fas fa-utensils"></i> Snack & Minuman</div>
                    <div class="feature-item"><i class="fas fa-tv"></i> Entertainment</div>
                    <div class="feature-item"><i class="fas fa-snowflake"></i> AC</div>
                    <div class="feature-item"><i class="fas fa-bed"></i> Reclining Seats</div>
                    <div class="feature-item"><i class="fas fa-plug"></i> Power Outlet</div>
                </div>

                <div class="bus-specs">
                    <div class="spec-item">
                        <span class="spec-label">Kapasitas</span>
                        <span class="spec-value">40 Penumpang</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Tahun</span>
                        <span class="spec-value">2022</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Fasilitas Toilet</span>
                        <span class="spec-value">Ya</span>
                    </div>
                </div>

                <div class="bus-price">
                    <div class="price-label">Mulai dari</div>
                    <div class="price-amount">Rp 120.000</div>
                </div>

                <a href="booking/index.php" class="btn-book">Pesan Sekarang</a>
            </div>
        </div>

        <!-- VIP Bus -->
        <div class="bus-card" data-type="vip">
            <div class="bus-image">
                <div class="bus-placeholder">🚌</div>
                <div class="bus-badge">VIP</div>
            </div>
            <div class="bus-content">
                <h3 class="bus-title">Sugeng Rahayu VIP</h3>
                <span class="bus-type">VIP Class</span>

                <div class="bus-features">
                    <div class="feature-item"><i class="fas fa-wifi"></i> Free WiFi</div>
                    <div class="feature-item"><i class="fas fa-utensils"></i> Premium Meal</div>
                    <div class="feature-item"><i class="fas fa-tv"></i> Premium Entertainment</div>
                    <div class="feature-item"><i class="fas fa-snowflake"></i> AC Premium</div>
                    <div class="feature-item"><i class="fas fa-bed"></i> Luxury Seats</div>
                    <div class="feature-item"><i class="fas fa-plug"></i> Power Outlet</div>
                </div>

                <div class="bus-specs">
                    <div class="spec-item">
                        <span class="spec-label">Kapasitas</span>
                        <span class="spec-value">28 Penumpang</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Tahun</span>
                        <span class="spec-value">2024</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Fasilitas Toilet</span>
                        <span class="spec-value">Ya</span>
                    </div>
                </div>

                <div class="bus-price">
                    <div class="price-label">Mulai dari</div>
                    <div class="price-amount">Rp 200.000</div>
                </div>

                <a href="booking/index.php" class="btn-book">Pesan Sekarang</a>
            </div>
        </div>

        <!-- Regular Bus -->
        <div class="bus-card" data-type="regular">
            <div class="bus-image">
                <div class="bus-placeholder">🚌</div>
                <div class="bus-badge">Regular</div>
            </div>
            <div class="bus-content">
                <h3 class="bus-title">Sugeng Rahayu Regular</h3>
                <span class="bus-type">Regular Class</span>

                <div class="bus-features">
                    <div class="feature-item"><i class="fas fa-snowflake"></i> AC</div>
                    <div class="feature-item"><i class="fas fa-tv"></i> Basic Entertainment</div>
                    <div class="feature-item"><i class="fas fa-bed"></i> Standard Seats</div>
                    <div class="feature-item"><i class="fas fa-plug"></i> Power Outlet</div>
                    <div class="feature-item"><i class="fas fa-utensils"></i> Snack Optional</div>
                    <div class="feature-item"><i class="fas fa-wifi"></i> WiFi Optional</div>
                </div>

                <div class="bus-specs">
                    <div class="spec-item">
                        <span class="spec-label">Kapasitas</span>
                        <span class="spec-value">50 Penumpang</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Tahun</span>
                        <span class="spec-value">2021</span>
                    </div>
                    <div class="spec-item">
                        <span class="spec-label">Fasilitas Toilet</span>
                        <span class="spec-value">Ya</span>
                    </div>
                </div>

                <div class="bus-price">
                    <div class="price-label">Mulai dari</div>
                    <div class="price-amount">Rp 80.000</div>
                </div>

                <a href="booking/index.php" class="btn-book">Pesan Sekarang</a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <h2 class="stats-title">Armada Kami Dalam Angka</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <span class="stat-number">45</span>
                <span class="stat-label">Unit Bus Modern</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">98%</span>
                <span class="stat-label">Tingkat Kenyamanan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">100%</span>
                <span class="stat-label">Standar Keselamatan</span>
            </div>
            <div class="stat-item">
                <span class="stat-number">24/7</span>
                <span class="stat-label">Layanan Support</span>
            </div>
        </div>
    </div>

    <!-- Maintenance Section -->
    <div class="maintenance-section">
        <h2 class="maintenance-title">Komitmen Perawatan</h2>
        <div class="maintenance-grid">
            <div class="maintenance-card">
                <div class="maintenance-icon">🔧</div>
                <h3 class="maintenance-title-card">Perawatan Rutin</h3>
                <p class="maintenance-description">Setiap armada menjalani perawatan rutin mingguan untuk memastikan kondisi optimal dan keselamatan maksimal.</p>
            </div>
            <div class="maintenance-card">
                <div class="maintenance-icon">📊</div>
                <h3 class="maintenance-title-card">Monitoring Real-time</h3>
                <p class="maintenance-description">Sistem monitoring 24/7 untuk melacak performa armada dan mengidentifikasi masalah sebelum terjadi.</p>
            </div>
            <div class="maintenance-card">
                <div class="maintenance-icon">🏆</div>
                <h3 class="maintenance-title-card">Standar Internasional</h3>
                <p class="maintenance-description">Semua perawatan mengikuti standar internasional dan sertifikasi dari lembaga terkait.</p>
            </div>
        </div>
    </div>

    <!-- Safety Section -->
    <div class="safety-section">
        <h2 class="safety-title">Fitur Keselamatan</h2>
        <div class="safety-grid">
            <div class="safety-item">
                <div class="safety-icon">🛡️</div>
                <h3 class="safety-title-item">ABS & EBD</h3>
                <p class="safety-description">Sistem pengereman canggih untuk menghindari kecelakaan</p>
            </div>
            <div class="safety-item">
                <div class="safety-icon">👁️</div>
                <h3 class="safety-title-item">Sensor Parkir</h3>
                <p class="safety-description">Bantuan parkir untuk kemudahan dan keselamatan</p>
            </div>
            <div class="safety-item">
                <div class="safety-icon">💡</div>
                <h3 class="safety-title-item">Lampu LED</h3>
                <p class="safety-description">Pencahayaan optimal untuk visibilitas maksimal</p>
            </div>
            <div class="safety-item">
                <div class="safety-icon">🚨</div>
                <h3 class="safety-title-item">GPS Tracking</h3>
                <p class="safety-description">Pelacakan real-time untuk monitoring perjalanan</p>
            </div>
            <div class="safety-item">
                <div class="safety-icon">🪑</div>
                <h3 class="safety-title-item">Seat Belt</h3>
                <p class="safety-description">Sabuk pengaman untuk setiap penumpang</p>
            </div>
            <div class="safety-item">
                <div class="safety-icon">🚪</div>
                <h3 class="safety-title-item">Emergency Exit</h3>
                <p class="safety-description">Pintu darurat yang mudah diakses</p>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Siap untuk Perjalanan Nyaman?</h2>
        <p>Pilih armada favorit Anda dan nikmati perjalanan yang aman serta nyaman bersama Sugeng Rahayu</p>
        <a href="jadwal.php" class="btn-cta">Cari Jadwal Sekarang →</a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const busCards = document.querySelectorAll('.bus-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all buttons
            filterButtons.forEach(btn => btn.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            busCards.forEach(card => {
                if (filterValue === 'all' || card.getAttribute('data-type') === filterValue) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });
    });
});
</script>

<?php include 'footer.php'; ?>