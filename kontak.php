<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .contact-container {
        max-width: 1200px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .contact-hero {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: <?php echo $light_cream; ?>;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 60px;
    }

    .contact-hero h1 {
        font-size: 3rem;
        margin-bottom: 20px;
        font-weight: bold;
    }

    .contact-hero p {
        font-size: 1.2rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 60px;
        margin-bottom: 80px;
    }

    .contact-form-section {
        background: white;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }

    .contact-form h2 {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 30px;
        font-size: 2rem;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: <?php echo $primary_blue; ?>;
        font-weight: 500;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
        width: 100%;
        padding: 15px;
        border: 2px solid #e1e1e1;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        font-family: inherit;
    }

    .form-group input:focus,
    .form-group textarea:focus,
    .form-group select:focus {
        outline: none;
        border-color: <?php echo $accent_orange; ?>;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .btn-submit {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 15px 40px;
        border: none;
        border-radius: 25px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        background: <?php echo $primary_blue; ?>;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .contact-info-section {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .info-card {
        background: white;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        text-align: center;
        transition: transform 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-5px);
    }

    .info-icon {
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .info-title {
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        font-size: 1.3rem;
        font-weight: bold;
    }

    .info-content {
        color: #666;
        line-height: 1.6;
    }

    .info-content p {
        margin-bottom: 10px;
    }

    .info-content a {
        color: <?php echo $accent_orange; ?>;
        text-decoration: none;
        font-weight: 500;
    }

    .info-content a:hover {
        text-decoration: underline;
    }

    .map-section {
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
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        height: 400px;
    }

    .map-container iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    .faq-section {
        margin-bottom: 80px;
    }

    .faq-title {
        text-align: center;
        font-size: 2.5rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 40px;
        font-weight: bold;
    }

    .faq-item {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .faq-question {
        padding: 25px 30px;
        background: <?php echo $light_cream; ?>;
        cursor: pointer;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.3s ease;
    }

    .faq-question:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
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
        padding: 0 30px;
        max-height: 0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .faq-answer p {
        padding: 25px 0;
        margin: 0;
        color: #666;
        line-height: 1.6;
    }

    .faq-item.active .faq-answer {
        max-height: 200px;
        padding: 25px 30px;
    }

    .faq-item.active .faq-toggle {
        transform: rotate(45deg);
    }

    .social-section {
        background: linear-gradient(135deg, <?php echo $accent_orange; ?>, #ff6b35);
        color: white;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
    }

    .social-section h2 {
        font-size: 2.5rem;
        margin-bottom: 20px;
    }

    .social-section p {
        font-size: 1.1rem;
        margin-bottom: 30px;
        opacity: 0.9;
    }

    .social-links {
        display: flex;
        justify-content: center;
        gap: 30px;
    }

    .social-link {
        color: white;
        font-size: 2rem;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    .social-link:hover {
        transform: scale(1.2);
    }

    @media (max-width: 768px) {
        .contact-hero h1 {
            font-size: 2rem;
        }

        .contact-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }

        .contact-form-section {
            padding: 30px 20px;
        }

        .social-links {
            flex-wrap: wrap;
            gap: 20px;
        }
    }
</style>

<div class="contact-container">
    <!-- Hero Section -->
    <div class="contact-hero">
        <h1>Hubungi Kami</h1>
        <p>Kami siap membantu Anda 24/7. Jangan ragu untuk menghubungi kami untuk informasi lebih lanjut atau bantuan.</p>
    </div>

    <!-- Contact Form & Info -->
    <div class="contact-grid">
        <!-- Contact Form -->
        <div class="contact-form-section">
            <h2>Kirim Pesan</h2>
            <form id="contactForm" method="POST">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="subject">Subjek *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Pilih Subjek</option>
                        <option value="booking">Informasi Booking</option>
                        <option value="complaint">Keluhan</option>
                        <option value="suggestion">Saran</option>
                        <option value="partnership">Kemitraan</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Pesan *</label>
                    <textarea id="message" name="message" required></textarea>
                </div>
                <button type="submit" class="btn-submit">Kirim Pesan</button>
            </form>
        </div>

        <!-- Contact Info -->
        <div class="contact-info-section">
            <div class="info-card">
                <div class="info-icon">📞</div>
                <h3 class="info-title">Telepon</h3>
                <div class="info-content">
                    <p><strong>Customer Service:</strong><br>(021) 1234-5678</p>
                    <p><strong>WhatsApp:</strong><br>+62 812-3456-7890</p>
                    <p><strong>Jam Operasional:</strong><br>Senin - Minggu: 24 Jam</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">✉️</div>
                <h3 class="info-title">Email</h3>
                <div class="info-content">
                    <p><strong>Customer Service:</strong><br><a href="mailto:cs@sugengrahayu.com">cs@sugengrahayu.com</a></p>
                    <p><strong>Partnership:</strong><br><a href="mailto:partnership@sugengrahayu.com">partnership@sugengrahayu.com</a></p>
                    <p><strong>General:</strong><br><a href="mailto:info@sugengrahayu.com">info@sugengrahayu.com</a></p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">📍</div>
                <h3 class="info-title">Alamat</h3>
                <div class="info-content">
                    <p><strong>Kantor Pusat:</strong><br>Jl. Contoh Alamat No. 123<br>Jakarta Pusat, DKI Jakarta 10110</p>
                    <p><strong>Terminal Utama:</strong><br>Terminal Bus Jakarta<br>Jl. Terminal No. 45, Jakarta</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-icon">🕒</div>
                <h3 class="info-title">Jam Operasional</h3>
                <div class="info-content">
                    <p><strong>Booking Online:</strong><br>24 Jam / 7 Hari</p>
                    <p><strong>Customer Service:</strong><br>06:00 - 22:00 WIB</p>
                    <p><strong>Check-in Counter:</strong><br>05:00 - 23:00 WIB</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Section -->
    <div class="map-section">
        <h2 class="map-title">Lokasi Kami</h2>
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8456!3d-6.2088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMzEuNiJTIDEwNsKwNTAnNDQuMiJF!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2 class="faq-title">Pertanyaan Umum</h2>
        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Bagaimana cara booking tiket?</h3>
                <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
                <p>Anda dapat booking tiket melalui website kami dengan mengklik menu "Booking" atau menghubungi customer service kami di (021) 1234-5678. Pastikan Anda memiliki data diri yang lengkap dan valid.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Berapa lama waktu check-in sebelum keberangkatan?</h3>
                <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
                <p>Waktu check-in minimal 2 jam sebelum keberangkatan untuk rute dalam kota, dan 3 jam untuk rute antar kota. Kami sarankan untuk datang lebih awal untuk menghindari keterlambatan.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Apakah ada batas berat bagasi?</h3>
                <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
                <p>Batas berat bagasi adalah 20 kg per penumpang. Untuk bagasi tambahan akan dikenakan biaya Rp 50.000 per kg. Pastikan barang berharga tidak dimasukkan ke bagasi.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Bagaimana kebijakan pembatalan dan refund?</h3>
                <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
                <p>Pembatalan dapat dilakukan maksimal 24 jam sebelum keberangkatan dengan refund 80%. Jika kurang dari 24 jam, refund 50%. Untuk pembatalan di hari H, tidak ada refund namun dapat dijadwalkan ulang.</p>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question" onclick="toggleFAQ(this)">
                <h3>Apakah tersedia fasilitas untuk penyandang disabilitas?</h3>
                <span class="faq-toggle">+</span>
            </div>
            <div class="faq-answer">
                <p>Ya, kami menyediakan fasilitas khusus untuk penyandang disabilitas. Silakan hubungi customer service kami minimal 48 jam sebelum keberangkatan untuk pengaturan khusus.</p>
            </div>
        </div>
    </div>

    <!-- Social Media Section -->
    <div class="social-section">
        <h2>Ikuti Kami</h2>
        <p>Tetap terhubung dengan kami melalui media sosial untuk informasi terbaru dan promo menarik!</p>
        <div class="social-links">
            <a href="#" class="social-link">📘 Facebook</a>
            <a href="#" class="social-link">📷 Instagram</a>
            <a href="#" class="social-link">🐦 Twitter</a>
            <a href="#" class="social-link">📺 YouTube</a>
            <a href="#" class="social-link">💼 LinkedIn</a>
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

// Form submission
document.getElementById('contactForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Simple validation
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
    const subject = document.getElementById('subject').value;
    const message = document.getElementById('message').value;
    
    if (!name || !email || !subject || !message) {
        alert('Mohon lengkapi semua field yang wajib diisi.');
        return;
    }
    
    // Here you would typically send the form data to your server
    alert('Terima kasih! Pesan Anda telah dikirim. Kami akan segera menghubungi Anda.');
    this.reset();
});
</script>

<?php include 'footer.php'; ?>