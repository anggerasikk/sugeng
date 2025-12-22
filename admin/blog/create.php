<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../signin.php');
}

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
    $thumbnail_path = null;
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
            }
        }
    }

    if (empty($errors)) {
        // Get current user ID
        $author_id = $_SESSION['user_id'];

        // Generate slug from title
        $base_slug = generate_slug($title);
        
        // Fallback if slug is empty (e.g., title contains only special characters)
        if (empty($base_slug)) {
            $base_slug = 'post-' . time();
        }
        
        // Ensure unique slug
        $slug = $base_slug;
        $counter = 1;
        while (true) {
            $check_query = "SELECT id FROM blog_posts WHERE slug = ?";
            $check_stmt = mysqli_prepare($koneksi, $check_query);
            mysqli_stmt_bind_param($check_stmt, "s", $slug);
            mysqli_stmt_execute($check_stmt);
            $check_result = mysqli_stmt_get_result($check_stmt);
            
            if (mysqli_num_rows($check_result) == 0) {
                break; // Slug is unique
            }
            
            $slug = $base_slug . '-' . $counter;
            $counter++;
        }

        // Prepare published_at
        $published_at = ($status === 'published') ? 'NOW()' : 'NULL';

        // Insert blog post
        $insert_query = "INSERT INTO blog_posts (title, slug, excerpt, content, category, tags, thumbnail, status, published_at, author_id, created_at, updated_at)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, $published_at, ?, NOW(), NOW())";

        $stmt = mysqli_prepare($koneksi, $insert_query);
        mysqli_stmt_bind_param($stmt, "ssssssssi", $title, $slug, $excerpt, $content, $category, $tags, $thumbnail_path, $status, $author_id);

        if (mysqli_stmt_execute($stmt)) {
            $new_post_id = mysqli_insert_id($koneksi);
            set_flash('success', 'Blog post created successfully.');
            redirect('view.php?id=' . $new_post_id);
        } else {
            $errors[] = 'Failed to create blog post. Please try again.';
        }
    }

    if (!empty($errors)) {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../header-admin.php';
?>

<style>
    .create-blog-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .create-blog-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 1000px;
        margin: 0 auto;
    }

    .create-blog-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        border-radius: 10px 10px 0 0;
        text-align: center;
    }

    .create-blog-header h1 {
        margin: 0;
        font-size: 1.8rem;
    }

    .create-blog-form {
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

        .create-blog-form {
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

<div class="create-blog-content">
    <div class="create-blog-card">
        <div class="create-blog-header">
            <h1>📝 Create New Blog Article</h1>
        </div>

        <form method="POST" action="" enctype="multipart/form-data" class="create-blog-form">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required maxlength="255">
                </div>

                <div class="form-group">
                    <label for="category">Category</label>
                    <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>" placeholder="e.g., Technology, Travel, News">
                </div>
            </div>

            <div class="form-group full-width">
                <label for="excerpt">Excerpt</label>
                <textarea id="excerpt" name="excerpt" maxlength="500" placeholder="Brief summary of the article..."><?php echo htmlspecialchars($_POST['excerpt'] ?? ''); ?></textarea>
                <div class="character-count" id="excerpt-count">0 / 500</div>
            </div>

            <div class="form-group full-width">
                <label for="content">Content *</label>
                <textarea id="content" name="content" required placeholder="Write your article content here..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="tags">Tags</label>
                    <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($_POST['tags'] ?? ''); ?>" placeholder="Comma-separated tags">
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="draft" <?php echo (($_POST['status'] ?? 'draft') === 'draft' ? 'selected' : ''); ?>>Draft</option>
                        <option value="published" <?php echo (($_POST['status'] ?? '') === 'published' ? 'selected' : ''); ?>>Published</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="thumbnail">Thumbnail Image</label>
                <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
                <small style="color: #666; margin-top: 5px; display: block;">Recommended size: 800x400px. Max 5MB. JPG, PNG, GIF, WebP allowed.</small>
                <img id="thumbnail-preview" class="thumbnail-preview" alt="Thumbnail preview">
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Create Article</button>
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
