<?php
session_start();

/* ======================
   DEMO BOOKING DATA
   ====================== */
$booking = [
    "booking_id" => 1,
    "bus_number" => "NB-1234",
    "amount" => 750,
    "seats" => "17,18",
    "route" => "Colombo → Kandy"
];

/* ======================
   PAYHERE CONFIG (SANDBOX)
   ====================== */
$merchant_id = "121XXXX";        // 🔴 Your PayHere sandbox merchant ID
$merchant_secret = "XXXXXXXX";  // 🔴 Your sandbox secret
$order_id = "BOOK_" . time();
$currency = "LKR";
$amount = $booking['amount'];

$hash = strtoupper(
    md5(
        $merchant_id .
        $order_id .
        number_format($amount, 2, '.', '') .
        $currency .
        strtoupper(md5($merchant_secret))
    )
);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pay Online</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body{
            background:linear-gradient(120deg,#e8f0ff,#f9fbff);
            min-height:100vh;
        }
        .card{
            border-radius:20px;
            box-shadow:0 12px 30px rgba(0,0,0,.08);
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card p-4 mx-auto" style="max-width:500px;">
        <h4 class="mb-3">💳 Pay Online</h4>

        <p><b>Bus:</b> <?= $booking['bus_number'] ?></p>
        <p><b>Route:</b> <?= $booking['route'] ?></p>
        <p><b>Seats:</b> <?= $booking['seats'] ?></p>
        <p class="fs-5"><b>Total:</b> LKR <?= number_format($amount,2) ?></p>

        <form method="post" action="https://sandbox.payhere.lk/pay/checkout">
            <input type="hidden" name="merchant_id" value="<?= $merchant_id ?>">
            <input type="hidden" name="return_url" value="http://localhost/bus_monitoring/passenger/payment_success.php">
            <input type="hidden" name="cancel_url" value="http://localhost/bus_monitoring/passenger/my_bookings.php">
            <input type="hidden" name="notify_url" value="http://localhost/bus_monitoring/passenger/payment_notify.php">

            <input type="hidden" name="order_id" value="<?= $order_id ?>">
            <input type="hidden" name="items" value="Bus Seat Booking">
            <input type="hidden" name="currency" value="<?= $currency ?>">
            <input type="hidden" name="amount" value="<?= number_format($amount,2,'.','') ?>">
            <input type="hidden" name="hash" value="<?= $hash ?>">

            <input type="hidden" name="first_name" value="Passenger">
            <input type="hidden" name="last_name" value="User">
            <input type="hidden" name="email" value="test@email.com">
            <input type="hidden" name="phone" value="0771234567">
            <input type="hidden" name="address" value="Sri Lanka">
            <input type="hidden" name="city" value="Colombo">
            <input type="hidden" name="country" value="Sri Lanka">

            <button class="btn btn-primary w-100 btn-lg mt-3">
                🔐 Pay Securely
            </button>
        </form>
    </div>
</div>

</body>
</html>
