<?php
require_once 'config.php';
include 'header.php';
?>

<style>
    .privasi-container {
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

    .privasi-content {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        padding: 50px;
        margin-bottom: 80px;
    }

    .privasi-section {
        margin-bottom: 40px;
    }

    .privasi-title {
        font-size: 1.8rem;
        color: var(--primary-blue);
        margin-bottom: 20px;
        font-weight: bold;
        border-bottom: 2px solid var(--accent-orange);
        padding-bottom: 10px;
    }

    .privasi-text {
        color: #555;
        line-height: 1.8;
        margin-bottom: 20px;
    }

    .privasi-list {
        margin-left: 20px;
        margin-bottom: 20px;
    }

    .privasi-list li {
        color: #555;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .highlight-box {
        background: #f8f9fa;
        border-left: 4px solid var(--primary-blue);
        padding: 20px;
        margin: 20px 0;
        border-radius: 0 10px 10px 0;
    }

    .highlight-title {
        font-weight: bold;
        color: var(--primary-blue);
        margin-bottom: 10px;
    }

    .highlight-text {
        color: #666;
        line-height: 1.6;
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .data-table th {
        background: var(--primary-blue);
        color: white;
        padding: 15px;
        text-align: left;
        font-weight: bold;
    }

    .data-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
    }

    .data-table tr:nth-child(even) {
        background: #f8f9fa;
    }

    .data-table tr:hover {
        background: #e3f2fd;
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

    .last-updated {
        text-align: center;
        color: #666;
        font-style: italic;
        margin-bottom: 40px;
    }

    .accordion {
        margin-bottom: 20px;
    }

    .accordion-item {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 10px;
        overflow: hidden;
    }

    .accordion-header {
        width: 100%;
        background: none;
        border: none;
        padding: 20px 25px;
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

    .accordion-header:hover {
        background: #f8f9fa;
    }

    .accordion-header i {
        transition: transform 0.3s ease;
    }

    .accordion-item.active .accordion-header i {
        transform: rotate(180deg);
    }

    .accordion-content {
        padding: 0 25px 20px;
        color: #666;
        line-height: 1.6;
        display: none;
    }

    .accordion-item.active .accordion-content {
        display: block;
    }

    @media (max-width: 768px) {
        .hero-section h1 {
            font-size: 2rem;
        }

        .privasi-content {
            padding: 30px 20px;
        }

        .contact-buttons {
            flex-direction: column;
            align-items: center;
        }

        .data-table {
            font-size: 0.9rem;
        }

        .data-table th,
        .data-table td {
            padding: 10px;
        }
    }
</style>

<div class="privasi-container">
    <!-- Hero Section -->
    <div class="hero-section">
        <h1>🔒 Kebijakan Privasi</h1>
        <p>Kami berkomitmen untuk melindungi privasi dan data pribadi Anda. Pelajari bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p>
    </div>

    <!-- Last Updated -->
    <div class="last-updated">
        Terakhir diperbarui: 15 Desember 2024
    </div>

    <!-- Privacy Content -->
    <div class="privasi-content">
        <!-- Introduction -->
        <div class="privasi-section">
            <h2 class="privasi-title">Pendahuluan</h2>
            <p class="privasi-text">
                Kebijakan Privasi ini menjelaskan bagaimana PT Sugeng Rahayu (selanjutnya disebut "kami", "kita", atau "perusahaan") 
                mengumpulkan, menggunakan, mengungkapkan, dan melindungi informasi pribadi Anda ketika Anda menggunakan layanan 
                transportasi kami, termasuk website, aplikasi mobile, dan layanan terkait lainnya.
            </p>
            <p class="privasi-text">
                Dengan menggunakan layanan kami, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan kebijakan privasi ini. 
                Kami berkomitmen untuk melindungi privasi Anda dan mematuhi Undang-Undang Nomor 27 Tahun 2022 tentang Pelindungan Data Pribadi.
            </p>
        </div>

        <!-- Information We Collect -->
        <div class="privasi-section">
            <h2 class="privasi-title">Informasi yang Kami Kumpulkan</h2>

            <div class="highlight-box">
                <div class="highlight-title">Informasi Pribadi yang Anda Berikan</div>
                <div class="highlight-text">
                    Kami mengumpulkan informasi yang Anda berikan secara langsung kepada kami, seperti:
                </div>
            </div>

            <ul class="privasi-list">
                <li>Nama lengkap, alamat email, nomor telepon, dan tanggal lahir</li>
                <li>Informasi identitas (KTP, SIM, atau paspor)</li>
                <li>Informasi pembayaran (nomor kartu kredit, rekening bank)</li>
                <li>Preferensi perjalanan dan riwayat pemesanan</li>
                <li>Informasi yang Anda berikan saat menghubungi customer service</li>
                <li>Data yang dikirim melalui formulir kontak atau survei</li>
            </ul>

            <div class="highlight-box">
                <div class="highlight-title">Informasi yang Dikumpulkan Otomatis</div>
                <div class="highlight-text">
                    Saat Anda menggunakan layanan kami, kami secara otomatis mengumpulkan:
                </div>
            </div>

            <ul class="privasi-list">
                <li>Alamat IP, jenis browser, dan sistem operasi</li>
                <li>Data lokasi geografis (dengan izin Anda)</li>
                <li>Halaman yang dikunjungi dan waktu yang dihabiskan</li>
                <li>Data penggunaan aplikasi dan fitur yang digunakan</li>
                <li>Cookies dan teknologi pelacakan serupa</li>
            </ul>
        </div>

        <!-- How We Use Information -->
        <div class="privasi-section">
            <h2 class="privasi-title">Cara Kami Menggunakan Informasi</h2>
            <p class="privasi-text">
                Kami menggunakan informasi yang dikumpulkan untuk berbagai tujuan, termasuk:
            </p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kegiatan</th>
                        <th>Tujuan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pemesanan Tiket</td>
                        <td>Memproses pemesanan, pembayaran, dan konfirmasi perjalanan</td>
                    </tr>
                    <tr>
                        <td>Layanan Pelanggan</td>
                        <td>Menangani inquiry, keluhan, dan memberikan dukungan</td>
                    </tr>
                    <tr>
                        <td>Komunikasi</td>
                        <td>Mengirim konfirmasi, pengingat, dan informasi penting</td>
                    </tr>
                    <tr>
                        <td>Personalisasi</td>
                        <td>Menyediakan rekomendasi dan pengalaman yang disesuaikan</td>
                    </tr>
                    <tr>
                        <td>Keamanan</td>
                        <td>Memantau aktivitas mencurigakan dan mencegah penipuan</td>
                    </tr>
                    <tr>
                        <td>Peningkatan Layanan</td>
                        <td>Analisis data untuk meningkatkan kualitas layanan</td>
                    </tr>
                    <tr>
                        <td>Kepatuhan Hukum</td>
                        <td>Memenuhi kewajiban hukum dan regulasi yang berlaku</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Information Sharing -->
        <div class="privasi-section">
            <h2 class="privasi-title">Berbagi Informasi</h2>
            <p class="privasi-text">
                Kami tidak menjual, menyewakan, atau membagikan informasi pribadi Anda kepada pihak ketiga, kecuali dalam kondisi berikut:
            </p>

            <ul class="privasi-list">
                <li><strong>Dengan Persetujuan Anda:</strong> Ketika Anda memberikan izin eksplisit untuk berbagi informasi</li>
                <li><strong>Penyedia Layanan:</strong> Dengan vendor terpercaya yang membantu operasional kami (dengan perlindungan kontrak)</li>
                <li><strong>Kepatuhan Hukum:</strong> Jika diwajibkan oleh hukum, perintah pengadilan, atau proses hukum</li>
                <li><strong>Keamanan:</strong> Untuk melindungi hak, properti, atau keselamatan kami dan pengguna lain</li>
                <li><strong>Akuisisi:</strong> Dalam kasus penggabungan, akuisisi, atau penjualan aset perusahaan</li>
            </ul>
        </div>

        <!-- Data Security -->
        <div class="privasi-section">
            <h2 class="privasi-title">Keamanan Data</h2>
            <p class="privasi-text">
                Kami menerapkan langkah-langkah keamanan teknis dan organisasi yang sesuai untuk melindungi informasi pribadi Anda:
            </p>

            <div class="highlight-box">
                <div class="highlight-title">Langkah-Langkah Keamanan</div>
                <div class="highlight-text">
                    Kami menggunakan berbagai teknologi dan prosedur keamanan untuk melindungi data Anda.
                </div>
            </div>

            <ul class="privasi-list">
                <li>Enkripsi data dalam transit dan penyimpanan (SSL/TLS)</li>
                <li>Akses terbatas ke data pribadi hanya untuk karyawan yang berwenang</li>
                <li>Sistem firewall dan deteksi intrusi</li>
                <li>Pembaruan keamanan rutin dan audit sistem</li>
                <li>Pelatihan keamanan bagi karyawan</li>
                <li>Pencadangan data teratur dengan enkripsi</li>
            </ul>
        </div>

        <!-- Cookies and Tracking -->
        <div class="privasi-section">
            <h2 class="privasi-title">Cookies dan Teknologi Pelacakan</h2>
            <p class="privasi-text">
                Kami menggunakan cookies dan teknologi serupa untuk meningkatkan pengalaman Anda di website kami:
            </p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Jenis Cookies</th>
                        <th>Tujuan</th>
                        <th>Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Cookies Esensial</td>
                        <td>Diperlukan untuk fungsi dasar website</td>
                        <td>Sesi atau Tetap</td>
                    </tr>
                    <tr>
                        <td>Cookies Analitik</td>
                        <td>Melacak kunjungan dan performa website</td>
                        <td>2 tahun</td>
                    </tr>
                    <tr>
                        <td>Cookies Fungsional</td>
                        <td>Mengingat preferensi dan pengaturan</td>
                        <td>1 tahun</td>
                    </tr>
                    <tr>
                        <td>Cookies Pemasaran</td>
                        <td>Menyediakan iklan yang relevan</td>
                        <td>90 hari</td>
                    </tr>
                </tbody>
            </table>

            <p class="privasi-text">
                Anda dapat mengelola preferensi cookies melalui pengaturan browser Anda. Namun, menonaktifkan cookies tertentu 
                dapat mempengaruhi fungsionalitas website.
            </p>
        </div>

        <!-- Your Rights -->
        <div class="privasi-section">
            <h2 class="privasi-title">Hak Anda</h2>
            <p class="privasi-text">
                Sesuai dengan Undang-Undang Pelindungan Data Pribadi, Anda memiliki hak-hak berikut:
            </p>

            <ul class="privasi-list">
                <li><strong>Hak Akses:</strong> Meminta salinan data pribadi yang kami simpan tentang Anda</li>
                <li><strong>Hak Perbaikan:</strong> Meminta koreksi data yang tidak akurat atau tidak lengkap</li>
                <li><strong>Hak Penghapusan:</strong> Meminta penghapusan data pribadi Anda dalam kondisi tertentu</li>
                <li><strong>Hak Pembatasan:</strong> Meminta pembatasan pemrosesan data pribadi Anda</li>
                <li><strong>Hak Portabilitas:</strong> Menerima data pribadi dalam format yang dapat dibaca mesin</li>
                <li><strong>Hak Keberatan:</strong> Mengajukan keberatan terhadap pemrosesan data pribadi Anda</li>
            </ul>

            <p class="privasi-text">
                Untuk menggunakan hak-hak ini, silakan hubungi kami melalui informasi kontak yang tersedia di bagian bawah halaman ini.
            </p>
        </div>

        <!-- Data Retention -->
        <div class="privasi-section">
            <h2 class="privasi-title">Penyimpanan Data</h2>
            <p class="privasi-text">
                Kami menyimpan data pribadi Anda selama diperlukan untuk memenuhi tujuan pengumpulan data atau sesuai dengan kewajiban hukum:
            </p>

            <ul class="privasi-list">
                <li><strong>Data Pemesanan:</strong> Disimpan selama 5 tahun untuk keperluan perpajakan dan audit</li>
                <li><strong>Data Komunikasi:</strong> Disimpan selama 2 tahun untuk referensi customer service</li>
                <li><strong>Data Analitik:</strong> Dianonimkan setelah 2 tahun untuk analisis tren</li>
                <li><strong>Data Pembayaran:</strong> Disimpan sesuai standar PCI DSS (maksimal 7 tahun)</li>
            </ul>
        </div>

        <!-- International Transfers -->
        <div class="privasi-section">
            <h2 class="privasi-title">Transfer Data Internasional</h2>
            <p class="privasi-text">
                Data pribadi Anda dapat dipindahkan ke dan diproses di negara lain di luar Indonesia. 
                Dalam hal ini, kami memastikan bahwa transfer data dilakukan dengan perlindungan yang memadai, 
                termasuk melalui klausul kontrak standar atau mekanisme transfer yang disetujui oleh otoritas yang berwenang.
            </p>
        </div>

        <!-- Children's Privacy -->
        <div class="privasi-section">
            <h2 class="privasi-title">Privasi Anak-Anak</h2>
            <p class="privasi-text">
                Layanan kami tidak ditujukan untuk anak di bawah 13 tahun. Kami tidak secara sengaja mengumpulkan 
                informasi pribadi dari anak di bawah 13 tahun. Jika kami mengetahui bahwa kami telah mengumpulkan 
                informasi pribadi dari anak di bawah 13 tahun, kami akan segera menghapus informasi tersebut.
            </p>
        </div>

        <!-- Changes to Privacy Policy -->
        <div class="privasi-section">
            <h2 class="privasi-title">Perubahan Kebijakan Privasi</h2>
            <p class="privasi-text">
                Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan signifikan akan diberitahukan 
                melalui email atau pemberitahuan di website kami. Versi terbaru akan selalu tersedia di halaman ini.
            </p>
        </div>

        <!-- Contact Information -->
        <div class="privasi-section">
            <h2 class="privasi-title">Informasi Kontak</h2>
            <p class="privasi-text">
                Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini atau ingin menggunakan hak-hak Anda terkait data pribadi, 
                silakan hubungi kami melalui:
            </p>

            <div class="highlight-box">
                <div class="highlight-title">Data Protection Officer</div>
                <div class="highlight-text">
                    Email: privacy@sugengrrahayu.com<br>
                    Telepon: (021) 1234-5678<br>
                    Alamat: Jl. Sudirman No. 123, Jakarta Pusat, 10230
                </div>
            </div>

            <p class="privasi-text">
                Kami akan menanggapi permintaan Anda dalam waktu 30 hari sesuai dengan ketentuan hukum yang berlaku.
            </p>
        </div>

        <!-- FAQ Accordion -->
        <div class="privasi-section">
            <h2 class="privasi-title">Pertanyaan Umum</h2>

            <div class="accordion">
                <div class="accordion-item">
                    <button class="accordion-header">
                        Apakah data saya aman dengan Sugeng Rahayu?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        Ya, kami menggunakan teknologi keamanan terkini dan mematuhi standar internasional untuk melindungi data Anda. 
                        Semua data dienkripsi dan hanya dapat diakses oleh personel yang berwenang.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">
                        Bagaimana cara menghapus data pribadi saya?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        Anda dapat meminta penghapusan data pribadi dengan menghubungi Data Protection Officer kami. 
                        Kami akan memproses permintaan Anda sesuai dengan ketentuan hukum yang berlaku.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">
                        Apakah data saya digunakan untuk iklan?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        Kami mungkin menggunakan data Anda untuk menyediakan iklan yang lebih relevan, tetapi kami tidak menjual 
                        data pribadi Anda kepada pengiklan. Semua aktivitas pemasaran dilakukan sesuai dengan preferensi Anda.
                    </div>
                </div>

                <div class="accordion-item">
                    <button class="accordion-header">
                        Bagaimana jika ada breach data?
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div class="accordion-content">
                        Jika terjadi breach data yang berdampak pada privasi Anda, kami akan memberitahu Anda dalam waktu 72 jam 
                        sesuai dengan ketentuan Undang-Undang Pelindungan Data Pribadi.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Contact Section -->
    <div class="contact-section">
        <h2 class="contact-title">Ada Pertanyaan?</h2>
        <p class="contact-description">Jika Anda memiliki pertanyaan tentang kebijakan privasi atau ingin menggunakan hak Anda terkait data pribadi, hubungi kami.</p>
        <div class="contact-buttons">
            <a href="kontak.php" class="btn-contact">Hubungi Kami</a>
            <a href="mailto:privacy@sugengrrahayu.com" class="btn-contact">Email Privacy Officer</a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const accordionHeaders = document.querySelectorAll('.accordion-header');

    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const accordionItem = this.parentElement;
            accordionItem.classList.toggle('active');
        });
    });
});
</script>

<?php include 'footer.php'; ?>
