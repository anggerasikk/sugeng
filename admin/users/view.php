<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../signin.php');
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    set_flash('error', 'User ID is required.');
    redirect('index.php');
}

$user_id = (int)$_GET['id'];

// Get user data
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    set_flash('error', 'User not found.');
    redirect('index.php');
}

$user = mysqli_fetch_assoc($result);

include '../header-admin.php';
?>

<style>
    .user-detail-content {
        margin-top: 70px;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .user-detail-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        max-width: 800px;
        margin: 0 auto;
    }

    .user-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        text-align: center;
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: <?php echo $accent_orange; ?>;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        margin: 0 auto 15px;
        color: white;
    }

    .user-name {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .user-email {
        font-size: 1rem;
        opacity: 0.9;
        margin: 5px 0 0;
    }

    .user-info {
        padding: 30px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .info-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border-left: 4px solid <?php echo $primary_blue; ?>;
    }

    .info-section h3 {
        color: <?php echo $primary_blue; ?>;
        margin: 0 0 15px;
        font-size: 1.1rem;
        font-weight: 600;
    }

    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .info-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }

    .info-label {
        font-weight: 500;
        color: #666;
    }

    .info-value {
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    .status-badge,
    .role-badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
        text-transform: uppercase;
    }

    .status-active {
        background: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background: #f8d7da;
        color: #721c24;
    }

    .role-admin {
        background: #dc3545;
        color: white;
    }

    .role-staff {
        background: #ffc107;
        color: #212529;
    }

    .role-user {
        background: #28a745;
        color: white;
    }

    .user-actions {
        display: flex;
        gap: 10px;
        justify-content: center;
        padding: 20px 30px;
        background: #f8f9fa;
        border-top: 1px solid #eee;
    }

    .btn-action {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        transition: all 0.3s;
    }

    .btn-edit {
        background: <?php echo $accent_orange; ?>;
        color: white;
    }

    .btn-edit:hover {
        background: <?php echo $primary_blue; ?>;
    }

    .btn-back {
        background: #6c757d;
        color: white;
    }

    .btn-back:hover {
        background: #545b62;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .user-actions {
            flex-direction: column;
        }

        .btn-action {
            text-align: center;
        }
    }
</style>

<div class="user-detail-content">
    <div class="user-detail-card">
        <div class="user-header">
            <div class="user-avatar">
                <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
            </div>
            <h1 class="user-name"><?php echo htmlspecialchars($user['full_name']); ?></h1>
            <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
        </div>

        <div class="user-info">
            <div class="info-grid">
                <div class="info-section">
                    <h3>👤 Personal Information</h3>
                    <div class="info-item">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['full_name']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Email:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Phone:</span>
                        <span class="info-value"><?php echo htmlspecialchars($user['phone'] ?? 'Not provided'); ?></span>
                    </div>
                </div>

                <div class="info-section">
                    <h3>🔐 Account Details</h3>
                    <div class="info-item">
                        <span class="info-label">User ID:</span>
                        <span class="info-value">#<?php echo $user['id']; ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Role:</span>
                        <span class="role-badge role-<?php echo $user['role']; ?>"><?php echo ucfirst($user['role']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status:</span>
                        <span class="status-badge status-<?php echo $user['status']; ?>"><?php echo ucfirst($user['status']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Joined:</span>
                        <span class="info-value"><?php echo format_date($user['created_at']); ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value"><?php echo $user['updated_at'] ? format_date($user['updated_at']) : 'Never'; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="user-actions">
            <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn-action btn-edit">✏️ Edit User</a>
            <a href="index.php" class="btn-action btn-back">⬅️ Back to Users</a>
        </div>
    </div>
</div>

<?php include '../footer-admin.php'; ?>
