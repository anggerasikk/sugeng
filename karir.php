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

    .karir-container {
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

    .btn-primary {
        background: var(--accent-orange);
        color: white;
        padding: 15px 40px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #ff6b35;
        transform: scale(1.05);
    }

    .why-join-section {
        margin-bottom: 80px;
    }

    .why-join-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 60px;
        font-weight: bold;
    }

    .why-join-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .why-join-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .why-join-card:hover {
        transform: translateY(-10px);
    }

    .why-join-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .why-join-title-card {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 15px;
        font-weight: bold;
    }

    .why-join-description {
        color: #666;
        line-height: 1.6;
    }

    .positions-section {
        margin-bottom: 80px;
    }

    .positions-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 60px;
        font-weight: bold;
    }

    .positions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
    }

    .position-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .position-card:hover {
        transform: translateY(-10px);
    }

    .position-header {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: white;
        padding: 30px;
    }

    .position-title {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .position-department {
        opacity: 0.9;
        font-size: 0.9rem;
    }

    .position-content {
        padding: 30px;
    }

    .position-details {
        margin-bottom: 25px;
    }

    .detail-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.9rem;
    }

    .detail-label {
        color: #666;
    }

    .detail-value {
        font-weight: 500;
        color: var(--primary-blue);
    }

    .position-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 25px;
    }

    .position-requirements {
        margin-bottom: 25px;
    }

    .requirements-title {
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .requirements-list {
        list-style: none;
        padding: 0;
    }

    .requirements-list li {
        color: #666;
        margin-bottom: 5px;
        padding-left: 20px;
        position: relative;
    }

    .requirements-list li:before {
        content: "✓";
        color: var(--accent-orange);
        font-weight: bold;
        position: absolute;
        left: 0;
    }

    .btn-apply {
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

    .btn-apply:hover {
        background: var(--secondary-blue);
        transform: translateY(-2px);
    }

    .culture-section {
        background: #f8f9fa;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .culture-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 40px;
        font-weight: bold;
    }

    .culture-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 40px;
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .culture-item {
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .culture-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }

    .culture-title-item {
        font-size: 1.2rem;
        color: var(--primary-blue);
        margin-bottom: 10px;
        font-weight: bold;
    }

    .culture-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .benefits-section {
        margin-bottom: 80px;
    }

    .benefits-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 60px;
        font-weight: bold;
    }

    .benefits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
    }

    .benefit-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .benefit-card:hover {
        transform: translateY(-10px);
    }

    .benefit-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .benefit-title {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 15px;
        font-weight: bold;
    }

    .benefit-description {
        color: #666;
        line-height: 1.6;
    }

    .application-section {
        background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
        color: white;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .application-title {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .application-description {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .application-form {
        max-width: 600px;
        margin: 0 auto;
        background: rgba(255, 255, 255, 0.1);
        padding: 40px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
    }

    .form-group {
        margin-bottom: 20px;
        text-align: left;
    }

    .form-label {
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 12px;
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.9);
        color: #333;
        font-size: 1rem;
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    .btn-submit {
        background: white;
        color: var(--accent-orange);
        padding: 15px 40px;
        border: none;
        border-radius: 25px;
        font-weight: bold;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        background: var(--primary-blue);
        color: white;
        transform: scale(1.05);
    }

    .contact-section {
        text-align: center;
        margin-bottom: 80px;
    }

    .contact-title {
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 30px;
        font-weight: bold;
    }

    .contact-info {
        max-width: 600px;
        margin: 0 auto;
        font-size: 1.1rem;
        line-height: 1.6;
        color: #666;
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .why-join-grid, .positions-grid, .benefits-grid {
            grid-template-columns: 1fr;
        }

        .culture-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .application-form {
            padding: 20px;
        }
    }
</style>

<div class="karir-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>🚀 Bergabung Dengan Kami</h1>
        <p>Bangun karir Anda di industri transportasi terdepan Indonesia. Kami mencari talenta terbaik untuk bersama-sama memberikan pelayanan transportasi yang luar biasa.</p>
        <a href="#positions" class="btn-primary">Lihat Lowongan →</a>
    </div>

    <!-- Why Join Section -->
    <div class="why-join-section">
        <h2 class="why-join-title">Mengapa Bergabung Dengan Sugeng Rahayu?</h2>
        <div class="why-join-grid">
            <div class="why-join-card">
                <div class="why-join-icon">🌟</div>
                <h3 class="why-join-title-card">Pengembangan Karir</h3>
                <p class="why-join-description">Program pengembangan karir yang komprehensif dengan pelatihan berkala dan kesempatan promosi yang jelas.</p>
            </div>
            <div class="why-join-card">
                <div class="why-join-icon">💰</div>
                <h3 class="why-join-title-card">Kompensasi Kompetitif</h3>
                <p class="why-join-description">Gaji dan tunjangan yang kompetitif sesuai industri, bonus kinerja, dan program insentif menarik.</p>
            </div>
            <div class="why-join-card">
                <div class="why-join-icon">👥</div>
                <h3 class="why-join-title-card">Tim Solid</h3>
                <p class="why-join-description">Bekerja dengan tim profesional dan suportif dalam lingkungan kerja yang positif dan kolaboratif.</p>
            </div>
            <div class="why-join-card">
                <div class="why-join-icon">🎯</div>
                <h3 class="why-join-title-card">Dampak Positif</h3>
                <p class="why-join-description">Kontribusi langsung terhadap kepuasan pelanggan dan pengembangan industri transportasi nasional.</p>
            </div>
            <div class="why-join-card">
                <div class="why-join-icon">🏢</div>
                <h3 class="why-join-title-card">Fasilitas Modern</h3>
                <p class="why-join-description">Kantor dengan fasilitas modern, teknologi terkini, dan lingkungan kerja yang nyaman.</p>
            </div>
            <div class="why-join-card">
                <div class="why-join-icon">⚖️</div>
                <h3 class="why-join-title-card">Work-Life Balance</h3>
                <p class="why-join-description">Kebijakan fleksibel, cuti yang memadai, dan program kesehatan untuk keseimbangan hidup kerja.</p>
            </div>
        </div>
    </div>

    <!-- Positions Section -->
    <div id="positions" class="positions-section">
        <h2 class="positions-title">Lowongan Pekerjaan</h2>
        <div class="positions-grid">
            <div class="position-card">
                <div class="position-header">
                    <h3 class="position-title">Software Developer</h3>
                    <div class="position-department">IT Department</div>
                </div>
                <div class="position-content">
                    <div class="position-details">
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Jakarta</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe</span>
                            <span class="detail-value">Full-time</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pengalaman</span>
                            <span class="detail-value">2+ tahun</span>
                        </div>
                    </div>

                    <p class="position-description">Mengembangkan dan memelihara sistem booking online, aplikasi mobile, dan platform digital perusahaan.</p>

                    <div class="position-requirements">
                        <div class="requirements-title">Persyaratan:</div>
                        <ul class="requirements-list">
                            <li>Pengalaman dengan PHP, JavaScript, MySQL</li>
                            <li>Memahami framework modern (Laravel/React)</li>
                            <li>Pengalaman dengan API development</li>
                            <li>Skill problem-solving yang baik</li>
                        </ul>
                    </div>

                    <a href="#application" class="btn-apply">Lamar Posisi Ini</a>
                </div>
            </div>

            <div class="position-card">
                <div class="position-header">
                    <h3 class="position-title">Customer Service</h3>
                    <div class="position-department">Customer Experience</div>
                </div>
                <div class="position-content">
                    <div class="position-details">
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Jakarta & Surabaya</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe</span>
                            <span class="detail-value">Full-time</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pengalaman</span>
                            <span class="detail-value">1+ tahun</span>
                        </div>
                    </div>

                    <p class="position-description">Menangani inquiry pelanggan, memberikan informasi jadwal, dan memastikan kepuasan pelanggan.</p>

                    <div class="position-requirements">
                        <div class="requirements-title">Persyaratan:</div>
                        <ul class="requirements-list">
                            <li>Komunikasi yang baik</li>
                            <li>Pengalaman customer service</li>
                            <li>Mampu bekerja shift</li>
                            <li>Memahami produk transportasi</li>
                        </ul>
                    </div>

                    <a href="#application" class="btn-apply">Lamar Posisi Ini</a>
                </div>
            </div>

            <div class="position-card">
                <div class="position-header">
                    <h3 class="position-title">Driver Profesional</h3>
                    <div class="position-department">Operations</div>
                </div>
                <div class="position-content">
                    <div class="position-details">
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Seluruh Indonesia</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe</span>
                            <span class="detail-value">Full-time</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pengalaman</span>
                            <span class="detail-value">3+ tahun</span>
                        </div>
                    </div>

                    <p class="position-description">Mengemudikan bus dengan aman dan profesional, memastikan kenyamanan penumpang selama perjalanan.</p>

                    <div class="position-requirements">
                        <div class="requirements-title">Persyaratan:</div>
                        <ul class="requirements-list">
                            <li>SIM B2 Umum</li>
                            <li>Pengalaman mengemudi bus</li>
                            <li>Sehat jasmani dan rohani</li>
                            <li>Bersedia bekerja shift</li>
                        </ul>
                    </div>

                    <a href="#application" class="btn-apply">Lamar Posisi Ini</a>
                </div>
            </div>

            <div class="position-card">
                <div class="position-header">
                    <h3 class="position-title">Marketing Specialist</h3>
                    <div class="position-department">Marketing</div>
                </div>
                <div class="position-content">
                    <div class="position-details">
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Jakarta</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe</span>
                            <span class="detail-value">Full-time</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pengalaman</span>
                            <span class="detail-value">2+ tahun</span>
                        </div>
                    </div>

                    <p class="position-description">Mengembangkan strategi pemasaran digital, mengelola kampanye, dan meningkatkan brand awareness.</p>

                    <div class="position-requirements">
                        <div class="requirements-title">Persyaratan:</div>
                        <ul class="requirements-list">
                            <li>Pengalaman digital marketing</li>
                            <li>Skill analisis data</li>
                            <li>Kreatif dan inovatif</li>
                            <li>Memahami target audience</li>
                        </ul>
                    </div>

                    <a href="#application" class="btn-apply">Lamar Posisi Ini</a>
                </div>
            </div>

            <div class="position-card">
                <div class="position-header">
                    <h3 class="position-title">Operations Manager</h3>
                    <div class="position-department">Operations</div>
                </div>
                <div class="position-content">
                    <div class="position-details">
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Jakarta</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe</span>
                            <span class="detail-value">Full-time</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pengalaman</span>
                            <span class="detail-value">5+ tahun</span>
                        </div>
                    </div>

                    <p class="position-description">Mengatur dan mengoptimalkan operasional harian perusahaan, memastikan efisiensi dan kepuasan pelanggan.</p>

                    <div class="position-requirements">
                        <div class="requirements-title">Persyaratan:</div>
                        <ul class="requirements-list">
                            <li>Pengalaman manajemen operasional</li>
                            <li>Skill leadership yang kuat</li>
                            <li>Memahami industri transportasi</li>
                            <li>Kemampuan problem-solving</li>
                        </ul>
                    </div>

                    <a href="#application" class="btn-apply">Lamar Posisi Ini</a>
                </div>
            </div>

            <div class="position-card">
                <div class="position-header">
                    <h3 class="position-title">HR Business Partner</h3>
                    <div class="position-department">Human Resources</div>
                </div>
                <div class="position-content">
                    <div class="position-details">
                        <div class="detail-item">
                            <span class="detail-label">Lokasi</span>
                            <span class="detail-value">Jakarta</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Tipe</span>
                            <span class="detail-value">Full-time</span>
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Pengalaman</span>
                            <span class="detail-value">3+ tahun</span>
                        </div>
                    </div>

                    <p class="position-description">Mengelola hubungan industrial, pengembangan karyawan, dan memastikan kepuasan internal perusahaan.</p>

                    <div class="position-requirements">
                        <div class="requirements-title">Persyaratan:</div>
                        <ul class="requirements-list">
                            <li>Background HR atau psikologi</li>
                            <li>Pengalaman HRBP</li>
                            <li>Komunikasi interpersonal baik</li>
                            <li>Memahami regulasi ketenagakerjaan</li>
                        </ul>
                    </div>

                    <a href="#application" class="btn-apply">Lamar Posisi Ini</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Culture Section -->
    <div class="culture-section">
        <h2 class="culture-title">Budaya Kerja Kami</h2>
        <div class="culture-grid">
            <div class="culture-item">
                <div class="culture-icon">🤝</div>
                <h3 class="culture-title-item">Kolaborasi</h3>
                <p class="culture-description">Kami percaya kekuatan tim dan saling mendukung untuk mencapai tujuan bersama.</p>
            </div>
            <div class="culture-item">
                <div class="culture-icon">🎯</div>
                <h3 class="culture-title-item">Fokus Pelanggan</h3>
                <p class="culture-description">Setiap keputusan kami selalu memprioritaskan kepuasan dan keamanan pelanggan.</p>
            </div>
            <div class="culture-item">
                <div class="culture-icon">🚀</div>
                <h3 class="culture-title-item">Inovasi</h3>
                <p class="culture-description">Kami terus berinovasi untuk memberikan solusi transportasi yang lebih baik.</p>
            </div>
            <div class="culture-item">
                <div class="culture-icon">⚖️</div>
                <h3 class="culture-title-item">Integritas</h3>
                <p class="culture-description">Kami menjalankan bisnis dengan jujur, transparan, dan bertanggung jawab.</p>
            </div>
            <div class="culture-item">
                <div class="culture-icon">🌟</div>
                <h3 class="culture-title-item">Kualitas</h3>
                <p class="culture-description">Kami berkomitmen memberikan pelayanan dengan standar kualitas tertinggi.</p>
            </div>
            <div class="culture-item">
                <div class="culture-icon">🌱</div>
                <h3 class="culture-title-item">Keberlanjutan</h3>
                <p class="culture-description">Kami peduli dengan lingkungan dan berkontribusi pada pembangunan berkelanjutan.</p>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="benefits-section">
        <h2 class="benefits-title">Benefit & Fasilitas</h2>
        <div class="benefits-grid">
            <div class="benefit-card">
                <div class="benefit-icon">🏥</div>
                <h3 class="benefit-title">BPJS Kesehatan & Ketenagakerjaan</h3>
                <p class="benefit-description">Cakupan kesehatan dan keselamatan kerja lengkap untuk Anda dan keluarga.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">💰</div>
                <h3 class="benefit-title">THR & Bonus</h3>
                <p class="benefit-description">Tunjangan hari raya dan bonus kinerja berdasarkan pencapaian target.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🏖️</div>
                <h3 class="benefit-title">Cuti & Libur</h3>
                <p class="benefit-description">Cuti tahunan, cuti sakit, dan hari libur nasional yang memadai.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">📚</div>
                <h3 class="benefit-title">Pelatihan & Pengembangan</h3>
                <p class="benefit-description">Program pelatihan berkala dan kesempatan pengembangan karir.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🚐</div>
                <h3 class="benefit-title">Transportasi</h3>
                <p class="benefit-description">Fasilitas transportasi untuk karyawan yang memerlukan.</p>
            </div>
            <div class="benefit-card">
                <div class="benefit-icon">🎉</div>
                <h3 class="benefit-title">Event & Gathering</h3>
                <p class="benefit-description">Acara perusahaan dan gathering untuk mempererat hubungan tim.</p>
            </div>
        </div>
    </div>

    <!-- Application Section -->
    <div id="application" class="application-section">
        <h2 class="application-title">Kirim Lamaran Anda</h2>
        <p class="application-description">Siap bergabung dengan tim kami? Kirim lamaran Anda dan CV terbaru. Kami akan menghubungi Anda dalam 3-5 hari kerja.</p>

        <form class="application-form" method="POST" action="process-application.php">
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap *</label>
                <input type="text" id="name" name="name" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="email">Email *</label>
                <input type="email" id="email" name="email" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="phone">Nomor Telepon *</label>
                <input type="tel" id="phone" name="phone" class="form-input" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="position">Posisi yang Dilamar *</label>
                <select id="position" name="position" class="form-select" required>
                    <option value="">Pilih Posisi</option>
                    <option value="Software Developer">Software Developer</option>
                    <option value="Customer Service">Customer Service</option>
                    <option value="Driver Profesional">Driver Profesional</option>
                    <option value="Marketing Specialist">Marketing Specialist</option>
                    <option value="Operations Manager">Operations Manager</option>
                    <option value="HR Business Partner">HR Business Partner</option>
                    <option value="other">Lainnya</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="experience">Pengalaman (tahun)</label>
                <select id="experience" name="experience" class="form-select">
                    <option value="">Pilih Pengalaman</option>
                    <option value="0-1">0-1 tahun</option>
                    <option value="1-3">1-3 tahun</option>
                    <option value="3-5">3-5 tahun</option>
                    <option value="5+">5+ tahun</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="message">Pesan/Sabda</label>
                <textarea id="message" name="message" class="form-textarea" placeholder="Ceritakan sedikit tentang diri Anda dan mengapa tertarik bergabung dengan Sugeng Rahayu..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Kirim Lamaran</button>
        </form>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <h2 class="contact-title">Butuh Informasi Lebih Lanjut?</h2>
        <p class="contact-info">
            Jika Anda memiliki pertanyaan tentang lowongan pekerjaan atau proses rekrutmen, 
            jangan ragu untuk menghubungi tim HR kami di <strong>hr@sugengrrahayu.com</strong> 
            atau telepon <strong>(021) 1234-5678</strong>.
        </p>
    </div>
</div>

<?php include 'footer.php'; ?>
