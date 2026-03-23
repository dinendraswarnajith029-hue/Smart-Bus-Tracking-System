<?php
session_start();
include "../includes/db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id     = $_SESSION['user_id'];
$bus_id      = intval($_POST['bus_id'] ?? 0);
$travel_date = $_POST['travel_date'] ?? '';
$seats       = trim($_POST['seats'] ?? '');

/* =========================
   BASIC VALIDATION
========================= */
if (!$bus_id || !$travel_date || !$seats) {
    die("Invalid booking request");
}

/* =========================
   CHECK SEAT CONFLICT
   (BUS + DATE BASED)
========================= */
$seatArray = explode(',', $seats);

$checkQ = $conn->prepare("
    SELECT seats 
    FROM bookings 
    WHERE bus_id = ? AND travel_date = ?
");
$checkQ->bind_param("is", $bus_id, $travel_date);
$checkQ->execute();
$result = $checkQ->get_result();

$alreadyBooked = [];

while ($row = $result->fetch_assoc()) {
    if (!empty($row['seats'])) {
        $alreadyBooked = array_merge(
            $alreadyBooked,
            explode(',', $row['seats'])
        );
    }
}

/* =========================
   CONFLICT DETECTION
========================= */
foreach ($seatArray as $seat) {
    if (in_array($seat, $alreadyBooked)) {
        die("Seat $seat already booked. Please go back and select another seat.");
    }
}

/* =========================
   INSERT BOOKING
========================= */
$insertQ = $conn->prepare("
    INSERT INTO bookings 
    (user_id, bus_id, travel_date, seats, booking_date, payment_status)
    VALUES (?, ?, ?, ?, NOW(), 'PENDING')
");

$insertQ->bind_param(
    "iiss",
    $user_id,
    $bus_id,
    $travel_date,
    $seats
);

$insertQ->execute();

/* =========================
   REDIRECT
========================= */
header("Location: ../passenger/my_bookings.php");
exit();
