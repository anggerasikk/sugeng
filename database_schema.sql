-- Database Schema untuk Bus Sugeng Rahayu
-- Jalankan di phpMyAdmin atau MySQL Command Line

CREATE DATABASE IF NOT EXISTS sugeng CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sugeng;

-- Tabel Users
CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    phone VARCHAR(20),
    role ENUM('user', 'admin', 'staff') DEFAULT 'user',
    status ENUM('active', 'inactive') DEFAULT 'active',
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabel Routes
CREATE TABLE IF NOT EXISTS routes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    origin VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    distance DECIMAL(8,2),
    estimated_duration TIME,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Bus Types
CREATE TABLE IF NOT EXISTS bus_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    capacity INT NOT NULL,
    facilities TEXT,
    image VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel Schedules
CREATE TABLE IF NOT EXISTS schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    route_id INT,
    bus_type_id INT,
    departure_date DATE NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME,
    price DECIMAL(10,2) NOT NULL,
    available_seats INT DEFAULT 50,
    total_seats INT DEFAULT 50,
    status ENUM('active', 'cancelled', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (route_id) REFERENCES routes(id),
    FOREIGN KEY (bus_type_id) REFERENCES bus_types(id)
);

-- Tabel Bookings
CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    schedule_id INT,
    booking_code VARCHAR(20) UNIQUE NOT NULL,
    passenger_name VARCHAR(100) NOT NULL,
    passenger_phone VARCHAR(20),
    passenger_email VARCHAR(100),
    passenger_id_number VARCHAR(20),
    seats_booked INT NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'cancelled', 'refunded') DEFAULT 'pending',
    booking_status ENUM('confirmed', 'checked_in', 'cancelled', 'completed') DEFAULT 'confirmed',
    payment_method VARCHAR(50),
    checkin_status ENUM('not_checked_in', 'checked_in') DEFAULT 'not_checked_in',
    checkin_time TIMESTAMP NULL,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (schedule_id) REFERENCES schedules(id)
);

-- Tabel Cancellation Requests
CREATE TABLE IF NOT EXISTS cancellation_requests (
    id INT PRIMARY KEY AUTO_INCREMENT,
    booking_id INT,
    user_id INT,
    reason TEXT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    admin_notes TEXT,
    refund_amount DECIMAL(10,2),
    refund_status ENUM('pending', 'processed', 'completed') DEFAULT 'pending',
    processed_by INT,
    processed_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (processed_by) REFERENCES users(id)
);

-- Tabel Blog Posts
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT,
    excerpt TEXT,
    category VARCHAR(100),
    tags TEXT,
    thumbnail VARCHAR(255),
    meta_description TEXT,
    author_id INT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id)
);

-- Tabel Activity Logs
CREATE TABLE IF NOT EXISTS activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Tabel News/Promo (existing, keeping for compatibility)
CREATE TABLE IF NOT EXISTS news (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    content TEXT,
    image VARCHAR(255),
    type ENUM('news', 'promo') DEFAULT 'news',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert Data Dummy untuk Testing

-- Insert Admin User
INSERT IGNORE INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@sugengrrahayu.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin');

-- Insert Sample Routes
INSERT IGNORE INTO routes (origin, destination, distance, estimated_duration, description) VALUES
('Jakarta', 'Bandung', 150.5, '03:00:00', 'Rute populer Jakarta - Bandung via tol'),
('Jakarta', 'Yogyakarta', 450.2, '08:00:00', 'Perjalanan jauh Jakarta - Yogyakarta'),
('Jakarta', 'Surabaya', 780.8, '12:00:00', 'Rute terpanjang Jakarta - Surabaya'),
('Bandung', 'Yogyakarta', 320.3, '06:00:00', 'Rute Bandung - Yogyakarta'),
('Yogyakarta', 'Surabaya', 320.6, '05:30:00', 'Rute Yogyakarta - Surabaya');

-- Insert Bus Types
INSERT IGNORE INTO bus_types (name, description, capacity, facilities) VALUES
('Premium', 'Bus premium dengan fasilitas lengkap', 36, 'WiFi, Snack, AC, Reclining Seats, Toilet'),
('Executive', 'Bus executive dengan kenyamanan tinggi', 40, 'WiFi, Minuman, AC, Reclining Seats, Toilet'),
('VIP', 'Bus VIP dengan pelayanan terbaik', 28, 'WiFi, Premium Meal, AC, Luxury Seats, Toilet'),
('Regular', 'Bus regular dengan harga terjangkau', 50, 'AC, Standard Seats, Toilet');

-- Insert Sample Schedules
INSERT IGNORE INTO schedules (route_id, bus_type_id, departure_date, departure_time, arrival_time, price, available_seats) VALUES
(1, 1, '2025-12-20', '08:00:00', '11:00:00', 150000, 36),
(1, 2, '2025-12-20', '14:00:00', '17:00:00', 120000, 40),
(2, 3, '2025-12-21', '06:00:00', '14:00:00', 200000, 28),
(2, 1, '2025-12-21', '20:00:00', '04:00:00', 180000, 36),
(3, 2, '2025-12-22', '22:00:00', '10:00:00', 250000, 40);

-- Insert Sample News
INSERT IGNORE INTO news (title, content, type, status) VALUES
('Promo Natal 2025', 'Dapatkan diskon hingga 30% untuk perjalanan Natal dan Tahun Baru', 'promo', 'active'),
('Armada Baru Executive Class', 'Kami meluncurkan armada baru Executive Class dengan fasilitas premium', 'news', 'active'),
('Jadwal Tambahan Libur Akhir Tahun', 'Tambahan jadwal untuk mengakomodasi lonjakan penumpang akhir tahun', 'news', 'active');

COMMIT;