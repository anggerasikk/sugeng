<?php 

require_once '../config.php';
require_once '../includes/functions.php';
include 'header.php';

// Get routes for search form
$routes = get_routes();

// Get blog posts for news section
$blog_posts = get_blog_posts(null, 3);
?>

<style>
    /* Reset & Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Hero Section with Search Form */
    .hero-section {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 80px 0 60px;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        text-align: center;
        margin-bottom: 40px;
    }

    .hero-content h1 {
        font-size: 3.5rem;
        margin-bottom: 20px;
        font-weight: 700;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
    }

    .hero-content p {
        font-size: 1.3rem;
        max-width: 800px;
        margin: 0 auto 30px;
        opacity: 0.95;
    }

    /* Search Form - Based on first image */
    .search-form-container {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        margin-top: 30px;
    }

    .search-form-title {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 25px;
        font-size: 1.5rem;
        text-align: center;
        font-weight: 600;
    }

    .search-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        align-items: end; /* Mengatur semua item sejajar di bagian bawah */
    }

    .form-group {
        margin-bottom: 0;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .form-select, .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        height: 48px;
        margin-top: auto; /* Mendorong ke bawah untuk sejajar */
    }

    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: <?php echo $accent_orange; ?>;
        box-shadow: 0 0 0 3px rgba(230, 115, 0, 0.1);
    }

    .search-button {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        height: 48px;
        width: 100%;
        margin-top: auto; /* Mendorong ke bawah untuk sejajar */
    }

    .search-button:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    /* Promotion Section */
    .promotion-section {
        padding: 80px 0;
        background: <?php echo $light_cream; ?>;
    }

    .section-title {
        text-align: center;
        color: <?php echo $primary_blue; ?>;
        font-size: 2.5rem;
        margin-bottom: 50px;
        position: relative;
    }

    .section-title::after {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 4px;
        background: <?php echo $accent_orange; ?>;
        border-radius: 2px;
    }

    .promotion-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 40px;
        align-items: center;
    }

    .promotion-image {
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        height: 400px;
        background: linear-gradient(45deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }

    .promotion-content h2 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 20px;
        font-size: 2rem;
    }

    .promotion-content p {
        color: #555;
        line-height: 1.8;
        margin-bottom: 30px;
        font-size: 1.1rem;
    }

    .promo-button {
        display: inline-block;
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 12px 30px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .promo-button:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.2);
    }

    /* Features Section */
    .features-section {
        padding: 80px 0;
        background: white;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
    }

    .feature-card {
        text-align: center;
        padding: 30px 20px;
        background: #f8f9fa;
        border-radius: 10px;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        border-color: <?php echo $accent_orange; ?>;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .feature-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 1.8rem;
    }

    .feature-card h3 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        font-size: 1.3rem;
    }

    .feature-card p {
        color: #666;
        line-height: 1.6;
    }

    /* Stats Section with Counting Animation */
    .stats-section {
        padding: 80px 0;
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        text-align: center;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
    }

    .stat-item h2 {
        font-size: 3.5rem;
        margin-bottom: 10px;
        font-weight: 700;
        font-variant-numeric: tabular-nums;
    }

    .stat-item p {
        font-size: 1.2rem;
        opacity: 0.9;
    }

    /* Latest News Section */
    .news-section {
        padding: 80px 0;
        background: <?php echo $light_cream; ?>;
    }

    .news-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 40px;
    }

    .news-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }

    .news-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    .news-image {
        height: 200px;
        background: linear-gradient(45deg, <?php echo $secondary_blue; ?>, <?php echo $accent_orange; ?>);
        position: relative;
        overflow: hidden;
    }

    .news-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.2), rgba(0,0,0,0.1));
    }

    .news-image .icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 3rem;
        color: white;
    }

    .news-content {
        padding: 25px;
    }

    .news-content h3 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        font-size: 1.3rem;
        line-height: 1.4;
    }

    .news-content p {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .news-date {
        color: <?php echo $accent_orange; ?>;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Destinations Section */
    .destinations-section {
        padding: 80px 0;
        background: white;
    }

    .destinations-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 30px;
        margin-top: 40px;
    }

    .destination-card {
        background: <?php echo $light_cream; ?>;
        border-radius: 10px;
        padding: 30px 20px;
        text-align: center;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .destination-card:hover {
        transform: translateY(-10px);
        border-color: <?php echo $accent_orange; ?>;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    .destination-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 1.5rem;
    }

    .destination-card h3 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
        font-size: 1.2rem;
    }

    .destination-card p {
        color: #666;
        font-size: 0.9rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .features-grid,
        .stats-grid,
        .destinations-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .news-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .promotion-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }
    }

    @media (max-width: 768px) {
        .hero-content h1 {
            font-size: 2.5rem;
        }
        
        .hero-content p {
            font-size: 1.1rem;
        }
        
        .section-title {
            font-size: 2rem;
        }
        
        .form-row {
            grid-template-columns: 1fr;
        }
        
        .features-grid,
        .stats-grid,
        .news-grid,
        .destinations-grid {
            grid-template-columns: 1fr;
        }
        
        .stat-item h2 {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 480px) {
        .container {
            padding: 0 15px;
        }
        
        .hero-section {
            padding: 60px 0 40px;
        }
        
        .hero-content h1 {
            font-size: 2rem;
        }
        
        .search-form-container {
            padding: 20px;
        }
        
        .section-title {
            font-size: 1.8rem;
        }
        
        .promotion-image {
            height: 250px;
        }
    }
</style>

<script>
    // Counter Animation
    document.addEventListener('DOMContentLoaded', function() {
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            const increment = target / (duration / 16); // 60fps
            const timer = setInterval(() => {
                start += increment;
                if (start >= target) {
                    element.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    element.textContent = Math.floor(start).toLocaleString();
                }
            }, 16);
        }
        
        // Animate counters when in view
        function checkCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const rect = counter.getBoundingClientRect();
                const isInView = rect.top < window.innerHeight && rect.bottom >= 0;
                
                if (isInView && !counter.hasAttribute('data-animated')) {
                    const target = parseInt(counter.getAttribute('data-target'));
                    animateCounter(counter, target);
                    counter.setAttribute('data-animated', 'true');
                }
            });
        }
        
        // Initialize counters
        window.addEventListener('scroll', checkCounters);
        window.addEventListener('load', checkCounters);
    });
</script>

<!-- Hero Section with Search Form -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <h1>Keramahan Awak Bus</h1>
            <p>Awak Bus Sugeng Rahayu selalu melayani Anda dengan sepenuh hati dan profesionalisme.</p>
        </div>

        <!-- Search Form based on first image -->
        <div class="search-form-container">
            <h3 class="search-form-title">Cari Tiket Bus</h3>
            <form class="search-form">
                <!-- Row 1: Asal, Tujuan, Tanggal Pergi -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="origin">Asal</label>
                        <select class="form-select" id="origin" required>
                            <option value="">Pilih Asal</option>
                            <option value="jakarta">Jakarta</option>
                            <option value="bandung">Bandung</option>
                            <option value="surabaya">Surabaya</option>
                            <option value="yogyakarta">Yogyakarta</option>
                            <option value="semarang">Semarang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="destination">Tujuan</label>
                        <select class="form-select" id="destination" required>
                            <option value="">Pilih Tujuan</option>
                            <option value="bali">Bali</option>
                            <option value="malang">Malang</option>
                            <option value="solo">Solo</option>
                            <option value="medan">Medan</option>
                            <option value="palembang">Palembang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="departure-date">Tanggal Pergi</label>
                        <input type="date" class="form-input" id="departure-date" required>
                    </div>
                </div>

                <!-- Row 2: Kelas, Jumlah Penumpang, Button -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="class">Kelas Armada</label>
                        <select class="form-select" id="class">
                            <option value="">Semua Kelas Armada</option>
                            <option value="ekonomi">Ekonomi</option>
                            <option value="bisnis">Bisnis</option>
                            <option value="executive">Executive</option>
                            <option value="premium">Premium</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="passengers">Jumlah Penumpang</label>
                        <select class="form-select" id="passengers">
                            <option value="1">1 Penumpang</option>
                            <option value="2">2 Penumpang</option>
                            <option value="3">3 Penumpang</option>
                            <option value="4">4 Penumpang</option>
                            <option value="5">5+ Penumpang</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="search-button" style="visibility: hidden;">Cari</label>
                        <button type="submit" class="search-button" id="search-button">Cari Tiket</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Promotion Section -->
<section class="promotion-section">
    <div class="container">
        <h2 class="section-title">Promo Spesial</h2>
        <div class="promotion-grid">
            <div class="promotion-image">
                <div style="text-align: center; color: white;">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🎉</div>
                    <p>Diskon 50% Tiket Bus</p>
                    <p style="font-size: 1.2rem; margin-top: 10px;">Hari Ulang Tahun Membership</p>
                </div>
            </div>
            <div class="promotion-content">
                <h2>Nikmati Perjalanan Nyaman dengan Harga Terjangkau</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                <a href="#" class="promo-button">Lihat Promo Lainnya</a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="container">
        <h2 class="section-title">Keunggulan Kami</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">🛡️</div>
                <h3>Keamanan Terjamin</h3>
                <p>Armada terawat dengan standar keselamatan tinggi dan awak bus berpengalaman.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">⏰</div>
                <h3>Ketepatan Waktu</h3>
                <p>Jadwal yang tepat dan real-time tracking untuk perjalanan yang terprediksi.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">💺</div>
                <h3>Kenyamanan Maksimal</h3>
                <p>Kursi ergonomis, AC, dan fasilitas lengkap untuk perjalanan yang menyenangkan.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">💰</div>
                <h3>Harga Terjangkau</h3>
                <p>Tarif kompetitif dengan berbagai pilihan kelas sesuai kebutuhan Anda.</p>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section with Counting Animation -->
<section class="stats-section">
    <div class="container">
        <h2 class="section-title" style="color: white;">Prestasi Kami</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <h2 class="counter" data-target="500">0</h2>
                <p>Armada Bus</p>
            </div>

            <div class="stat-item">
                <h2 class="counter" data-target="100">0</h2>
                <p>Kota Tujuan</p>
            </div>

            <div class="stat-item">
                <h2 class="counter" data-target="1000000">0</h2>
                <p>Penumpang Setia</p>
            </div>

            <div class="stat-item">
                <h2 class="counter" data-target="25">0</h2>
                <p>Tahun Pengalaman</p>
            </div>
        </div>
    </div>
</section>

<!-- Latest News Section -->
<section class="news-section">
    <div class="container">
        <h2 class="section-title">Berita Terbaru</h2>
        <div class="news-grid">
            <div class="news-card">
                <div class="news-image">
                    <div class="icon">🚌</div>
                </div>
                <div class="news-content">
                    <h3>Sistem Booking Online Terbaru dengan Fitur Real-Time Tracking</h3>
                    <p>Nikmati kemudahan booking tiket online dengan fitur tracking perjalanan secara real-time.</p>
                    <div class="news-date">📅 27 November 2025</div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">
                    <div class="icon">🌱</div>
                </div>
                <div class="news-content">
                    <h3>Komitmen Kurangi Emisi dengan Bus Ramah Lingkungan</h3>
                    <p>Kami berkomitmen menggunakan teknologi ramah lingkungan untuk masa depan yang lebih baik.</p>
                    <div class="news-date">📅 18 November 2025</div>
                </div>
            </div>

            <div class="news-card">
                <div class="news-image">
                    <div class="icon">🎯</div>
                </div>
                <div class="news-content">
                    <h3>Rute Baru Jawa-Bali dengan Fasilitas Premium</h3>
                    <p>Temukan kenyamanan baru dengan rute terbaru dan fasilitas premium yang kami tawarkan.</p>
                    <div class="news-date">📅 10 November 2025</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Destinations Section -->
<section class="destinations-section">
    <div class="container">
        <h2 class="section-title">Destinasi Populer</h2>
        <div class="destinations-grid">
            <div class="destination-card">
                <div class="destination-icon">🏙️</div>
                <h3>Jakarta</h3>
                <p>Ibu kota dengan berbagai destinasi menarik</p>
            </div>

            <div class="destination-card">
                <div class="destination-icon">🌋</div>
                <h3>Bandung</h3>
                <p>Kota kembang dengan udara sejuk</p>
            </div>

            <div class="destination-card">
                <div class="destination-icon">🏯</div>
                <h3>Yogyakarta</h3>
                <p>Kota budaya dan pendidikan</p>
            </div>

            <div class="destination-card">
                <div class="destination-icon">🏝️</div>
                <h3>Bali</h3>
                <p>Pulau dewata dengan pesona alam</p>
            </div>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>