<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .rute-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .rute-hero {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 60px;
    }

    .rute-hero h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .rute-hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .rute-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 40px;
        margin-bottom: 80px;
    }

    .rute-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .rute-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }

    .rute-image {
        height: 200px;
        background: linear-gradient(45deg, <?php echo $accent_orange; ?>, <?php echo $secondary_blue; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 4rem;
        position: relative;
    }

    .rute-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: white;
        color: <?php echo $primary_blue; ?>;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: bold;
    }

    .rute-content {
        padding: 30px;
    }

    .rute-title {
        font-size: 1.5rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
    }

    .rute-description {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .rute-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-size: 0.9rem;
        color: #888;
        margin-bottom: 5px;
    }

    .detail-value {
        font-weight: 500;
        color: <?php echo $primary_blue; ?>;
    }

    .rute-features {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 20px;
    }

    .feature-tag {
        background: <?php echo $light_cream; ?>;
        color: <?php echo $primary_blue; ?>;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .btn-rute {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 12px 25px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-rute:hover {
        background: <?php echo $primary_blue; ?>;
        transform: scale(1.05);
    }

    .map-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        margin-bottom: 80px;
    }

    .map-title {
        text-align: center;
        font-size: 2.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .map-container {
        height: 400px;
        background: linear-gradient(45deg, <?php echo $light_cream; ?>, #e9ecef);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: <?php echo $primary_blue; ?>;
        font-size: 1.5rem;
        font-weight: 500;
    }

    .terminal-section {
        margin-bottom: 80px;
    }

    .terminal-title {
        text-align: center;
        font-size: 2.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .terminal-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .terminal-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        text-align: center;
    }

    .terminal-image {
        height: 180px;
        background: linear-gradient(45deg, <?php echo $secondary_blue; ?>, <?php echo $primary_blue; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
    }

    .terminal-content {
        padding: 25px;
    }

    .terminal-name {
        font-size: 1.3rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
    }

    .terminal-address {
        color: #666;
        line-height: 1.5;
        margin-bottom: 15px;
    }

    .terminal-facilities {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 20px;
    }

    .facility-icon {
        font-size: 1.5rem;
    }

    .terminal-hours {
        color: <?php echo $accent_orange; ?>;
        font-weight: 500;
    }

    .faq-section {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 60px 0;
        border-radius: 15px;
    }

    .faq-title {
        text-align: center;
        font-size: 2.5rem;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .faq-grid {
        max-width: 800px;
        margin: 0 auto;
        display: grid;
        gap: 20px;
        padding: 0 20px;
    }

    .faq-item {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .faq-question {
        padding: 20px;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s ease;
    }

    .faq-question:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .faq-question h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .faq-toggle {
        font-size: 1.5rem;
        transition: transform 0.3s ease;
    }

    .faq-answer {
        padding: 0 20px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-answer p {
        padding: 20px 0;
        margin: 0;
        line-height: 1.6;
    }

    .faq-item.active .faq-answer {
        max-height: 200px;
        padding: 20px;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(45deg);
    }

    @media (max-width: 768px) {
        .rute-hero h1 {
            font-size: 2rem;
        }

        .rute-grid {
            grid-template-columns: 1fr;
        }

        .rute-details {
            grid-template-columns: 1fr;
        }

        .terminal-grid {
            grid-template-columns: 1fr;
        }

        .faq-grid {
            padding: 0 10px;
        }
    }
</style>

<div class="rute-container">
    <!-- Hero Section -->
    <div class="rute-hero">
        <h1>🗺️ Rute & Destinasi</h1>
        <p>Jelajahi berbagai rute perjalanan kami yang menghubungkan kota-kota utama di Indonesia</p>
    </div>

    <!-- Routes Grid -->
    <div class="rute-grid">
        <!-- Route 1: Jakarta - Bandung -->
        <div class="rute-card">
            <div class="rute-image">
                🏙️
                <div class="rute-badge">Populer</div>
            </div>
            <div class="rute-content">
                <h3 class="rute-title">Jakarta → Bandung</h3>
                <p class="rute-description">Rute terpendek dan terpopuler dengan pemandangan pegunungan yang indah. Perjalanan nyaman dengan berbagai pilihan bus.</p>
                <div class="rute-details">
                    <div class="detail-item">
                        <div class="detail-label">Jarak</div>
                        <div class="detail-value">150 km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi</div>
                        <div class="detail-value">3 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga mulai</div>
                        <div class="detail-value">Rp 80.000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Keberangkatan</div>
                        <div class="detail-value">24 jam</div>
                    </div>
                </div>
                <div class="rute-features">
                    <span class="feature-tag">AC</span>
                    <span class="feature-tag">WiFi</span>
                    <span class="feature-tag">Toilet</span>
                    <span class="feature-tag">Snack</span>
                </div>
                <a href="jadwal.php?origin=Jakarta&destination=Bandung" class="btn-rute">Lihat Jadwal</a>
            </div>
        </div>

        <!-- Route 2: Jakarta - Yogyakarta -->
        <div class="rute-card">
            <div class="rute-image">
                🏛️
                <div class="rute-badge">Premium</div>
            </div>
            <div class="rute-content">
                <h3 class="rute-title">Jakarta → Yogyakarta</h3>
                <p class="rute-description">Perjalanan malam yang nyaman menuju kota budaya. Bus premium dengan fasilitas lengkap untuk istirahat maksimal.</p>
                <div class="rute-details">
                    <div class="detail-item">
                        <div class="detail-label">Jarak</div>
                        <div class="detail-value">450 km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi</div>
                        <div class="detail-value">8-9 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga mulai</div>
                        <div class="detail-value">Rp 150.000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Keberangkatan</div>
                        <div class="detail-value">Malam hari</div>
                    </div>
                </div>
                <div class="rute-features">
                    <span class="feature-tag">AC</span>
                    <span class="feature-tag">WiFi</span>
                    <span class="feature-tag">Reclining</span>
                    <span class="feature-tag">Snack</span>
                </div>
                <a href="jadwal.php?origin=Jakarta&destination=Yogyakarta" class="btn-rute">Lihat Jadwal</a>
            </div>
        </div>

        <!-- Route 3: Surabaya - Bali -->
        <div class="rute-card">
            <div class="rute-image">
                🏖️
                <div class="rute-badge">Wisata</div>
            </div>
            <div class="rute-content">
                <h3 class="rute-title">Surabaya → Bali</h3>
                <p class="rute-description">Rute wisata favorit dengan pemandangan laut yang memukau. Bus executive dengan fasilitas premium untuk perjalanan jauh.</p>
                <div class="rute-details">
                    <div class="detail-item">
                        <div class="detail-label">Jarak</div>
                        <div class="detail-value">350 km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi</div>
                        <div class="detail-value">8 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga mulai</div>
                        <div class="detail-value">Rp 200.000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Keberangkatan</div>
                        <div class="detail-value">Pagi & Malam</div>
                    </div>
                </div>
                <div class="rute-features">
                    <span class="feature-tag">AC</span>
                    <span class="feature-tag">WiFi</span>
                    <span class="feature-tag">Toilet</span>
                    <span class="feature-tag">Entertainment</span>
                </div>
                <a href="jadwal.php?origin=Surabaya&destination=Bali" class="btn-rute">Lihat Jadwal</a>
            </div>
        </div>

        <!-- Route 4: Bandung - Surabaya -->
        <div class="rute-card">
            <div class="rute-image">
                🌆
            </div>
            <div class="rute-content">
                <h3 class="rute-title">Bandung → Surabaya</h3>
                <p class="rute-description">Rute bisnis dan wisata yang menghubungkan dua kota metropolitan. Pilihan bus dengan berbagai kelas untuk kebutuhan berbeda.</p>
                <div class="rute-details">
                    <div class="detail-item">
                        <div class="detail-label">Jarak</div>
                        <div class="detail-value">700 km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi</div>
                        <div class="detail-value">12-13 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga mulai</div>
                        <div class="detail-value">Rp 180.000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Keberangkatan</div>
                        <div class="detail-value">Malam hari</div>
                    </div>
                </div>
                <div class="rute-features">
                    <span class="feature-tag">AC</span>
                    <span class="feature-tag">WiFi</span>
                    <span class="feature-tag">Toilet</span>
                    <span class="feature-tag">Snack</span>
                </div>
                <a href="jadwal.php?origin=Bandung&destination=Surabaya" class="btn-rute">Lihat Jadwal</a>
            </div>
        </div>

        <!-- Route 5: Yogyakarta - Semarang -->
        <div class="rute-card">
            <div class="rute-image">
                🕌
            </div>
            <div class="rute-content">
                <h3 class="rute-title">Yogyakarta → Semarang</h3>
                <p class="rute-description">Rute pendek dengan pemandangan alam yang indah. Cocok untuk perjalanan sehari atau kombinasi dengan wisata budaya.</p>
                <div class="rute-details">
                    <div class="detail-item">
                        <div class="detail-label">Jarak</div>
                        <div class="detail-value">130 km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi</div>
                        <div class="detail-value">3-4 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga mulai</div>
                        <div class="detail-value">Rp 60.000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Keberangkatan</div>
                        <div class="detail-value">24 jam</div>
                    </div>
                </div>
                <div class="rute-features">
                    <span class="feature-tag">AC</span>
                    <span class="feature-tag">WiFi</span>
                    <span class="feature-tag">Toilet</span>
                </div>
                <a href="jadwal.php?origin=Yogyakarta&destination=Semarang" class="btn-rute">Lihat Jadwal</a>
            </div>
        </div>

        <!-- Route 6: Jakarta - Bali -->
        <div class="rute-card">
            <div class="rute-image">
                ✈️
                <div class="rute-badge">VIP</div>
            </div>
            <div class="rute-content">
                <h3 class="rute-title">Jakarta → Bali</h3>
                <p class="rute-description">Rute premium untuk perjalanan jauh ke pulau dewata. Bus VIP dengan fasilitas mewah untuk kenyamanan maksimal.</p>
                <div class="rute-details">
                    <div class="detail-item">
                        <div class="detail-label">Jarak</div>
                        <div class="detail-value">950 km</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Durasi</div>
                        <div class="detail-value">18-20 jam</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Harga mulai</div>
                        <div class="detail-value">Rp 350.000</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Keberangkatan</div>
                        <div class="detail-value">Malam hari</div>
                    </div>
                </div>
                <div class="rute-features">
                    <span class="feature-tag">AC</span>
                    <span class="feature-tag">WiFi</span>
                    <span class="feature-tag">VIP Seat</span>
                    <span class="feature-tag">Full Service</span>
                </div>
                <a href="jadwal.php?origin=Jakarta&destination=Bali" class="btn-rute">Lihat Jadwal</a>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <h2 class="map-title">Peta Rute Perjalanan</h2>
        <div class="map-container">
            <div>
                🗺️ Interactive Map - Rute Sugeng Rahayu<br>
                <small>Menampilkan semua destinasi yang kami layani</small>
            </div>
        </div>
    </div>

    <!-- Terminal Section -->
    <div class="terminal-section">
        <h2 class="terminal-title">Terminal & Titik Keberangkatan</h2>
        <div class="terminal-grid">
            <div class="terminal-card">
                <div class="terminal-image">🏢</div>
                <div class="terminal-content">
                    <h3 class="terminal-name">Terminal Bus Jakarta</h3>
                    <p class="terminal-address">Jl. Terminal No. 45, Jakarta Pusat<br>DKI Jakarta 10110</p>
                    <div class="terminal-facilities">
                        <span class="facility-icon">🅿️</span>
                        <span class="facility-icon">🍽️</span>
                        <span class="facility-icon">🛋️</span>
                        <span class="facility-icon">🛒</span>
                    </div>
                    <p class="terminal-hours">24 Jam Operasional</p>
                </div>
            </div>

            <div class="terminal-card">
                <div class="terminal-image">🏛️</div>
                <div class="terminal-content">
                    <h3 class="terminal-name">Terminal Lebak Bulus</h3>
                    <p class="terminal-address">Jl. Lebak Bulus Raya, Jakarta Selatan<br>DKI Jakarta 12440</p>
                    <div class="terminal-facilities">
                        <span class="facility-icon">🅿️</span>
                        <span class="facility-icon">🍽️</span>
                        <span class="facility-icon">🛋️</span>
                        <span class="facility-icon">💺</span>
                    </div>
                    <p class="terminal-hours">05:00 - 23:00 WIB</p>
                </div>
            </div>

            <div class="terminal-card">
                <div class="terminal-image">🌆</div>
                <div class="terminal-content">
                    <h3 class="terminal-name">Terminal Bandung</h3>
                    <p class="terminal-address">Jl. Kebon Kawung No. 1, Bandung<br>Jawa Barat 40271</p>
                    <div class="terminal-facilities">
                        <span class="facility-icon">🅿️</span>
                        <span class="facility-icon">🍽️</span>
                        <span class="facility-icon">🛋️</span>
                        <span class="facility-icon">🚇</span>
                    </div>
                    <p class="terminal-hours">24 Jam Operasional</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2 class="faq-title">Pertanyaan tentang Rute</h2>
        <div class="faq-grid">
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Apakah semua rute beroperasi setiap hari?</h3>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Ya, sebagian besar rute kami beroperasi setiap hari. Namun, beberapa rute premium mungkin memiliki jadwal khusus. Anda dapat mengecek ketersediaan melalui fitur pencarian jadwal di website kami.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Berapa lama waktu transit di terminal?</h3>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Waktu transit bervariasi tergantung rute. Untuk rute pendek (3-4 jam), transit sekitar 15-30 menit. Untuk rute jauh, kami menyediakan waktu istirahat 1-2 jam untuk kenyamanan penumpang.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Apakah ada rute baru yang akan dibuka?</h3>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Ya, kami terus mengembangkan jaringan rute baru. Saat ini kami sedang mempersiapkan rute Jakarta - Malang dan Surabaya - Lombok. Pantau terus website dan media sosial kami untuk informasi terbaru.</p>
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question" onclick="toggleFAQ(this)">
                    <h3>Bagaimana jika cuaca buruk mempengaruhi perjalanan?</h3>
                    <span class="faq-toggle">+</span>
                </div>
                <div class="faq-answer">
                    <p>Keselamatan penumpang adalah prioritas utama. Jika cuaca buruk, kami akan menunda atau membatalkan keberangkatan. Penumpang akan mendapat informasi melalui SMS dan dapat melakukan reschedule atau refund sesuai kebijakan.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFAQ(element) {
    const faqItem = element.parentElement;
    const isActive = faqItem.classList.contains('active');

    // Close all FAQ items
    document.querySelectorAll('.faq-item').forEach(item => {
        item.classList.remove('active');
    });

    // Open clicked item if it wasn't active
    if (!isActive) {
        faqItem.classList.add('active');
    }
}
</script>

<?php include 'footer.php'; ?>