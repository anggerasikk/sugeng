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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        .header {
            background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
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
            color: <?php echo $light_cream; ?>;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo-text span {
            color: <?php echo $accent_orange; ?>;
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
            color: <?php echo $light_cream; ?>;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: <?php echo $accent_orange; ?>;
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
            background-color: <?php echo $light_cream; ?>;
            color: <?php echo $primary_blue; ?>;
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
            color: <?php echo $light_cream; ?>;
            border: 2px solid <?php echo $light_cream; ?>;
        }

        .btn-login:hover {
            background-color: <?php echo $light_cream; ?>;
            color: <?php echo $primary_blue; ?>;
        }

        .btn-register {
            background-color: <?php echo $accent_orange; ?>;
            color: white;
            border: 2px solid <?php echo $accent_orange; ?>;
        }

        .btn-register:hover {
            background-color: transparent;
            color: <?php echo $accent_orange; ?>;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: <?php echo $light_cream; ?>;
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
                background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
                flex-direction: column;
                padding: 20px;
                gap: 15px;
            }

            .nav-menu.active {
                display: flex;
            }

            .auth-buttons {
                flex-direction: column;
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
                color: <?php echo $light_cream; ?>;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            }

            .dropdown-content a:hover {
                background: rgba(255, 255, 255, 0.2);
                color: <?php echo $accent_orange; ?>;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-container">
            <div class="logo">
                <a href="index.php" class="logo-text">Sugeng <span>Rahayu</span></a>
            </div>

            <button class="mobile-menu-btn" onclick="toggleMenu()">☰</button>

            <nav>
                <ul class="nav-menu" id="navMenu">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link">Beranda</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">Perjalanan</a>
                        <div class="dropdown-content">
                            <a href="jadwal.php">Jadwal Perjalanan</a>
                            <a href="rute.php">Rute Perjalanan</a>
                            <a href="booking.php">Booking Tiket</a>
                            <a href="promo.php">Promo & Diskon</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link">Perusahaan</a>
                        <div class="dropdown-content">
                            <a href="tentang.php">Tentang Kami</a>
                            <a href="visi-misi.php">Visi & Misi</a>
                            <a href="armada.php">Armada Bus</a>
                            <a href="karir.php">Karir</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="kontak.php" class="nav-link">Kontak</a>
                    </li>
                </ul>
            </nav>

            <div class="auth-buttons">
                <a href="login.php" class="btn btn-login">Masuk</a>
                <a href="register.php" class="btn btn-register">Daftar</a>
            </div>
        </div>
    </header>

    <script>
        function toggleMenu() {
            const navMenu = document.getElementById('navMenu');
            navMenu.classList.toggle('active');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const dropdowns = document.querySelectorAll('.dropdown');
            dropdowns.forEach(dropdown => {
                if (!dropdown.contains(event.target)) {
                    const dropdownContent = dropdown.querySelector('.dropdown-content');
                    if (dropdownContent) {
                        dropdownContent.style.display = 'none';
                    }
                }
            });
        });

        // Handle dropdown on mobile
        const dropdowns = document.querySelectorAll('.dropdown');
        dropdowns.forEach(dropdown => {
            const link = dropdown.querySelector('.nav-link');
            link.addEventListener('click', function(e) {
                if (window.innerWidth <= 768) {
                    e.preventDefault();
                    const content = this.nextElementSibling;
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';
                }
            });
        });
    </script>