<?php
// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../beranda/signin.php');
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8f9fa;
            color: #333;
            line-height: 1.6;
        }

        /* Top Navigation Bar */
        .top-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, #001BB7 0%, #0033CC 100%);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 30px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-brand h1 {
            color: white;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Arial', sans-serif;
        }

        .nav-brand h1 span {
            color: #FF8040;
            font-size: 24px;
        }

        .nav-brand span {
            color: #FF8040;
            font-size: 0.9rem;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 10px 15px;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.1);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
            font-weight: 600;
        }

        .nav-icon {
            font-size: 1.1rem;
        }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.8rem;
            opacity: 0.8;
        }

        .btn-logout {
            background: #FF8040;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #e67339;
            transform: translateY(-1px);
        }

        /* Main Content */
        .main-content {
            margin-top: 70px;
            padding: 30px;
            min-height: calc(100vh - 70px);
        }

        .page-header {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            border-bottom: 3px solid #001BB7;
        }

        .page-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #001BB7;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #666;
            font-size: 1rem;
        }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border-left-color: #ffc107;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border-left-color: #17a2b8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .top-nav {
                padding: 0 15px;
                height: 60px;
            }

            .nav-brand h1 {
                font-size: 1.2rem;
            }

            .nav-menu {
                display: none; /* Hide menu on mobile, could add hamburger later */
            }

            .main-content {
                padding: 15px;
                margin-top: 70px;
            }

            .user-details {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Top Navigation Bar -->
    <nav class="top-nav">
        <div class="nav-brand">
            <h1>Sugeng <span>Rahayu</span></h1>
            <span>Admin Panel</span>
        </div>

        <div class="nav-menu">
            <div class="nav-item">
                <a href="<?php echo base_url('admin/index.php'); ?>" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                    <span class="nav-icon">📊</span>
                    Dashboard
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('admin/bookings/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/bookings/') !== false ? 'active' : ''; ?>">
                    <span class="nav-icon">🎫</span>
                    Bookings
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('admin/schedules/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/schedules/') !== false ? 'active' : ''; ?>">
                    <span class="nav-icon">🚌</span>
                    Schedules
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('admin/users/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/users/') !== false ? 'active' : ''; ?>">
                    <span class="nav-icon">👥</span>
                    Users
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('admin/cancellations/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/cancellations/') !== false ? 'active' : ''; ?>">
                    <span class="nav-icon">⚠️</span>
                    Cancellations
                </a>
            </div>
            <div class="nav-item">
                <a href="<?php echo base_url('admin/blog/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/blog/') !== false ? 'active' : ''; ?>">
                    <span class="nav-icon">📝</span>
                    Blog
                </a>
            </div>
        </div>

        <div class="nav-user">
            <div class="user-info">
                <div class="user-avatar">
                    <?php echo strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?>
                </div>
                <div class="user-details">
                    <div class="user-name"><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></div>
                    <div class="user-role"><?php echo ucfirst($_SESSION['user_role'] ?? 'admin'); ?></div>
                </div>
            </div>
            <a href="<?php echo base_url('auth/logout.php'); ?>" class="btn-logout" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
        </div>
    </nav>

    <!-- Display Flash Messages -->
    <?php display_flash(); ?>
</body>
</html>
