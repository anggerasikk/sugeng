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

    .syarat-container {
        max-width: 1000px;
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

    .syarat-tabs {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        margin-bottom: 80px;
        overflow: hidden;
    }

    .tabs-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .tabs-nav {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .tab-link {
        flex: 1;
        text-align: center;
        padding: 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
        font-weight: 500;
        color: #666;
    }

    .tab-link:hover {
        background: rgba(0,26,187,0.05);
        color: var(--primary-blue);
    }

    .tab-link.active {
        background: var(--primary-blue);
        color: white;
        border-bottom-color: var(--accent-orange);
    }

    .tab-content {
        padding: 40px;
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    .syarat-section {
        margin-bottom: 40px;
    }

    .syarat-title {
        font-size: 1.8rem;
        color: var(--primary-blue);
        margin-bottom: 20px;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .syarat-title i {
        margin-right: 15px;
        font-size: 1.5rem;
    }

    .syarat-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .syarat-item {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid var(--primary-blue);
        transition: all 0.3s ease;
    }

    .syarat-item:hover {
        background: #e9ecef;
        transform: translateX(5px);
    }

    .syarat-item-number {
        display: inline-block;
        background: var(--primary-blue);
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        margin-right: 15px;
        flex-shrink: 0;
    }

    .syarat-item-content {
        flex: 1;
    }

    .syarat-item-title {
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 5px;
    }

    .syarat-item-description {
        color: #666;
        line-height: 1.5;
    }

    .important-note {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border: 1px solid #f39c12;
        border-radius: 10px;
        padding: 25px;
        margin: 30px 0;
    }

    .note-title {
        font-weight: bold;
        color: #d68910;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }

    .note-title i {
        margin-right: 10px;
    }

    .note-content {
        color: #856404;
        line-height: 1.6;
    }

    .contact-section {
        background: linear-gradient(135deg, var(--accent-orange), #ff6b35);
        color: white;
        padding: 60px 0;
        text-align: center;
        border-radius: 15px;
        margin-bottom: 80px;
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

    .contact-buttons {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .btn-contact {
        background: white;
        color: var(--accent-orange);
        padding: 15px 30px;
        border: none;
        border-radius: 25px;
        text-decoration: none;
        display: inline-block;
        font-weight: bold;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .btn-contact:hover {
        background: var(--primary-blue);
        color: white;
        transform: scale(1.05);
    }

    .faq-section {
        margin-bottom: 80px;
    }

    .faq-title {
        text-align: center;
        font-size: 2.5rem;
        color: var(--primary-blue);
        margin-bottom: 60px;
        font-weight: bold;
    }

    .faq-item {
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .faq-question {
        width: 100%;
        background: none;
        border: none;
        padding: 25px 30px;
        text-align: left;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 500;
        color: var(--primary-blue);
        transition: all 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-question:hover {
        background: #f8f9fa;
    }

    .faq-question i {
        transition: transform 0.3s ease;
    }

    .faq-item.active .faq-question i {
        transform: rotate(180deg);
    }

    .faq-answer {
        padding: 0 30px 25px;
        color: #666;
        line-height: 1.6;
        display: none;
    }

    .faq-item.active .faq-answer {
        display: block;
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .tabs-nav {
            flex-direction: column;
        }

        .tab-link {
            border-bottom: none;
            border-right: 3px solid transparent;
        }

        .tab-link.active {
            border-right-color: var(--accent-orange);
            border-bottom-color: transparent;
        }

        .contact-buttons {
            flex-direction: column;
            align-items: center;
        }

        .syarat-item {
            flex-direction: column;
            text-align: center;
        }

        .syarat-item-number {
            margin-bottom: 10px;
            margin-right: 0;
        }
    }
</style>

<div class="syarat-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>📋 Syarat & Ketentuan</h1>
        <p>Pahami syarat dan ketentuan penggunaan layanan Sugeng Rahayu untuk pengalaman perjalanan yang optimal dan aman.</p>
    </div>

    <!-- Syarat Tabs -->
    <div class="syarat-tabs">
        <div class="tabs-header">
            <ul class="tabs-nav">
                <li class="tab-link active" data-tab="booking">Pemesanan</li>
                <li class="tab-link" data-tab="payment">Pembayaran</li>
                <li class="tab-link" data-tab="travel">Perjalanan</li>
                <li class="tab-link" data-tab="refund">Pengembalian</li>
            </ul>
        </div>

        <!-- Booking Tab -->
        <div id="booking" class="tab-content active">
            <div class="syarat-section">
                <h2 class="syarat-title"><i class="fas fa-ticket-alt"></i>Syarat Pemesanan Tiket</h2>
                <ul class="syarat-list">
                    <li class="syarat-item">
                        <span class="syarat-item-number">1</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Identitas Penumpang</div>
                            <div class="syarat-item-description">Penumpang wajib memiliki identitas diri yang valid (KTP/SIM/Paspor) sesuai dengan nama yang tertera pada tiket.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">2</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Batas Usia</div>
                            <div class="syarat-item-description">Penumpang minimal berusia 3 tahun. Bayi di bawah 3 tahun tidak diperkenankan naik bus untuk alasan keselamatan.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">3</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Konfirmasi Pemesanan</div>
                            <div class="syarat-item-description">Pemesanan tiket harus dikonfirmasi minimal 2 jam sebelum keberangkatan untuk perjalanan antar kota.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">4</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Perubahan Jadwal</div>
                            <div class="syarat-item-description">Perubahan jadwal dapat dilakukan maksimal 24 jam sebelum keberangkatan dengan biaya administrasi.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">5</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Kuota Kursi</div>
                            <div class="syarat-item-description">Pemesanan tiket bersifat first-come, first-served. Kursi akan direservasi setelah pembayaran lunas.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Payment Tab -->
        <div id="payment" class="tab-content">
            <div class="syarat-section">
                <h2 class="syarat-title"><i class="fas fa-credit-card"></i>Syarat Pembayaran</h2>
                <ul class="syarat-list">
                    <li class="syarat-item">
                        <span class="syarat-item-number">1</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Metode Pembayaran</div>
                            <div class="syarat-item-description">Pembayaran dapat dilakukan melalui transfer bank, kartu kredit, e-wallet, atau tunai di loket resmi.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">2</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Konfirmasi Pembayaran</div>
                            <div class="syarat-item-description">Pembayaran harus dikonfirmasi dalam waktu maksimal 1 jam setelah pemesanan untuk menghindari pembatalan otomatis.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">3</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Batas Waktu Pembayaran</div>
                            <div class="syarat-item-description">Untuk pemesanan online, pembayaran harus dilakukan dalam waktu 30 menit setelah reservasi kursi.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">4</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Biaya Tambahan</div>
                            <div class="syarat-item-description">Biaya administrasi dan convenience fee mungkin dikenakan tergantung metode pembayaran yang dipilih.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">5</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Refund Pembayaran</div>
                            <div class="syarat-item-description">Pengembalian dana akan diproses dalam 7-14 hari kerja tergantung kebijakan bank dan metode pembayaran.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Travel Tab -->
        <div id="travel" class="tab-content">
            <div class="syarat-section">
                <h2 class="syarat-title"><i class="fas fa-bus"></i>Syarat Perjalanan</h2>
                <ul class="syarat-list">
                    <li class="syarat-item">
                        <span class="syarat-item-number">1</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Waktu Keberangkatan</div>
                            <div class="syarat-item-description">Penumpang wajib hadir di terminal minimal 30 menit sebelum keberangkatan untuk check-in dan verifikasi.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">2</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Barang Bawaan</div>
                            <div class="syarat-item-description">Barang bawaan maksimal 20kg per penumpang. Barang berharga harus dibawa sendiri, bukan dimasukkan ke bagasi.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">3</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Perilaku di Dalam Bus</div>
                            <div class="syarat-item-description">Penumpang wajib menjaga ketertiban, tidak merokok, dan menghormati sesama penumpang selama perjalanan.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">4</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Kesehatan Penumpang</div>
                            <div class="syarat-item-description">Penumpang dengan kondisi kesehatan khusus wajib memberitahu saat pemesanan untuk penanganan yang tepat.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">5</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Perubahan Rute</div>
                            <div class="syarat-item-description">Perusahaan berhak mengubah rute atau jadwal jika diperlukan untuk alasan keselamatan atau kondisi darurat.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Refund Tab -->
        <div id="refund" class="tab-content">
            <div class="syarat-section">
                <h2 class="syarat-title"><i class="fas fa-undo-alt"></i>Kebijakan Pengembalian</h2>
                <ul class="syarat-list">
                    <li class="syarat-item">
                        <span class="syarat-item-number">1</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Pembatalan 24 Jam Sebelumnya</div>
                            <div class="syarat-item-description">Pengembalian dana 100% jika pembatalan dilakukan minimal 24 jam sebelum keberangkatan.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">2</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Pembatalan 12-24 Jam Sebelumnya</div>
                            <div class="syarat-item-description">Pengembalian dana 75% dikurangi biaya administrasi jika pembatalan dilakukan 12-24 jam sebelum keberangkatan.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">3</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Pembatalan Kurang dari 12 Jam</div>
                            <div class="syarat-item-description">Pengembalian dana 50% jika pembatalan dilakukan kurang dari 12 jam sebelum keberangkatan.</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">4</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">No Show</div>
                            <div class="syarat-item-description">Tidak ada pengembalian dana jika penumpang tidak hadir pada saat keberangkatan (no show).</div>
                        </div>
                    </li>
                    <li class="syarat-item">
                        <span class="syarat-item-number">5</span>
                        <div class="syarat-item-content">
                            <div class="syarat-item-title">Force Majeure</div>
                            <div class="syarat-item-description">Pengembalian penuh tanpa potongan untuk pembatalan akibat force majeure seperti bencana alam atau kondisi darurat.</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Important Note -->
    <div class="important-note">
        <div class="note-title">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>PENTING!</strong>
        </div>
        <div class="note-content">
            Syarat dan ketentuan ini dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya. 
            Pastikan Anda selalu membaca syarat terbaru sebelum melakukan pemesanan. 
            Dengan melakukan pemesanan, Anda dianggap telah menyetujui semua syarat dan ketentuan yang berlaku.
        </div>
    </div>

    <!-- FAQ Section -->
    <div class="faq-section">
        <h2 class="faq-title">Pertanyaan Umum</h2>

        <div class="faq-item">
            <button class="faq-question">
                Apakah saya bisa membatalkan tiket setelah melakukan pembayaran?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                Ya, Anda dapat membatalkan tiket dengan ketentuan pengembalian dana sesuai kebijakan yang berlaku. 
                Pembatalan dapat dilakukan melalui website, aplikasi, atau langsung di loket resmi minimal 2 jam sebelum keberangkatan.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                Bagaimana jika saya terlambat datang ke terminal?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                Jika Anda terlambat lebih dari 15 menit setelah jadwal keberangkatan, tiket akan dianggap hangus (no show) 
                dan tidak ada pengembalian dana. Kami menyarankan untuk tiba di terminal minimal 30 menit sebelum keberangkatan.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                Apakah ada batasan usia untuk penumpang?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                Penumpang minimal berusia 3 tahun. Untuk bayi di bawah 3 tahun, tidak diperkenankan naik bus untuk alasan keselamatan. 
                Anak-anak di bawah 12 tahun harus didampingi oleh orang dewasa.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                Bagaimana jika bus mengalami keterlambatan?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                Jika terjadi keterlambatan lebih dari 2 jam, Anda berhak mendapatkan kompensasi sesuai ketentuan yang berlaku. 
                Informasi keterlambatan akan diberitahukan melalui SMS atau aplikasi.
            </div>
        </div>

        <div class="faq-item">
            <button class="faq-question">
                Apakah saya bisa membawa hewan peliharaan?
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="faq-answer">
                Hewan peliharaan tidak diperkenankan dibawa ke dalam bus kecuali anjing pendamping untuk penyandang disabilitas. 
                Pastikan untuk memberitahu kami terlebih dahulu jika Anda membawa anjing pendamping.
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <h2 class="contact-title">Butuh Bantuan?</h2>
        <p class="contact-description">Jika Anda memiliki pertanyaan lebih lanjut tentang syarat dan ketentuan, jangan ragu untuk menghubungi kami.</p>
        <div class="contact-buttons">
            <a href="kontak.php" class="btn-contact">Hubungi Kami</a>
            <a href="https://wa.me/6281234567890" class="btn-contact">WhatsApp Support</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Remove active class from all tabs
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab
            this.classList.add('active');
            const tabId = this.getAttribute('data-tab');
            document.getElementById(tabId).classList.add('active');
        });
    });

    // FAQ Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const faqItem = this.parentElement;
            faqItem.classList.toggle('active');
        });
    });
});
</script>

<?php include 'footer.php'; ?>
