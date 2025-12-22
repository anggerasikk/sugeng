<?php
require_once '../config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    if (is_admin()) {
        header("Location: " . SITE_URL . "/admin/index.php");
        exit;
    } else {
        header("Location: " . SITE_URL . "/beranda/index.php");
        exit;
    }
}

include 'header.php'; 
?>

<style>
    :root {
        --primary-blue: <?php echo $primary_blue; ?>;
        --secondary-blue: <?php echo $secondary_blue; ?>;
        --accent-orange: <?php echo $accent_orange; ?>;
        --light-cream: <?php echo $light_cream; ?>;
    }

    /* Reset & Base Styles */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: #333;
        background-color: #f8f9fa;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        flex: 1;
    }

    /* Auth Container */
    .auth-container {
        display: flex;
        min-height: calc(100vh - 200px);
        align-items: center;
        justify-content: center;
        padding: 60px 0;
    }

    .auth-wrapper {
        width: 100%;
        max-width: 1000px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* Left Side - Brand Section */
    .auth-brand {
        background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        color: var(--light-cream);
    }

    .brand-content {
        max-width: 400px;
    }

    .brand-content h1 {
        font-size: 2.8rem;
        margin-bottom: 20px;
        font-weight: 700;
        color: var(--light-cream);
    }

    .brand-content p {
        font-size: 1.2rem;
        line-height: 1.6;
        opacity: 0.95;
        margin-bottom: 30px;
    }

    .read-more-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--accent-orange);
        text-decoration: none;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        padding: 10px 0;
    }

    .read-more-link:hover {
        color: #ffa366;
        transform: translateX(5px);
    }

    .read-more-link::after {
        content: '→';
        font-size: 1.2rem;
        transition: transform 0.3s ease;
    }

    .read-more-link:hover::after {
        transform: translateX(5px);
    }

    /* Right Side - Form Section */
    .auth-content {
        padding: 60px 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .auth-title {
        margin-bottom: 30px;
    }

    .auth-title h2 {
        color: var(--primary-blue);
        font-size: 2rem;
        margin-bottom: 10px;
        font-weight: 700;
    }

    .auth-title p {
        color: #666;
        font-size: 1.1rem;
    }

    /* Form Styles */
    .auth-form {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #555;
        font-weight: 500;
        font-size: 0.95rem;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--accent-orange);
        box-shadow: 0 0 0 3px rgba(230, 115, 0, 0.1);
    }

    /* Checkbox and Forgot Password */
    .form-options {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .checkbox-group input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }

    .checkbox-group label {
        margin: 0;
        font-size: 0.95rem;
        color: #555;
        cursor: pointer;
    }

    .forgot-password {
        color: var(--accent-orange);
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .forgot-password:hover {
        color: #e67300;
        text-decoration: underline;
    }

    /* Buttons */
    .auth-button {
        background: var(--accent-orange);
        color: white;
        border: none;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
    }

    .auth-button:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        margin: 25px 0;
        color: #666;
        font-size: 0.9rem;
    }

    .divider::before,
    .divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e0e0e0;
    }

    .divider span {
        padding: 0 15px;
    }

    /* Google Button */
    .google-button {
        background: white;
        color: #333;
        border: 2px solid #e0e0e0;
        padding: 14px 20px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .google-button:hover {
        background: #f8f9fa;
        border-color: #d0d0d0;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Sign Up/Sign In Link */
    .auth-footer {
        text-align: center;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
        color: #666;
        font-size: 0.95rem;
    }

    .auth-footer a {
        color: var(--accent-orange);
        text-decoration: none;
        font-weight: 600;
        margin-left: 5px;
        transition: color 0.3s ease;
    }

    .auth-footer a:hover {
        color: #e67300;
        text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .auth-wrapper {
            grid-template-columns: 1fr;
            max-width: 600px;
        }
        
        .auth-brand {
            padding: 40px 30px;
        }
        
        .brand-content h1 {
            font-size: 2.4rem;
        }
        
        .auth-content {
            padding: 40px 30px;
        }
    }

    @media (max-width: 768px) {
        .auth-wrapper {
            max-width: 500px;
        }
        
        .auth-brand {
            padding: 30px 20px;
        }
        
        .brand-content h1 {
            font-size: 2rem;
        }
        
        .brand-content p {
            font-size: 1.1rem;
        }
        
        .auth-content {
            padding: 30px 20px;
        }
        
        .auth-title h2 {
            font-size: 1.8rem;
        }
        
        .auth-title p {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .auth-container {
            padding: 30px 0;
        }
        
        .auth-wrapper {
            border-radius: 8px;
        }
        
        .auth-brand {
            padding: 25px 15px;
        }
        
        .brand-content h1 {
            font-size: 1.8rem;
        }
        
        .brand-content p {
            font-size: 1rem;
        }
        
        .auth-content {
            padding: 25px 15px;
        }
        
        .form-options {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
    }
</style>

<div class="auth-container">
    <?php echo display_flash(); ?>
    
    <div class="auth-wrapper">
        <!-- Left Side - Brand Section -->
        <div class="auth-brand">
            <div class="brand-content">
                <h1>Sugeng Rahayu</h1>
                <p>Penyedia layanan transportasi bus terpercaya dengan pengalaman lebih dari 10 tahun. Melayani perjalanan antar kota dengan nyaman, aman, dan terjangkau.</p>
                <a href="index.php" class="read-more-link">Read More</a>
            </div>
        </div>

        <!-- Right Side - Form Section -->
        <div class="auth-content">
            <div class="auth-title">
                <h2>Welcome Back</h2>
                <p>Sign in to your account</p>
            </div>

            <form class="auth-form" action="../auth/login-process.php" method="POST">
                <?php echo csrf_field(); ?>
                
                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-input" id="email" name="email" placeholder="domat@example.com" required>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-input" id="password" name="password" placeholder="Enter your password" required>
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="auth/forgot-password.php" class="forgot-password">Forgot Password?</a>
                </div>

                <!-- Sign In Button -->
                <button type="submit" class="auth-button">Sign In</button>

                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>

                <!-- Google Sign In -->
                <button type="button" class="google-button">
                    <span>G</span>
                    Sign in with Google
                </button>

                <!-- Sign Up Link -->
                <div class="auth-footer">
                    Don't have an account? <a href="signup.php">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
