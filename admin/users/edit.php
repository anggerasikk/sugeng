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

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && verify_csrf_token($_POST['csrf_token'])) {
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $role = sanitize_input($_POST['role']);
    $status = sanitize_input($_POST['status']);

    // Validate input
    $errors = [];

    if (empty($full_name)) {
        $errors[] = 'Full name is required.';
    }

    if (empty($email)) {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    if (!in_array($role, ['user', 'admin', 'staff'])) {
        $errors[] = 'Invalid role selected.';
    }

    if (!in_array($status, ['active', 'inactive'])) {
        $errors[] = 'Invalid status selected.';
    }

    // Check if email is already taken by another user
    $email_check_query = "SELECT id FROM users WHERE email = ? AND id != ?";
    $email_check_stmt = mysqli_prepare($koneksi, $email_check_query);
    mysqli_stmt_bind_param($email_check_stmt, "si", $email, $user_id);
    mysqli_stmt_execute($email_check_stmt);
    $email_check_result = mysqli_stmt_get_result($email_check_stmt);

    if (mysqli_num_rows($email_check_result) > 0) {
        $errors[] = 'Email is already taken by another user.';
    }

    if (empty($errors)) {
        // Update user
        $update_query = "UPDATE users SET full_name = ?, email = ?, phone = ?, role = ?, status = ?, updated_at = NOW() WHERE id = ?";
        $update_stmt = mysqli_prepare($koneksi, $update_query);
        mysqli_stmt_bind_param($update_stmt, "sssssi", $full_name, $email, $phone, $role, $status, $user_id);

        if (mysqli_stmt_execute($update_stmt)) {
            set_flash('success', 'User updated successfully.');
            redirect('view.php?id=' . $user_id);
        } else {
            $errors[] = 'Failed to update user. Please try again.';
        }
    }

    if (!empty($errors)) {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../header-admin.php';
?>

<style>
    .edit-user-content {
        margin-top: 70px;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .edit-user-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .edit-user-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .edit-user-header h1 {
        color: <?php echo $primary_blue; ?>;
        margin: 0;
        font-size: 1.8rem;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
        color: <?php echo $primary_blue; ?>;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 1rem;
        transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: <?php echo $primary_blue; ?>;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
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

    .current-info {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        border-left: 4px solid <?php echo $primary_blue; ?>;
    }

    .current-info strong {
        color: <?php echo $primary_blue; ?>;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
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

<div class="edit-user-content">
    <div class="edit-user-card">
        <div class="edit-user-header">
            <h1>✏️ Edit User</h1>
        </div>

        <div class="current-info">
            <strong>Editing:</strong> <?php echo htmlspecialchars($user['full_name']); ?> (<?php echo htmlspecialchars($user['email']); ?>)
        </div>

        <form method="POST" action="">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="staff" <?php echo $user['role'] === 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="admin" <?php echo $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" <?php echo $user['status'] === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo $user['status'] === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Save Changes</button>
                <a href="view.php?id=<?php echo $user['id']; ?>" class="btn-cancel">❌ Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include '../footer-admin.php'; ?>
