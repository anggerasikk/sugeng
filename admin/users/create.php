<?php
require_once '../../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../signin.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['csrf_token']) && verify_csrf_token($_POST['csrf_token'])) {
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
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

    if (empty($password)) {
        $errors[] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters long.';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match.';
    }

    if (!in_array($role, ['user', 'admin', 'staff'])) {
        $errors[] = 'Invalid role selected.';
    }

    if (!in_array($status, ['active', 'inactive'])) {
        $errors[] = 'Invalid status selected.';
    }

    // Check if email is already taken
    if (empty($errors)) {
        $email_check_query = "SELECT id FROM users WHERE email = ?";
        $email_check_stmt = mysqli_prepare($koneksi, $email_check_query);
        mysqli_stmt_bind_param($email_check_stmt, "s", $email);
        mysqli_stmt_execute($email_check_stmt);
        $email_check_result = mysqli_stmt_get_result($email_check_stmt);

        if (mysqli_num_rows($email_check_result) > 0) {
            $errors[] = 'Email is already taken.';
        }
    }

    if (empty($errors)) {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $insert_query = "INSERT INTO users (full_name, email, phone, password, role, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
        $insert_stmt = mysqli_prepare($koneksi, $insert_query);
        mysqli_stmt_bind_param($insert_stmt, "ssssss", $full_name, $email, $phone, $hashed_password, $role, $status);

        if (mysqli_stmt_execute($insert_stmt)) {
            $new_user_id = mysqli_insert_id($koneksi);
            set_flash('success', 'User created successfully.');
            redirect('view.php?id=' . $new_user_id);
        } else {
            $errors[] = 'Failed to create user. Please try again.';
        }
    }

    if (!empty($errors)) {
        set_flash('error', implode('<br>', $errors));
    }
}

include '../header-admin.php';
?>

<style>
    .create-user-content {
        margin-top: 70px;
        padding: 20px;
        min-height: calc(100vh - 70px);
    }

    .create-user-card {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        max-width: 600px;
        margin: 0 auto;
    }

    .create-user-header {
        text-align: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .create-user-header h1 {
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

    .password-strength {
        margin-top: 5px;
        font-size: 0.8rem;
        color: #666;
    }

    .password-strength.weak {
        color: #dc3545;
    }

    .password-strength.medium {
        color: #ffc107;
    }

    .password-strength.strong {
        color: #28a745;
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

<div class="create-user-content">
    <div class="create-user-card">
        <div class="create-user-header">
            <h1>➕ Create New User</h1>
        </div>

        <form method="POST" action="">
            <?php echo csrf_field(); ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">Full Name *</label>
                    <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="user" <?php echo ($_POST['role'] ?? '') === 'user' ? 'selected' : ''; ?>>User</option>
                        <option value="staff" <?php echo ($_POST['role'] ?? '') === 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required minlength="6">
                    <div class="password-strength" id="password-strength"></div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password *</label>
                    <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
                </div>
            </div>

            <div class="form-group">
                <label for="status">Status *</label>
                <select id="status" name="status" required>
                    <option value="active" <?php echo ($_POST['status'] ?? 'active') === 'active' ? 'selected' : ''; ?>>Active</option>
                    <option value="inactive" <?php echo ($_POST['status'] ?? '') === 'inactive' ? 'selected' : ''; ?>>Inactive</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Create User</button>
                <a href="index.php" class="btn-cancel">❌ Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const strengthIndicator = document.getElementById('password-strength');

    if (password.length === 0) {
        strengthIndicator.textContent = '';
        strengthIndicator.className = 'password-strength';
        return;
    }

    let strength = 0;
    if (password.length >= 6) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;

    if (strength <= 1) {
        strengthIndicator.textContent = 'Weak password';
        strengthIndicator.className = 'password-strength weak';
    } else if (strength <= 3) {
        strengthIndicator.textContent = 'Medium password';
        strengthIndicator.className = 'password-strength medium';
    } else {
        strengthIndicator.textContent = 'Strong password';
        strengthIndicator.className = 'password-strength strong';
    }
});

document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;

    if (password !== confirmPassword) {
        this.setCustomValidity('Passwords do not match');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php include '../footer-admin.php'; ?>
