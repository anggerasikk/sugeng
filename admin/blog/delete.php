<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../../beranda/signin.php');
}

// Get post ID
$post_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$post_id) {
    set_flash('error', 'Invalid post ID.');
    redirect('index.php');
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && verify_csrf_token($_POST['csrf_token'])) {
    // Fetch post to get thumbnail path
    $query = "SELECT thumbnail FROM blog_posts WHERE id = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $post = mysqli_fetch_assoc($result);

    // Delete the post
    $delete_query = "DELETE FROM blog_posts WHERE id = ?";
    $delete_stmt = mysqli_prepare($koneksi, $delete_query);
    mysqli_stmt_bind_param($delete_stmt, "i", $post_id);

    if (mysqli_stmt_execute($delete_stmt)) {
        // Delete thumbnail file if it exists
        if ($post['thumbnail'] && file_exists('../../' . $post['thumbnail'])) {
            unlink('../../' . $post['thumbnail']);
        }

        set_flash('success', 'Blog post deleted successfully.');
        redirect('index.php');
    } else {
        set_flash('error', 'Failed to delete blog post. Please try again.');
        redirect('index.php');
    }
}

// Fetch post details for confirmation
$query = "SELECT title FROM blog_posts WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    set_flash('error', 'Blog post not found.');
    redirect('index.php');
}

$post = mysqli_fetch_assoc($result);

include '../header-admin.php';
?>

<style>
    .delete-blog-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .delete-blog-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 500px;
        width: 100%;
        text-align: center;
    }

    .delete-blog-header {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        padding: 30px;
        border-radius: 10px 10px 0 0;
    }

    .delete-blog-header h1 {
        margin: 0;
        font-size: 1.8rem;
    }

    .delete-blog-body {
        padding: 30px;
    }

    .warning-icon {
        font-size: 4rem;
        color: #dc3545;
        margin-bottom: 20px;
    }

    .post-title {
        font-size: 1.2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 20px;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 5px;
        border-left: 4px solid <?php echo $primary_blue; ?>;
    }

    .warning-text {
        color: #666;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        justify-content: center;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .btn-delete:hover {
        background: #c82333;
    }

    .btn-cancel {
        background: #6c757d;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        text-decoration: none;
        display: inline-block;
        transition: background 0.3s;
    }

    .btn-cancel:hover {
        background: #545b62;
    }

    @media (max-width: 768px) {
        .delete-blog-content {
            padding: 10px;
        }

        .delete-blog-body {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-delete,
        .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="delete-blog-content">
    <div class="delete-blog-card">
        <div class="delete-blog-header">
            <h1>🗑️ Delete Blog Article</h1>
        </div>

        <div class="delete-blog-body">
            <div class="warning-icon">⚠️</div>

            <div class="post-title">
                "<?php echo htmlspecialchars($post['title']); ?>"
            </div>

            <div class="warning-text">
                <p><strong>Are you sure you want to delete this blog article?</strong></p>
                <p>This action cannot be undone. The article and its thumbnail image will be permanently removed from the system.</p>
            </div>

            <form method="POST" action="">
                <?php echo csrf_field(); ?>

                <div class="form-actions">
                    <button type="submit" class="btn-delete" onclick="return confirm('Are you absolutely sure you want to delete this article? This action cannot be undone.')">🗑️ Yes, Delete Article</button>
                    <a href="view.php?id=<?php echo $post_id; ?>" class="btn-cancel">👁️ View Article</a>
                    <a href="index.php" class="btn-cancel">❌ Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer-admin.php'; ?>