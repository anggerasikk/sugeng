<?php
require_once '../../config.php';
require_once '../../includes/functions.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../signin.php');
}

include '../header-admin.php';
?>

<style>
    .blog-content {
        margin-top: 70px;
        margin-left: 0;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .blog-header {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .blog-header h1 {
        color: <?php echo $primary_blue; ?>;
        margin: 0;
    }

    .btn-add {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        text-decoration: none;
        font-weight: 500;
    }

    .btn-add:hover {
        background: <?php echo $primary_blue; ?>;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        text-align: center;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: bold;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 5px;
    }

    .stat-label {
        color: #666;
        font-size: 0.9rem;
    }

    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    .filters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-group label {
        margin-bottom: 5px;
        font-weight: 500;
        color: <?php echo $primary_blue; ?>;
    }

    .filter-group input,
    .filter-group select {
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .btn-filter {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
    }

    .btn-filter:hover {
        background: <?php echo $primary_blue; ?>;
    }

    .blog-table {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }

    .table-header {
        background: <?php echo $primary_blue; ?>;
        color: white;
        padding: 15px 20px;
        font-weight: bold;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #eee;
    }

    th {
        background: #f8f9fa;
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    tr:hover {
        background: #f8f9fa;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-published {
        background: #d4edda;
        color: #155724;
    }

    .status-draft {
        background: #fff3cd;
        color: #856404;
    }

    .thumbnail {
        width: 60px;
        height: 40px;
        object-fit: cover;
        border-radius: 3px;
    }

    .excerpt {
        max-width: 300px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 0.8rem;
        text-decoration: none;
        display: inline-block;
    }

    .btn-view {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    .btn-edit {
        background: <?php echo $accent_orange; ?>;
        color: white;
    }

    .btn-delete {
        background: #dc3545;
        color: white;
    }

    .pagination {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .page-link {
        padding: 8px 12px;
        border: 1px solid #ddd;
        background: white;
        color: <?php echo $primary_blue; ?>;
        text-decoration: none;
        border-radius: 3px;
    }

    .page-link.active {
        background: <?php echo $primary_blue; ?>;
        color: white;
        border-color: <?php echo $primary_blue; ?>;
    }

    .page-link:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    @media (max-width: 768px) {
        .blog-content {
            margin-left: 0;
        }

        .blog-header {
            flex-direction: column;
            gap: 15px;
            text-align: center;
        }

        .filters-grid {
            grid-template-columns: 1fr;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
</style>

<div class="blog-content">
    <div class="blog-header">
        <h1>📝 Blog Management</h1>
        <a href="create.php" class="btn-add">➕ New Article</a>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $total_posts = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM blog_posts"))['total'];
                echo $total_posts;
                ?>
            </div>
            <div class="stat-label">Total Articles</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $published_posts = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM blog_posts WHERE status = 'published'"))['total'];
                echo $published_posts;
                ?>
            </div>
            <div class="stat-label">Published</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $draft_posts = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM blog_posts WHERE status = 'draft'"))['total'];
                echo $draft_posts;
                ?>
            </div>
            <div class="stat-label">Drafts</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">
                <?php
                $total_views = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(views) as total FROM blog_posts"))['total'] ?? 0;
                echo number_format($total_views);
                ?>
            </div>
            <div class="stat-label">Total Views</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-section">
        <form method="GET" action="">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="search">Search Articles</label>
                    <input type="text" id="search" name="search" value="<?php echo $_GET['search'] ?? ''; ?>" placeholder="Title or content...">
                </div>
                <div class="filter-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">All Status</option>
                        <option value="published" <?php echo ($_GET['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                        <option value="draft" <?php echo ($_GET['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="category">Category</label>
                    <select id="category" name="category">
                        <option value="">All Categories</option>
                        <?php
                        $categories = mysqli_query($koneksi, "SELECT DISTINCT category FROM blog_posts WHERE category IS NOT NULL AND category != '' ORDER BY category");
                        while ($cat = mysqli_fetch_assoc($categories)) {
                            $selected = ($_GET['category'] ?? '') === $cat['category'] ? 'selected' : '';
                            echo "<option value='{$cat['category']}' $selected>{$cat['category']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">🔍 Filter</button>
                </div>
            </div>
        </form>
    </div>

    <!-- Blog Posts Table -->
    <div class="blog-table">
        <div class="table-header">
            📝 Blog Articles
        </div>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Thumbnail</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Category</th>
                        <th>Author</th>
                        <th>Views</th>
                        <th>Status</th>
                        <th>Published</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Build query with filters
                    $where_conditions = [];
                    $params = [];
                    $types = "";

                    if (!empty($_GET['search'])) {
                        $search = "%" . mysqli_real_escape_string($koneksi, $_GET['search']) . "%";
                        $where_conditions[] = "(title LIKE ? OR content LIKE ? OR excerpt LIKE ?)";
                        $params[] = $search;
                        $params[] = $search;
                        $params[] = $search;
                        $types .= "sss";
                    }

                    if (!empty($_GET['status'])) {
                        $where_conditions[] = "status = ?";
                        $params[] = $_GET['status'];
                        $types .= "s";
                    }

                    if (!empty($_GET['category'])) {
                        $where_conditions[] = "category = ?";
                        $params[] = $_GET['category'];
                        $types .= "s";
                    }

                    $where_clause = !empty($where_conditions) ? "WHERE " . implode(" AND ", $where_conditions) : "";

                    // Pagination
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $per_page = 10;
                    $offset = ($page - 1) * $per_page;

                    // Count total records
                    $count_query = "SELECT COUNT(*) as total FROM blog_posts bp LEFT JOIN users u ON bp.author_id = u.id $where_clause";
                    $count_stmt = mysqli_prepare($koneksi, $count_query);
                    if (!empty($params)) {
                        mysqli_stmt_bind_param($count_stmt, $types, ...$params);
                    }
                    mysqli_stmt_execute($count_stmt);
                    $count_result = mysqli_stmt_get_result($count_stmt);
                    $total_records = mysqli_fetch_assoc($count_result)['total'];
                    $total_pages = ceil($total_records / $per_page);

                    // Main query
                    $query = "SELECT bp.*, u.full_name as author_name FROM blog_posts bp
                             LEFT JOIN users u ON bp.author_id = u.id
                             $where_clause
                             ORDER BY bp.created_at DESC
                             LIMIT ? OFFSET ?";

                    $stmt = mysqli_prepare($koneksi, $query);
                    $params[] = $per_page;
                    $params[] = $offset;
                    $types .= "ii";
                    mysqli_stmt_bind_param($stmt, $types, ...$params);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) > 0) {
                        while ($post = mysqli_fetch_assoc($result)) {
                            $status_class = 'status-' . $post['status'];
                            ?>
                            <tr>
                                <td>
                                    <?php if ($post['thumbnail']) { ?>
                                        <img src="../../uploads/<?php echo htmlspecialchars($post['thumbnail']); ?>" alt="Thumbnail" class="thumbnail">
                                    <?php } else { ?>
                                        <div style="width: 60px; height: 40px; background: #f0f0f0; border-radius: 3px; display: flex; align-items: center; justify-content: center; color: #999;">📷</div>
                                    <?php } ?>
                                </td>
                                <td><?php echo htmlspecialchars($post['title']); ?></td>
                                <td><?php echo htmlspecialchars($post['slug']); ?></td>
                                <td><?php echo htmlspecialchars($post['category'] ?? 'Uncategorized'); ?></td>
                                <td><?php echo htmlspecialchars($post['author_name'] ?? 'Unknown'); ?></td>
                                <td><?php echo number_format($post['views']); ?></td>
                                <td><span class="status-badge <?php echo $status_class; ?>"><?php echo ucfirst($post['status']); ?></span></td>
                                <td><?php echo $post['published_at'] ? format_date($post['published_at']) : 'Not published'; ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="view.php?id=<?php echo $post['id']; ?>" class="btn-action btn-view">👁️ View</a>
                                        <a href="edit.php?id=<?php echo $post['id']; ?>" class="btn-action btn-edit">✏️ Edit</a>
                                        <a href="delete.php?id=<?php echo $post['id']; ?>" class="btn-action btn-delete" onclick="return confirm('Are you sure you want to delete this article?')">🗑️ Delete</a>
                                    </div>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px;">
                                <p style="color: #666; margin: 0;">No blog posts found matching your criteria</p>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1) { ?>
            <div class="pagination">
                <?php
                $query_string = $_GET;
                unset($query_string['page']);

                for ($i = 1; $i <= $total_pages; $i++) {
                    $query_string['page'] = $i;
                    $url = '?' . http_build_query($query_string);
                    $active_class = $i === $page ? 'active' : '';
                    echo "<a href='$url' class='page-link $active_class'>$i</a>";
                }
                ?>
            </div>
        <?php } ?>
    </div>
</div>

<script>
// Auto-submit form on filter change
document.querySelectorAll('select').forEach(select => {
    select.addEventListener('change', function() {
        this.closest('form').submit();
    });
});
</script>

<?php include '../footer-admin.php'; ?>
