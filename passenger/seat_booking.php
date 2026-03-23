<?php
session_start();
include "../includes/db.php";
include "../includes/header.php";
include "../includes/navbar.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$bus_id = intval($_GET['id'] ?? 0);
$travel_date = $_GET['travel_date'] ?? '';
if (!$bus_id || !$travel_date) {
    die("Bus ID or Travel Date missing");
}
/* FETCH BUS SEAT COUNT */
$busQ = $conn->prepare("SELECT seat_count FROM buses WHERE id = ?");
$busQ->bind_param("i", $bus_id);
$busQ->execute();
$bus = $busQ->get_result()->fetch_assoc();
if (!$bus) die("Bus not found");
$totalSeats = (int)$bus['seat_count'];
/* FETCH BOOKED SEATS */
$bookedSeats = [];
$q = $conn->prepare("
    SELECT seats 
    FROM bookings 
    WHERE bus_id = ? AND travel_date = ?
");
$q->bind_param("is", $bus_id, $travel_date);
$q->execute();
$result = $q->get_result();
while ($row = $result->fetch_assoc()) {
    if (!empty($row['seats'])) {
        $bookedSeats = array_merge($bookedSeats, explode(',', $row['seats']));
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Seat Booking</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* ================= BACKGROUND ================= */
body{
    min-height:100vh;
    background:
        linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.45)),
        url("../assets/img/new_bus.png") center/cover no-repeat fixed;
}
/* ================= CARD ================= */
.booking-card{
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    box-shadow: 0 25px 60px rgba(0,0,0,.35);
}
/* ================= LEGEND ================= */
.legend span{
    display:inline-flex;
    align-items:center;
    margin-right:15px;
    font-weight:600;
}
.seat-icon{
    width:26px;
    height:26px;
    border-radius:6px;
    margin-right:6px;
}
.available{ background:#000; }
.selected{ background:#28a745; }
.occupied{ background:#dc3545; }

/* ================= BUS LAYOUT ================= */
.bus-wrapper{
    margin:auto;
    padding:25px;
    background:#f8f9fa;
    border-radius:20px;
    border:3px solid #999;
    width:max-content;
}
.seat-row{
    display:flex;
    align-items:center;
    margin-bottom:8px;
}
.aisle{
    width:40px;
}
.seat-icon{
    cursor:pointer;
}
.seat-icon.occupied{
    cursor:not-allowed;
}
.driver{
    text-align:right;
    font-size:28px;
    margin-bottom:10px;
}
</style>
</head>
<body>
<div class="container py-5">
<div class="text-white mb-4">
    <h2 class="fw-bold">🪑 Seat Booking Simulator</h2>
    <p>
        Travel Date:
        <b><?= date('d M Y', strtotime($travel_date)) ?></b>
    </p>
</div>
<div class="card booking-card p-4">

    <!-- LEGEND -->
    <h5 class="mb-3">Seat Legend</h5>
    <div class="legend mb-3">
        <span><div class="seat-icon available"></div>Available</span>
        <span><div class="seat-icon selected"></div>Selected</span>
        <span><div class="seat-icon occupied"></div>Occupied</span>
    </div>
    <hr>
    <form method="POST" action="../actions/booking_action.php">
        <input type="hidden" name="bus_id" value="<?= $bus_id ?>">
        <input type="hidden" name="travel_date" value="<?= htmlspecialchars($travel_date) ?>">
        <input type="hidden" name="seats" id="selectedSeats">
        <!-- BUS LAYOUT -->
        <div class="bus-wrapper my-4">
            <div class="driver">🛞 Driver</div>
            <?php
            $seatNo = 1;
            $rows = ceil($totalSeats / 4);
            for ($row = 1; $row <= $rows; $row++) {
                echo "<div class='seat-row'>";

                for ($i = 1; $i <= 2; $i++) {
                    if ($seatNo > $totalSeats) break;
                    $cls = in_array($seatNo, $bookedSeats) ? 'occupied' : 'available';
                    echo "<div class='seat-icon $cls' data-seat='$seatNo'></div>";
                    $seatNo++;
                }

                echo "<div class='aisle'></div>";

                for ($i = 1; $i <= 2; $i++) {
                    if ($seatNo > $totalSeats) break;
                    $cls = in_array($seatNo, $bookedSeats) ? 'occupied' : 'available';
                    echo "<div class='seat-icon $cls' data-seat='$seatNo'></div>";
                    $seatNo++;
                }

                echo "</div>";
            }
            ?>
        </div>
        <button class="btn btn-primary btn-lg w-100 mt-3">
            ✅ Confirm Seats
        </button>
    </form>
</div>
</div>
<script src="../assets/js/seat.js"></script>
<?php include "../includes/footer.php"; ?>
</body>
</html>
