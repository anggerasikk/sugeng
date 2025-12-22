<?php
require_once '../config.php';
include '../header.php';

$id = $_GET['id'] ?? ''; // ID Jadwal
$query = mysqli_query($koneksi, "SELECT s.*, r.origin, r.destination, bt.name as bus_type_name FROM schedules s JOIN routes r ON s.route_id = r.id JOIN bus_types bt ON s.bus_type_id = bt.id WHERE s.id = '$id'");
$bus = mysqli_fetch_assoc($query);

if (!$bus) {
    redirect('index.php');
}

// Calculate available seats
$booked_query = mysqli_query($koneksi, "SELECT COUNT(*) as booked FROM bookings WHERE schedule_id = '$id' AND booking_status IN ('confirmed', 'checked_in')");
$booked = mysqli_fetch_assoc($booked_query)['booked'];
$available_seats = $bus['total_seats'] - $booked;
?>

<div style="max-width: 900px; margin: 40px auto; padding: 0 20px;">
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
        
        <div style="background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            <h2 style="color: #001BB7; margin-bottom: 25px;">Data Penumpang</h2>
            <form action="process.php" method="POST">
                <input type="hidden" name="schedule_id" value="<?php echo htmlspecialchars($bus['id']); ?>">
                <input type="hidden" name="travel_date" value="<?php echo htmlspecialchars($bus['departure_date']); ?>">
                <input type="hidden" name="price" value="<?php echo htmlspecialchars($bus['price']); ?>">

                <div style="margin-bottom: 20px;">
                    <label style="display:block; margin-bottom: 8px; font-weight: bold;">Nama Lengkap (Sesuai KTP)</label>
                    <input type="text" name="passenger_name" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:6px;" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="display:block; margin-bottom: 8px; font-weight: bold;">Nomor Identitas/NIK</label>
                        <input type="text" name="passenger_identity" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:6px;" required>
                    </div>
                    <div>
                        <label style="display:block; margin-bottom: 8px; font-weight: bold;">Nomor WhatsApp</label>
                        <input type="tel" name="passenger_phone" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:6px;" required>
                    </div>
                </div>

                <div style="margin-bottom: 30px;">
                    <label style="display:block; margin-bottom: 8px; font-weight: bold;">Jumlah Kursi</label>
                    <input type="number" name="num_passengers" min="1" max="<?php echo htmlspecialchars($available_seats); ?>" value="1" style="width:100%; padding:12px; border:1px solid #ddd; border-radius:6px;" required>
                </div>

                <button type="submit" style="width:100%; background:#FF8040; color:white; border:none; padding:15px; border-radius:8px; font-size:16px; font-weight:bold; cursor:pointer;">
                    Lanjutkan ke Pembayaran
                </button>
            </form>
        </div>

        <div style="background: #001BB7; color: white; padding: 25px; border-radius: 12px; height: fit-content;">
            <h3 style="border-bottom: 1px solid rgba(255,255,255,0.2); padding-bottom: 15px; margin-bottom: 15px;">Ringkasan</h3>
            <p style="margin-bottom: 5px; opacity: 0.8;">Bus:</p>
            <p style="font-weight: bold; font-size: 18px; margin-bottom: 15px;"><?php echo htmlspecialchars($bus['bus_type_name'] ?? 'N/A'); ?></p>

            <p style="margin-bottom: 5px; opacity: 0.8;">Rute:</p>
            <p style="font-weight: bold; margin-bottom: 15px;"><?php echo htmlspecialchars($bus['origin'] ?? 'N/A'); ?> ➔ <?php echo htmlspecialchars($bus['destination'] ?? 'N/A'); ?></p>

            <p style="margin-bottom: 5px; opacity: 0.8;">Keberangkatan:</p>
            <p style="font-weight: bold;"><?php echo $bus['departure_date'] ? format_date($bus['departure_date']) : 'N/A'; ?> | <?php echo $bus['departure_time'] ? format_time($bus['departure_time']) : 'N/A'; ?></p>

            <p style="margin-bottom: 5px; opacity: 0.8;">Harga per Kursi:</p>
            <p style="font-weight: bold; font-size: 16px; color: #FF8040;"><?php echo $bus['price'] ? format_currency($bus['price']) : 'N/A'; ?></p>
        </div>

    </div>
</div>

<?php include '../footer.php'; ?>