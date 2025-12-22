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

// Fetch existing post
$query = "SELECT * FROM blog_posts WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    set_flash('error', 'Blog post not found.');
    redirect('index.php');
}

$post = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && verify_csrf_token($_POST['csrf_token'])) {
    $title = sanitize_input($_POST['title']);
    $excerpt = sanitize_input($_POST['excerpt']);
    $content = $_POST['content']; // Allow HTML content
    $category = sanitize_input($_POST['category']);
    $status = sanitize_input($_POST['status']);
    $tags = sanitize_input($_POST['tags']);

    // Validate input
    $errors = [];

    if (empty($title)) {
        $errors[] = 'Title is required.';
    } elseif (empty(preg_replace('/[^a-zA-Z0-9]/', '', $title))) {
        $errors[] = 'Title must contain at least one letter or number.';
    }

    if (empty($content)) {
        $errors[] = 'Content is required.';
    }

    if (!in_array($status, ['draft', 'published'])) {
        $errors[] = 'Invalid status selected.';
    }

    // Handle thumbnail upload
    $thumbnail_path = $post['thumbnail']; // Keep existing thumbnail by default
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB

        if (!in_array($_FILES['thumbnail']['type'], $allowed_types)) {
            $errors[] = 'Invalid image type. Only JPG, PNG, GIF, and WebP are allowed.';
        } elseif ($_FILES['thumbnail']['size'] > $max_size) {
            $errors[] = 'Image size too large. Maximum 5MB allowed.';
        } else {
            // Create uploads directory if it doesn't exist
            $upload_dir = '../../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate unique filename
            $extension = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
            $filename = 'blog_' . time() . '_' . uniqid() . '.' . $extension;
            $thumbnail_path = 'uploads/' . $filename;

            if (!move_uploaded_file($_FILES['thumbnail']['tmp_name'], '../../' . $thumbnail_path)) {
                $errors[] = 'Failed to upload image.';
            } else {
                // Delete old thumbnail if it exists and is different
                if ($post['thumbnail'] && $post['thumbnail'] !== $thumbnail_path && file_exists('../../' . $post['thumbnail'])) {
                    unlink('../../' . $post['thumbnail']);
                }
            }
        }
    }

    if (empty($errors)) {
        // Generate slug from title (only if title changed)
        $slug = $post['slug'];
        if ($title !== $post['title']) {
            $base_slug = generate_slug($title);
            
            // Fallback if slug is empty (e.g., title contains only special characters)
            if (empty($base_slug)) {
                $base_slug = 'post-' . time();
            }
            
            // Ensure unique slug (excluding current post)
            $slug = $base_slug;
            $counter = 1;
            while (true) {
                $check_query = "SELECT id FROM blog_posts WHERE slug = ? AND id != ?";
                $check_stmt = mysqli_prepare($koneksi, $check_query);
                mysqli_stmt_bind_param($check_stmt, "si", $slug, $post_id);
                mysqli_stmt_execute($check_stmt);
                $check_result = mysqli_stmt_get_result($check_stmt);
                
                if (mysqli_num_rows($check_result) == 0) {
                    break; // Slug is unique
                }
                
                $slug = $base_slug . '-' . $counter;
                $counter++;
            }
        }

        // Prepare published_at
        $published_at = ($status === 'published' && $post['status'] !== 'published') ? 'NOW()' : 
                       ($status === 'published' ? $post['published_at'] : 'NULL');

        // Update blog post
        $update_query = "UPDATE blog_posts SET 
                        title = ?, slug = ?, excerpt = ?, content = ?, category = ?, 
                        tags = ?, thumbnail = ?, status = ?, published_at = " . ($published_at === 'NOW()' ? 'NOW()' : '?') . ", 
                        updated_at = NOW() 
                        WHERE id = ?";

        $stmt = mysqli_prepare($koneksi, $update_query);
        if ($published_at === 'NOW()') {
            mysqli_stmt_bind_param($stmt, "sssssssssi", $title, $slug, $excerpt, $content, $category, $tags, $thumbnail_path, $status, $post_id);
        } else {
            mysqli_stmt_bind_param($stmt, "sssssssssi", $title, $slug, $excerpt, $content, $category, $tags, $thumbnail_path, $status, $published_at, $post_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            set_flash('success', 'Blog post updated successfully.');
            redirect('view.php?id=' . $post_id);
        } else {
            $errors[] = 'Failed to update blog post. Please try again.';
        }
    }

    if (!empty($errors)) {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../header-admin.php';
?>

<style>
    .edit-blog-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .edit-blog-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 1000px;
        margin: 0 auto;
    }

    .edit-blog-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .edit-blog-header h1 {
        margin: 0;
        font-size: 1.8rem;
    }

    .edit-blog-form {
        padding: 30px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 5px;
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: <?php echo $primary_blue; ?>;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 120px;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .thumbnail-preview {
        margin-top: 10px;
        max-width: 200px;
        max-height: 150px;
        border-radius: 5px;
        display: none;
    }

    .current-thumbnail {
        margin-top: 10px;
        max-width: 200px;
        max-height: 150px;
        border-radius: 5px;
        border: 2px solid #ddd;
    }

    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .btn-submit {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        font-size: 1rem;
        transition: background 0.3s;
    }

    .btn-submit:hover {
        background: <?php echo $primary_blue; ?>;
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

    .character-count {
        font-size: 0.8rem;
        color: #666;
        text-align: right;
        margin-top: 5px;
    }

    .character-count.warning {
        color: #ffc107;
    }

    .character-count.danger {
        color: #dc3545;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
        }

        .edit-blog-form {
            padding: 20px;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-submit,
        .btn-cancel {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="edit-blog-content">
    <div class="edit-blog-card">
        <div class="edit-blog-header">
            <h1>✏️ Edit Blog Article</h1>
        </div>

        <form method="POST" action="" enctype="multipart/form-data" class="edit-blog-form">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required maxlength="255">
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($post['category'] ?? ''); ?>" placeholder="e.g., Technology, Travel, News">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="excerpt">Excerpt</label>
                <textarea id="excerpt" name="excerpt" maxlength="500" placeholder="Brief summary of the article..."><?php echo htmlspecialchars($post['excerpt'] ?? ''); ?></textarea>
                <div class="character-count" id="excerpt-count"><?php echo strlen($post['excerpt'] ?? ''); ?> / 500</div>
            </div>

            <div class="form-group full-width">
                <label for="content">Content *</label>
                <textarea id="content" name="content" required placeholder="Write your article content here..."><?php echo htmlspecialchars($post['content']); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($post['tags'] ?? ''); ?>" placeholder="Comma-separated tags">
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="draft" <?php echo ($post['status'] === 'draft' ? 'selected' : ''); ?>>Draft</option>
                        <option value="published" <?php echo ($post['status'] === 'published' ? 'selected' : ''); ?>>Published</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="thumbnail">Thumbnail Image</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                <small style="color: #666; margin-top: 5px; display: block;">Recommended size: 800x400px. Max 5MB. JPG, PNG, GIF, WebP allowed. Leave empty to keep current image.</small>
                <?php if ($post['thumbnail']) { ?>
                    <div style="margin-top: 10px;">
                        <strong>Current thumbnail:</strong><br>
                        <img src="../../<?php echo htmlspecialchars($post['thumbnail']); ?>" alt="Current thumbnail" class="current-thumbnail">
                    </div>
                <?php } ?>
                <img id="thumbnail-preview" class="thumbnail-preview" alt="Thumbnail preview">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Update Article</button>
                <a href="view.php?id=<?php echo $post_id; ?>" class="btn-cancel">👁️ View Article</a>
                <a href="index.php" class="btn-cancel">❌ Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
// Character count for excerpt
document.getElementById('excerpt').addEventListener('input', function() {
    const count = this.value.length;
    const counter = document.getElementById('excerpt-count');
    counter.textContent = count + ' / 500';

    counter.classList.remove('warning', 'danger');
    if (count > 450) {
        counter.classList.add('warning');
    }
    if (count > 500) {
        counter.classList.add('danger');
    }
});

// Thumbnail preview
document.getElementById('thumbnail').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('thumbnail-preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// Initialize character count on page load
document.addEventListener('DOMContentLoaded', function() {
    const excerpt = document.getElementById('excerpt');
    if (excerpt.value.length > 0) {
        excerpt.dispatchEvent(new Event('input'));
    }
});
</script>

<?php include '../footer-admin.php'; ?>