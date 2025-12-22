<?php
require_once '../../config.php';
include '../header-admin.php'; 
?>

<style>
    body > div {
        margin-top: 70px;
        padding: 20px;
    }
</style>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $date = $_POST['operational_date'];
    $time = $_POST['departure_time'];
    $bus_type = $_POST['bus_type'];
    $price = $_POST['price'];
    $seats = $_POST['total_seats'];

    $query = "INSERT INTO schedules (origin, destination, operational_date, departure_time, bus_type, price, total_seats, available_seats, status) 
              VALUES ('$origin', '$destination', '$date', '$time', '$bus_type', '$price', '$seats', '$seats', 'active')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Jadwal Berhasil Ditambahkan!'); window.location='index.php';</script>";
    }
}
?>

<div style="max-width: 800px; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
    <h2 style="color: <?php echo $primary_blue; ?>; border-bottom: 2px solid <?php echo $accent_orange; ?>; padding-bottom: 10px;">Tambah Jadwal Baru</h2>
    <form action="" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
        <div>
            <label>Kota Asal</label>
            <input type="text" name="origin" required style="width:100%; padding:10px; border:1px solid #ddd;">
        </div>
        <div>
            <label>Kota Tujuan</label>
            <input type="text" name="destination" required style="width:100%; padding:10px; border:1px solid #ddd;">
        </div>
        <div>
            <label>Tanggal Operasional</label>
            <input type="date" name="operational_date" required style="width:100%; padding:10px; border:1px solid #ddd;">
        </div>
        <div>
            <label>Jam Keberangkatan</label>
            <input type="time" name="departure_time" required style="width:100%; padding:10px; border:1px solid #ddd;">
        </div>
        <div>
            <label>Tipe Bus</label>
            <select name="bus_type" style="width:100%; padding:10px; border:1px solid #ddd;">
                <option value="economy">Economy</option>
                <option value="patas">Patas</option>
                <option value="executive">Executive</option>
            </select>
        </div>
        <div>
            <label>Harga Tiket (Rp)</label>
            <input type="number" name="price" required style="width:100%; padding:10px; border:1px solid #ddd;">
        </div>
        <div style="grid-column: span 2;">
            <label>Total Kursi</label>
            <input type="number" name="total_seats" value="45" style="width:100%; padding:10px; border:1px solid #ddd;">
        </div>
        <button type="submit" style="grid-column: span 2; background: <?php echo $accent_orange; ?>; color: white; border: none; padding: 15px; font-weight: bold; cursor: pointer;">SIMPAN JADWAL</button>
    </form>
</div>