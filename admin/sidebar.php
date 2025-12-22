<style>
    .sidebar {
        position: fixed;
        top: 70px;
        left: 0;
        width: 260px;
        height: calc(100vh - 70px);
        background: white;
        box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        z-index: 1000;
        overflow-y: auto;
    }

    .sidebar-header {
        padding: 20px;
        background: <?php echo $primary_blue; ?>;
        color: white;
        text-align: center;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }

    .sidebar-header h2 {
        margin: 0;
        font-size: 1.2rem;
        font-weight: 600;
    }

    .sidebar-header p {
        margin: 5px 0 0 0;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .sidebar-nav {
        padding: 20px 0;
    }

    .nav-section {
        margin-bottom: 20px;
    }

    .nav-section-title {
        padding: 10px 20px;
        font-size: 0.8rem;
        font-weight: 600;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .nav-item {
        position: relative;
    }

    .nav-link {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .nav-link:hover {
        background: #f8f9fa;
        color: <?php echo $primary_blue; ?>;
    }

    .nav-link.active {
        background: <?php echo $primary_blue; ?>20;
        color: <?php echo $primary_blue; ?>;
        border-right: 3px solid <?php echo $primary_blue; ?>;
    }

    .nav-icon {
        margin-right: 12px;
        font-size: 1.1rem;
        width: 20px;
        text-align: center;
    }

    .nav-text {
        flex: 1;
    }

    .nav-arrow {
        font-size: 0.8rem;
        transition: transform 0.3s ease;
    }

    .nav-item.has-submenu .nav-link.active .nav-arrow {
        transform: rotate(90deg);
    }

    .submenu {
        display: none;
        background: #f8f9fa;
        border-left: 2px solid <?php echo $primary_blue; ?>20;
    }

    .submenu.show {
        display: block;
    }

    .submenu-item {
        padding: 8px 20px 8px 44px;
        color: #666;
        text-decoration: none;
        font-size: 0.9rem;
        display: block;
        transition: all 0.3s ease;
    }

    .submenu-item:hover {
        color: <?php echo $primary_blue; ?>;
        background: rgba(0,123,183,0.05);
    }

    .submenu-item.active {
        color: <?php echo $primary_blue; ?>;
        font-weight: 500;
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: <?php echo $primary_blue; ?>;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
        }
    }
</style>

<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2>Bus Sugeng Rahayu</h2>
        <p>Admin Panel</p>
    </div>

    <nav class="sidebar-nav">
        <!-- Main Navigation -->
        <div class="nav-section">
            <a href="<?php echo base_url('admin/index.php'); ?>" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                <span class="nav-icon">📊</span>
                <span class="nav-text">Dashboard</span>
            </a>

            <a href="<?php echo base_url('admin/bookings/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/bookings/') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">🎫</span>
                <span class="nav-text">Bookings</span>
            </a>

            <a href="<?php echo base_url('admin/schedules/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/schedules/') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">🚌</span>
                <span class="nav-text">Schedules</span>
            </a>

            <a href="<?php echo base_url('admin/users/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/users/') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">👥</span>
                <span class="nav-text">Users</span>
            </a>

            <a href="<?php echo base_url('admin/cancellations/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/cancellations/') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">⚠️</span>
                <span class="nav-text">Cancellations</span>
            </a>

            <a href="<?php echo base_url('admin/blog/index.php'); ?>" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/blog/') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">📝</span>
                <span class="nav-text">Blog</span>
            </a>
        </div>

        <!-- Management Section -->
        <div class="nav-section">
            <div class="nav-section-title">Management</div>

            <div class="nav-item has-submenu">
                <button class="nav-link submenu-toggle <?php echo strpos($_SERVER['REQUEST_URI'], '/routes/') !== false || strpos($_SERVER['REQUEST_URI'], '/bus-types/') !== false ? 'active' : ''; ?>">
                    <span class="nav-icon">⚙️</span>
                    <span class="nav-text">Inventory</span>
                    <span class="nav-arrow">▶</span>
                </button>
                <div class="submenu <?php echo strpos($_SERVER['REQUEST_URI'], '/routes/') !== false || strpos($_SERVER['REQUEST_URI'], '/bus-types/') !== false ? 'show' : ''; ?>">
                    <a href="routes/index.php" class="submenu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/routes/') !== false ? 'active' : ''; ?>">Routes</a>
                    <a href="bus-types/index.php" class="submenu-item <?php echo strpos($_SERVER['REQUEST_URI'], '/bus-types/') !== false ? 'active' : ''; ?>">Bus Types</a>
                </div>
            </div>
        </div>

        <!-- Reports Section -->
        <div class="nav-section">
            <div class="nav-section-title">Reports</div>

            <a href="reports/bookings.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/reports/bookings') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">📈</span>
                <span class="nav-text">Booking Reports</span>
            </a>

            <a href="reports/revenue.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/reports/revenue') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">💰</span>
                <span class="nav-text">Revenue Reports</span>
            </a>

            <a href="reports/users.php" class="nav-link <?php echo strpos($_SERVER['REQUEST_URI'], '/reports/users') !== false ? 'active' : ''; ?>">
                <span class="nav-icon">👤</span>
                <span class="nav-text">User Reports</span>
            </a>
        </div>
    </nav>
</div>

<!-- Mobile toggle button -->
<button class="sidebar-toggle" id="sidebarToggle" style="display: none;">☰</button>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar toggle
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');

    function checkScreenSize() {
        if (window.innerWidth <= 768) {
            sidebarToggle.style.display = 'block';
        } else {
            sidebarToggle.style.display = 'none';
            sidebar.classList.remove('show');
        }
    }

    checkScreenSize();
    window.addEventListener('resize', checkScreenSize);

    sidebarToggle.addEventListener('click', function() {
        sidebar.classList.toggle('show');
    });

    // Submenu toggle
    document.querySelectorAll('.submenu-toggle').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const submenu = this.nextElementSibling;
            submenu.classList.toggle('show');

            // Close other submenus
            document.querySelectorAll('.submenu').forEach(otherSubmenu => {
                if (otherSubmenu !== submenu) {
                    otherSubmenu.classList.remove('show');
                }
            });
        });
    });
});
</script>
