<?php include '../header.php'; ?>

<style>
    .blog-hero {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 80px 0 60px;
        text-align: center;
    }

    .blog-hero h1 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: <?php echo $light_cream; ?>;
    }

    .blog-hero p {
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
        opacity: 0.9;
    }

    .blog-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    .blog-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 40px;
        margin-bottom: 60px;
    }

    .featured-article {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .article-image {
        width: 100%;
        height: 300px;
        background: linear-gradient(45deg, <?php echo $secondary_blue; ?>, <?php echo $accent_orange; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        position: relative;
    }

    .article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .article-content {
        padding: 30px;
    }

    .article-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        color: #666;
        font-size: 0.9rem;
    }

    .article-meta span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .article-content h2 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        font-size: 1.8rem;
    }

    .article-content p {
        line-height: 1.6;
        color: #555;
        margin-bottom: 20px;
    }

    .read-more {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s ease;
    }

    .read-more:hover {
        background: #e67300;
    }

    .other-news {
        background: <?php echo $light_cream; ?>;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .other-news h3 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid <?php echo $accent_orange; ?>;
    }

    .news-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .news-item {
        display: flex;
        gap: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #ddd;
    }

    .news-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .news-image {
        width: 80px;
        height: 80px;
        background: linear-gradient(45deg, <?php echo $secondary_blue; ?>, <?php echo $accent_orange; ?>);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    .news-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 5px;
    }

    .news-content h4 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 5px;
        font-size: 1rem;
    }

    .news-content p {
        color: #666;
        font-size: 0.85rem;
        line-height: 1.4;
    }

    .news-date {
        color: <?php echo $accent_orange; ?>;
        font-size: 0.8rem;
        margin-top: 5px;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 30px;
        margin-top: 40px;
    }

    .article-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .article-card:hover {
        transform: translateY(-5px);
    }

    .card-image {
        width: 100%;
        height: 200px;
        background: linear-gradient(45deg, <?php echo $secondary_blue; ?>, <?php echo $accent_orange; ?>);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .card-content {
        padding: 20px;
    }

    .card-content h3 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 10px;
        font-size: 1.3rem;
    }

    .card-content p {
        color: #666;
        line-height: 1.5;
        margin-bottom: 15px;
        font-size: 0.9rem;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
    }

    .card-date {
        color: <?php echo $accent_orange; ?>;
        font-size: 0.85rem;
    }

    @media (max-width: 768px) {
        .blog-content {
            grid-template-columns: 1fr;
        }
        
        .articles-grid {
            grid-template-columns: 1fr;
        }
        
        .blog-hero h1 {
            font-size: 2rem;
        }
    }
</style>

<!-- Hero Section -->
<section class="blog-hero">
    <div class="blog-container">
        <h1>Berita & Artikel Sugeng Rahayu</h1>
        <p>Informasi terbaru seputar transportasi bus, layanan, dan inovasi dari Sugeng Rahayu</p>
    </div>
</section>

<!-- Main Content -->
<div class="blog-container">
    <div class="blog-content">
        <!-- Featured Article -->
        <div class="featured-article">
            <div class="article-image">
                <!-- Placeholder for featured image -->
                <div style="text-align: center; color: white;">
                    <div style="font-size: 3rem; margin-bottom: 10px;">🚌</div>
                    <p>Gambar Featured Artikel</p>
                </div>
            </div>
            <div class="article-content">
                <div class="article-meta">
                    <span>📅 27 November 2025</span>
                    <span>👁️ 1.245 Dilihat</span>
                    <span>🏷️ Inovasi</span>
                </div>
                <h2>Sugeng Rahayu Luncurkan Sistem Booking Online Terbaru dengan Fitur Real-Time Tracking</h2>
                <p>Dalam upaya meningkatkan kenyamanan pelanggan, Sugeng Rahayu meluncurkan sistem booking online generasi terbaru yang dilengkapi dengan fitur real-time tracking. Penumpang kini dapat memantau posisi bus secara langsung melalui aplikasi mobile...</p>
                <p>Fitur ini tidak hanya memberikan informasi akurat tentang perkiraan waktu kedatangan, tetapi juga meningkatkan transparansi layanan. Dengan teknologi GPS terkini, penumpang dapat merencanakan perjalanan dengan lebih baik dan mengurangi waktu tunggu.</p>
                <a href="#" class="read-more">Baca Selengkapnya →</a>
            </div>
        </div>

        <!-- Other News Sidebar -->
        <div class="other-news">
            <h3>Berita Lainnya</h3>
            <div class="news-list">
                <div class="news-item">
                    <div class="news-image">
                        <div style="text-align: center; font-size: 0.7rem;">
                            <div>🎖️</div>
                            <small>Penghargaan</small>
                        </div>
                    </div>
                    <div class="news-content">
                        <h4>Hadirkan Layanan Publik Berkualitas, Sugeng Rahayu Terima Penghargaan dari Kemenhub RI</h4>
                        <p>Karanganyar, 6 Agustus 2025 - Konsistensi Sugeng Rahayu dalam memberikan layanan publik yang prima kembali mendapatkan pengakuan dari Pemerintah, melalui...</p>
                        <div class="news-date">📅 6 Agustus 2025</div>
                    </div>
                </div>

                <div class="news-item">
                    <div class="news-image">
                        <div style="text-align: center; font-size: 0.7rem;">
                            <div>🔒</div>
                            <small>Keamanan</small>
                        </div>
                    </div>
                    <div class="news-content">
                        <h4>KARYA: Solusi Aman Menitipkan Barang Berharga di Bus Sugeng Rahayu</h4>
                        <p>Dalam setiap perjalanan jauh, membawa barang berharga seringkali menjadi kekhawatiran tersendiri bagi penumpang. Risiko kehilangan atau tertinggal bisa saja...</p>
                        <div class="news-date">📅 15 September 2025</div>
                    </div>
                </div>

                <div class="news-item">
                    <div class="news-image">
                        <div style="text-align: center; font-size: 0.7rem;">
                            <div>🆔</div>
                            <small>Regulasi</small>
                        </div>
                    </div>
                    <div class="news-content">
                        <h4>Pentingnya Penggunaan Identitas Resmi dalam Pembelian Tiket Sugeng Rahayu</h4>
                        <p>Sebagai perusahaan transportasi yang mengutamakan kenyamanan dan keamanan pelanggan, Sugeng Rahayu senantiasa berkomitmen menghadirkan...</p>
                        <div class="news-date">📅 3 Oktober 2025</div>
                    </div>
                </div>

                <div class="news-item">
                    <div class="news-image">
                        <div style="text-align: center; font-size: 0.7rem;">
                            <div>🔔</div>
                            <small>Inovasi</small>
                        </div>
                    </div>
                    <div class="news-content">
                        <h4>Inovasi Baru Sugeng Rahayu, Panggil Awak Bus Semudah Menekan Tombol Bel</h4>
                        <p>Karanganyar, 12 Agustus 2025 - Sugeng Rahayu berhasil menghadirkan terobosan demi memberikan layanan terbaik bagi para pengguna jasanya. Inovasi yang...</p>
                        <div class="news-date">📅 12 Agustus 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Grid -->
    <h2 style="color: <?php echo $primary_blue; ?>; margin-bottom: 20px; text-align: center;">Artikel Terbaru Lainnya</h2>
    <div class="articles-grid">
        <!-- Article 1 -->
        <div class="article-card">
            <div class="card-image">
                <div style="text-align: center; color: white;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">🛡️</div>
                    <p>Keamanan Digital</p>
                </div>
            </div>
            <div class="card-content">
                <h3>Waspada Modus Penipuan Digital, Pastikan Beli Tiket Sugeng Rahayu Melalui Sumber Resmi</h3>
                <p>Di era serba digital, penipuan bukan lagi hal baru. Modus scam marak terjadi di sektor keuangan, e-commerce, hingga media sosial. Para pelaku kejahatan siber memanfaatkan...</p>
                <div class="card-meta">
                    <span class="card-date">📅 25 Oktober 2025</span>
                    <a href="#" class="read-more" style="padding: 5px 15px; font-size: 0.8rem;">Baca</a>
                </div>
            </div>
        </div>

        <!-- Article 2 -->
        <div class="article-card">
            <div class="card-image">
                <div style="text-align: center; color: white;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">🌱</div>
                    <p>Lingkungan</p>
                </div>
            </div>
            <div class="card-content">
                <h3>Sugeng Rahayu Komitmen Kurangi Emisi dengan Bus Ramah Lingkungan</h3>
                <p>Sebagai bentuk kepedulian terhadap lingkungan, Sugeng Rahayu mulai mengoperasikan armada bus dengan teknologi Euro 5 yang lebih ramah lingkungan dan mengurangi...</p>
                <div class="card-meta">
                    <span class="card-date">📅 18 November 2025</span>
                    <a href="#" class="read-more" style="padding: 5px 15px; font-size: 0.8rem;">Baca</a>
                </div>
            </div>
        </div>

        <!-- Article 3 -->
        <div class="article-card">
            <div class="card-image">
                <div style="text-align: center; color: white;">
                    <div style="font-size: 2rem; margin-bottom: 10px;">🎯</div>
                    <p>Layanan</p>
                </div>
            </div>
            <div class="card-content">
                <h3>Tingkatkan Layanan, Sugeng Rahayu Buka Rute Baru Jawa-Bali dengan Fasilitas Premium</h3>
                <p>Melayani kebutuhan transportasi yang terus berkembang, Sugeng Rahayu resmi membuka rute baru menghubungkan Jawa Timur dengan Bali. Armada terbaru dilengkapi dengan...</p>
                <div class="card-meta">
                    <span class="card-date">📅 10 November 2025</span>
                    <a href="#" class="read-more" style="padding: 5px 15px; font-size: 0.8rem;">Baca</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>