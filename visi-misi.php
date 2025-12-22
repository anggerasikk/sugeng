<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .visi-misi-container {
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

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 80px;
    }

    .visi-card, .misi-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .visi-card:hover, .misi-card:hover {
        transform: translateY(-10px);
    }

    .card-icon {
        font-size: 4rem;
        margin-bottom: 30px;
    }

    .visi-card .card-icon {
        color: var(--primary-blue);
    }

    .misi-card .card-icon {
        color: var(--accent-orange);
    }

    .card-title {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 20px;
    }

    .card-description {
        color: #666;
        line-height: 1.8;
        font-size: 1.1rem;
    }

    .values-section {
        margin-bottom: 80px;
    }

    .values-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
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
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px 30px;
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
        color: var(--primary-blue);
        margin-bottom: 15px;
        font-weight: bold;
    }

    .value-description {
        color: #666;
        line-height: 1.6;
    }

    .timeline-section {
        margin-bottom: 80px;
    }

    .timeline-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 60px;
        font-weight: bold;
    }

    .timeline {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }

    .timeline::after {
        content: '';
        position: absolute;
        width: 6px;
        background: var(--primary-blue);
        top: 0;
        bottom: 0;
        left: 50%;
        margin-left: -3px;
    }

    .timeline-item {
        padding: 10px 40px;
        position: relative;
        background: inherit;
        width: 50%;
        box-sizing: border-box;
    }

    .timeline-item:nth-child(odd) {
        left: 0;
    }

    .timeline-item:nth-child(even) {
        left: 50%;
    }

    .timeline-item::after {
        content: '';
        position: absolute;
        width: 25px;
        height: 25px;
        right: -17px;
        background: white;
        border: 4px solid var(--accent-orange);
        top: 15px;
        border-radius: 50%;
        z-index: 1;
    }

    .timeline-item:nth-child(even)::after {
        left: -16px;
    }

    .timeline-content {
        padding: 20px 30px;
        background: white;
        position: relative;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .timeline-year {
        font-size: 1.5rem;
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .timeline-description {
        color: #666;
        line-height: 1.6;
    }

    .goals-section {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: var(--light-cream);
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .goals-title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .goals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .goal-item {
        text-align: center;
        padding: 30px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .goal-number {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .goal-label {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    .commitment-section {
        text-align: center;
        margin-bottom: 80px;
    }

    .commitment-title {
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 30px;
        font-weight: bold;
    }

    .commitment-text {
        max-width: 800px;
        margin: 0 auto;
        font-size: 1.2rem;
        line-height: 1.8;
        color: #555;
    }

    .cta-section {
        background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
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
        background: var(--primary-blue);
        color: white;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .timeline::after {
            left: 31px;
        }

        .timeline-item {
            width: 100%;
            padding-left: 70px;
            padding-right: 25px;
        }

        .timeline-item:nth-child(even) {
            left: 0;
        }

        .timeline-item::after {
            left: 15px;
        }

        .timeline-item:nth-child(even)::after {
            left: 15px;
        }

        .goals-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="visi-misi-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>🎯 Visi & Misi</h1>
        <p>Komitmen kami untuk memberikan pelayanan transportasi terbaik dan berkelanjutan untuk Indonesia</p>
    </div>

    <!-- Visi & Misi Section -->
    <div class="content-grid">
        <div class="visi-card">
            <div class="card-icon">👁️</div>
            <h2 class="card-title">Visi</h2>
            <p class="card-description">
                Menjadi perusahaan transportasi bus terdepan di Indonesia yang memberikan pengalaman perjalanan yang aman, nyaman, dan terjangkau untuk semua lapisan masyarakat, sambil berkontribusi pada pembangunan berkelanjutan dan pelestarian lingkungan.
            </p>
        </div>

        <div class="misi-card">
            <div class="card-icon">🎯</div>
            <h2 class="card-title">Misi</h2>
            <p class="card-description">
                Memberikan layanan transportasi berkualitas tinggi dengan armada modern dan crew profesional, mengembangkan jaringan rute yang luas, menerapkan teknologi terkini untuk kemudahan pelanggan, serta berkomitmen pada tanggung jawab sosial dan lingkungan.
            </p>
        </div>
    </div>

    <!-- Values Section -->
    <div class="values-section">
        <h2 class="values-title">Nilai-Nilai Inti Kami</h2>
        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon">🛡️</div>
                <h3 class="value-title">Keselamatan</h3>
                <p class="value-description">Keselamatan penumpang dan crew adalah prioritas utama dalam setiap aspek operasional kami.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">⭐</div>
                <h3 class="value-title">Kualitas</h3>
                <p class="value-description">Kami berkomitmen memberikan pelayanan terbaik dengan standar kualitas yang tinggi dan konsisten.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🤝</div>
                <h3 class="value-title">Integritas</h3>
                <p class="value-description">Kami menjalankan bisnis dengan jujur, transparan, dan bertanggung jawab kepada semua stakeholders.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🌱</div>
                <h3 class="value-title">Keberlanjutan</h3>
                <p class="value-description">Kami berkontribusi pada pembangunan berkelanjutan dan pelestarian lingkungan hidup.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">👥</div>
                <h3 class="value-title">Pelayanan</h3>
                <p class="value-description">Fokus kami adalah memberikan pelayanan prima yang melampaui ekspektasi pelanggan.</p>
            </div>
            <div class="value-card">
                <div class="value-icon">🚀</div>
                <h3 class="value-title">Inovasi</h3>
                <p class="value-description">Kami terus berinovasi untuk memberikan solusi transportasi yang lebih baik dan efisien.</p>
            </div>
        </div>
    </div>

    <!-- Timeline Section -->
    <div class="timeline-section">
        <h2 class="timeline-title">Sejarah Perjalanan Kami</h2>
        <div class="timeline">
            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2013</h3>
                    <p class="timeline-description">Sugeng Rahayu didirikan dengan visi memberikan transportasi yang aman dan terjangkau. Memulai operasi dengan 5 unit bus di rute Jakarta-Bandung.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2015</h3>
                    <p class="timeline-description">Memperluas jaringan rute ke Yogyakarta dan Surabaya. Memperkenalkan sistem booking online pertama di industri transportasi bus nasional.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2017</h3>
                    <p class="timeline-description">Meluncurkan armada bus premium dengan fasilitas lengkap. Mendapatkan sertifikasi keselamatan dari Kementerian Perhubungan.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2019</h3>
                    <p class="timeline-description">Memperkenalkan aplikasi mobile untuk booking dan tracking real-time. Menambah rute ke Bali dan memperluas jaringan hingga 15 kota besar.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2021</h3>
                    <p class="timeline-description">Meluncurkan program Go Green dengan armada bus ramah lingkungan. Mendapatkan penghargaan sebagai perusahaan transportasi terbaik.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2023</h3>
                    <p class="timeline-description">Memperkenalkan sistem check-in online dan boarding pass digital. Menjadi pionir dalam transformasi digital industri transportasi bus.</p>
                </div>
            </div>

            <div class="timeline-item">
                <div class="timeline-content">
                    <h3 class="timeline-year">2025</h3>
                    <p class="timeline-description">Meluncurkan armada bus listrik dan memperluas jaringan ke seluruh Indonesia. Berkomitmen menjadi leader dalam transportasi berkelanjutan.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Goals Section -->
    <div class="goals-section">
        <h2 class="goals-title">Target Kami di 2025</h2>
        <div class="goals-grid">
            <div class="goal-item">
                <div class="goal-number">50+</div>
                <div class="goal-label">Unit Armada Modern</div>
            </div>
            <div class="goal-item">
                <div class="goal-number">25+</div>
                <div class="goal-label">Rute Tujuan</div>
            </div>
            <div class="goal-item">
                <div class="goal-number">2M+</div>
                <div class="goal-label">Penumpang Puas</div>
            </div>
            <div class="goal-item">
                <div class="goal-number">100%</div>
                <div class="goal-label">Bus Ramah Lingkungan</div>
            </div>
        </div>
    </div>

    <!-- Commitment Section -->
    <div class="commitment-section">
        <h2 class="commitment-title">Komitmen Kami</h2>
        <p class="commitment-text">
            Sugeng Rahayu berkomitmen untuk terus memberikan yang terbaik bagi pelanggan, masyarakat, dan lingkungan. 
            Kami percaya bahwa transportasi yang baik bukan hanya tentang mengantar penumpang dari satu tempat ke tempat lain, 
            tetapi juga tentang menciptakan pengalaman yang positif, berkontribusi pada pembangunan ekonomi, 
            dan menjaga kelestarian lingkungan untuk generasi mendatang.
        </p>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2>Bergabunglah Dengan Kami!</h2>
        <p>Jadilah bagian dari perjalanan kami menuju masa depan transportasi yang lebih baik. Pesan tiket Anda sekarang dan rasakan pengalaman perjalanan yang berbeda!</p>
        <a href="booking/index.php" class="btn-cta">Mulai Perjalanan →</a>
    </div>
</div>

<?php include 'footer.php'; ?>
