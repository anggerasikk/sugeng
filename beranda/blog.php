<?php
require_once '../config.php';
require_once '../includes/functions.php';

// Get parameters
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$category = isset($_GET['category']) ? sanitize_input($_GET['category']) : '';
$tag = isset($_GET['tag']) ? sanitize_input($_GET['tag']) : '';
$search = isset($_GET['search']) ? sanitize_input($_GET['search']) : '';

// Get blog posts with pagination
$per_page = 9;
$blog_data = get_published_blog_posts($page, $per_page, $category, $tag, $search);

// Get categories for filter
$categories = get_blog_categories();

// Get popular posts for sidebar
$popular_posts = get_popular_blog_posts(5);

// Get recent posts for sidebar
$recent_posts = get_recent_blog_posts(5);

// Get tags cloud
$tags_cloud = get_blog_tags_cloud(20);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog & Berita - Bus Sugeng Rahayu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        .blog-header {
            background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
            color: white;
            padding: 80px 0 60px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .blog-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="%23ffffff" fill-opacity="0.1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>') bottom center no-repeat;
            background-size: cover;
        }

        .blog-header-content {
            position: relative;
            z-index: 1;
        }

        .blog-header h1 {
            font-size: 3.5rem;
            margin-bottom: 20px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .blog-header p {
            font-size: 1.3rem;
            max-width: 800px;
            margin: 0 auto 30px;
            opacity: 0.95;
        }

        /* Search Box */
        .blog-search-box {
            max-width: 600px;
            margin: 30px auto 0;
            position: relative;
        }

        .blog-search-input {
            width: 100%;
            padding: 15px 50px 15px 20px;
            border: none;
            border-radius: 50px;
            font-size: 1.1rem;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .blog-search-input:focus {
            outline: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            background: white;
        }

        .blog-search-button {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: <?php echo $accent_orange; ?>;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .blog-search-button:hover {
            background: <?php echo $primary_blue; ?>;
            transform: translateY(-50%) scale(1.1);
        }

        /* Main Content Layout */
        .blog-content {
            padding: 60px 0;
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
        }

        @media (max-width: 992px) {
            .blog-content {
                grid-template-columns: 1fr;
            }
        }

        /* Blog Grid */
        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
            margin-bottom: 40px;
        }

        .blog-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            height: 100%;
            border: 2px solid transparent;
        }

        .blog-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            border-color: <?php echo $accent_orange; ?>;
        }

        .blog-card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
        }

        .blog-card-image {
            height: 200px;
            width: 100%;
            position: relative;
            overflow: hidden;
        }

        .blog-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .blog-card:hover .blog-card-image img {
            transform: scale(1.05);
        }

        .blog-card-category {
            position: absolute;
            top: 15px;
            left: 15px;
            background: <?php echo $accent_orange; ?>;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .blog-card-content {
            padding: 25px;
        }

        .blog-card-title {
            color: <?php echo $primary_blue; ?>;
            font-size: 1.3rem;
            margin-bottom: 12px;
            line-height: 1.4;
            font-weight: 600;
        }

        .blog-card-excerpt {
            color: #666;
            line-height: 1.6;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .blog-card-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.85rem;
            color: #888;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .blog-card-date {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .blog-card-views {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* No Results */
        .no-results {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            grid-column: 1 / -1;
        }

        .no-results-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }

        .no-results h3 {
            color: <?php echo $primary_blue; ?>;
            margin-bottom: 10px;
            font-size: 1.5rem;
        }

        .no-results p {
            color: #666;
            margin-bottom: 30px;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Sidebar */
        .blog-sidebar {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            height: fit-content;
            position: sticky;
            top: 100px;
        }

        .sidebar-section {
            margin-bottom: 40px;
        }

        .sidebar-section:last-child {
            margin-bottom: 0;
        }

        .sidebar-section h3 {
            color: <?php echo $primary_blue; ?>;
            font-size: 1.3rem;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid <?php echo $accent_orange; ?>;
            position: relative;
        }

        .sidebar-section h3::after {
            content: '';
            position: absolute;
            bottom: -3px;
            left: 0;
            width: 50px;
            height: 3px;
            background: <?php echo $primary_blue; ?>;
        }

        /* Categories */
        .categories-list {
            list-style: none;
        }

        .categories-list li {
            margin-bottom: 10px;
        }

        .categories-list a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            background: #f8f9fa;
            border-radius: 8px;
            text-decoration: none;
            color: #555;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }

        .categories-list a:hover {
            background: <?php echo $primary_blue; ?>;
            color: white;
            border-left-color: <?php echo $accent_orange; ?>;
            transform: translateX(5px);
        }

        .category-count {
            background: <?php echo $accent_orange; ?>;
            color: white;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Recent Posts */
        .recent-posts-list {
            list-style: none;
        }

        .recent-posts-list li {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .recent-posts-list li:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .recent-post {
            display: flex;
            gap: 15px;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
        }

        .recent-post:hover {
            transform: translateX(5px);
        }

        .recent-post-image {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            overflow: hidden;
            flex-shrink: 0;
        }

        .recent-post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .recent-post-content h4 {
            color: <?php echo $primary_blue; ?>;
            font-size: 0.95rem;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .recent-post-date {
            color: #888;
            font-size: 0.8rem;
        }

        /* Tags Cloud */
        .tags-cloud {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tag-link {
            display: inline-block;
            padding: 8px 15px;
            background: #f8f9fa;
            color: #666;
            text-decoration: none;
            border-radius: 20px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .tag-link:hover {
            background: <?php echo $primary_blue; ?>;
            color: white;
            border-color: <?php echo $primary_blue; ?>;
            transform: translateY(-2px);
        }

        /* Pagination */
        .blog-pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 50px;
            padding-top: 30px;
            border-top: 1px solid #eee;
        }

        .pagination-link {
            padding: 10px 16px;
            border: 1px solid #ddd;
            background: white;
            color: <?php echo $primary_blue; ?>;
            text-decoration: none;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
            min-width: 40px;
            text-align: center;
        }

        .pagination-link:hover {
            background: <?php echo $primary_blue; ?>;
            color: white;
            border-color: <?php echo $primary_blue; ?>;
            transform: translateY(-2px);
        }

        .pagination-link.active {
            background: <?php echo $primary_blue; ?>;
            color: white;
            border-color: <?php echo $primary_blue; ?>;
        }

        .pagination-link.disabled {
            background: #f8f9fa;
            color: #999;
            border-color: #eee;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .pagination-link.disabled:hover {
            background: #f8f9fa;
            color: #999;
            border-color: #eee;
            transform: none;
        }

        .pagination-info {
            color: #666;
            font-size: 0.9rem;
            margin: 0 15px;
        }

        /* Active Filters */
        .active-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 30px;
            align-items: center;
        }

        .active-filter {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: <?php echo $primary_blue; ?>;
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
        }

        .active-filter-remove {
            background: white;
            color: <?php echo $primary_blue; ?>;
            border: none;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .active-filter-remove:hover {
            background: <?php echo $accent_orange; ?>;
            color: white;
        }

        .clear-filters {
            color: <?php echo $primary_blue; ?>;
            text-decoration: none;
            font-size: 0.9rem;
            margin-left: 10px;
        }

        .clear-filters:hover {
            text-decoration: underline;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .blog-header {
                padding: 60px 0 40px;
            }

            .blog-header h1 {
                font-size: 2.5rem;
            }

            .blog-header p {
                font-size: 1.1rem;
            }

            .blog-search-input {
                padding: 12px 45px 12px 15px;
                font-size: 1rem;
            }

            .blog-grid {
                grid-template-columns: 1fr;
            }

            .blog-content {
                padding: 40px 0;
                gap: 30px;
            }

            .blog-sidebar {
                position: static;
            }

            .pagination-link {
                padding: 8px 12px;
                min-width: 35px;
            }

            .pagination-info {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .blog-header h1 {
                font-size: 2rem;
            }

            .blog-card-content {
                padding: 20px;
            }

            .blog-card-title {
                font-size: 1.1rem;
            }

            .active-filters {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Blog Header -->
    <header class="blog-header">
        <div class="container">
            <div class="blog-header-content">
                <h1>📰 Blog & Berita</h1>
                <p>Ikuti perkembangan terbaru, promo spesial, dan informasi terkini seputar layanan Bus Sugeng Rahayu</p>
                
                <!-- Search Form -->
                <div class="blog-search-box">
                    <form method="GET" action="" id="blog-search-form">
                        <input type="text" 
                               name="search" 
                               class="blog-search-input" 
                               placeholder="Cari artikel atau berita..."
                               value="<?php echo htmlspecialchars($search); ?>">
                        <button type="submit" class="blog-search-button">
                            🔍
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container">
        <!-- Active Filters -->
        <?php if (!empty($category) || !empty($tag) || !empty($search)): ?>
        <div class="active-filters">
            <strong>Filter Aktif:</strong>
            
            <?php if (!empty($category)): ?>
            <span class="active-filter">
                Kategori: <?php echo htmlspecialchars($category); ?>
                <a href="<?php echo remove_query_param('category'); ?>" class="active-filter-remove">×</a>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($tag)): ?>
            <span class="active-filter">
                Tag: <?php echo htmlspecialchars($tag); ?>
                <a href="<?php echo remove_query_param('tag'); ?>" class="active-filter-remove">×</a>
            </span>
            <?php endif; ?>
            
            <?php if (!empty($search)): ?>
            <span class="active-filter">
                Pencarian: "<?php echo htmlspecialchars($search); ?>"
                <a href="<?php echo remove_query_param('search'); ?>" class="active-filter-remove">×</a>
            </span>
            <?php endif; ?>
            
            <a href="blog.php" class="clear-filters">Hapus Semua Filter</a>
        </div>
        <?php endif; ?>

        <div class="blog-content">
            <!-- Main Blog Content -->
            <div class="blog-main">
                <?php if (!empty($blog_data['posts'])): ?>
                    <div class="blog-grid">
                        <?php foreach ($blog_data['posts'] as $post): 
                            // Determine icon based on category
                            $icon = '📰';
                            $category_lower = strtolower($post['category'] ?? '');
                            if (strpos($category_lower, 'teknologi') !== false || strpos($category_lower, 'sistem') !== false) {
                                $icon = '🚌';
                            } elseif (strpos($category_lower, 'lingkungan') !== false || strpos($category_lower, 'hijau') !== false) {
                                $icon = '🌱';
                            } elseif (strpos($category_lower, 'rute') !== false || strpos($category_lower, 'perjalanan') !== false) {
                                $icon = '🎯';
                            } elseif (strpos($category_lower, 'promo') !== false || strpos($category_lower, 'diskon') !== false) {
                                $icon = '🎉';
                            }
                            
                            // Generate excerpt if not exists
                            $excerpt = $post['excerpt'] ?? generate_excerpt($post['content'], 120);
                            
                            // Format date
                            $date = format_date($post['published_at'] ?? $post['created_at']);
                        ?>
                        <article class="blog-card">
                            <a href="blog-detail.php?slug=<?php echo urlencode($post['slug']); ?>" class="blog-card-link">
                                <div class="blog-card-image">
                                    <?php if (!empty($post['thumbnail'])): 
                                        $thumbnail_path = strpos($post['thumbnail'], 'http') === 0 ? 
                                            $post['thumbnail'] : '../' . $post['thumbnail'];
                                    ?>
                                        <img src="<?php echo htmlspecialchars($thumbnail_path); ?>" 
                                             alt="<?php echo htmlspecialchars($post['title']); ?>">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; background: linear-gradient(45deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>); 
                                                    display: flex; align-items: center; justify-content: center; color: white; font-size: 3rem;">
                                            <?php echo $icon; ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($post['category'])): ?>
                                    <span class="blog-card-category">
                                        <?php echo htmlspecialchars($post['category']); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="blog-card-content">
                                    <h3 class="blog-card-title">
                                        <?php echo htmlspecialchars($post['title']); ?>
                                    </h3>
                                    
                                    <p class="blog-card-excerpt">
                                        <?php echo htmlspecialchars($excerpt); ?>
                                    </p>
                                    
                                    <div class="blog-card-meta">
                                        <span class="blog-card-date">
                                            📅 <?php echo $date; ?>
                                        </span>
                                        <span class="blog-card-views">
                                            👁️ <?php echo number_format($post['views'] ?? 0); ?>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </article>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-results">
                        <div class="no-results-icon">📭</div>
                        <h3>Tidak ada artikel ditemukan</h3>
                        <p>
                            <?php if (!empty($search)): ?>
                                Maaf, tidak ada artikel yang sesuai dengan pencarian "<?php echo htmlspecialchars($search); ?>".
                            <?php elseif (!empty($category)): ?>
                                Maaf, tidak ada artikel dalam kategori "<?php echo htmlspecialchars($category); ?>".
                            <?php elseif (!empty($tag)): ?>
                                Maaf, tidak ada artikel dengan tag "<?php echo htmlspecialchars($tag); ?>".
                            <?php else: ?>
                                Belum ada artikel yang dipublikasikan. Silakan kembali lagi nanti.
                            <?php endif; ?>
                        </p>
                        <a href="blog.php" class="pagination-link" style="display: inline-block;">
                            ← Kembali ke Semua Artikel
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Pagination -->
                <?php if ($blog_data['total_pages'] > 1): ?>
                <div class="blog-pagination">
                    <!-- Previous Page -->
                    <?php if ($page > 1): ?>
                        <a href="<?php echo build_pagination_url($page - 1, $category, $tag, $search); ?>" 
                           class="pagination-link">
                            ←
                        </a>
                    <?php else: ?>
                        <span class="pagination-link disabled">←</span>
                    <?php endif; ?>

                    <!-- Page Numbers -->
                    <?php
                    $start_page = max(1, $page - 2);
                    $end_page = min($blog_data['total_pages'], $page + 2);
                    
                    for ($i = $start_page; $i <= $end_page; $i++):
                        $active = $i == $page ? 'active' : '';
                    ?>
                        <a href="<?php echo build_pagination_url($i, $category, $tag, $search); ?>" 
                           class="pagination-link <?php echo $active; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <!-- Next Page -->
                    <?php if ($page < $blog_data['total_pages']): ?>
                        <a href="<?php echo build_pagination_url($page + 1, $category, $tag, $search); ?>" 
                           class="pagination-link">
                            →
                        </a>
                    <?php else: ?>
                        <span class="pagination-link disabled">→</span>
                    <?php endif; ?>

                    <!-- Page Info -->
                    <span class="pagination-info">
                        Halaman <?php echo $page; ?> dari <?php echo $blog_data['total_pages']; ?>
                        (Total <?php echo $blog_data['total_posts']; ?> artikel)
                    </span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <aside class="blog-sidebar">
                <!-- Categories -->
                <div class="sidebar-section">
                    <h3>Kategori</h3>
                    <ul class="categories-list">
                        <li>
                            <a href="blog.php">
                                <span>Semua Kategori</span>
                                <span class="category-count"><?php echo $blog_data['total_posts']; ?></span>
                            </a>
                        </li>
                        <?php foreach ($categories as $cat): ?>
                        <li>
                            <a href="blog.php?category=<?php echo urlencode($cat['category']); ?>">
                                <span><?php echo htmlspecialchars($cat['category']); ?></span>
                                <span class="category-count"><?php echo $cat['post_count']; ?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Popular Posts -->
                <div class="sidebar-section">
                    <h3>Artikel Populer</h3>
                    <ul class="recent-posts-list">
                        <?php foreach ($popular_posts as $popular): 
                            $popular_date = format_date($popular['published_at'] ?? $popular['created_at']);
                        ?>
                        <li>
                            <a href="blog-detail.php?slug=<?php echo urlencode($popular['slug']); ?>" class="recent-post">
                                <div class="recent-post-image">
                                    <?php if (!empty($popular['thumbnail'])): 
                                        $thumb_path = strpos($popular['thumbnail'], 'http') === 0 ? 
                                            $popular['thumbnail'] : '../' . $popular['thumbnail'];
                                    ?>
                                        <img src="<?php echo htmlspecialchars($thumb_path); ?>" 
                                             alt="<?php echo htmlspecialchars($popular['title']); ?>">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; background: #f0f0f0; 
                                                    display: flex; align-items: center; justify-content: center; 
                                                    color: #999; font-size: 1.5rem;">
                                            📰
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="recent-post-content">
                                    <h4><?php echo htmlspecialchars($popular['title']); ?></h4>
                                    <div class="recent-post-date">
                                        📅 <?php echo $popular_date; ?>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Recent Posts -->
                <div class="sidebar-section">
                    <h3>Artikel Terbaru</h3>
                    <ul class="recent-posts-list">
                        <?php foreach ($recent_posts as $recent): 
                            $recent_date = format_date($recent['published_at'] ?? $recent['created_at']);
                        ?>
                        <li>
                            <a href="blog-detail.php?slug=<?php echo urlencode($recent['slug']); ?>" class="recent-post">
                                <div class="recent-post-image">
                                    <?php if (!empty($recent['thumbnail'])): 
                                        $thumb_path = strpos($recent['thumbnail'], 'http') === 0 ? 
                                            $recent['thumbnail'] : '../' . $recent['thumbnail'];
                                    ?>
                                        <img src="<?php echo htmlspecialchars($thumb_path); ?>" 
                                             alt="<?php echo htmlspecialchars($recent['title']); ?>">
                                    <?php else: ?>
                                        <div style="width: 100%; height: 100%; background: #f0f0f0; 
                                                    display: flex; align-items: center; justify-content: center; 
                                                    color: #999; font-size: 1.5rem;">
                                            📰
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="recent-post-content">
                                    <h4><?php echo htmlspecialchars($recent['title']); ?></h4>
                                    <div class="recent-post-date">
                                        📅 <?php echo $recent_date; ?>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <!-- Tags Cloud -->
                <div class="sidebar-section">
                    <h3>Tag Populer</h3>
                    <div class="tags-cloud">
                        <?php foreach ($tags_cloud as $tag_name => $tag_count): ?>
                            <a href="blog.php?tag=<?php echo urlencode($tag_name); ?>" class="tag-link">
                                <?php echo htmlspecialchars($tag_name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="sidebar-section">
                    <a href="index.php" class="pagination-link" style="display: block; text-align: center;">
                        ← Kembali ke Beranda
                    </a>
                </div>
            </aside>
        </div>
    </main>

    <!-- Helper Functions -->
    <?php
    /**
     * Helper function to build pagination URL
     */
    function build_pagination_url($page, $category, $tag, $search) {
        $params = [];
        if ($page > 1) $params['page'] = $page;
        if (!empty($category)) $params['category'] = $category;
        if (!empty($tag)) $params['tag'] = $tag;
        if (!empty($search)) $params['search'] = $search;
        
        return 'blog.php' . (!empty($params) ? '?' . http_build_query($params) : '');
    }

    /**
     * Helper function to remove query parameter
     */
    function remove_query_param($param) {
        $params = $_GET;
        unset($params[$param]);
        unset($params['page']); // Reset to page 1
        
        return 'blog.php' . (!empty($params) ? '?' . http_build_query($params) : '');
    }
    ?>

    <script>
    // Enhance search experience
    document.addEventListener('DOMContentLoaded', function() {
        // Auto focus on search input
        const searchInput = document.querySelector('.blog-search-input');
        if (searchInput) {
            searchInput.focus();
        }

        // Handle form submission
        const searchForm = document.getElementById('blog-search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                const searchValue = searchInput.value.trim();
                if (searchValue === '') {
                    e.preventDefault();
                    window.location.href = 'blog.php';
                }
            });
        }

        // Smooth scroll for pagination links
        document.querySelectorAll('.pagination-link:not(.disabled)').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetUrl = this.href;
                
                // Add loading animation
                document.body.style.opacity = '0.8';
                document.body.style.cursor = 'wait';
                
                setTimeout(() => {
                    window.location.href = targetUrl;
                }, 300);
            });
        });

        // Highlight active filter
        const currentUrl = window.location.href;
        document.querySelectorAll('.categories-list a, .tag-link').forEach(link => {
            if (link.href === currentUrl) {
                link.style.background = '<?php echo $primary_blue; ?>';
                link.style.color = 'white';
                link.style.borderColor = '<?php echo $primary_blue; ?>';
            }
        });
    });

    // Add animation to cards on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all blog cards
    document.querySelectorAll('.blog-card').forEach(card => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(card);
    });
    </script>

</body>
</html>

<?php include 'footer.php'; ?>