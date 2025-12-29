<?php
require_once 'config.php';
include 'header.php';

// Get filter parameters from URL (from index.php form)
$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$travel_date = $_GET['date'] ?? date('Y-m-d');
$bus_type = $_GET['class'] ?? '';
$num_passengers = intval($_GET['passengers'] ?? 1);

// Build query with filters
$query = "SELECT s.*, r.origin, r.destination, bt.name as bus_type_name,
          (s.total_seats - s.available_seats) as booked_seats
          FROM schedules s
          LEFT JOIN routes r ON s.route_id = r.id
          LEFT JOIN bus_types bt ON s.bus_type_id = bt.id
          WHERE s.status = 'active' AND s.departure_date = ?";

$params = [$travel_date];
$types = 's';

if (!empty($origin)) {
    $query .= " AND r.origin = ?";
    $params[] = $origin;
    $types .= 's';
}

if (!empty($destination)) {
    $query .= " AND r.destination = ?";
    $params[] = $destination;
    $types .= 's';
}

if (!empty($bus_type)) {
    $query .= " AND bt.name = ?";
    $params[] = $bus_type;
    $types .= 's';
}

// Check if enough seats available
$query .= " AND s.available_seats >= ?";
$params[] = $num_passengers;
$types .= 'i';

$query .= " ORDER BY s.departure_time ASC";

// Prepare and execute
$stmt = mysqli_prepare($koneksi, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$schedules_result = mysqli_stmt_get_result($stmt);

// Get unique cities from database for dropdown
$cities_query = "SELECT DISTINCT origin as city FROM schedules WHERE status = 'active'
                 UNION
                 SELECT DISTINCT destination as city FROM schedules WHERE status = 'active'
                 ORDER BY city";
$cities_result = mysqli_query($koneksi, $cities_query);
$db_cities = [];
while($row = mysqli_fetch_assoc($cities_result)) {
    $db_cities[] = $row['city'];
}

// Comprehensive list of Indonesian cities
$indonesian_cities = [
    'Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Semarang', 'Makassar', 'Palembang', 'Tangerang',
    'Depok', 'Bekasi', 'Bogor', 'Malang', 'Padang', 'Pekanbaru', 'Banjarmasin', 'Batam',
    'Pontianak', 'Samarinda', 'Denpasar', 'Yogyakarta', 'Solo', 'Cirebon', 'Tasikmalaya',
    'Cimahi', 'Sukabumi', 'Majalengka', 'Garut', 'Purwakarta', 'Subang', 'Indramayu',
    'Kuningan', 'Ciamis', 'Banjar', 'Sumedang', 'Karawang', 'Pangandaran', 'Cianjur',
    'Sukabumi', 'Lebak', 'Pandeglang', 'Serang', 'Tangerang Selatan', 'Cilegon',
    'Purwokerto', 'Cilacap', 'Banyumas', 'Purbalingga', 'Banjarnegara', 'Wonosobo',
    'Magelang', 'Temanggung', 'Kendal', 'Batang', 'Pekalongan', 'Tegal', 'Brebes',
    'Salatiga', 'Boyolali', 'Sukoharjo', 'Wonogiri', 'Karanganyar', 'Sragen', 'Grobogan',
    'Blora', 'Rembang', 'Pati', 'Kudus', 'Jepara', 'Demak', 'Purwodadi', 'Klaten',
    'Sleman', 'Bantul', 'Kulon Progo', 'Gunung Kidul', 'Pacitan', 'Ponorogo', 'Trenggalek',
    'Tulungagung', 'Blitar', 'Kediri', 'Nganjuk', 'Jombang', 'Mojokerto', 'Sidoarjo',
    'Gresik', 'Lamongan', 'Tuban', 'Bojonegoro', 'Ngawi', 'Madiun', 'Magetan', 'Pamekasan',
    'Sumenep', 'Bangkalan', 'Sampang', 'Kediri', 'Probolinggo', 'Situbondo', 'Bondowoso',
    'Banyuwangi', 'Jember', 'Lumajang', 'Pasuruan', 'Batu', 'Surabaya', 'Mojokerto',
    'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo',
    'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik',
    'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar',
    'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan',
    'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang',
    'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan',
    'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo',
    'Surabaya', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro',
    'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri',
    'Blitar', 'Tulungagung', 'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun',
    'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan', 'Gresik', 'Sidoarjo', 'Surabaya',
    'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung', 'Trenggalek',
    'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro', 'Tuban', 'Lamongan',
    'Gresik', 'Sidoarjo', 'Mojokerto', 'Jombang', 'Nganjuk', 'Kediri', 'Blitar', 'Tulungagung',
    'Trenggalek', 'Ponorogo', 'Pacitan', 'Ngawi', 'Madiun', 'Magetan', 'Bojonegoro'
];
// Get bus types from database
$bus_types_query = "SELECT DISTINCT bus_type FROM schedules WHERE status = 'active' ORDER BY bus_type";
$bus_types_result = mysqli_query($koneksi, $bus_types_query);
$bus_types = [];
while($row = mysqli_fetch_assoc($bus_types_result)) {
    $bus_types[] = $row['bus_type'];
}
?>

<style>
    .jadwal-container {
        max-width: 1400px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .jadwal-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 40px;
        border-radius: 12px;
        margin-bottom: 30px;
    }

    .jadwal-header h1 {
        font-size: 2.5rem;
        margin-bottom: 10px;
    }

    .jadwal-header p {
        font-size: 1.1rem;
        opacity: 0.9;
    }

    /* Filter Section */
    .filter-section {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .filter-title {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.3rem;
        margin-bottom: 20px;
        font-weight: 600;
    }

    .filter-form {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        align-items: end;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        margin-bottom: 8px;
        font-weight: 500;
        color: #555;
        font-size: 0.95rem;
    }

    .form-select, .form-input {
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-input:focus {
        outline: none;
        border-color: <?php echo $accent_orange; ?>;
        box-shadow: 0 0 0 3px rgba(230, 115, 0, 0.1);
    }

    .btn-filter {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-filter:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    .btn-reset {
        background: white;
        color: <?php echo $primary_blue; ?>;
        border: 2px solid <?php echo $primary_blue; ?>;
        padding: 10px 25px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-reset:hover {
        background: <?php echo $primary_blue; ?>;
        color: white;
    }

    /* Search Summary */
    .search-summary {
        background: <?php echo $light_cream; ?>;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        border-left: 4px solid <?php echo $accent_orange; ?>;
    }

    .search-summary h3 {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.2rem;
        margin-bottom: 10px;
    }

    .search-info {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        color: #666;
        font-size: 0.95rem;
    }

    .search-info span {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Schedule Cards */
    .schedules-grid {
        display: grid;
        gap: 20px;
    }

    .schedule-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid <?php echo $primary_blue; ?>;
    }

    .schedule-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        border-left-color: <?php echo $accent_orange; ?>;
    }

    .schedule-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 20px;
        padding-bottom: 20px;
        border-bottom: 2px dashed #e0e0e0;
    }

    .route-info {
        flex: 1;
    }

    /* Route Layout */
    .route-main {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .city-origin, .city-destination {
        text-align: center;
        flex: 1;
    }

    .city-name {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .city-origin .city-name {
        color: <?php echo $primary_blue; ?>;
    }

    .city-destination .city-name {
        color: <?php echo $accent_orange; ?>;
    }

    .city-label {
        font-size: 0.9rem;
        color: #666;
    }

    .arrow-container {
        font-size: 2rem;
        color: #666;
        padding: 0 10px;
    }

    .time-info {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        color: #666;
        font-size: 0.95rem;
        margin-top: 10px;
    }

    .time-item {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 8px 12px;
        background: #f8f9fa;
        border-radius: 6px;
    }

    .price-section {
        text-align: right;
    }

    .price-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .price {
        font-size: 2rem;
        font-weight: 700;
        color: <?php echo $accent_orange; ?>;
    }

    .price-per {
        font-size: 0.85rem;
        color: #999;
    }

    .schedule-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }

    .detail-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f8f9fa;
        border-radius: 8px;
    }

    .detail-icon {
        font-size: 1.5rem;
    }

    .detail-content {
        flex: 1;
    }

    .detail-label {
        font-size: 0.85rem;
        color: #666;
    }

    .detail-value {
        font-weight: 600;
        color: #333;
    }

    .bus-type-badge {
        display: inline-block;
        padding: 6px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-ekonomi {
        background: #e3f2fd;
        color: #1976d2;
    }

    .badge-bisnis {
        background: #f3e5f5;
        color: #7b1fa2;
    }

    .badge-executive {
        background: #fff3e0;
        color: #f57c00;
    }

    .badge-premium {
        background: #fce4ec;
        color: #c2185b;
    }

    .badge-vip {
        background: #e8f5e9;
        color: #388e3c;
    }

    .badge-regular {
        background: #f5f5f5;
        color: #616161;
    }

    .facilities {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 20px;
    }

    .facility-tag {
        padding: 6px 12px;
        background: <?php echo $light_cream; ?>;
        border-radius: 20px;
        font-size: 0.85rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .schedule-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .seats-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .seats-available {
        font-weight: 600;
        color: #4CAF50;
    }

    .seats-limited {
        font-weight: 600;
        color: #FF9800;
    }

    .seats-full {
        font-weight: 600;
        color: #f44336;
    }

    .btn-book {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-book:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    .btn-book:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    .no-results {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .no-results-icon {
        font-size: 5rem;
        margin-bottom: 20px;
    }

    .no-results h3 {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .no-results p {
        color: #666;
        font-size: 1.1rem;
        margin-bottom: 20px;
    }

    @media (max-width: 768px) {
        .jadwal-header h1 {
            font-size: 2rem;
        }

        .filter-form {
            grid-template-columns: 1fr;
        }

        .schedule-header {
            flex-direction: column;
            gap: 20px;
        }

        .price-section {
            text-align: left;
            width: 100%;
        }

        .route-main {
            flex-direction: column;
            text-align: center;
        }

        .arrow-container {
            transform: rotate(90deg);
            margin: 10px 0;
        }

        .city-name {
            font-size: 1.5rem;
        }

        .time-info {
            flex-direction: column;
            gap: 10px;
        }

        .schedule-footer {
            flex-direction: column;
            gap: 15px;
            align-items: stretch;
        }

        .btn-book {
            width: 100%;
            text-align: center;
        }
    }
</style>

<div class="jadwal-container">
    <?php echo display_flash(); ?>

    <div class="jadwal-header">
        <h1>🚌 Jadwal Bus Tersedia</h1>
        <p>Pilih jadwal perjalanan yang sesuai dengan kebutuhan Anda</p>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-title">🔍 Filter Pencarian</div>
        <form class="filter-form" method="GET" action="">
            <!-- Kota Asal - Data dari Database -->
            <div class="form-group">
                <label for="origin">Kota Asal</label>
                <select class="form-select" id="origin" name="origin">
                    <option value="">Semua Kota</option>
                    <?php foreach($cities as $city): ?>
                        <option value="<?php echo htmlspecialchars($city); ?>" 
                                <?php echo ($origin == $city) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($city); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Kota Tujuan - Data dari Database -->
            <div class="form-group">
                <label for="destination">Kota Tujuan</label>
                <select class="form-select" id="destination" name="destination">
                    <option value="">Semua Kota</option>
                    <?php foreach($cities as $city): ?>
                        <option value="<?php echo htmlspecialchars($city); ?>"
                                <?php echo ($destination == $city) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($city); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Tanggal Pergi -->
            <div class="form-group">
                <label for="date">Tanggal Pergi</label>
                <input type="date" class="form-input" id="date" name="date" 
                       value="<?php echo htmlspecialchars($travel_date); ?>"
                       min="<?php echo date('Y-m-d'); ?>">
            </div>

            <!-- Kelas Armada - Data dari Database -->
            <div class="form-group">
                <label for="class">Kelas Armada</label>
                <select class="form-select" id="class" name="class">
                    <option value="">Semua Kelas</option>
                    <?php foreach($bus_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>"
                                <?php echo ($bus_type == $type) ? 'selected' : ''; ?>>
                            <?php echo ucfirst(htmlspecialchars($type)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Jumlah Penumpang -->
            <div class="form-group">
                <label for="passengers">Jumlah Penumpang</label>
                <select class="form-select" id="passengers" name="passengers">
                    <option value="1" <?php echo ($num_passengers == 1) ? 'selected' : ''; ?>>1 Penumpang</option>
                    <option value="2" <?php echo ($num_passengers == 2) ? 'selected' : ''; ?>>2 Penumpang</option>
                    <option value="3" <?php echo ($num_passengers == 3) ? 'selected' : ''; ?>>3 Penumpang</option>
                    <option value="4" <?php echo ($num_passengers == 4) ? 'selected' : ''; ?>>4 Penumpang</option>
                    <option value="5" <?php echo ($num_passengers == 5) ? 'selected' : ''; ?>>5+ Penumpang</option>
                </select>
            </div>

            <div class="form-group">
                <label style="visibility: hidden;">Action</label>
                <button type="submit" class="btn-filter">🔍 Cari</button>
            </div>

            <div class="form-group">
                 <label style="visibility: hidden;">Reset</label>
                 <a href="<?php echo $_SERVER['PHP_SELF']; ?>" class="btn-reset">🔄 Reset</a>
            </div>
        </form>
    </div>

    <!-- Search Summary -->
    <?php if (!empty($origin) || !empty($destination) || !empty($bus_type)): ?>
    <div class="search-summary">
        <h3>📋 Hasil Pencarian:</h3>
        <div class="search-info">
            <?php if (!empty($origin)): ?>
                <span>📍 Asal: <strong><?php echo htmlspecialchars($origin); ?></strong></span>
            <?php endif; ?>
            <?php if (!empty($destination)): ?>
                <span>📍 Tujuan: <strong><?php echo htmlspecialchars($destination); ?></strong></span>
            <?php endif; ?>
            <span>📅 Tanggal: <strong><?php echo format_date($travel_date); ?></strong></span>
            <?php if (!empty($bus_type)): ?>
                <span>🚌 Kelas: <strong><?php echo ucfirst($bus_type); ?></strong></span>
            <?php endif; ?>
            <span>👥 Penumpang: <strong><?php echo $num_passengers; ?> orang</strong></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Schedule Cards -->
    <div class="schedules-grid">
        <?php if (mysqli_num_rows($schedules_result) > 0): ?>
            <?php while($schedule = mysqli_fetch_assoc($schedules_result)): 
                $seats_percentage = ($schedule['available_seats'] / $schedule['total_seats']) * 100;
                $seats_class = $seats_percentage > 50 ? 'seats-available' : ($seats_percentage > 20 ? 'seats-limited' : 'seats-full');
            ?>
                <div class="schedule-card">
                    <div class="schedule-header">
                        <div class="route-info">
                            <!-- TAMPILKAN KOTA ASAL DAN TUJUAN DARI DATABASE -->
                            <div class="route-main">
                                <div class="city-origin">
                                    <div class="city-label">Kota Asal</div>
                                    <div class="city-name"><?php echo htmlspecialchars($schedule['origin']); ?></div>
                                </div>
                                
                                <div class="arrow-container">
                                    →
                                </div>
                                
                                <div class="city-destination">
                                    <div class="city-label">Kota Tujuan</div>
                                    <div class="city-name"><?php echo htmlspecialchars($schedule['destination']); ?></div>
                                </div>
                            </div>
                            
                            <div class="time-info">
                                <div class="time-item">
                                    🕐 Berangkat: <strong><?php echo format_time($schedule['departure_time']); ?></strong>
                                </div>
                                <div class="time-item">
                                    🕐 Tiba: <strong><?php echo format_time($schedule['arrival_time']); ?></strong>
                                </div>
                                <?php if (!empty($schedule['duration'])): ?>
                                    <div class="time-item">
                                        ⏱️ Durasi: <strong><?php echo htmlspecialchars($schedule['duration']); ?></strong>
                                    </div>
                                <?php endif; ?>
                                <div class="time-item">
                                    🚌 Bus: <strong><?php echo htmlspecialchars($schedule['bus_number'] ?? 'N/A'); ?></strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="price-section">
                            <div class="price-label">Harga per kursi</div>
                            <div class="price"><?php echo format_currency($schedule['price']); ?></div>
                            <div class="price-per">Total: <?php echo format_currency($schedule['price'] * $num_passengers); ?></div>
                        </div>
                    </div>

                    <div class="schedule-details">
                        <div class="detail-item">
                            <div class="detail-icon">🚌</div>
                            <div class="detail-content">
                                <div class="detail-label">Kelas Bus</div>
                                <div class="detail-value">
                                    <?php 
                                    $badge_mapping = [
                                        'Premium' => 'badge-premium',
                                        'Executive' => 'badge-executive',
                                        'VIP' => 'badge-vip',
                                        'Regular' => 'badge-regular',
                                        'Ekonomi' => 'badge-ekonomi',
                                        'Bisnis' => 'badge-bisnis'
                                    ];
                                    $badge_class = $badge_mapping[$schedule['bus_type']] ?? 'badge-regular';
                                    ?>
                                    <span class="bus-type-badge <?php echo $badge_class; ?>">
                                        <?php echo ucfirst($schedule['bus_type']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">📍</div>
                            <div class="detail-content">
                                <div class="detail-label">Rute</div>
                                <div class="detail-value">
                                    <?php echo htmlspecialchars($schedule['origin']); ?> → <?php echo htmlspecialchars($schedule['destination']); ?>
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">💺</div>
                            <div class="detail-content">
                                <div class="detail-label">Kursi Tersedia</div>
                                <div class="detail-value <?php echo $seats_class; ?>">
                                    <?php echo $schedule['available_seats']; ?> / <?php echo $schedule['total_seats']; ?>
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">📅</div>
                            <div class="detail-content">
                                <div class="detail-label">Tanggal Keberangkatan</div>
                                <div class="detail-value"><?php echo format_date($schedule['departure_date']); ?></div>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($schedule['facilities'])): ?>
                    <div class="facilities">
                        <?php
                        $facilities = explode(',', $schedule['facilities']);
                        $facility_icons = [
                            'AC' => '❄️',
                            'Toilet' => '🚻',
                            'WiFi' => '📶',
                            'Snack' => '🍪',
                            'Reclining Seat' => '🛋️',
                            'TV' => '📺',
                            'Charger' => '🔌'
                        ];
                        foreach($facilities as $facility):
                            $facility = trim($facility);
                            $icon = $facility_icons[$facility] ?? '✓';
                        ?>
                            <span class="facility-tag"><?php echo $icon; ?> <?php echo htmlspecialchars($facility); ?></span>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="schedule-footer">
                        <div class="seats-info">
                            <?php if ($schedule['available_seats'] >= $num_passengers): ?>
                                <span class="<?php echo $seats_class; ?>">
                                    ✓ Tersedia untuk <?php echo $num_passengers; ?> penumpang
                                </span>
                            <?php else: ?>
                                <span class="seats-full">
                                    ⚠️ Kursi tidak mencukupi
                                </span>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Link Book Sekarang dengan parameter dari database -->
                        <a href="booking/book-detail.php?schedule_id=<?php echo $schedule['id']; ?>&origin=<?php echo urlencode($schedule['origin']); ?>&destination=<?php echo urlencode($schedule['destination']); ?>&travel_date=<?php echo urlencode($travel_date); ?>&num_passengers=<?php echo $num_passengers; ?>" 
                           class="btn-book" 
                           <?php echo ($schedule['available_seats'] < $num_passengers) ? 'onclick="return false;" style="opacity:0.5; cursor:not-allowed;"' : ''; ?>>
                            🎫 Book Sekarang
                        </a>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-results">
                <div class="no-results-icon">😔</div>
                <h3>Tidak Ada Jadwal Ditemukan</h3>
                <p>Maaf, tidak ada jadwal bus yang sesuai dengan kriteria pencarian Anda.</p>
                <p>Silakan coba dengan filter yang berbeda atau pilih tanggal lain.</p>
                <a href="<?php echo basename($_SERVER['PHP_SELF']); ?>" class="btn-reset" style="margin-top: 20px;">🔄 Reset Pencarian</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Form validation
document.querySelector('.filter-form').addEventListener('submit', function(e) {
    const origin = document.getElementById('origin').value;
    const destination = document.getElementById('destination').value;
    
    if (origin && destination && origin === destination) {
        e.preventDefault();
        alert('Kota asal dan tujuan tidak boleh sama!');
        return false;
    }
});

// Set minimum date to today
document.getElementById('date').min = new Date().toISOString().split('T')[0];
</script>

<?php include 'footer.php'; ?>