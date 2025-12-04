<?php include '../header.php'; ?>

<style>
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
    }

    .news-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 20px;
    }

    /* Hero Section - Simpler */
    .news-hero {
        text-align: center;
        padding: 60px 0 40px;
        border-bottom: 2px solid <?php echo $accent_orange; ?>;
        margin-bottom: 40px;
    }

    .news-hero h1 {
        font-size: 3rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    .news-hero p {
        font-size: 1.2rem;
        color: #666;
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* Main Content Layout */
    .main-content {
        display: flex;
        gap: 40px;
        margin-bottom: 60px;
    }

    .left-column {
        flex: 3;
    }

    .right-column {
        flex: 1;
    }

    /* Featured Article - Large */
    .featured-article {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 40px;
        border: 1px solid #eaeaea;
    }

    .featured-image {
        width: 100%;
        height: 350px;
        background: linear-gradient(45deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        position: relative;
        overflow: hidden;
    }

    .featured-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.2), rgba(0,0,0,0.1));
    }

    .featured-image .icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 4rem;
        color: white;
        opacity: 0.9;
    }

    .featured-content {
        padding: 40px;
    }

    .featured-content h2 {
        font-size: 2.2rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 20px;
        line-height: 1.3;
        font-weight: 700;
    }

    .featured-content p {
        font-size: 1.1rem;
        color: #555;
        line-height: 1.7;
        margin-bottom: 25px;
    }

    .featured-meta {
        display: flex;
        align-items: center;
        gap: 20px;
        color: #777;
        font-size: 0.9rem;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid #eee;
    }

    .featured-meta span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .read-more-btn {
        display: inline-block;
        background: <?php echo $accent_orange; ?>;
        color: white;
        padding: 12px 30px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        font-size: 1rem;
    }

    .read-more-btn:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(230, 115, 0, 0.2);
    }

    /* Other News Section */
    .other-news {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #eaeaea;
        margin-bottom: 40px;
    }

    .section-title {
        font-size: 1.8rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid <?php echo $accent_orange; ?>;
        font-weight: 700;
    }

    .news-list {
        display: flex;
        flex-direction: column;
        gap: 25px;
    }

    .news-item {
        padding: 20px;
        background: #f8f9fa;
        border-radius: 6px;
        border-left: 4px solid <?php echo $primary_blue; ?>;
        transition: all 0.3s ease;
    }

    .news-item:hover {
        background: #f0f2f5;
        transform: translateX(5px);
    }

    .news-item h3 {
        font-size: 1.2rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 8px;
        font-weight: 600;
        line-height: 1.4;
    }

    .news-item p {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.5;
        margin-bottom: 10px;
    }

    .news-date {
        font-size: 0.85rem;
        color: <?php echo $accent_orange; ?>;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Articles Grid - 3 columns */
    .articles-section {
        margin-top: 60px;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        margin-top: 30px;
    }

    .article-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 1px solid #eaeaea;
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .card-image {
        width: 100%;
        height: 180px;
        background: linear-gradient(45deg, <?php echo $secondary_blue; ?>, <?php echo $accent_orange; ?>);
        position: relative;
        overflow: hidden;
    }

    .card-image::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(45deg, rgba(0,0,0,0.2), rgba(0,0,0,0.1));
    }

    .card-image .icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 2.5rem;
        color: white;
        opacity: 0.9;
    }

    .card-content {
        padding: 25px;
    }

    .card-content h3 {
        font-size: 1.3rem;
        color: <?php echo $primary_blue; ?>;
        margin-bottom: 15px;
        line-height: 1.4;
        font-weight: 600;
    }

    .card-content p {
        font-size: 0.95rem;
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .card-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .card-date {
        font-size: 0.9rem;
        color: <?php echo $accent_orange; ?>;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .read-more-small {
        background: <?php echo $primary_blue; ?>;
        color: white;
        padding: 8px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .read-more-small:hover {
        background: <?php echo $secondary_blue; ?>;
    }

    /* Responsive Design */
    @media (max-width: 992px) {
        .main-content {
            flex-direction: column;
        }
        
        .articles-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .featured-content {
            padding: 30px;
        }
        
        .featured-content h2 {
            font-size: 1.8rem;
        }
    }

    @media (max-width: 768px) {
        .news-hero h1 {
            font-size: 2.2rem;
        }
        
        .news-hero p {
            font-size: 1rem;
        }
        
        .articles-grid {
            grid-template-columns: 1fr;
        }
        
        .featured-image {
            height: 250px;
        }
        
        .featured-content h2 {
            font-size: 1.6rem;
        }
        
        .section-title {
            font-size: 1.5rem;
        }
        
        .news-container {
            padding: 20px 15px;
        }
    }

    @media (max-width: 480px) {
        .featured-content {
            padding: 20px;
        }
        
        .featured-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .news-item {
            padding: 15px;
        }
        
        .card-content {
            padding: 20px;
        }
    }
</style>

<!-- Hero Section -->
<section class="news-hero">
    <div class="news-container">
        <h1>Our News</h1>
        <p>ColdBrews in two Dowskop Coffee and Beverages Industry Expertise in Indonesia</p>
    </div>
</section>

<!-- Main Content -->
<div class="news-container">
    <div class="main-content">
        <!-- Left Column - Featured Article -->
        <div class="left-column">
            <!-- Featured Article -->
            <div class="featured-article">
                <div class="featured-image">
                    <div class="icon">☕</div>
                </div>
                <div class="featured-content">
                    <h2>Visited Doesoen Sitap Coffee, The Producer of Robusta in Central Java</h2>
                    <div class="featured-meta">
                        <span>📅 27 November 2025</span>
                        <span>👁️ 1,245 Views</span>
                        <span>🏷️ Coffee Industry</span>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                    <a href="#" class="read-more-btn">Read More</a>
                </div>
            </div>

            <!-- Other News -->
            <div class="other-news">
                <h2 class="section-title">Other News</h2>
                <div class="news-list">
                    <div class="news-item">
                        <h3>Cold Brew Coffee, How to Drink Cold Coffee is More Enjoyable</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="news-date">📅 15 November 2025</div>
                    </div>
                    
                    <div class="news-item">
                        <h3>Meet Coffee Tonic, the Sensation of Drinking Coffee-Flavored Soda</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="news-date">📅 10 November 2025</div>
                    </div>
                    
                    <div class="news-item">
                        <h3>Workshop Coffee Sharing Session</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        <div class="news-date">📅 5 November 2025</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Recent News -->
        <div class="right-column">
            <div class="other-news">
                <h2 class="section-title">Recent News</h2>
                <div class="news-list">
                    <div class="news-item">
                        <h3>Workshop Coffee Brewing</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <div class="news-date">📅 12 November 2025</div>
                    </div>
                    
                    <div class="news-item">
                        <h3>Workshop Coffee Brewing</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <div class="news-date">📅 8 November 2025</div>
                    </div>
                    
                    <div class="news-item">
                        <h3>New Coffee Product Launch</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <div class="news-date">📅 3 November 2025</div>
                    </div>
                    
                    <div class="news-item">
                        <h3>Coffee Farm Visit Report</h3>
                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        <div class="news-date">📅 28 October 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="articles-section">
        <h2 class="section-title">Latest Articles</h2>
        <div class="articles-grid">
            <!-- Article 1 -->
            <div class="article-card">
                <div class="card-image">
                    <div class="icon">🛡️</div>
                </div>
                <div class="card-content">
                    <h3>Website Text 1</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="card-meta">
                        <span class="card-date">📅 20 November 2025</span>
                        <a href="#" class="read-more-small">Read</a>
                    </div>
                </div>
            </div>

            <!-- Article 2 -->
            <div class="article-card">
                <div class="card-image">
                    <div class="icon">🌱</div>
                </div>
                <div class="card-content">
                    <h3>Website Text 2</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="card-meta">
                        <span class="card-date">📅 18 November 2025</span>
                        <a href="#" class="read-more-small">Read</a>
                    </div>
                </div>
            </div>

            <!-- Article 3 -->
            <div class="article-card">
                <div class="card-image">
                    <div class="icon">🎯</div>
                </div>
                <div class="card-content">
                    <h3>Website Text 3</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                    <div class="card-meta">
                        <span class="card-date">📅 15 November 2025</span>
                        <a href="#" class="read-more-small">Read</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>