<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .sitemap-container {
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

    .sitemap-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
        margin-bottom: 80px;
    }

    .sitemap-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 30px;
        transition: transform 0.3s ease;
    }

    .sitemap-section:hover {
        transform: translateY(-5px);
    }

    .section-header {
        border-bottom: 2px solid var(--accent-orange);
        padding-bottom: 15px;
        margin-bottom: 25px;
    }

    .section-title {
        font-size: 1.5rem;
        color: var(--primary-blue);
        margin-bottom: 5px;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .section-title i {
        margin-right: 10px;
        font-size: 1.3rem;
    }

    .section-description {
        color: #666;
        font-size: 0.9rem;
        line-height: 1.5;
    }

    .sitemap-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sitemap-links li {
        margin-bottom: 12px;
    }

    .sitemap-link {
        color: var(--primary-blue);
        text-decoration: none;
        font-size: 1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        padding: 8px 0;
        border-radius: 5px;
    }

    .sitemap-link:hover {
        color: var(--accent-orange);
        background: rgba(255, 107, 53, 0.1);
        padding-left: 10px;
    }

    .sitemap-link i {
        margin-right: 10px;
        width: 16px;
        font-size: 0.9rem;
    }

    .sitemap-link.external {
        color: var(--secondary-blue);
    }

    .sitemap-link.external:hover {
        color: var(--accent-orange);
    }

    .main-pages {
        grid-column: span 2;
    }

    .main-pages .sitemap-links {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }

    .main-pages .sitemap-links li {
        margin-bottom: 15px;
    }

    .main-pages .sitemap-link {
        font-size: 1.1rem;
        font-weight: 500;
    }

    .quick-links {
        background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
        color: white;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .quick-links h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .quick-links p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .quick-links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 30px;
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .quick-link-item {
        background: rgba(255, 255, 255, 0.1);
        padding: 25px;
        border-radius: 15px;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }

    .quick-link-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.2);
    }

    .quick-link-icon {
        font-size: 2rem;
        margin-bottom: 15px;
    }

    .quick-link-title {
        font-size: 1.2rem;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .quick-link-description {
        font-size: 0.9rem;
        opacity: 0.9;
        line-height: 1.5;
    }

    .search-section {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 40px;
        text-align: center;
        margin-bottom: 80px;
    }

    .search-title {
        font-size: 2rem;
        color: var(--primary-blue);
        margin-bottom: 20px;
        font-weight: bold;
    }

    .search-description {
        color: #666;
        margin-bottom: 30px;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
    }

    .search-form {
        max-width: 500px;
        margin: 0 auto;
        position: relative;
    }

    .search-input {
        width: 100%;
        padding: 15px 50px 15px 20px;
        border: 2px solid #e9ecef;
        border-radius: 25px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-blue);
        box-shadow: 0 0 0 3px rgba(0, 26, 187, 0.1);
    }

    .search-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: var(--primary-blue);
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-btn:hover {
        background: var(--secondary-blue);
    }

    .stats-section {
        background: #f8f9fa;
        padding: 60px 0;
        border-radius: 15px;
        margin-bottom: 80px;
    }

    .stats-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
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
        text-align: center;
        padding: 30px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .stat-number {
        font-size: 2.5rem;
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .stat-label {
        color: #666;
        font-size: 1rem;
    }

    .contact-section {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        color: white;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
    }

    .contact-title {
        font-size: 2.5rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .contact-description {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-contact {
        background: white;
        color: var(--primary-blue);
        padding: 15px 40px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
        font-size: 1.1rem;
        transition: all 0.3s ease;
    }

    .btn-contact:hover {
        background: var(--accent-orange);
        color: white;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .sitemap-grid {
            grid-template-columns: 1fr;
        }

        .main-pages {
            grid-column: span 1;
        }

        .main-pages .sitemap-links {
            grid-template-columns: 1fr;
        }

        .quick-links-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .search-form {
            max-width: 100%;
        }
    }
</style>

<div class="sitemap-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>🗺️ Peta Situs</h1>
        <p>Jelajahi semua halaman dan fitur yang tersedia di website Sugeng Rahayu. Temukan apa yang Anda cari dengan mudah.</p>
    </div>

    <!-- Main Pages -->
    <div class="sitemap-grid">
        <div class="sitemap-section main-pages">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-home"></i>Halaman Utama</h2>
                <p class="section-description">Halaman-halaman utama website Sugeng Rahayu</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="index.php" class="sitemap-link"><i class="fas fa-home"></i>Beranda</a></li>
                <li><a href="jadwal.php" class="sitemap-link"><i class="fas fa-calendar"></i>Jadwal</a></li>
                <li><a href="rute.php" class="sitemap-link"><i class="fas fa-route"></i>Rute</a></li>
                <li><a href="armada.php" class="sitemap-link"><i class="fas fa-bus"></i>Armada</a></li>
                <li><a href="promo.php" class="sitemap-link"><i class="fas fa-tags"></i>Promo</a></li>
                <li><a href="tentang.php" class="sitemap-link"><i class="fas fa-info-circle"></i>Tentang Kami</a></li>
                <li><a href="kontak.php" class="sitemap-link"><i class="fas fa-phone"></i>Kontak</a></li>
                <li><a href="karir.php" class="sitemap-link"><i class="fas fa-briefcase"></i>Karir</a></li>
            </ul>
        </div>
    </div>

    <!-- Other Sections -->
    <div class="sitemap-grid">
        <div class="sitemap-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-shopping-cart"></i>Pemesanan</h2>
                <p class="section-description">Layanan pemesanan dan booking</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="booking/index.php" class="sitemap-link"><i class="fas fa-search"></i>Cari Jadwal</a></li>
                <li><a href="booking/search-schedule.php" class="sitemap-link"><i class="fas fa-list"></i>Daftar Jadwal</a></li>
                <li><a href="booking/book-detail.php" class="sitemap-link"><i class="fas fa-info"></i>Detail Pemesanan</a></li>
                <li><a href="booking/payment.php" class="sitemap-link"><i class="fas fa-credit-card"></i>Pembayaran</a></li>
                <li><a href="booking/sucess.php" class="sitemap-link"><i class="fas fa-check-circle"></i>Konfirmasi</a></li>
            </ul>
        </div>

        <div class="sitemap-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-user"></i>Akun Pengguna</h2>
                <p class="section-description">Kelola akun dan riwayat perjalanan</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="user/profile.php" class="sitemap-link"><i class="fas fa-user-circle"></i>Profil</a></li>
                <li><a href="user/edit-profile.php" class="sitemap-link"><i class="fas fa-edit"></i>Edit Profil</a></li>
                <li><a href="user/change-password.php" class="sitemap-link"><i class="fas fa-key"></i>Ubah Password</a></li>
                <li><a href="user/history.php" class="sitemap-link"><i class="fas fa-history"></i>Riwayat Pemesanan</a></li>
            </ul>
        </div>

        <div class="sitemap-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-cog"></i>Layanan</h2>
                <p class="section-description">Layanan tambahan dan dukungan</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="checkin/index.php" class="sitemap-link"><i class="fas fa-qrcode"></i>Check-in Online</a></li>
                <li><a href="cancellation/index.php" class="sitemap-link"><i class="fas fa-times-circle"></i>Pembatalan</a></li>
                <li><a href="cancellation/submit.php" class="sitemap-link"><i class="fas fa-paper-plane"></i>Ajukan Pembatalan</a></li>
            </ul>
        </div>

        <div class="sitemap-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-shield-alt"></i>Informasi</h2>
                <p class="section-description">Informasi penting dan kebijakan</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="<?php echo base_url('syarat.php'); ?>" class="sitemap-link"><i class="fas fa-file-contract"></i>Syarat & Ketentuan</a></li>
                <li><a href="<?php echo base_url('privasi.php'); ?>" class="sitemap-link"><i class="fas fa-lock"></i>Kebijakan Privasi</a></li>
                <li><a href="visi-misi.php" class="sitemap-link"><i class="fas fa-bullseye"></i>Visi & Misi</a></li>
            </ul>
        </div>

        <div class="sitemap-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-user-shield"></i>Admin Panel</h2>
                <p class="section-description">Panel administrasi (khusus admin)</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="admin/index.php" class="sitemap-link"><i class="fas fa-tachometer-alt"></i>Dashboard Admin</a></li>
                <li><a href="admin/schedules/index.php" class="sitemap-link"><i class="fas fa-calendar-check"></i>Kelola Jadwal</a></li>
                <li><a href="admin/bookings/index.php" class="sitemap-link"><i class="fas fa-ticket-alt"></i>Kelola Booking</a></li>
            </ul>
        </div>

        <div class="sitemap-section">
            <div class="section-header">
                <h2 class="section-title"><i class="fas fa-external-link-alt"></i>Tautan Eksternal</h2>
                <p class="section-description">Tautan ke platform lain</p>
            </div>
            <ul class="sitemap-links">
                <li><a href="https://wa.me/6281234567890" class="sitemap-link external" target="_blank"><i class="fab fa-whatsapp"></i>WhatsApp Support</a></li>
                <li><a href="https://www.instagram.com/sugengrrahayu" class="sitemap-link external" target="_blank"><i class="fab fa-instagram"></i>Instagram</a></li>
                <li><a href="https://www.facebook.com/sugengrrahayu" class="sitemap-link external" target="_blank"><i class="fab fa-facebook"></i>Facebook</a></li>
                <li><a href="mailto:info@sugengrrahayu.com" class="sitemap-link external"><i class="fas fa-envelope"></i>Email</a></li>
            </ul>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="quick-links">
        <h2>Tautan Cepat</h2>
        <p>Akses cepat ke fitur-fitur utama Sugeng Rahayu</p>
        <div class="quick-links-grid">
            <div class="quick-link-item">
                <div class="quick-link-icon">🎫</div>
                <h3 class="quick-link-title">Pesan Tiket</h3>
                <p class="quick-link-description">Cari dan pesan tiket bus dengan mudah</p>
            </div>
            <div class="quick-link-item">
                <div class="quick-link-icon">📅</div>
                <h3 class="quick-link-title">Cek Jadwal</h3>
                <p class="quick-link-description">Lihat jadwal keberangkatan tersedia</p>
            </div>
            <div class="quick-link-item">
                <div class="quick-link-icon">🚌</div>
                <h3 class="quick-link-title">Info Armada</h3>
                <p class="quick-link-description">Pelajari tentang armada kami</p>
            </div>
            <div class="quick-link-item">
                <div class="quick-link-icon">📞</div>
                <h3 class="quick-link-title">Bantuan</h3>
                <p class="quick-link-description">Hubungi customer service kami</p>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <h2 class="search-title">Cari Halaman</h2>
        <p class="search-description">Tidak menemukan yang Anda cari? Gunakan pencarian untuk menemukan halaman spesifik</p>
        <form class="search-form" onsubmit="searchPages(event)">
            <input type="text" class="search-input" placeholder="Ketik nama halaman atau fitur..." id="searchInput">
            <button type="submit" class="search-btn">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div id="searchResults" style="margin-top: 20px;"></div>
    </div>

    <!-- Stats Section -->
    <div class="stats-section">
        <h2 class="stats-title">Website Dalam Angka</h2>
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">25+</div>
                <div class="stat-label">Halaman Website</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">15+</div>
                <div class="stat-label">Rute Tujuan</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">50+</div>
                <div class="stat-number">Unit Armada</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">24/7</div>
                <div class="stat-label">Layanan Support</div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <h2 class="contact-title">Butuh Bantuan Navigasi?</h2>
        <p class="contact-description">Jika Anda kesulitan menemukan halaman yang diinginkan atau memiliki saran untuk perbaikan peta situs, hubungi kami.</p>
        <a href="kontak.php" class="btn-contact">Hubungi Kami →</a>
    </div>
</div>

<script>
function searchPages(event) {
    event.preventDefault();
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const resultsDiv = document.getElementById('searchResults');

    // Define all pages and their keywords
    const pages = [
        { name: 'Beranda', url: 'index.php', keywords: ['beranda', 'home', 'utama', 'dashboard'] },
        { name: 'Jadwal', url: 'jadwal.php', keywords: ['jadwal', 'schedule', 'keberangkatan', 'departure'] },
        { name: 'Rute', url: 'rute.php', keywords: ['rute', 'route', 'tujuan', 'destination', 'terminal'] },
        { name: 'Armada', url: 'armada.php', keywords: ['armada', 'bus', 'kendaraan', 'fleet'] },
        { name: 'Promo', url: 'promo.php', keywords: ['promo', 'diskon', 'promotion', 'deal'] },
        { name: 'Tentang Kami', url: 'tentang.php', keywords: ['tentang', 'about', 'company', 'perusahaan'] },
        { name: 'Kontak', url: 'kontak.php', keywords: ['kontak', 'contact', 'hubungi', 'cs'] },
        { name: 'Karir', url: 'karir.php', keywords: ['karir', 'career', 'kerja', 'job', 'lowongan'] },
        { name: 'Visi & Misi', url: 'visi-misi.php', keywords: ['visi', 'misi', 'vision', 'mission', 'tujuan'] },
        { name: 'Syarat & Ketentuan', url: 'syarat.php', keywords: ['syarat', 'ketentuan', 'terms', 'conditions', 'rules'] },
        { name: 'Kebijakan Privasi', url: 'privasi.php', keywords: ['privasi', 'privacy', 'policy', 'data'] },
        { name: 'Pemesanan', url: 'booking/index.php', keywords: ['booking', 'pesan', 'tiket', 'reservation'] },
        { name: 'Profil', url: 'user/profile.php', keywords: ['profil', 'profile', 'akun', 'account'] },
        { name: 'Admin Dashboard', url: 'admin/index.php', keywords: ['admin', 'dashboard', 'administrator'] }
    ];

    // Search for matches
    const matches = pages.filter(page =>
        page.name.toLowerCase().includes(searchTerm) ||
        page.keywords.some(keyword => keyword.includes(searchTerm))
    );

    // Display results
    if (matches.length > 0) {
        let resultsHtml = '<h3 style="color: var(--primary-blue); margin-bottom: 15px;">Hasil Pencarian:</h3><ul style="list-style: none; padding: 0;">';
        matches.forEach(match => {
            resultsHtml += `<li style="margin-bottom: 10px;"><a href="${match.url}" style="color: var(--primary-blue); text-decoration: none; padding: 10px; border-radius: 5px; display: block; transition: all 0.3s ease;" onmouseover="this.style.background='rgba(0,26,187,0.1)'" onmouseout="this.style.background='transparent'">${match.name}</a></li>`;
        });
        resultsHtml += '</ul>';
        resultsDiv.innerHTML = resultsHtml;
    } else {
        resultsDiv.innerHTML = '<p style="color: #666; font-style: italic;">Tidak ada hasil ditemukan. Coba kata kunci lain.</p>';
    }
}

// Add click handlers for quick link items
document.addEventListener('DOMContentLoaded', function() {
    const quickLinkItems = document.querySelectorAll('.quick-link-item');

    quickLinkItems.forEach((item, index) => {
        item.addEventListener('click', function() {
            const links = [
                'booking/index.php',
                'jadwal.php',
                'armada.php',
                'kontak.php'
            ];
            if (links[index]) {
                window.location.href = links[index];
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>
