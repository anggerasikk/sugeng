<?php
// Warna dari palette
$primary_blue = "#001BB7";
$secondary_blue = "#0046FF";
$accent_orange = "#FF8040";
$light_cream = "#F5F1DC";
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sugeng Rahayu - Booking Transportasi Bus</title>
    <style>
        :root {
            --primary-blue: <?php echo $primary_blue; ?>;
            --secondary-blue: <?php echo $secondary_blue; ?>;
            --accent-orange: <?php echo $accent_orange; ?>;
            --light-cream: <?php echo $light_cream; ?>;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .header {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            padding: 0 20px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-text {
            color: var(--light-cream);
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo-text span {
            color: var(--accent-orange);
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: var(--light-cream);
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: var(--accent-orange);
        }

        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background: white;
            min-width: 200px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            overflow: hidden;
            z-index: 1001;
        }

        .dropdown-content a {
            color: #333;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            transition: all 0.3s ease;
            border-bottom: 1px solid #f0f0f0;
        }

        .dropdown-content a:hover {
            background-color: var(--light-cream);
            color: var(--primary-blue);
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-login {
            background-color: transparent;
            color: var(--light-cream);
            border: 2px solid var(--light-cream);
        }

        .btn-login:hover {
            background-color: var(--light-cream);
            color: var(--primary-blue);
        }

        .btn-register {
            background-color: var(--accent-orange);
            color: white;
            border: 2px solid var(--accent-orange);
        }

        .btn-register:hover {
            background-color: transparent;
            color: var(--accent-orange);
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: var(--light-cream);
            font-size: 24px;
            cursor: pointer;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
                flex-direction: column;
                padding: 20px;
                gap: 15px;
            }

            .nav-menu.active {
                display: flex;
            }

            .auth-buttons {
                flex-direction: row;
                gap: 10px;
            }

            .mobile-menu-btn {
                display: block;
            }

            .dropdown-content {
                position: static;
                box-shadow: none;
                background: rgba(255, 255, 255, 0.1);
            }

            .dropdown-content a {
                color: var(--light-cream);
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .dropdown-content a:hover {
                background: rgba(255, 255, 255, 0.2);
                color: var(--accent-orange);
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="<?php echo base_url('index.php'); ?>" class="logo-text">Sugeng <span>Rahayu</span></a>
            </div>

            <button class="mobile-menu-btn" onclick="toggleMenu()">☰</button>

            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item">
                        <a href="<?php echo base_url('index.php'); ?>" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">Perjalanan</a>
                        <div class="dropdown-content">
                            <a href="<?php echo base_url('jadwal.php'); ?>">Jadwal Perjalanan</a>
                            <a href="<?php echo base_url('rute.php'); ?>">Rute & Destinasi</a>
                            <a href="<?php echo base_url('checkin/index.php'); ?>">Check-in Online</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">Perusahaan</a>
                        <div class="dropdown-content">
                            <a href="<?php echo base_url('visi-misi.php'); ?>">Visi & Misi</a>
                            <a href="<?php echo base_url('armada.php'); ?>">Armada Bus</a>
                            <a href="<?php echo base_url('karir.php'); ?>">Karir</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">Informasi</a>
                        <div class="dropdown-content">
                            <a href="<?php echo base_url('syarat.php'); ?>">Syarat & Ketentuan</a>
                            <a href="<?php echo base_url('privasi.php'); ?>">Kebijakan Privasi</a>
                            <a href="<?php echo base_url('sitemap.php'); ?>">Peta Situs</a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="auth-buttons">
                <?php if (is_logged_in()): ?>
                    <a href="<?php echo base_url('user/profile.php'); ?>" class="btn btn-login">
                        <?php echo $_SESSION['user_name']; ?>
                    </a>
                    <a href="<?php echo base_url('auth/logout.php'); ?>" class="btn btn-register">Logout</a>
                <?php else: ?>
                    <a href="<?php echo base_url('beranda/signin.php'); ?>" class="btn btn-login">Masuk</a>
                    <a href="<?php echo base_url('beranda/signup.php'); ?>" class="btn btn-register">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </header>


