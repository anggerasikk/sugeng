<?php
// Handle PDF download FIRST - before any output or includes
if (isset($_GET['download']) && $_GET['download'] == 'pdf' && isset($_GET['code'])) {
    // Clean any previous output and start fresh output buffering
    ob_clean();
    ob_start();

    // Minimal includes for PDF generation - no headers or footers
    // Start session without config to avoid any output
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Database connection only (avoid config.php which may output errors)
    $koneksi = new mysqli('localhost', 'root', '', 'sugeng');
    if ($koneksi->connect_error) {
        die("Connection failed: " . $koneksi->connect_error);
    }
    $koneksi->set_charset("utf8mb4");

    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: ../auth/login.php');
        exit();
    }

    $booking_code = $_GET['code'];
    $user_id = $_SESSION['user_id'];

// Verify booking belongs to user and is paid
$query = "SELECT b.*, s.departure_time, s.arrival_time, r.origin, r.destination, bt.name as bus_type, s.price, b.created_at
          FROM bookings b
          JOIN schedules s ON b.schedule_id = s.id
          JOIN routes r ON s.route_id = r.id
          JOIN bus_types bt ON s.bus_type_id = bt.id
          WHERE b.booking_code = ? AND b.user_id = ? AND b.payment_status = 'paid'";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "si", $booking_code, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        header('Location: profile.php');
        exit();
    }

    $booking = mysqli_fetch_assoc($result);

    // Include Composer autoloader for TCPDF
    require_once '../vendor/autoload.php';

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Bus Ticket System');
    $pdf->SetAuthor('Bus Company');
    $pdf->SetTitle('E-Ticket - ' . $booking['booking_code']);
    $pdf->SetSubject('Bus Ticket');

    // Remove default header/footer
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    // Set margins
    $pdf->SetMargins(15, 15, 15);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Add a page
    $pdf->AddPage();

    // Main ticket background with border
    $pdf->SetFillColor(233, 247, 255);
    $pdf->Rect(10, 10, 190, 75, 'FD');
    $pdf->SetDrawColor(0, 119, 255);
    $pdf->SetLineWidth(0.5);
    $pdf->Rect(10, 10, 190, 75);

    // Header section with blue background
    $pdf->SetFillColor(0, 119, 255);
    $pdf->Rect(15, 15, 170, 12, 'F');
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 12);
    $pdf->SetXY(15, 16);
    $pdf->Cell(120, 10, 'BUS TICKET SYSTEM', 0, 0, 'L');
    $pdf->Cell(50, 10, $booking['seat_numbers'] ?: $booking['seats_booked'] . 'A', 0, 1, 'R');

    // Left section (70% width) - Passenger and Travel Details
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetXY(15, 32);

    // Passenger Information Section
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(248, 249, 250);
    $pdf->Cell(170, 6, 'INFORMASI PENUMPANG', 1, 1, 'L', true);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(35, 5, 'Nama:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(45, 5, $booking['passenger_name'], 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(25, 5, 'Kelas:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, $booking['bus_type'], 0, 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(35, 5, 'No. Booking:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(45, 5, $booking['booking_code'], 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(25, 5, 'Tanggal:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, date('d F Y', strtotime($booking['created_at'])), 0, 1);

    // Travel Information Section
    $pdf->Ln(2);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(248, 249, 250);
    $pdf->Cell(170, 6, 'INFORMASI PERJALANAN', 1, 1, 'L', true);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(35, 5, 'Berangkat:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, date('l, d F Y H:i', strtotime($booking['departure_time'])), 0, 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(35, 5, 'Dari:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(65, 5, 'Terminal ' . $booking['origin'] . ' (' . strtoupper(substr($booking['origin'], 0, 3)) . ')', 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(15, 5, 'Ke:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, 'Terminal ' . $booking['destination'] . ' (' . strtoupper(substr($booking['destination'], 0, 3)) . ')', 0, 1);

    // Right section (30% width) - Boarding Pass with border
    $pdf->SetFillColor(248, 251, 255);
    $pdf->Rect(130, 32, 55, 53, 'FD');
    $pdf->SetDrawColor(0, 89, 214);
    $pdf->SetLineWidth(0.3);
    $pdf->Rect(130, 32, 55, 53);

    // Boarding Pass header
    $pdf->SetFillColor(0, 89, 214);
    $pdf->Rect(130, 32, 55, 8, 'F');
    $pdf->SetTextColor(255, 255, 255);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetXY(130, 33);
    $pdf->Cell(55, 6, 'BOARDING PASS', 0, 1, 'C');

    // Seat number (prominent)
    $pdf->SetTextColor(0, 89, 214);
    $pdf->SetFont('helvetica', 'B', 20);
    $pdf->SetXY(130, 42);
    $pdf->Cell(55, 12, $booking['seat_numbers'] ?: $booking['seats_booked'] . 'A', 0, 1, 'C');

    // Passenger details in boarding pass
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->SetXY(130, 56);
    $pdf->Cell(55, 4, $booking['passenger_name'], 0, 1, 'C');

    $pdf->SetFont('helvetica', '', 7);
    $pdf->SetXY(130, 61);
    $pdf->Cell(55, 3, strtoupper($booking['origin']) . ' -> ' . strtoupper($booking['destination']), 0, 1, 'C');
    $pdf->Cell(55, 3, 'Kelas: ' . $booking['bus_type'], 0, 1, 'C');
    $pdf->Cell(55, 3, 'Gate: 06', 0, 1, 'C');

    // Barcode simulation with better design - more visible
    $pdf->SetXY(130, 72);
    $pdf->SetFillColor(0, 0, 0);
    $pdf->SetDrawColor(0, 0, 0);
    $pdf->SetLineWidth(0.1);
    for ($i = 0; $i < 35; $i++) {
        $width = rand(1, 5) * 0.3;
        $height = 8;
        $pdf->Cell($width, $height, '', 0, 0, 'C', true);
        $pdf->Cell(0.1, $height, '', 0, 0, 'C', false);
    }

    // Detailed Information Section (below the ticket)
    $pdf->SetXY(15, 95);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(0, 8, 'DETAIL BOOKING LENGKAP', 1, 1, 'C', true);

    $pdf->SetFont('helvetica', '', 9);
    $pdf->Ln(2);

    // Two columns for details
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Kode Booking:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(50, 5, $booking['booking_code'], 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(30, 5, 'Jumlah Kursi:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, $booking['seats_booked'] . ' kursi', 0, 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Nama Penumpang:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(50, 5, $booking['passenger_name'], 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(30, 5, 'Nomor Kursi:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, $booking['seat_numbers'] ?: 'Belum ditentukan', 0, 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Telepon:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(50, 5, $booking['passenger_phone'], 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(30, 5, 'Email:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, $booking['passenger_email'], 0, 1);

    $pdf->Ln(3);

    // Travel Details Section
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->SetFillColor(240, 240, 240);
    $pdf->Cell(0, 8, 'DETAIL PERJALANAN', 1, 1, 'C', true);

    $pdf->SetFont('helvetica', '', 9);
    $pdf->Ln(2);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Rute:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, $booking['origin'] . ' -> ' . $booking['destination'], 0, 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Keberangkatan:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(50, 5, date('d M Y H:i', strtotime($booking['departure_time'])), 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(25, 5, 'Kedatangan:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, date('d M Y H:i', strtotime($booking['arrival_time'])), 0, 1);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Tipe Bus:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(50, 5, $booking['bus_type'], 0, 0);

    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(25, 5, 'Harga/Kursi:', 0, 0);
    $pdf->SetFont('helvetica', '', 9);
    $pdf->Cell(0, 5, 'Rp ' . number_format($booking['price'], 0, ',', '.'), 0, 1);

    $total_price = $booking['price'] * $booking['seats_booked'];
    $pdf->SetFont('helvetica', 'B', 8);
    $pdf->Cell(40, 5, 'Total Pembayaran:', 0, 0);
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->SetTextColor(0, 89, 214);
    $pdf->Cell(0, 5, 'Rp ' . number_format($total_price, 0, ',', '.'), 0, 1);

    $pdf->Ln(5);

    // Terms and Conditions
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetFont('helvetica', 'B', 9);
    $pdf->SetFillColor(255, 248, 220);
    $pdf->Cell(0, 6, 'CATATAN PENTING', 1, 1, 'C', true);

    $pdf->SetFont('helvetica', '', 8);
    $pdf->Ln(1);
    $pdf->MultiCell(0, 4, "• Datanglah ke terminal keberangkatan minimal 30 menit sebelum waktu keberangkatan\n• Bawa e-tiket ini dan KTP asli untuk proses check-in\n• Tiket tidak dapat dikembalikan dan tidak dapat dipindahtangankan\n• Untuk perubahan jadwal atau pembatalan, hubungi customer service", 0, 'L');

    $pdf->Ln(3);

    // Footer
    $pdf->SetFont('helvetica', 'I', 8);
    $pdf->SetTextColor(128, 128, 128);
    $pdf->Cell(0, 4, 'E-Ticket ini dibuat pada: ' . date('d F Y H:i:s'), 0, 1, 'C');
    $pdf->Cell(0, 4, 'Terima kasih telah memilih layanan Bus Sugeng Rahayu', 0, 1, 'C');

    // Output PDF - this must be the ONLY output
    $pdf->Output('e-ticket-' . $booking['booking_code'] . '.pdf', 'D');
    exit();
}

// Start normal page processing
session_start();
require_once '../config.php';
require_once '../includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Check if booking code is provided
if (!isset($_GET['code'])) {
    header('Location: profile.php');
    exit();
}

$booking_code = $_GET['code'];
$user_id = $_SESSION['user_id'];

// Verify booking belongs to user and is paid
$query = "SELECT b.*, s.departure_time, s.arrival_time, r.origin, r.destination, bt.name as bus_type, s.price, b.created_at
          FROM bookings b
          JOIN schedules s ON b.schedule_id = s.id
          JOIN routes r ON s.route_id = r.id
          JOIN bus_types bt ON s.bus_type_id = bt.id
          WHERE b.booking_code = ? AND b.user_id = ? AND b.payment_status = 'paid'";

$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "si", $booking_code, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 0) {
    header('Location: profile.php');
    exit();
}

$booking = mysqli_fetch_assoc($result);

// If not downloading PDF, show HTML page
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket - <?php echo $booking['booking_code']; ?></title>
    <style>
        body {
            background:#f2f2f2;
            font-family: Arial, sans-serif;
        }

        .ticket {
            width: 900px;
            height: 260px;
            margin: 40px auto;
            background: linear-gradient(to right, #e9f7ff, #ffffff);
            border-radius: 15px;
            display: flex;
            box-shadow: 0 10px 25px rgba(0,0,0,.2);
            overflow: hidden;
        }

        /* KIRI */
        .ticket-left {
            width: 70%;
            padding: 20px;
        }

        .header {
            background: linear-gradient(to right, #0077ff, #004bb5);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header span {
            font-weight: bold;
            letter-spacing: 1px;
        }

        .content {
            margin-top: 15px;
            font-size: 14px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .label {
            color: #555;
            font-size: 12px;
        }

        .value {
            font-weight: bold;
            color: #003b8e;
        }

        /* PEMISAH */
        .divider {
            width: 2px;
            background: repeating-linear-gradient(
                to bottom,
                #999,
                #999 6px,
                transparent 6px,
                transparent 12px
            );
        }

        /* KANAN */
        .ticket-right {
            width: 30%;
            padding: 20px;
            background: #f8fbff;
            text-align: center;
        }

        .boarding {
            background: #0059d6;
            color: white;
            padding: 8px;
            border-radius: 8px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .seat {
            font-size: 36px;
            font-weight: bold;
            color: #0059d6;
        }

        .info {
            font-size: 13px;
            margin-top: 10px;
        }

        .barcode {
            margin-top: 15px;
            height: 60px;
            background: repeating-linear-gradient(
                90deg,
                #000 0px,
                #000 1px,
                transparent 1px,
                transparent 2px,
                #000 2px,
                #000 4px,
                transparent 4px,
                transparent 5px,
                #000 5px,
                #000 8px,
                transparent 8px,
                transparent 9px,
                #000 9px,
                #000 10px,
                transparent 10px,
                transparent 11px,
                #000 11px,
                #000 14px,
                transparent 14px,
                transparent 15px,
                #000 15px,
                #000 16px,
                transparent 16px,
                transparent 17px,
                #000 17px,
                #000 20px,
                transparent 20px,
                transparent 21px,
                #000 21px,
                #000 22px,
                transparent 22px,
                transparent 23px,
                #000 23px,
                #000 26px,
                transparent 26px,
                transparent 27px,
                #000 27px,
                #000 28px,
                transparent 28px,
                transparent 29px,
                #000 29px,
                #000 32px,
                transparent 32px,
                transparent 33px,
                #000 33px,
                #000 34px,
                transparent 34px,
                transparent 35px,
                #000 35px,
                #000 38px,
                transparent 38px,
                transparent 39px,
                #000 39px,
                #000 40px,
                transparent 40px,
                transparent 41px,
                #000 41px,
                #000 44px,
                transparent 44px,
                transparent 45px,
                #000 45px,
                #000 46px,
                transparent 46px,
                transparent 47px,
                #000 47px,
                #000 50px,
                transparent 50px,
                transparent 51px,
                #000 51px,
                #000 52px,
                transparent 52px,
                transparent 53px,
                #000 53px,
                #000 56px,
                transparent 56px,
                transparent 57px,
                #000 57px,
                #000 58px,
                transparent 58px,
                transparent 59px,
                #000 59px,
                #000 62px,
                transparent 62px,
                transparent 63px
            );
        }

        .download-section {
            text-align: center;
            margin-top: 20px;
        }

        .download-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }

        .download-btn:hover {
            background: #218838;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <?php include '../header.php'; ?>

    <div class="ticket">
        <!-- KIRI -->
        <div class="ticket-left">
            <div class="header">
                <span>BUS TICKET SYSTEM</span>
                <span><?php echo $booking['seat_numbers'] ?: $booking['seats_booked'] . 'A'; ?></span>
            </div>

            <div class="content">
                <div class="row">
                    <div>
                        <div class="label">NAMA PEMESAN</div>
                        <div class="value"><?php echo $booking['passenger_name']; ?></div>
                    </div>
                    <div>
                        <div class="label">KELAS</div>
                        <div class="value"><?php echo $booking['bus_type']; ?></div>
                    </div>
                    <div>
                        <div class="label">TANGGAL PEMESANAN</div>
                        <div class="value"><?php echo date('d F Y', strtotime($booking['created_at'])); ?></div>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <div class="label">BERANGKAT</div>
                        <div class="value"><?php echo date('l', strtotime($booking['departure_time'])); ?>, <?php echo date('d F Y', strtotime($booking['departure_time'])); ?></div>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <div class="label">BERANGKAT DARI</div>
                        <div class="value">Terminal <?php echo $booking['origin']; ?> (<?php echo strtoupper(substr($booking['origin'], 0, 3)); ?>)</div>
                    </div>
                    <div>
                        <div class="label">TIBA DI</div>
                        <div class="value">Terminal <?php echo $booking['destination']; ?> (<?php echo strtoupper(substr($booking['destination'], 0, 3)); ?>)</div>
                    </div>
                </div>

                <div class="row">
                    <div>
                        <div class="label">NO BOOKING</div>
                        <div class="value"><?php echo $booking['booking_code']; ?></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PEMISAH -->
        <div class="divider"></div>

        <!-- KANAN -->
        <div class="ticket-right">
            <div class="boarding">BOARDING PASS</div>
            <div class="seat"><?php echo $booking['seat_numbers'] ?: $booking['seats_booked'] . 'A'; ?></div>

            <div class="info">
                <strong><?php echo $booking['passenger_name']; ?></strong><br>
                <b><?php echo strtoupper($booking['origin']); ?> -> <?php echo strtoupper($booking['destination']); ?></b><br>
                Kelas: <?php echo $booking['bus_type']; ?><br>
                Gate: 06
            </div>

            <div class="barcode"></div>
        </div>
    </div>

    <div class="download-section">
        <a href="?code=<?php echo urlencode($booking_code); ?>&download=pdf" class="download-btn">
            📄 Download PDF
        </a>
    </div>

    <?php include '../footer.php'; ?>
</body>
</html>
