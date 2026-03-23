<?php
session_start();
include "../includes/db.php";
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
/* FETCH LATEST BOOKING */
$stmt = $conn->prepare("
    SELECT 
        bk.id AS booking_id,
        bk.seats,
        bk.travel_date,
        bk.booking_date,
        bk.payment_status,
        bk.payment_method,
        b.bus_number,
        b.bus_type,
        b.rate,
        b.start_point,
        b.end_point,
        b.driver_contact
    FROM bookings bk
    JOIN buses b ON bk.bus_id = b.id
    WHERE bk.user_id = ?
    ORDER BY bk.id DESC
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    die("No bookings found.");
}

$busTypeLabel = [
    'normal' => 'Normal Bus',
    'semi' => 'Semi Bus',
    'super' => 'Super Coach Bus',
    'semi_super' => 'Semi Super Coach Bus',
];

$seatCount = count(explode(',', $booking['seats']));
$totalAmount = $seatCount * $booking['rate'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $method = $_POST['payment_method'];

    if (in_array($method, ['ONLINE', 'ON_BOARD'])) {
        $status = $method === 'ON_BOARD' ? 'PAY ON BUS' : 'PAID ONLINE';

        $up = $conn->prepare("
            UPDATE bookings SET payment_method = ?, payment_status = ?
            WHERE id = ?
        ");
        $up->bind_param("ssi", $method, $status, $booking['booking_id']);
        $up->execute();

        if ($method === 'ONLINE') {
            header("Location: pay_online.php");
            exit();
        }
        header("Location: my_bookings.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Booking</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body{
    min-height:100vh;
    font-family:"Segoe UI",sans-serif;
    background:
        linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
        url("../assets/img/new_bus.png") center/cover fixed;
    color:#000;
}

/* Page title */
.page-title{
    color:#fff;
    font-weight:800;
    letter-spacing:.5px;
}

/* Glass card */
.card{
    border-radius:22px;
    background:rgba(255,255,255,.95);
    backdrop-filter: blur(10px);
    border:none;
    box-shadow:0 18px 45px rgba(0,0,0,.25);
    transition:.3s;
}
.card:hover{
    transform: translateY(-3px);
}

/* Soft badge */
.badge-soft{
    background:#eef5ff;
    color:#0d6efd;
    padding:8px 14px;
    border-radius:50px;
    font-weight:600;
    display:inline-block;
    margin-bottom:4px;
}

/* Buttons */
.btn-primary{
    background:linear-gradient(135deg,#0d6efd,#20c997);
    border:0;
    border-radius:14px;
}
.print-btn{
    background:#198754;
    color:#fff;
    border-radius:14px;
}

/* Receipt rows */
.receipt-row{
    border-bottom:1px dashed #dee2e6;
    padding:10px 0;
}

/* Section headings */
h5{
    font-weight:700;
}
</style>
</head>

<body>

<div class="container py-5">

<h2 class="page-title mb-4">📘 My Booking</h2>

<!-- BUS DETAILS -->
<div class="card p-4 mb-4">
<h5 class="mb-3">🚌 Bus Details</h5>

<div class="row g-3">
<div class="col-md-4">
<span class="badge-soft">Bus Number</span>
<div class="fw-bold"><?= $booking['bus_number'] ?></div>
</div>

<div class="col-md-4">
<span class="badge-soft">Bus Type</span>
<div class="fw-bold"><?= $busTypeLabel[$booking['bus_type']] ?></div>
</div>

<div class="col-md-4">
<span class="badge-soft">Route</span>
<div class="fw-bold"><?= $booking['start_point'] ?> → <?= $booking['end_point'] ?></div>
</div>

<div class="col-md-4">
<span class="badge-soft">Travel Date</span>
<div class="fw-bold"><?= date('d M Y', strtotime($booking['travel_date'])) ?></div>
</div>

<div class="col-md-4">
<span class="badge-soft">Seats</span>
<div class="fw-bold"><?= $booking['seats'] ?></div>
</div>

<div class="col-md-4">
<span class="badge-soft">Contact</span>
<div>
<a href="tel:<?= $booking['driver_contact'] ?>" class="text-decoration-none fw-bold text-primary">
📞 <?= $booking['driver_contact'] ?>
</a>
</div>
</div>
</div>
</div>

<!-- PAYMENT METHOD -->
<?php if (!$booking['payment_method']): ?>
<div class="card p-4 mb-4">
<h5 class="mb-3">💳 Payment Method</h5>
<form method="post">
<div class="row g-3 align-items-center">
<div class="col-md-6">
<select name="payment_method" class="form-select form-select-lg" required>
<option value="">Select Payment Method</option>
<option value="ONLINE">💳 Pay Online</option>
<option value="ON_BOARD">💵 Pay on Bus</option>
</select>
</div>
<div class="col-md-4 d-grid">
<button class="btn btn-primary btn-lg">Confirm Payment</button>
</div>
</div>
</form>
</div>
<?php endif; ?>
<!-- RECEIPT -->
<div class="card p-4">
<h5 class="mb-3">🧾 Booking Receipt</h5>
<div class="receipt-row d-flex justify-content-between">
<span>Booking ID</span><b>#<?= $booking['booking_id'] ?></b>
</div>
<div class="receipt-row d-flex justify-content-between">
<span>Booking Time</span><b><?= $booking['booking_date'] ?></b>
</div>
<div class="receipt-row d-flex justify-content-between">
<span>Payment Method</span>
<b><?= $booking['payment_method'] ?? 'Not Selected' ?></b>
</div>
<div class="receipt-row d-flex justify-content-between">
<span>Payment Status</span>
<b class="text-success"><?= $booking['payment_status'] ?? 'Pending' ?></b>
</div>
<div class="receipt-row d-flex justify-content-between">
<span>Total Amount</span>
<b>LKR <?= number_format($totalAmount,2) ?></b>
</div>
<div class="mt-4">
<button onclick="window.print()" class="btn print-btn">
🖨 Print / Save PDF
</button>
</div>

</div>
</div>

</body>
</html>
