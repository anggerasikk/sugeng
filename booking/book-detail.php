<?php
require_once '../config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_flash('error', 'Silakan login terlebih dahulu untuk melakukan booking');
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    redirect(base_url('beranda/signin.php'));
}

$schedule_id = intval($_GET['schedule_id'] ?? 0);
$origin = $_GET['origin'] ?? '';
$destination = $_GET['destination'] ?? '';
$travel_date = $_GET['travel_date'] ?? date('Y-m-d');
$num_passengers = intval($_GET['num_passengers'] ?? 1);

if (!$schedule_id) {
    set_flash('error', 'Jadwal tidak valid');
    redirect(base_url('jadwal.php'));
}

// Get schedule details
$query = "SELECT s.*, r.origin, r.destination FROM schedules s LEFT JOIN routes r ON s.route_id = r.id WHERE s.id = ? AND s.status = 'active'";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $schedule_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$schedule = mysqli_fetch_assoc($result);

if (!$schedule) {
    set_flash('error', 'Jadwal tidak ditemukan');
    redirect(base_url('jadwal.php'));
}

// Get booked seats for this schedule
$booked_query = "SELECT seat_numbers FROM bookings WHERE schedule_id = ? AND booking_status NOT IN ('cancelled')";
$booked_stmt = mysqli_prepare($koneksi, $booked_query);
mysqli_stmt_bind_param($booked_stmt, "i", $schedule_id);
mysqli_stmt_execute($booked_stmt);
$booked_result = mysqli_stmt_get_result($booked_stmt);

$booked_seats = [];
while ($row = mysqli_fetch_assoc($booked_result)) {
    if (!empty($row['seat_numbers'])) {
        $seats = explode(',', $row['seat_numbers']);
        $booked_seats = array_merge($booked_seats, $seats);
    }
}

include '../header.php';
?>

<style>
    .booking-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .booking-header {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        text-align: center;
    }

    .booking-header h1 {
        font-size: 2rem;
        margin-bottom: 10px;
    }

    .booking-content {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 30px;
    }

    .booking-form {
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-title {
        color: <?php echo $primary_blue; ?>;
        font-size: 1.3rem;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid <?php echo $accent_orange; ?>;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #555;
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus {
        outline: none;
        border-color: <?php echo $accent_orange; ?>;
        box-shadow: 0 0 0 3px rgba(230, 115, 0, 0.1);
    }

    /* Seat Selection */
    .seat-selection {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .time-slots {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .time-slot {
        padding: 10px 20px;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .time-slot.active {
        background: <?php echo $primary_blue; ?>;
        color: white;
        border-color: <?php echo $primary_blue; ?>;
    }

    .seats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-bottom: 20px;
    }

    .seat {
        aspect-ratio: 1;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        background: #4CAF50;
        color: white;
    }

    .seat.booked {
        background: <?php echo $accent_orange; ?>;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .seat.selected {
        background: <?php echo $primary_blue; ?>;
        border-color: <?php echo $primary_blue; ?>;
        transform: scale(1.1);
    }

    .seat-legend {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-bottom: 20px;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }

    .legend-box {
        width: 30px;
        height: 30px;
        border-radius: 5px;
    }

    .legend-box.available {
        background: #4CAF50;
    }

    .legend-box.booked {
        background: <?php echo $accent_orange; ?>;
    }

    .legend-box.selected {
        background: <?php echo $primary_blue; ?>;
    }

    /* Summary Box */
    .summary-box {
        background: linear-gradient(135deg, <?php echo $primary_blue; ?>, <?php echo $secondary_blue; ?>);
        color: white;
        padding: 25px;
        border-radius: 12px;
        height: fit-content;
        position: sticky;
        top: 20px;
    }

    .summary-title {
        border-bottom: 1px solid rgba(255,255,255,0.2);
        padding-bottom: 15px;
        margin-bottom: 15px;
        font-size: 1.3rem;
    }

    .summary-item {
        margin-bottom: 15px;
    }

    .summary-label {
        opacity: 0.8;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .summary-value {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .summary-total {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 2px solid rgba(255,255,255,0.3);
    }

    .total-label {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .total-value {
        font-size: 2rem;
        font-weight: 700;
        color: <?php echo $accent_orange; ?>;
    }

    .btn-submit {
        background: <?php echo $accent_orange; ?>;
        color: white;
        border: none;
        padding: 15px 30px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100%;
    }

    .btn-submit:hover {
        background: #e67300;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(230, 115, 0, 0.3);
    }

    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
        transform: none;
    }

    @media (max-width: 968px) {
        .booking-content {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .seats-grid {
            grid-template-columns: repeat(4, 1fr);
        }
    }
</style>

<div class="booking-container">
    <?php echo display_flash(); ?>

    <div class="booking-header">
        <h1>📋 Form Reservasi Tiket Bus</h1>
        <p>Pesan Tiket Perjalanan Anda dengan Mudah</p>
    </div>

    <form id="bookingForm" action="confirm-booking.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <input type="hidden" name="schedule_id" value="<?php echo $schedule_id; ?>">
        <input type="hidden" name="travel_date" value="<?php echo htmlspecialchars($travel_date); ?>">
        <input type="hidden" name="num_passengers" value="<?php echo $num_passengers; ?>">
        <input type="hidden" name="selected_seats" id="selected_seats" value="">

        <div class="booking-content">
            <!-- Left Column: Form -->
            <div class="booking-form">
                <!-- Asal & Tujuan -->
                <div class="form-section">
                    <h3 class="section-title">📍 Asal Keberangkatan & Tujuan</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Asal Keberangkatan</label>
                            <input type="text" class="form-input" value="<?php echo htmlspecialchars($schedule['origin']); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Tujuan</label>
                            <input type="text" class="form-input" value="<?php echo htmlspecialchars($schedule['destination']); ?>" readonly>
                        </div>
                    </div>
                </div>

                <!-- Tanggal & Jumlah Penumpang -->
                <div class="form-section">
                    <h3 class="section-title">📅 Tanggal Berangkat & Jumlah Penumpang</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Tanggal Berangkat</label>
                            <input type="text" class="form-input" value="<?php echo format_date($travel_date); ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Jumlah Penumpang</label>
                            <input type="text" class="form-input" value="<?php echo $num_passengers; ?> Penumpang" readonly>
                        </div>
                    </div>
                </div>

                <!-- Pilih Jadwal Keberangkatan -->
                <div class="form-section">
                    <h3 class="section-title">🕐 Pilih Jadwal Keberangkatan</h3>
                    <div class="time-slots">
                        <div class="time-slot active">
                            <?php echo format_time($schedule['departure_time']); ?>
                        </div>
                    </div>
                </div>

                <!-- Pilih Kursi -->
                <div class="form-section">
                    <h3 class="section-title">💺 Pilih Kursi</h3>
                    <div class="seat-selection">
                        <div class="seat-legend">
                            <div class="legend-item">
                                <div class="legend-box available"></div>
                                <span>Tersedia</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-box booked"></div>
                                <span>Terisi</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-box selected"></div>
                                <span>Dipilih</span>
                            </div>
                        </div>

                        <div class="seats-grid" id="seatsGrid">
                            <?php
                            $rows = ['A', 'B', 'C', 'D', 'E'];
                            $cols = 8;
                            $seat_number = 1;
                            
                            for ($col = 1; $col <= $cols; $col++) {
                                for ($row = 0; $row < 4; $row++) {
                                    if ($row == 2) continue; // Skip for aisle
                                    
                                    $seat_name = $seat_number;
                                    $is_booked = in_array($seat_name, $booked_seats);
                                    $class = $is_booked ? 'seat booked' : 'seat';
                                    
                                    echo '<div class="' . $class . '" data-seat="' . $seat_name . '">' . $seat_name . '</div>';
                                    $seat_number++;
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Data Penumpang -->
                <div class="form-section">
                    <h3 class="section-title">👤 Data Penumpang</h3>
                    <div class="form-group">
                        <label>Nama Lengkap (Sesuai KTP) *</label>
                        <input type="text" class="form-input" name="passenger_name" placeholder="Contoh: John Doe" required>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nomor Identitas/NIK *</label>
                            <input type="text" class="form-input" name="passenger_identity" placeholder="327xxxxxxxxxxx" required>
                        </div>
                        <div class="form-group">
                            <label>Nomor WhatsApp *</label>
                            <input type="tel" class="form-input" name="passenger_phone" placeholder="08123456789" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit" id="btnSubmit" disabled>
                    💳 Lanjutkan ke Pembayaran
                </button>
            </div>

            <!-- Right Column: Summary -->
            <div class="summary-box">
                <h3 class="summary-title">📋 Ringkasan Pesanan</h3>

                <div class="summary-item">
                    <div class="summary-label">Kelas Bus:</div>
                    <div class="summary-value"><?php echo ucfirst($schedule['bus_type']); ?></div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">Rute:</div>
                    <div class="summary-value">
                        <?php echo htmlspecialchars($schedule['origin']); ?> → 
                        <?php echo htmlspecialchars($schedule['destination']); ?>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">Keberangkatan:</div>
                    <div class="summary-value">
                        <?php echo format_date($travel_date); ?> | 
                        <?php echo format_time($schedule['departure_time']); ?>
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">Kursi Dipilih:</div>
                    <div class="summary-value" id="selectedSeatsDisplay">Belum dipilih</div>
                </div>

                <div class="summary-item">
                    <div class="summary-label">Harga per Kursi:</div>
                    <div class="summary-value"><?php echo format_currency($schedule['price']); ?></div>
                </div>

                <div class="summary-total">
                    <div class="total-label">Total Pembayaran:</div>
                    <div class="total-value" id="totalPrice">
                        <?php echo format_currency($schedule['price'] * $num_passengers); ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
const numPassengers = <?php echo $num_passengers; ?>;
const pricePerSeat = <?php echo $schedule['price']; ?>;
const bookedSeats = <?php echo json_encode($booked_seats); ?>;
let selectedSeats = [];

// Seat selection
document.querySelectorAll('.seat:not(.booked)').forEach(seat => {
    seat.addEventListener('click', function() {
        const seatNumber = this.dataset.seat;
        
        if (this.classList.contains('selected')) {
            // Deselect
            this.classList.remove('selected');
            selectedSeats = selectedSeats.filter(s => s !== seatNumber);
        } else {
            // Check if already selected max seats
            if (selectedSeats.length >= numPassengers) {
                alert(`Anda hanya dapat memilih ${numPassengers} kursi`);
                return;
            }
            // Select
            this.classList.add('selected');
            selectedSeats.push(seatNumber);
        }
        
        updateSummary();
    });
});

function updateSummary() {
    // Update selected seats display
    const seatsDisplay = document.getElementById('selectedSeatsDisplay');
    if (selectedSeats.length > 0) {
        seatsDisplay.textContent = selectedSeats.join(', ');
    } else {
        seatsDisplay.textContent = 'Belum dipilih';
    }
    
    // Update hidden input
    document.getElementById('selected_seats').value = selectedSeats.join(',');
    
    // Enable/disable submit button
    const btnSubmit = document.getElementById('btnSubmit');
    if (selectedSeats.length === numPassengers) {
        btnSubmit.disabled = false;
        btnSubmit.textContent = '💳 Lanjutkan ke Pembayaran';
    } else {
        btnSubmit.disabled = true;
        btnSubmit.textContent = `Pilih ${numPassengers - selectedSeats.length} kursi lagi`;
    }
}

// Form validation
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    if (selectedSeats.length !== numPassengers) {
        e.preventDefault();
        alert(`Harap pilih ${numPassengers} kursi sebelum melanjutkan`);
        return false;
    }
});
</script>

<?php include '../footer.php'; ?>