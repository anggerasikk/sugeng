<?php
require_once '../config.php';

// Check if user is admin
if (!is_admin()) {
    set_flash('error', 'Access denied. Admin login required.');
    redirect('../beranda/signin.php');
}

include 'header-admin.php';
?>

<div class="main-content">
    <div class="page-header">
        <h1 class="page-title">📊 Dashboard Admin</h1>
        <p class="page-subtitle">Pantau performa sistem dan kelola operasional bus secara real-time</p>
    </div>

    <!-- Statistics Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #001BB7; position: relative;">
            <h4 style="margin: 0; color: #666; font-size: 0.9rem;">Total Reservasi Hari Ini</h4>
            <h2 style="margin: 10px 0 0; color: #001BB7; font-size: 2rem;">
                <?php
                $today_bookings = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bookings WHERE DATE(created_at) = CURDATE()"))['total'];
                echo number_format($today_bookings);
                ?>
            </h2>
            <small style="color: #666;">reservasi baru</small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #FF8040; position: relative;">
            <h4 style="margin: 0; color: #666; font-size: 0.9rem;">Total Reservasi Bulan Ini</h4>
            <h2 style="margin: 10px 0 0; color: #FF8040; font-size: 2rem;">
                <?php
                $month_bookings = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM bookings WHERE MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"))['total'];
                echo number_format($month_bookings);
                ?>
            </h2>
            <small style="color: #666;">total bulan ini</small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #dc3545; position: relative;">
            <h4 style="margin: 0; color: #666; font-size: 0.9rem;">Pending Cancellations</h4>
            <h2 style="margin: 10px 0 0; color: #dc3545; font-size: 2rem;">
                <?php
                $pending_cancellations = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM cancellation_requests WHERE status = 'pending'"))['total'];
                echo number_format($pending_cancellations);
                ?>
            </h2>
            <span style="position: absolute; top: 15px; right: 15px; background: #dc3545; color: white; border-radius: 50%; width: 20px; height: 20px; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">
                <?php echo $pending_cancellations; ?>
            </span>
            <small style="color: #666;">menunggu approval</small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #28a745;">
            <h4 style="margin: 0; color: #666; font-size: 0.9rem;">Total Pendapatan Hari Ini</h4>
            <h2 style="margin: 10px 0 0; color: #28a745; font-size: 2rem;">
                <?php
                $today_revenue = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_amount) as revenue FROM bookings WHERE payment_status = 'paid' AND DATE(created_at) = CURDATE()"))['revenue'] ?? 0;
                echo 'Rp ' . number_format($today_revenue, 0, ',', '.');
                ?>
            </h2>
            <small style="color: #666;">dari pembayaran</small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #17a2b8;">
            <h4 style="margin: 0; color: #666; font-size: 0.9rem;">Total Pendapatan Bulan Ini</h4>
            <h2 style="margin: 10px 0 0; color: #17a2b8; font-size: 2rem;">
                <?php
                $month_revenue = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_amount) as revenue FROM bookings WHERE payment_status = 'paid' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"))['revenue'] ?? 0;
                echo 'Rp ' . number_format($month_revenue, 0, ',', '.');
                ?>
            </h2>
            <small style="color: #666;">total bulan ini</small>
        </div>

        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border-left: 5px solid #6f42c1;">
            <h4 style="margin: 0; color: #666; font-size: 0.9rem;">Tingkat Okupansi Rata-rata</h4>
            <h2 style="margin: 10px 0 0; color: #6f42c1; font-size: 2rem;">
                <?php
                // Calculate average occupancy rate
                $occupancy_query = mysqli_query($koneksi, "
                    SELECT AVG((booked_seats / total_seats) * 100) as avg_occupancy
                    FROM schedules s
                    LEFT JOIN (
                        SELECT schedule_id, COUNT(*) as booked_seats
                        FROM bookings
                        WHERE booking_status = 'confirmed'
                        GROUP BY schedule_id
                    ) b ON s.id = b.schedule_id
                    WHERE s.status = 'active'
                ");
                $avg_occupancy = mysqli_fetch_assoc($occupancy_query)['avg_occupancy'] ?? 0;
                echo number_format($avg_occupancy, 1) . '%';
                ?>
            </h2>
            <small style="color: #666;">dari semua jadwal</small>
        </div>
    </div>

    <!-- Charts Section -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(500px, 1fr)); gap: 30px; margin-bottom: 30px;">
        <!-- Reservation Trend Chart -->
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 20px; color: #001BB7; font-size: 1.2rem;">📈 Tren Reservasi (7 Hari Terakhir)</h3>
            <p style="margin-bottom: 20px; color: #666; font-size: 0.9rem;">Grafik ini menampilkan jumlah kumulatif reservasi selama 7 hari terakhir.</p>
            <div id="reservationChart" style="height: 300px;"></div>
        </div>

        <!-- Monthly Revenue Chart -->
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 20px; color: #001BB7; font-size: 1.2rem;">💰 Pendapatan Bulanan</h3>
            <div id="revenueChart" style="height: 300px;"></div>
        </div>
    </div>

    <!-- Notifications Section -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
        <!-- Recent Activities -->
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 20px; color: #001BB7; font-size: 1.2rem;">🔔 Notifikasi & Aktivitas Terbaru</h3>

            <?php
            // Get recent notifications
            $notifications = [];

            // New bookings today
            $new_bookings_today = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = CURDATE()"))['count'];
            if ($new_bookings_today > 0) {
                $notifications[] = [
                    'type' => 'booking',
                    'icon' => '🎫',
                    'title' => 'Reservasi Baru Hari Ini',
                    'message' => "Ada {$new_bookings_today} reservasi baru yang dibuat hari ini",
                    'time' => 'Hari ini',
                    'priority' => 'normal'
                ];
            }

            // Pending cancellations
            $pending_cancellations = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as count FROM cancellation_requests WHERE status = 'pending'"))['count'];
            if ($pending_cancellations > 0) {
                $notifications[] = [
                    'type' => 'cancellation',
                    'icon' => '⚠️',
                    'title' => 'Pembatalan Menunggu Approval',
                    'message' => "Ada {$pending_cancellations} permintaan pembatalan yang perlu disetujui",
                    'time' => 'Segera',
                    'priority' => 'high'
                ];
            }

            // System alerts (dummy data for demonstration)
            $notifications[] = [
                'type' => 'system',
                'icon' => '🚨',
                'title' => 'Alert Sistem',
                'message' => 'Bus dengan ID BUS-001 penuh untuk jadwal hari ini',
                'time' => '2 jam yang lalu',
                'priority' => 'medium'
            ];

            // Recent activities from activity_logs
            $activities = mysqli_query($koneksi, "
                SELECT al.*, u.full_name
                FROM activity_logs al
                LEFT JOIN users u ON al.user_id = u.id
                ORDER BY al.created_at DESC
                LIMIT 5
            ");

            while ($activity = mysqli_fetch_assoc($activities)) {
                $icon = '📝';
                $priority = 'low';

                if (strpos($activity['action'], 'login') !== false) {
                    $icon = '🔐';
                } elseif (strpos($activity['action'], 'booking') !== false) {
                    $icon = '🎫';
                } elseif (strpos($activity['action'], 'cancel') !== false) {
                    $icon = '❌';
                    $priority = 'medium';
                } elseif (strpos($activity['action'], 'user') !== false) {
                    $icon = '👤';
                }

                $notifications[] = [
                    'type' => 'activity',
                    'icon' => $icon,
                    'title' => htmlspecialchars($activity['description'] ?? $activity['action']),
                    'message' => 'Oleh ' . htmlspecialchars($activity['full_name'] ?? 'System'),
                    'time' => format_date($activity['created_at']),
                    'priority' => $priority
                ];
            }

            // Display notifications
            if (empty($notifications)) {
                echo '<p style="color: #666; text-align: center; padding: 40px;">Tidak ada notifikasi terbaru</p>';
            } else {
                foreach ($notifications as $notif) {
                    $border_color = '#001BB7';
                    if ($notif['priority'] === 'high') $border_color = '#dc3545';
                    elseif ($notif['priority'] === 'medium') $border_color = '#ffc107';
                    ?>
                    <div style="display: flex; align-items: center; padding: 15px; border-left: 4px solid <?php echo $border_color; ?>; margin-bottom: 10px; background: #f8f9fa; border-radius: 5px;">
                        <div style="font-size: 1.5rem; margin-right: 15px;"><?php echo $notif['icon']; ?></div>
                        <div style="flex: 1;">
                            <div style="font-weight: 600; color: #333; margin-bottom: 3px;"><?php echo $notif['title']; ?></div>
                            <div style="color: #666; font-size: 0.9rem;"><?php echo $notif['message']; ?></div>
                        </div>
                        <div style="color: #999; font-size: 0.8rem;"><?php echo $notif['time']; ?></div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>

        <!-- Quick Actions -->
        <div style="background: white; padding: 25px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
            <h3 style="margin-bottom: 20px; color: #001BB7; font-size: 1.2rem;">⚡ Quick Actions</h3>

            <div style="display: flex; flex-direction: column; gap: 10px;">
                <a href="bookings/index.php" style="display: block; padding: 15px; background: #001BB7; color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: 500; transition: all 0.3s ease;">
                    🎫 Kelola Bookings
                </a>

                <a href="cancellations/index.php" style="display: block; padding: 15px; background: #FF8040; color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: 500; transition: all 0.3s ease;">
                    ⚠️ Proses Pembatalan
                </a>

                <a href="schedules/index.php" style="display: block; padding: 15px; background: #28a745; color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: 500; transition: all 0.3s ease;">
                    🚌 Kelola Jadwal
                </a>

                <a href="users/index.php" style="display: block; padding: 15px; background: #17a2b8; color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: 500; transition: all 0.3s ease;">
                    👥 Kelola Users
                </a>

                <a href="blog/index.php" style="display: block; padding: 15px; background: #6f42c1; color: white; text-decoration: none; border-radius: 5px; text-align: center; font-weight: 500; transition: all 0.3s ease;">
                    📝 Kelola Blog
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Reservation Trend Chart (7 days cumulative)
<?php
$reservation_data = [];
$cumulative = 0;
for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $count = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as count FROM bookings WHERE DATE(created_at) = '$date'"))['count'];
    $cumulative += $count;
    $reservation_data[] = $cumulative;
}
$reservation_labels = [];
for ($i = 6; $i >= 0; $i--) {
    $reservation_labels[] = date('d/m', strtotime("-{$i} days"));
}
?>

Highcharts.chart('reservationChart', {
    chart: {
        type: 'line',
        backgroundColor: 'transparent'
    },
    title: {
        text: null
    },
    xAxis: {
        categories: <?php echo json_encode($reservation_labels); ?>,
        gridLineWidth: 1,
        gridLineColor: '#f0f0f0'
    },
    yAxis: {
        title: {
            text: 'Jumlah Reservasi Kumulatif'
        },
        gridLineWidth: 1,
        gridLineColor: '#f0f0f0'
    },
    series: [{
        name: 'Reservasi',
        data: <?php echo json_encode($reservation_data); ?>,
        color: '#001BB7',
        lineWidth: 3,
        marker: {
            radius: 5,
            fillColor: '#001BB7'
        }
    }],
    credits: {
        enabled: false
    },
    legend: {
        enabled: false
    }
});

// Monthly Revenue Chart
<?php
$revenue_data = [];
$revenue_labels = [];
for ($i = 11; $i >= 0; $i--) {
    $month = date('Y-m', strtotime("-{$i} months"));
    $revenue = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(total_amount) as revenue FROM bookings WHERE payment_status = 'paid' AND DATE_FORMAT(created_at, '%Y-%m') = '$month'"))['revenue'] ?? 0;
    $revenue_data[] = (float)$revenue;
    $revenue_labels[] = date('M Y', strtotime($month . '-01'));
}
?>

Highcharts.chart('revenueChart', {
    chart: {
        type: 'column',
        backgroundColor: 'transparent'
    },
    title: {
        text: null
    },
    xAxis: {
        categories: <?php echo json_encode($revenue_labels); ?>,
        gridLineWidth: 1,
        gridLineColor: '#f0f0f0'
    },
    yAxis: {
        title: {
            text: 'Pendapatan (Rp)'
        },
        gridLineWidth: 1,
        gridLineColor: '#f0f0f0',
        labels: {
            formatter: function() {
                return 'Rp ' + Highcharts.numberFormat(this.value, 0, ',', '.');
            }
        }
    },
    tooltip: {
        formatter: function() {
            return '<b>' + this.x + '</b><br/>' +
                   'Pendapatan: <b>Rp ' + Highcharts.numberFormat(this.y, 0, ',', '.') + '</b>';
        }
    },
    series: [{
        name: 'Pendapatan',
        data: <?php echo json_encode($revenue_data); ?>,
        color: '#28a745'
    }],
    credits: {
        enabled: false
    },
    legend: {
        enabled: false
    }
});
</script>

<?php include 'footer-admin.php'; ?>
