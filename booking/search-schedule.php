<?php
require_once '../config.php';
require_once '../includes/functions.php';
include '../header.php';

$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$departure_date = $_GET['departure_date'] ?? '';

// Get available schedules using the function from functions.php
$schedules = get_available_schedules($origin, $destination, $departure_date);
?>

<div style="max-width: 1000px; margin: 40px auto; padding: 0 20px;">
    <h2 style="color: #001BB7; border-left: 5px solid #FF8040; padding-left: 15px; margin-bottom: 30px;">
        Jadwal: <?php echo htmlspecialchars($origin); ?> ➔ <?php echo htmlspecialchars($destination); ?>
    </h2>

    <?php if (!empty($schedules)): ?>
        <?php foreach($schedules as $bus): ?>
            <div style="background: white; border: 1px solid #eee; border-radius: 10px; padding: 20px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
                <div>
                    <span style="background: #001BB7; color: white; padding: 4px 12px; border-radius: 20px; font-size: 12px; text-transform: uppercase;">
                        <?php echo htmlspecialchars($bus['bus_type_name'] ?? 'Regular'); ?>
                    </span>
                    <h3 style="margin: 10px 0; color: #333;"><?php echo htmlspecialchars($bus['bus_type_name'] ?? 'Bus'); ?></h3>
                    <p style="margin: 0; color: #666;">
                        🕒 <strong><?php echo format_time($bus['departure_time']); ?></strong> ➔ <?php echo format_time($bus['arrival_time']); ?>
                    </p>
                </div>

                <div style="text-align: right;">
                    <p style="margin: 0; font-size: 20px; font-weight: bold; color: #FF8040;">
                        <?php echo format_currency($bus['price']); ?>
                    </p>
                    <p style="margin: 5px 0 15px; font-size: 13px; color: #888;">Tersisa: <?php echo htmlspecialchars($bus['available_seats']); ?> Kursi</p>
                    <a href="book-detail.php?id=<?php echo htmlspecialchars($bus['id']); ?>" style="background: #001BB7; color: white; text-decoration: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;">
                        Pilih
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div style="text-align: center; padding: 50px; background: #fff; border-radius: 10px;">
            <p style="color: #666;">Maaf, jadwal belum tersedia untuk rute dan tanggal ini.</p>
            <a href="index.php" style="color: #001BB7; font-weight: bold;">Kembali cari</a>
        </div>
    <?php endif; ?>
</div>

<?php include '../footer.php'; ?>