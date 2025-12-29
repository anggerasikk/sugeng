<?php
require_once '../config.php';
require_once '../includes/functions.php';

// Redirect if already logged in
if (is_logged_in()) {
    if (is_admin()) {
        redirect(base_url('admin/index.php'));
    } else {
        redirect(base_url('user/profile.php'));
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

    /* Same CSS as signin.php */
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

    .checkbox-group {
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 10px 0 20px;
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

    .checkbox-group a {
        color: var(--accent-orange);
        text-decoration: none;
        font-weight: 500;
    }

    .checkbox-group a:hover {
        text-decoration: underline;
    }

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
                <h2>Get Started Now</h2>
                <p>Let's create your account</p>
            </div>

            <form class="auth-form" action="../auth/register-process.php" method="POST">
                <?php echo csrf_field(); ?>
                
                <!-- Full Name Field -->
                <div class="form-group">
                    <label for="fullname">Full Name</label>
                    <input type="text" class="form-input" id="fullname" name="fullname" placeholder="Dominic Matthew" required>
                </div>

                <!-- Email Field -->
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-input" id="email" name="email" placeholder="donat@example.com" required>
                </div>

                <!-- Password Field -->
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-input" id="password" name="password" placeholder="Set your password" required minlength="8">
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-input" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
                </div>

                <!-- Terms & Conditions -->
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" name="terms" required>
                    <label for="terms">I agree to <a href="syarat.php">Term & Condition</a></label>
                </div>

                <!-- Sign Up Button -->
                <button type="submit" class="auth-button">Sign up</button>

                <!-- Divider -->
                <div class="divider">
                    <span>or</span>
                </div>

                <!-- Google Sign Up -->
                <button type="button" class="google-button">
                    <span>G</span>
                    Sign up with Google
                </button>

                <!-- Sign In Link -->
                <div class="auth-footer">
                    Already have an account? <a href="signin.php">Sign in</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
