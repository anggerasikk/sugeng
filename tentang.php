<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .about-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .about-hero {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 80px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 60px;
    }

    .about-hero h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .about-hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .story-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        align-items: center;
        margin-bottom: 80px;
    }

    .story-content h2 {
        font-size: 2.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 30px;
        font-weight: bold;
    }

    .story-content p {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #555;
        margin-bottom: 20px;
    }

    .story-image {
        background: linear-gradient(45deg, <?php echo $accent_orange; ?>, <?php echo $secondary_blue; ?>);
        height: 400px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
    }

    .values-section {
        margin-bottom: 80px;
    }

    .values-title {
        text-align: center;
        font-size: 2.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 60px;
        font-weight: bold;
    }

    .values-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .value-card {
        background: white;
        padding: 40px 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .value-card:hover {
        transform: translateY(-10px);
    }

    .value-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .value-title {
        font-size: 1.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .value-description {
        color: #666;
        line-height: 1.6;
    }

    .stats-section {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 80px;
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
        text-align: center;
    }

    .stat-number {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .stat-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .team-section {
        margin-bottom: 80px;
    }

    .team-title {
        text-align: center;
        font-size: 2.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 60px;
        font-weight: bold;
    }

    .team-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
    }

    .team-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .team-card:hover {
        transform: translateY(-10px);
    }

    .team-image {
        height: 250px;
        background: linear-gradient(45deg, <?php echo $accent_orange; ?>, <?php echo $secondary_blue; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .team-content {
        padding: 30px;
    }

    .team-name {
        font-size: 1.3rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .team-position {
        color: <?php echo $accent_orange; ?>;
        margin-bottom: 15px;
        font-weight: 500;
    }

    .team-description {
        color: #666;
        line-height: 1.6;
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
        color: <?php echo $accent_orange; ?>;
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
        .about-hero h1 {
            font-size: 2rem;
        }

        .story-section {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .values-grid {
            grid-template-columns: 1fr;
        }

        .team-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="about-container">
    <!-- Hero Section -->
    <div class="about-hero">
        <h1>Tentang Sugeng Rahayu</h1>
        <p>Penyedia layanan transportasi bus terpercaya dengan pengalaman lebih dari 10 tahun melayani perjalanan antar kota Anda.</p>
    </div>

    <!-- Story Section -->
    <div class="story-section">
        <div class="story-content">
            <h2>Cerita Kami</h2>
            <p>Didirikan pada tahun 2013, Sugeng Rahayu dimulai dari sebuah mimpi sederhana: memberikan layanan transportasi yang aman, nyaman, dan terjangkau untuk semua orang. Dengan pengalaman lebih dari satu dekade, kami telah melayani jutaan penumpang dan menjadi pilihan utama untuk perjalanan antar kota di Indonesia.</p>
            <p>Kami percaya bahwa perjalanan bukan hanya tentang tiba di tujuan, tetapi juga tentang pengalaman yang menyenangkan selama perjalanan. Oleh karena itu, kami terus berinovasi untuk memberikan pelayanan terbaik dengan armada modern, crew profesional, dan teknologi terkini.</p>
            <p>Sejak awal, komitmen kami adalah memberikan pelayanan prima dengan harga yang kompetitif. Kami telah membangun reputasi sebagai perusahaan transportasi yang dapat diandalkan, dengan tingkat kepuasan pelanggan yang tinggi dan standar keselamatan yang ketat.</p>
        </div>
        <div class="story-image">🚌</div>
    </div>

    <!-- Values Section -->
    <div class="values-section">
        <h2 class="values-title">Nilai-Nilai Kami</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">🛡️</div>
                <h3 class="value-title">Keselamatan</h3>
                <p class="value-description">Keselamatan penumpang adalah prioritas utama kami. Semua armada kami dilengkapi dengan sistem keamanan terkini dan crew yang terlatih.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">⭐</div>
                <h3 class="value-title">Kualitas</h3>
                <p class="value-description">Kami berkomitmen memberikan pelayanan berkualitas tinggi dengan armada modern, fasilitas lengkap, dan crew profesional.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3 class="value-title">Kepercayaan</h3>
                <p class="value-description">Kepercayaan pelanggan adalah aset terbesar kami. Kami selalu menjaga integritas dan transparansi dalam setiap layanan.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🌱</div>
                <h3 class="value-title">Inovasi</h3>
                <p class="value-description">Kami terus berinovasi dengan teknologi terkini untuk memberikan pengalaman perjalanan yang lebih baik dan efisien.</p>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">10+</div>
                <div class="stat-label">Tahun Pengalaman</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-label">Unit Armada</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">2M+</div>
                <div class="stat-label">Penumpang Puas</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">15+</div>
                <div class="stat-label">Rute Tujuan</div>
            </div>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section">
        <h2 class="team-title">Tim Kami</h2>
        <div class="team-grid">
            <div class="team-card">
                <div class="team-image">👨‍💼</div>
                <div class="team-content">
                    <h3 class="team-name">Ahmad Sugeng</h3>
                    <h4 class="team-position">CEO & Founder</h4>
                    <p class="team-description">Berpengalaman lebih dari 15 tahun di industri transportasi, Ahmad memimpin visi dan misi perusahaan untuk memberikan pelayanan terbaik.</p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">👩‍💼</div>
                <div class="team-content">
                    <h3 class="team-name">Siti Rahayu</h3>
                    <h4 class="team-position">COO & Co-Founder</h4>
                    <p class="team-description">Ahli dalam manajemen operasional, Siti memastikan semua aspek operasional berjalan lancar dan efisien.</p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">👨‍🔧</div>
                <div class="team-content">
                    <h3 class="team-name">Budi Santoso</h3>
                    <h4 class="team-position">Head of Fleet Management</h4>
                    <p class="team-description">Bertanggung jawab atas pemeliharaan dan pengelolaan armada bus untuk memastikan keselamatan dan kenyamanan penumpang.</p>
                </div>
            </div>
            <div class="team-card">
                <div class="team-image">👩‍💻</div>
                <div class="team-content">
                    <h3 class="team-name">Maya Sari</h3>
                    <h4 class="team-position">Head of Customer Service</h4>
                    <p class="team-description">Memimpin tim customer service untuk memastikan kepuasan pelanggan dan menangani keluhan dengan profesional.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Bergabunglah Dengan Kami!</h2>
        <p>Jadilah bagian dari jutaan pelanggan yang telah mempercayai Sugeng Rahayu untuk perjalanan mereka. Pesan tiket sekarang dan rasakan pengalaman perjalanan yang berbeda!</p>
        <a href="booking/index.php" class="btn-cta">Mulai Perjalanan →</a>
    </div>
</div>

<?php include 'footer.php'; ?>