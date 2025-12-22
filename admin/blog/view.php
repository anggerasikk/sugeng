<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../../beranda/signin.php');
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    set_flash('error', 'Blog post ID is required.');
    redirect('index.php');
}

$post_id = (int)$_GET['id'];

// Get blog post data
$query = "SELECT bp.*, u.full_name as author_name FROM blog_posts bp
         LEFT JOIN users u ON bp.author_id = u.id
         WHERE bp.id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    set_flash('error', 'Blog post not found.');
    redirect('index.php');
}

$post = mysqli_fetch_assoc($result);

// Update view count if status is published
if ($post['status'] === 'published') {
    $update_views = "UPDATE blog_posts SET views = views + 1 WHERE id = ?";
    $views_stmt = mysqli_prepare($koneksi, $update_views);
    mysqli_stmt_bind_param($views_stmt, "i", $post_id);
    mysqli_stmt_execute($views_stmt);
}

include '../header-admin.php';
?>

<style>
    .blog-view-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .blog-view-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        max-width: 1000px;
        margin: 0 auto;
    }

    .blog-header-section {
        background: linear-gradient(135deg, #001BB7 0%, #0033CC 100%);
        color: white;
        padding: 40px 30px;
        position: relative;
    }

    .blog-header-section::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 200px;
        height: 200px;
        background: rgba(255,255,255,0.05);
        border-radius: 50%;
        transform: translate(100px, -100px);
    }

    .blog-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .blog-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 15px;
        line-height: 1.2;
    }

    .blog-excerpt {
        font-size: 1.2rem;
        opacity: 0.9;
        line-height: 1.4;
    }

    .blog-content-section {
        padding: 40px 30px;
    }

    .blog-content-text {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
        margin-bottom: 30px;
    }

    .blog-content-text h1,
    .blog-content-text h2,
    .blog-content-text h3,
    .blog-content-text h4,
    .blog-content-text h5,
    .blog-content-text h6 {
        color: #001BB7;
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .blog-content-text p {
        margin-bottom: 20px;
    }

    .blog-content-text ul,
    .blog-content-text ol {
        margin-bottom: 20px;
        padding-left: 30px;
    }

    .blog-content-text blockquote {
        border-left: 4px solid #001BB7;
        padding-left: 20px;
        margin: 20px 0;
        font-style: italic;
        color: #666;
    }

    .blog-details {
        background: #f8f9fa;
        padding: 30px;
        border-top: 1px solid #e9ecef;
    }

    .details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
    }

    .detail-label {
        font-weight: 600;
        color: #001BB7;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .detail-value {
        color: #333;
        font-size: 1rem;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-published {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .status-archived {
        background: #e2e3e5;
        color: #383d41;
    }

    .blog-actions {
        background: white;
        padding: 30px;
        border-top: 1px solid #e9ecef;
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .btn-action {
        padding: 12px 24px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }

    .btn-edit {
        background: #001BB7;
        color: white;
    }

    .btn-edit:hover {
        background: #0033CC;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,27,183,0.3);
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .btn-delete:hover {
        background: #c82333;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(220,53,69,0.3);
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(108,117,125,0.3);
    }

    .thumbnail-container {
        text-align: center;
        margin-bottom: 30px;
    }

    .blog-thumbnail {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    @media (max-width: 768px) {
        .blog-view-content {
            padding: 15px;
        }

        .blog-header-section {
            padding: 30px 20px;
        }

        .blog-title {
            font-size: 2rem;
        }

        .blog-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .blog-content-section {
            padding: 30px 20px;
        }

        .blog-details {
            padding: 20px;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .blog-actions {
            padding: 20px;
            flex-direction: column;
        }

        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="blog-view-content">
    <div class="blog-view-card">
        <!-- Blog Header -->
        <div class="blog-header-section">
            <div class="blog-meta">
                <span>📅 <?php echo format_date($post['created_at']); ?></span>
                <span>👤 <?php echo htmlspecialchars($post['author_name'] ?? 'Unknown Author'); ?></span>
                <span>👁️ <?php echo number_format($post['views'] ?? 0); ?> views</span>
                <span class="status-badge status-<?php echo $post['status']; ?>">
                    <?php echo ucfirst($post['status']); ?>
                </span>
            </div>
            <h1 class="blog-title"><?php echo htmlspecialchars($post['title']); ?></h1>
            <?php if (!empty($post['excerpt'])): ?>
                <p class="blog-excerpt"><?php echo htmlspecialchars($post['excerpt']); ?></p>
            <?php endif; ?>
        </div>

        <!-- Blog Content -->
        <div class="blog-content-section">
            <?php if (!empty($post['thumbnail'])): ?>
                <div class="thumbnail-container">
                    <img src="<?php echo htmlspecialchars($post['thumbnail']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" class="blog-thumbnail">
                </div>
            <?php endif; ?>

            <div class="blog-content-text">
                <?php echo $post['content']; ?>
            </div>
        </div>

        <!-- Blog Details -->
        <div class="blog-details">
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Slug</div>
                    <div class="detail-value"><?php echo htmlspecialchars($post['slug'] ?? 'N/A'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Tag</div>
                    <div class="detail-value"><?php echo htmlspecialchars($post['tags'] ?? 'Tidak ada tag'); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <div class="detail-value">
                        <span class="status-badge status-<?php echo $post['status']; ?>">
                            <?php echo ucfirst($post['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Dibuat Pada</div>
                    <div class="detail-value"><?php echo format_date($post['created_at']); ?> <?php echo format_time($post['created_at']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Terakhir Diupdate</div>
                    <div class="detail-value"><?php echo format_date($post['updated_at']); ?> <?php echo format_time($post['updated_at']); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Diterbitkan Pada</div>
                    <div class="detail-value">
                        <?php echo $post['published_at'] ? format_date($post['published_at']) . ' ' . format_time($post['published_at']) : 'Belum diterbitkan'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="blog-actions">
            <a href="index.php" class="btn-action btn-back">
                <span>⬅️</span> Kembali ke Daftar
            </a>
            <a href="edit.php?id=<?php echo $post_id; ?>" class="btn-action btn-edit">
                <span>✏️</span> Edit Artikel
            </a>
            <a href="delete.php?id=<?php echo $post_id; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this article?')">
                <span>🗑️</span> Hapus Artikel
            </a>
        </div>
    </div>
</div>

<?php include '../footer-admin.php'; ?>