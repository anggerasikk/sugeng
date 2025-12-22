<?php
// includes/functions.php

// Database connection
function get_db_connection() {
    static $conn = null;
    if ($conn === null) {
        $conn = new mysqli('localhost', 'root', '', 'sugeng');
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $conn->set_charset("utf8mb4");
    }
    return $conn;
}

// Cek Staff
function is_staff() {
    return (isset($_SESSION['user_role']) && in_array($_SESSION['user_role'], ['admin', 'staff']));
}

// Generate booking code
function generate_booking_code() {
    $prefix = 'SGR';
    $timestamp = date('ymdHis');
    $random = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
    return $prefix . $timestamp . $random;
}

// Validate phone number (Indonesian format)
function is_valid_phone($phone) {
    // Remove all non-numeric characters
    $phone = preg_replace('/\D/', '', $phone);

    // Check if it's a valid Indonesian phone number
    // Format: +62xxxxxxxxx or 0xxxxxxxxx or 62xxxxxxxxx
    if (preg_match('/^(?:\+62|62|0)[8-9][0-9]{7,11}$/', $phone)) {
        return true;
    }

    return false;
}

// Validate booking data
function validate_booking_data($schedule_id, $travel_date, $passenger_name, $passenger_phone, $passenger_identity, $num_passengers) {
    $errors = [];

    // Check if schedule exists and is available
    $schedule = get_schedule_by_id($schedule_id);
    if (!$schedule) {
        $errors[] = 'Jadwal tidak ditemukan.';
    } elseif ($schedule['available_seats'] < $num_passengers) {
        $errors[] = 'Kursi tidak mencukupi untuk jumlah penumpang.';
    }

    // Validate passenger name
    if (empty($passenger_name) || strlen($passenger_name) < 3) {
        $errors[] = 'Nama penumpang minimal 3 karakter.';
    }

    // Validate phone number
    if (!is_valid_phone($passenger_phone)) {
        $errors[] = 'Format nomor telepon tidak valid.';
    }

    // Validate identity number
    if (empty($passenger_identity) || !preg_match('/^[0-9]{16}$/', $passenger_identity)) {
        $errors[] = 'Nomor identitas harus 16 digit angka.';
    }

    // Validate number of passengers
    if ($num_passengers < 1 || $num_passengers > 4) {
        $errors[] = 'Jumlah penumpang minimal 1 dan maksimal 4 orang.';
    }

    // Validate travel date
    $departure_datetime = strtotime($travel_date . ' ' . $schedule['departure_time']);
    $now = time();
    if ($departure_datetime <= $now) {
        $errors[] = 'Tanggal keberangkatan harus di masa depan.';
    }

    return $errors;
}

// Generate slug for blog posts
function generate_slug($string) {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9\s-]/', '', $string);
    $string = preg_replace('/[\s-]+/', '-', $string);
    return trim($string, '-');
}









// Get user by ID
function get_user_by_id($id) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get user by email
function get_user_by_email($email) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get booking by code
function get_booking_by_code($code) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        SELECT b.*, s.departure_date, s.departure_time, s.arrival_time,
               r.origin, r.destination, bt.name as bus_type
        FROM bookings b
        JOIN schedules s ON b.schedule_id = s.id
        JOIN routes r ON s.route_id = r.id
        JOIN bus_types bt ON s.bus_type_id = bt.id
        WHERE b.booking_code = ?
    ");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get schedule by ID
function get_schedule_by_id($id) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        SELECT s.*, r.origin, r.destination, bt.name as bus_type_name,
               bt.description as bus_description, bt.facilities
        FROM schedules s
        JOIN routes r ON s.route_id = r.id
        JOIN bus_types bt ON s.bus_type_id = bt.id
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get available schedules
function get_available_schedules($origin = null, $destination = null, $date = null) {
    $conn = get_db_connection();
    $query = "
        SELECT s.*, r.origin, r.destination, bt.name as bus_type_name,
               bt.description as bus_description, bt.facilities
        FROM schedules s
        JOIN routes r ON s.route_id = r.id
        JOIN bus_types bt ON s.bus_type_id = bt.id
        WHERE s.status = 'active' AND s.available_seats > 0
    ";

    $params = [];
    $types = "";

    if ($origin) {
        $query .= " AND r.origin = ?";
        $params[] = $origin;
        $types .= "s";
    }

    if ($destination) {
        $query .= " AND r.destination = ?";
        $params[] = $destination;
        $types .= "s";
    }

    if ($date) {
        $query .= " AND s.departure_date = ?";
        $params[] = $date;
        $types .= "s";
    }

    $query .= " ORDER BY s.departure_time ASC";

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get user bookings
function get_user_bookings($user_id, $status = null) {
    $conn = get_db_connection();
    $query = "
        SELECT b.*, s.departure_date, s.departure_time, s.arrival_time,
               r.origin, r.destination, bt.name as bus_type
        FROM bookings b
        JOIN schedules s ON b.schedule_id = s.id
        JOIN routes r ON s.route_id = r.id
        JOIN bus_types bt ON s.bus_type_id = bt.id
        WHERE b.user_id = ?
    ";

    $params = [$user_id];
    $types = "i";

    if ($status) {
        $query .= " AND b.booking_status = ?";
        $params[] = $status;
        $types .= "s";
    }

    $query .= " ORDER BY b.created_at DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get cancellation requests
function get_cancellation_requests($user_id = null, $status = null) {
    $conn = get_db_connection();
    $query = "
        SELECT cr.*, b.booking_code, b.passenger_name, b.total_amount,
               s.departure_date, r.origin, r.destination,
               u.full_name as processed_by_name
        FROM cancellation_requests cr
        JOIN bookings b ON cr.booking_id = b.id
        JOIN schedules s ON b.schedule_id = s.id
        JOIN routes r ON s.route_id = r.id
        LEFT JOIN users u ON cr.processed_by = u.id
        WHERE 1=1
    ";

    $params = [];
    $types = "";

    if ($user_id) {
        $query .= " AND cr.user_id = ?";
        $params[] = $user_id;
        $types .= "i";
    }

    if ($status) {
        $query .= " AND cr.status = ?";
        $params[] = $status;
        $types .= "s";
    }

    $query .= " ORDER BY cr.created_at DESC";

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get blog posts
function get_blog_posts($category = null, $limit = null, $status = 'published') {
    $conn = get_db_connection();
    $query = "
        SELECT bp.*, u.full_name as author_name
        FROM blog_posts bp
        LEFT JOIN users u ON bp.author_id = u.id
        WHERE bp.status = ?
    ";

    $params = [$status];
    $types = "s";

    if ($category) {
        $query .= " AND bp.category = ?";
        $params[] = $category;
        $types .= "s";
    }

    $query .= " ORDER BY bp.published_at DESC";

    if ($limit) {
        $query .= " LIMIT ?";
        $params[] = $limit;
        $types .= "i";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get blog post by slug
function get_blog_post_by_slug($slug) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        SELECT bp.*, u.full_name as author_name
        FROM blog_posts bp
        LEFT JOIN users u ON bp.author_id = u.id
        WHERE bp.slug = ? AND bp.status = 'published'
    ");
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Get related blog posts
function get_related_posts($current_id, $category, $limit = 3) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        SELECT * FROM blog_posts
        WHERE id != ? AND category = ? AND status = 'published'
        ORDER BY published_at DESC
        LIMIT ?
    ");
    $stmt->bind_param("isi", $current_id, $category, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get dashboard statistics
function get_dashboard_stats() {
    $conn = get_db_connection();

    $stats = [];

    // Today's reservations
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count FROM bookings
        WHERE DATE(created_at) = CURDATE()
    ");
    $stmt->execute();
    $stats['today_reservations'] = $stmt->get_result()->fetch_assoc()['count'];

    // This month's reservations
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count FROM bookings
        WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())
    ");
    $stmt->execute();
    $stats['month_reservations'] = $stmt->get_result()->fetch_assoc()['count'];

    // Pending cancellations
    $stmt = $conn->prepare("
        SELECT COUNT(*) as count FROM cancellation_requests
        WHERE status = 'pending'
    ");
    $stmt->execute();
    $stats['pending_cancellations'] = $stmt->get_result()->fetch_assoc()['count'];

    // Today's revenue
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(total_amount), 0) as revenue FROM bookings
        WHERE DATE(created_at) = CURDATE() AND payment_status = 'paid'
    ");
    $stmt->execute();
    $stats['today_revenue'] = $stmt->get_result()->fetch_assoc()['revenue'];

    // This month's revenue
    $stmt = $conn->prepare("
        SELECT COALESCE(SUM(total_amount), 0) as revenue FROM bookings
        WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE()) AND payment_status = 'paid'
    ");
    $stmt->execute();
    $stats['month_revenue'] = $stmt->get_result()->fetch_assoc()['revenue'];

    // Average occupancy
    $stmt = $conn->prepare("
        SELECT AVG((total_seats - available_seats) / total_seats * 100) as occupancy
        FROM schedules
        WHERE status = 'active'
    ");
    $stmt->execute();
    $stats['avg_occupancy'] = round($stmt->get_result()->fetch_assoc()['occupancy'], 1);

    return $stats;
}

// Log activity
function log_activity($user_id, $action, $description = null) {
    $conn = get_db_connection();
    $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $stmt = $conn->prepare("
        INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("issss", $user_id, $action, $description, $ip, $user_agent);
    $stmt->execute();
}

// Send email (basic implementation - in production, use PHPMailer or similar)
function send_email($to, $subject, $message, $headers = null) {
    if (!$headers) {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= 'From: Bus Sugeng Rahayu <noreply@sugengrrahayu.com>' . "\r\n";
    }

    // In development, just log the email
    error_log("Email to: $to\nSubject: $subject\nMessage: $message");

    // Uncomment for production
    // return mail($to, $subject, $message, $headers);
    return true;
}

// Send booking confirmation email
function send_booking_confirmation_email($to, $booking_code, $passenger_name, $total_price) {
    $subject = "Konfirmasi Booking - $booking_code";

    $message = "
    <html>
    <head>
        <title>Konfirmasi Booking Bus Sugeng Rahayu</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #001BB7; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; }
            .booking-code { background: #FF8040; color: white; padding: 10px; border-radius: 5px; text-align: center; font-size: 18px; font-weight: bold; }
            .details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #001BB7; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Bus Sugeng Rahayu</h1>
                <p>Booking Berhasil!</p>
            </div>
            <div class='content'>
                <p>Halo <strong>$passenger_name</strong>,</p>
                <p>Terima kasih telah memilih Bus Sugeng Rahayu untuk perjalanan Anda. Booking Anda telah berhasil diproses.</p>

                <div class='booking-code'>
                    Kode Booking: $booking_code
                </div>

                <div class='details'>
                    <h3>Detail Booking:</h3>
                    <p><strong>Total Pembayaran:</strong> " . format_currency($total_price) . "</p>
                    <p><strong>Status:</strong> Menunggu Pembayaran</p>
                </div>

                <p>Silakan lakukan pembayaran sesuai dengan metode yang telah dipilih dan lakukan konfirmasi pembayaran.</p>
                <p>Jika Anda memiliki pertanyaan, silakan hubungi customer service kami.</p>

                <p>Salam,<br>Tim Bus Sugeng Rahayu</p>
            </div>
        </div>
    </body>
    </html>
    ";

    return send_email($to, $subject, $message);
}

// Send check-in confirmation email
function send_checkin_confirmation_email($to, $booking_code, $passenger_name, $seat_numbers, $booking_details) {
    $subject = "Check-in Berhasil - $booking_code";

    $seats_display = implode(', ', $seat_numbers);

    $message = "
    <html>
    <head>
        <title>Check-in Berhasil - Bus Sugeng Rahayu</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #001BB7; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
            .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; }
            .booking-code { background: #FF8040; color: white; padding: 10px; border-radius: 5px; text-align: center; font-size: 18px; font-weight: bold; }
            .seats { background: #4CAF50; color: white; padding: 15px; border-radius: 5px; text-align: center; font-size: 16px; font-weight: bold; margin: 15px 0; }
            .details { background: white; padding: 15px; margin: 15px 0; border-left: 4px solid #001BB7; }
            .warning { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>Bus Sugeng Rahayu</h1>
                <p>Check-in Berhasil!</p>
            </div>
            <div class='content'>
                <p>Halo <strong>$passenger_name</strong>,</p>
                <p>Check-in online Anda telah berhasil diproses. Berikut adalah detail perjalanan Anda:</p>

                <div class='booking-code'>
                    Kode Booking: $booking_code
                </div>

                <div class='seats'>
                    Nomor Kursi Anda: $seats_display
                </div>

                <div class='details'>
                    <h3>Detail Perjalanan:</h3>
                    <p><strong>Rute:</strong> {$booking_details['origin']} → {$booking_details['destination']}</p>
                    <p><strong>Tanggal Keberangkatan:</strong> " . format_date($booking_details['departure_date']) . "</p>
                    <p><strong>Waktu Keberangkatan:</strong> " . format_time($booking_details['departure_time']) . "</p>
                </div>

                <div class='warning'>
                    <strong>Penting:</strong>
                    <ul>
                        <li>Silakan tiba di terminal minimal 30 menit sebelum keberangkatan</li>
                        <li>Bawa kartu identitas asli sesuai yang didaftarkan</li>
                        <li>Simpan email ini sebagai bukti check-in</li>
                    </ul>
                </div>

                <p>Terima kasih telah menggunakan layanan Bus Sugeng Rahayu. Selamat berkendara!</p>

                <p>Salam,<br>Tim Bus Sugeng Rahayu</p>
            </div>
        </div>
    </body>
    </html>
    ";

    return send_email($to, $subject, $message);
}



// Get routes list
function get_routes() {
    $conn = get_db_connection();
    $result = $conn->query("SELECT * FROM routes WHERE status = 'active' ORDER BY origin, destination");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get bus types
function get_bus_types() {
    $conn = get_db_connection();
    $result = $conn->query("SELECT * FROM bus_types WHERE status = 'active' ORDER BY name");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get users list (admin)
function get_users($role = null, $status = null) {
    $conn = get_db_connection();
    $query = "SELECT * FROM users WHERE 1=1";
    $params = [];
    $types = "";

    if ($role) {
        $query .= " AND role = ?";
        $params[] = $role;
        $types .= "s";
    }

    if ($status) {
        $query .= " AND status = ?";
        $params[] = $status;
        $types .= "s";
    }

    $query .= " ORDER BY created_at DESC";

    $stmt = $conn->prepare($query);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Update schedule seats
function update_schedule_seats($schedule_id, $seats_booked) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        UPDATE schedules
        SET available_seats = available_seats - ?
        WHERE id = ? AND available_seats >= ?
    ");
    $stmt->bind_param("iii", $seats_booked, $schedule_id, $seats_booked);
    return $stmt->execute();
}

// Check if user can cancel booking
function can_cancel_booking($booking_id, $user_id) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        SELECT b.*, s.departure_date
        FROM bookings b
        JOIN schedules s ON b.schedule_id = s.id
        WHERE b.id = ? AND b.user_id = ? AND b.booking_status = 'confirmed'
    ");
    $stmt->bind_param("ii", $booking_id, $user_id);
    $stmt->execute();
    $booking = $stmt->get_result()->fetch_assoc();

    if (!$booking) return false;

    // Check if departure is more than 24 hours away
    $departure_time = strtotime($booking['departure_date'] . ' ' . $booking['departure_time']);
    $now = time();
    $hours_diff = ($departure_time - $now) / 3600;

    return $hours_diff > 24;
}

// Get chart data for dashboard
function get_chart_data($type, $period = 'month') {
    $conn = get_db_connection();

    switch ($type) {
        case 'reservations':
            if ($period == 'week') {
                $query = "
                    SELECT DATE(created_at) as date, COUNT(*) as count
                    FROM bookings
                    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                    GROUP BY DATE(created_at)
                    ORDER BY date
                ";
            } else {
                $query = "
                    SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
                    FROM bookings
                    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                    GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                    ORDER BY month
                ";
            }
            break;

        case 'revenue':
            $query = "
                SELECT DATE_FORMAT(created_at, '%Y-%m') as month, SUM(total_amount) as revenue
                FROM bookings
                WHERE payment_status = 'paid' AND created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month
            ";
            break;

        case 'cancellations':
            $query = "
                SELECT DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count
                FROM cancellation_requests
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(created_at, '%Y-%m')
                ORDER BY month
            ";
            break;
    }

    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get popular routes
function get_popular_routes($limit = 5) {
    $conn = get_db_connection();
    $stmt = $conn->prepare("
        SELECT r.origin, r.destination, COUNT(b.id) as booking_count
        FROM routes r
        JOIN schedules s ON r.id = s.route_id
        JOIN bookings b ON s.id = b.schedule_id
        GROUP BY r.id
        ORDER BY booking_count DESC
        LIMIT ?
    ");
    $stmt->bind_param("i", $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get peak hours
function get_peak_hours() {
    $conn = get_db_connection();
    $result = $conn->query("
        SELECT HOUR(departure_time) as hour, COUNT(*) as count
        FROM schedules s
        JOIN bookings b ON s.id = b.schedule_id
        GROUP BY HOUR(departure_time)
        ORDER BY count DESC
        LIMIT 5
    ");
    return $result->fetch_all(MYSQLI_ASSOC);
}

// File Upload Function
if (!function_exists('upload_file')) {
    function upload_file($file, $allowed_types = ['jpg', 'jpeg', 'png', 'pdf']) {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'File upload error'];
        }

        $file_name = $file['name'];
        $file_size = $file['size'];
        $file_tmp = $file['tmp_name'];

        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($file_ext, $allowed_types)) {
            return ['success' => false, 'message' => 'File type not allowed'];
        }

        if ($file_size > MAX_FILE_SIZE) {
            return ['success' => false, 'message' => 'File size too large'];
        }

        $new_file_name = generate_unique_code() . '.' . $file_ext;
        $upload_path = UPLOAD_PATH . $new_file_name;

        if (!is_dir(UPLOAD_PATH)) {
            mkdir(UPLOAD_PATH, 0755, true);
        }

        if (move_uploaded_file($file_tmp, $upload_path)) {
            return ['success' => true, 'file_path' => $upload_path, 'file_name' => $new_file_name];
        } else {
            return ['success' => false, 'message' => 'Failed to upload file'];
        }
    }
}
?>
