<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .promo-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .promo-hero {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 60px;
    }

    .promo-hero h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .promo-hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .promo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .promo-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .promo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .promo-image {
        height: 200px;
        background: linear-gradient(45deg, <?php echo $accent_orange; ?>, <?php echo $secondary_blue; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }

    .promo-content {
        padding: 30px;
    }

    .promo-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
    }

    .promo-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .promo-discount {
        font-size: 2rem;
        font-weight: bold;
        color: <?php echo $accent_orange; ?>;
        margin-bottom: 15px;
    }

    .promo-validity {
        color: #888;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    .btn-promo {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-promo:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
        transform: scale(1.05);
    }

    .promo-banner {
        background: linear-gradient(135deg, <?php echo $accent_orange; ?>, #ff6b35);
        color: white;
        padding: 40px;
        border-radius: 15px;
        text-align: center;
        margin-bottom: 60px;
    }

    .promo-banner h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .promo-banner p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .promo-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        margin-bottom: 60px;
    }

    .stat-item {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
    }

    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
    }

    .stat-label {
        color: #666;
        font-size: 1.1rem;
    }

    @media (max-width: 768px) {
        .promo-hero h1 {
            font-size: 2rem;
        }

        .promo-grid {
            grid-template-columns: 1fr;
        }

        .promo-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="promo-container">
    <!-- Hero Section -->
    <div class="promo-hero">
        <h1>🎉 Promo & Diskon Spesial</h1>
        <p>Hemat hingga 50% untuk perjalanan Anda! Promo terbatas, buruan pesan sekarang!</p>
    </div>

    <!-- Stats Section -->
    <div class="promo-stats">
        <div class="stat-item">
            <div class="stat-number">50%</div>
            <div class="stat-label">Diskon Maksimal</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">1000+</div>
            <div class="stat-label">Pelanggan Puas</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Layanan Support</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">15+</div>
            <div class="stat-label">Rute Tujuan</div>
        </div>
    </div>

    <!-- Special Banner -->
    <div class="promo-banner">
        <h2>🔥 FLASH SALE - Diskon 40%!</h2>
        <p>Promo terbatas untuk 100 pemesan pertama hari ini. Booking sekarang dan hemat biaya perjalanan Anda!</p>
        <a href="jadwal.php" class="btn-promo">Pesan Sekarang →</a>
    </div>

    <!-- Promo Grid -->
    <div class="promo-grid">
        <!-- Promo 1 -->
        <div class="promo-card">
            <div class="promo-image">🎟️</div>
            <div class="promo-content">
                <h3 class="promo-title">Early Bird Discount</h3>
                <p class="promo-description">Pesan tiket minimal 7 hari sebelum keberangkatan dan dapatkan diskon hingga 25%!</p>
                <div class="promo-discount">25% OFF</div>
                <div class="promo-validity">Berlaku untuk semua rute • S&K berlaku</div>
                <a href="jadwal.php" class="btn-promo">Booking Sekarang</a>
            </div>
        </div>

        <!-- Promo 2 -->
        <div class="promo-card">
            <div class="promo-image">👨‍👩‍👧‍👦</div>
            <div class="promo-content">
                <h3 class="promo-title">Family Package</h3>
                <p class="promo-description">Diskon spesial untuk keluarga (minimal 4 orang). Hemat hingga 30% untuk perjalanan bersama!</p>
                <div class="promo-discount">30% OFF</div>
                <div class="promo-validity">Minimal 4 orang • Berlaku weekday</div>
                <a href="jadwal.php" class="btn-promo">Lihat Detail</a>
            </div>
        </div>

        <!-- Promo 3 -->
        <div class="promo-card">
            <div class="promo-image">🎓</div>
            <div class="promo-content">
                <h3 class="promo-title">Student Discount</h3>
                <p class="promo-description">Diskon khusus mahasiswa dengan menunjukkan KTM/KRS yang masih berlaku.</p>
                <div class="promo-discount">20% OFF</div>
                <div class="promo-validity">Dengan KTM aktif • Semua rute</div>
                <a href="jadwal.php" class="btn-promo">Pesan Tiket</a>
            </div>
        </div>

        <!-- Promo 4 -->
        <div class="promo-card">
            <div class="promo-image">💼</div>
            <div class="promo-content">
                <h3 class="promo-title">Corporate Package</h3>
                <p class="promo-description">Paket khusus perusahaan dengan harga spesial untuk perjalanan dinas karyawan.</p>
                <div class="promo-discount">35% OFF</div>
                <div class="promo-validity">Minimal 10 tiket • Custom schedule</div>
                <a href="kontak.php" class="btn-promo">Hubungi Kami</a>
            </div>
        </div>

        <!-- Promo 5 -->
        <div class="promo-card">
            <div class="promo-image">🌙</div>
            <div class="promo-content">
                <h3 class="promo-title">Midnight Express</h3>
                <p class="promo-description">Diskon spesial untuk perjalanan malam hari (keberangkatan setelah jam 22:00).</p>
                <div class="promo-discount">15% OFF</div>
                <div class="promo-validity">Keberangkatan 22:00-05:00 • All routes</div>
                <a href="jadwal.php" class="btn-promo">Cek Jadwal</a>
            </div>
        </div>

        <!-- Promo 6 -->
        <div class="promo-card">
            <div class="promo-image">🔄</div>
            <div class="promo-content">
                <h3 class="promo-title">Round Trip Special</h3>
                <p class="promo-description">Hemat lebih banyak dengan paket pulang pergi. Diskon hingga 40% untuk rute PP!</p>
                <div class="promo-discount">40% OFF</div>
                <div class="promo-validity">Pulang pergi • Berlaku 30 hari</div>
                <a href="jadwal.php" class="btn-promo">Booking PP</a>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="promo-banner">
        <h2>🚀 Jangan Lewatkan Kesempatan Ini!</h2>
        <p>Promo bisa berubah sewaktu-waktu. Segera booking tiket Anda dan nikmati perjalanan nyaman dengan Sugeng Rahayu!</p>
        <a href="jadwal.php" class="btn-promo">Mulai Booking Tiket →</a>
    </div>
</div>

<?php include 'footer.php'; ?>