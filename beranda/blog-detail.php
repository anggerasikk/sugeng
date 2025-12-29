<?php
require_once '../config.php';
require_once '../includes/functions.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';
if (empty($slug)) {
    header('Location: index.php');
    exit;
}

// Get blog post from database
$query = "SELECT bp.*, u.full_name as author_name FROM blog_posts bp
          LEFT JOIN users u ON bp.author_id = u.id
          WHERE bp.slug = ? AND bp.status = 'published' 
          AND (bp.published_at <= NOW() OR bp.published_at IS NULL)";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "s", $slug);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    // Check if it's one of the static fallback posts
    $static_posts = [
        'sistem-booking-online-terbaru' => [
            'title' => 'Sistem Booking Online Terbaru dengan Fitur Real-Time Tracking',
            'content' => '<p>Kami dengan bangga memperkenalkan sistem booking online terbaru yang dilengkapi dengan fitur real-time tracking. Fitur ini memungkinkan Anda untuk melacak posisi bus secara langsung melalui website atau aplikasi kami.</p>
            
            <h2>Fitur Utama Sistem Baru</h2>
            
            <h3>1. Real-Time Tracking</h3>
            <p>Dengan teknologi GPS terbaru, Anda dapat melihat posisi bus secara real-time dari keberangkatan hingga kedatangan. Tidak perlu menebak-nebak lagi kapan bus akan tiba.</p>
            
            <h3>2. Notifikasi Otomatis</h3>
            <p>Sistem akan mengirimkan notifikasi via SMS dan email untuk:</p>
            <ul>
                <li>Konfirmasi booking</li>
                <li>Pemberitahuan keberangkatan</li>
                <li>Estimasi waktu tiba</li>
                <li>Update delay (jika ada)</li>
            </ul>
            
            <h3>3. Pembayaran yang Lebih Mudah</h3>
            <p>Kami menambahkan berbagai metode pembayaran baru termasuk e-wallet, virtual account, dan QRIS untuk memudahkan transaksi Anda.</p>
            
            <p>Sistem ini sudah dapat diakses melalui website resmi kami dan akan segera tersedia dalam bentuk aplikasi mobile di App Store dan Google Play Store.</p>',
            'excerpt' => 'Nikmati kemudahan booking tiket online dengan fitur tracking perjalanan secara real-time.',
            'category' => 'Teknologi',
            'author_name' => 'Admin Sugeng Rahayu',
            'created_at' => '2025-11-27 10:00:00',
            'views' => 1250
        ],
        'komitmen-kurangi-emisi-dengan-bus-ramah-lingkungan' => [
            'title' => 'Komitmen Kurangi Emisi dengan Bus Ramah Lingkungan',
            'content' => '<p>Sebagai perusahaan transportasi yang peduli terhadap lingkungan, Sugeng Rahayu Bus berkomitmen untuk mengurangi emisi karbon dengan menggunakan bus ramah lingkungan.</p>
            
            <h2>Inisiatif Ramah Lingkungan Kami</h2>
            
            <h3>1. Bus Bertenaga Listrik</h3>
            <p>Kami telah memulai transisi dengan menambahkan 10 unit bus listrik ke dalam armada kami. Bus-bus ini tidak menghasilkan emisi gas buang dan lebih hemat energi.</p>
            
            <h3>2. Bahan Bakar Ramah Lingkungan</h3>
            <p>Untuk bus konvensional, kami menggunakan bahan bakar berkualitas tinggi yang telah memenuhi standar emisi Euro 4 untuk mengurangi polusi udara.</p>
            
            <h3>3. Program Daur Ulang</h3>
            <p>Kami menerapkan program daur ulang untuk limbah yang dihasilkan selama operasional, termasuk oli bekas, ban bekas, dan komponen lainnya.</p>
            
            <h3>4. Efisiensi Rute</h3>
            <p>Dengan sistem routing yang cerdas, kami mengoptimalkan rute perjalanan untuk mengurangi konsumsi bahan bakar dan waktu tempuh.</p>
            
            <p>Kami percaya bahwa bisnis yang berkelanjutan adalah bisnis yang memperhatikan dampaknya terhadap lingkungan dan masyarakat.</p>',
            'excerpt' => 'Kami berkomitmen menggunakan teknologi ramah lingkungan untuk masa depan yang lebih baik.',
            'category' => 'Lingkungan',
            'author_name' => 'Admin Sugeng Rahayu',
            'created_at' => '2025-11-18 14:30:00',
            'views' => 890
        ],
        'rute-baru-jawa-bali-dengan-fasilitas-premium' => [
            'title' => 'Rute Baru Jawa-Bali dengan Fasilitas Premium',
            'content' => '<p>Kami dengan senang hati mengumumkan pembukaan rute baru dari Jawa ke Bali dengan fasilitas premium untuk kenyamanan perjalanan Anda.</p>
            
            <h2>Rute Baru yang Tersedia</h2>
            
            <h3>1. Jakarta - Denpasar (via Tol Trans Jawa)</h3>
            <p>Perjalanan langsung dengan waktu tempuh 24 jam. Berangkat pukul 18:00, tiba pukul 18:00 keesokan harinya.</p>
            
            <h3>2. Surabaya - Denpasar</h3>
            <p>Perjalanan siang dan malam dengan pilihan berbagai kelas bus.</p>
            
            <h3>3. Yogyakarta - Denpasar</h3>
            <p>Rute wisata dengan jadwal yang fleksibel untuk para pelancong.</p>
            
            <h2>Fasilitas Premium</h2>
            
            <h3>1. Kursi Executive Class</h3>
            <p>Kursi dengan recline 160°, footrest, dan headrest yang nyaman.</p>
            
            <h3>2. Hiburan Onboard</h3>
            <p>Layar LCD personal dengan akses ke film, musik, dan games.</p>
            
            <h3>3. Wi-Fi Gratis</h3>
            <p>Akses internet selama perjalanan untuk bekerja atau hiburan.</p>
            
            <h3>4. Makanan dan Minuman</h3>
            <p>Paket makan lengkap dengan pilihan menu yang beragam.</p>
            
            <p>Pesan tiket Anda sekarang dan nikmati pengalaman perjalanan yang tak terlupakan!</p>',
            'excerpt' => 'Temukan kenyamanan baru dengan rute terbaru dan fasilitas premium yang kami tawarkan.',
            'category' => 'Rute',
            'author_name' => 'Admin Sugeng Rahayu',
            'created_at' => '2025-11-10 09:15:00',
            'views' => 1120
        ]
    ];
    
    if (isset($static_posts[$slug])) {
        $post = $static_posts[$slug];
        $post['id'] = 0;
        $post['slug'] = $slug;
        $post['status'] = 'published';
        $post['thumbnail'] = '';
        $post['tags'] = '';
        $post['updated_at'] = $post['created_at'];
        $post['published_at'] = $post['created_at'];
    } else {
        header('Location: index.php');
        exit;
    }
} else {
    $post = mysqli_fetch_assoc($result);
    
    // Increment view count
    $update_query = "UPDATE blog_posts SET views = views + 1 WHERE id = ?";
    $update_stmt = mysqli_prepare($koneksi, $update_query);
    mysqli_stmt_bind_param($update_stmt, "i", $post['id']);
    mysqli_stmt_execute($update_stmt);
}

include 'header.php';
?>

<style>
    .blog-detail-page {
        padding: 40px 0;
        background: #f8f9fa;
        min-height: 100vh;
    }
    
    .blog-container {
        max-width: 800px;
        margin: 0 auto;
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .blog-header {
        padding: 40px 40px 20px;
    }
    
    .blog-header h1 {
        color: <?php echo $primary_blue; ?>;
        font-size: 2.5rem;
        margin-bottom: 15px;
        line-height: 1.3;
    }
    
    .blog-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 20px;
    }
    
    .blog-category {
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 3px 12px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .blog-views {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .blog-featured-image {
        width: 100%;
        max-height: 400px;
        object-fit: cover;
    }
    
    .blog-content {
        padding: 40px;
        line-height: 1.8;
        font-size: 1.1rem;
        color: #333;
    }
    
    .blog-content p {
        margin-bottom: 20px;
    }
    
    .blog-content h2, .blog-content h3 {
        color: <?php echo $primary_blue; ?>;
        margin: 30px 0 15px;
    }
    
    .blog-content ul, .blog-content ol {
        margin-left: 20px;
        margin-bottom: 20px;
    }
    
    .blog-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
    }
    
    .blog-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .blog-tag {
        background: #f0f0f0;
        color: #666;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s;
    }
    
    .blog-tag:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }
    
    .back-button {
        display: inline-block;
        background: <?php echo $primary_blue; ?>;
        color: white;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        margin: 20px 0;
        transition: all 0.3s ease;
    }
    
    .back-button:hover {
        background: <?php echo $secondary_blue; ?>;
        transform: translateY(-2px);
    }
    
    .share-buttons {
        display: flex;
        gap: 10px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .share-button {
        padding: 8px 16px;
        border-radius: 5px;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s;
    }
    
    .share-facebook {
        background: #3b5998;
        color: white;
    }
    
    .share-twitter {
        background: #1da1f2;
        color: white;
    }
    
    .share-whatsapp {
        background: #25d366;
        color: white;
    }
    
    @media (max-width: 768px) {
        .blog-header, .blog-content {
            padding: 20px;
        }
        
        .blog-header h1 {
            font-size: 1.8rem;
        }
        
        .blog-featured-image {
            height: 250px;
        }
        
        .blog-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>

<div class="blog-detail-page">
    <div class="container">
        <a href="index.php" class="back-button">← Kembali ke Beranda</a>
        
        <div class="blog-container">
            <div class="blog-header">
                <h1><?php echo htmlspecialchars($post['title']); ?></h1>
                <div class="blog-meta">
                    <span class="blog-date">📅 <?php echo format_date($post['published_at'] ?? $post['created_at']); ?></span>
                    <?php if (!empty($post['category'])): ?>
                        <span class="blog-category"><?php echo htmlspecialchars($post['category']); ?></span>
                    <?php endif; ?>
                    <span class="blog-author">✍️ By: <?php echo htmlspecialchars($post['author_name'] ?? 'Admin'); ?></span>
                    <span class="blog-views">👁️ <?php echo number_format($post['views'] ?? 0); ?> views</span>
                </div>
            </div>
            
            <?php if (!empty($post['thumbnail'])): ?>
                <?php
                $thumbnail_path = strpos($post['thumbnail'], 'http') === 0 ? $post['thumbnail'] : '../' . $post['thumbnail'];
                ?>
                <img src="<?php echo htmlspecialchars($thumbnail_path); ?>" 
                     alt="<?php echo htmlspecialchars($post['title']); ?>" 
                     class="blog-featured-image">
            <?php endif; ?>
            
            <div class="blog-content">
                <?php 
                if (isset($post['content'])) {
                    echo $post['content'];
                } else {
                    echo nl2br(htmlspecialchars($post['content']));
                }
                ?>
            </div>
            
            <?php if (!empty($post['tags'])): ?>
                <div class="blog-tags">
                    <strong>Tags:</strong>
                    <?php
                    $tags = explode(',', $post['tags']);
                    foreach ($tags as $tag) {
                        $tag = trim($tag);
                        if (!empty($tag)) {
                            echo '<a href="blog.php?tag=' . urlencode($tag) . '" class="blog-tag">' . htmlspecialchars($tag) . '</a>';
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <div class="share-buttons">
                <span style="margin-right: 10px; font-weight: 500;">Bagikan:</span>
                <?php
                $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                $share_url = urlencode($current_url);
                $share_title = urlencode($post['title']);
                ?>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" 
                   target="_blank" 
                   class="share-button share-facebook">
                    Facebook
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" 
                   target="_blank" 
                   class="share-button share-twitter">
                    Twitter
                </a>
                <a href="https://api.whatsapp.com/send?text=<?php echo $share_title . ' ' . $share_url; ?>" 
                   target="_blank" 
                   class="share-button share-whatsapp">
                    WhatsApp
                </a>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="back-button">← Kembali ke Beranda</a>
            <a href="blog.php" class="back-button" style="background: <?php echo $accent_orange; ?>; margin-left: 10px;">
                📰 Lihat Semua Berita
            </a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>