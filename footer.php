<?php
// Warna dari palette
$primary_blue = "#001BB7";
$secondary_blue = "#0046FF";
$accent_orange = "#FF8040";
$light_cream = "#F5F1DC";
?>

<footer style="background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>); color: <?php echo $light_cream; ?>; padding: 40px 0 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 40px; margin-bottom: 40px;">
            
            <!-- Company Info -->
            <div>
                <h3 style="color: <?php echo $accent_orange; ?>; margin-bottom: 20px; font-size: 24px;">Sugeng <span style="color: <?php echo $light_cream; ?>">Rahayu</span></h3>
                <p style="line-height: 1.6; margin-bottom: 20px;">
                    Penyedia layanan transportasi bus terpercaya dengan pengalaman lebih dari 10 tahun. 
                    Melayani perjalanan antar kota dengan nyaman, aman, dan terjangkau.
                </p>
                <div style="display: flex; gap: 15px;">
                    <a href="#" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">
                        <i>FB</i>
                    </a>
                    <a href="#" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">
                        <i>IG</i>
                    </a>
                    <a href="#" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">
                        <i>TW</i>
                    </a>
                    <a href="#" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">
                        <i>YT</i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 style="color: <?php echo $accent_orange; ?>; margin-bottom: 20px; font-size: 18px;">Tautan Cepat</h4>
                <ul style="list-style: none; line-height: 2.5;">
                    <li><a href="index.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Beranda</a></li>
                    <li><a href="jadwal.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Jadwal Perjalanan</a></li>
                    <li><a href="booking.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Booking Tiket</a></li>
                    <li><a href="promo.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Promo & Diskon</a></li>
                    <li><a href="tentang.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Tentang Kami</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 style="color: <?php echo $accent_orange; ?>; margin-bottom: 20px; font-size: 18px;">Kontak Kami</h4>
                <div style="line-height: 2;">
                    <p>📞 (021) 1234-5678</p>
                    <p>📱 +62 812-3456-7890</p>
                    <p>✉️ info@sugengrahayu.com</p>
                    <p>📍 Jl. Contoh Alamat No. 123, Jakarta</p>
                </div>
            </div>

            <!-- Maps Section -->
            <div>
                <h4 style="color: <?php echo $accent_orange; ?>; margin-bottom: 20px; font-size: 18px;">Lokasi Kami</h4>
                <div style="background: rgba(255, 255, 255, 0.1); border-radius: 8px; padding: 15px; height: 200px;">
                    <!-- Placeholder untuk Google Maps -->
                    <div style="width: 100%; height: 100%; background: rgba(255, 255, 255, 0.2); border-radius: 5px; display: flex; align-items: center; justify-content: center; color: <?php echo $light_cream; ?>;">
                        <div style="text-align: center;">
                            <div style="font-size: 48px; margin-bottom: 10px;">🗺️</div>
                            <p>Google Maps Integration</p>
                            <small>Koordinat: -6.2088, 106.8456</small>
                        </div>
                    </div>
                    <!-- Untuk implementasi nyata, ganti dengan:
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.521260322283!2d106.8456!3d-6.2088!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMzEuNiJTIDEwNsKwNTAnNDQuMiJF!5e0!3m2!1sen!2sid!4v1620000000000!5m2!1sen!2sid" 
                        width="100%" 
                        height="100%" 
                        style="border:0; border-radius: 5px;" 
                        allowfullscreen="" 
                        loading="lazy">
                    </iframe>
                    -->
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div style="border-top: 1px solid rgba(255, 255, 255, 0.2); padding: 20px 0; text-align: center;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                <p style="margin: 0;">&copy; <?php echo date('Y'); ?> Sugeng Rahayu. Semua hak dilindungi.</p>
                <div style="display: flex; gap: 20px;">
                    <a href="syarat.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Syarat & Ketentuan</a>
                    <a href="privasi.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Kebijakan Privasi</a>
                    <a href="sitemap.php" style="color: <?php echo $light_cream; ?>; text-decoration: none; transition: color 0.3s ease;">Sitemap</a>
                </div>
            </div>
        </div>
    </div>
</footer>

</body>
</html>