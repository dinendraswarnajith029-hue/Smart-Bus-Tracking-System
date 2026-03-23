<?php
include '../includes/db.php';
$id = intval($_GET['id']);
$result = mysqli_query($conn, "SELECT * FROM buses WHERE id = $id");
$bus = mysqli_fetch_assoc($result);
if (!$bus) {
    die("Bus not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Live Bus Tracking</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    min-height: 100vh;
    margin: 0;
    font-family: "Segoe UI", sans-serif;
    background:
        linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)),
        url("../assets/img/new_bus.png") center / cover no-repeat fixed;
}
/*  CARD */
.tracking-card {
    max-width: 950px;
    margin: 50px auto;
    padding: 35px;
    background: rgba(255,255,255,0.95);
    border-radius: 22px;
    box-shadow: 0 25px 55px rgba(0,0,0,0.3);
}
/* HEADER */
.tracking-title {
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 10px;
}

/*  BUS NUMBER  */
.bus-number {
    font-size: 1.2rem;
    font-weight: 600;
    color: #0d6efd;
}
/* ROUTE  */
.route {
    font-size: 1.05rem;
    font-weight: 500;
    margin-bottom: 12px;
}
/* TIME BADGES  */
.time-box {
    background: #eef5ff;
    padding: 8px 14px;
    border-radius: 12px;
    display: inline-block;
    margin-right: 10px;
    font-weight: 500;
}
/*  MAP  */
iframe {
    width: 100%;
    height: 450px;
    border-radius: 18px;
    border: none;
    margin-top: 20px;
}
/* ANIMATION  */
.tracking-card {
    animation: fadeUp .6s ease;
}
@keyframes fadeUp {
    from { opacity: 0; transform: translateY(25px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>
</head>

<body>

<div class="tracking-card">

    <h3 class="tracking-title mb-3">
        🚌 Live Bus Location
    </h3>

    <!-- Bus Number -->
    <p class="bus-number">
        <?= htmlspecialchars($bus['bus_number']); ?>
    </p>

    <!-- Route -->
    <p class="route">
        <?= htmlspecialchars($bus['start_point']); ?>
        →
        <?= htmlspecialchars($bus['end_point']); ?>
    </p>

    <!-- Times -->
    <div class="mb-3">
        <span class="time-box">⏰ <?= $bus['departure_time']; ?></span>
        <span class="time-box">🕒 <?= $bus['arrival_time']; ?></span>
    </div>

    <!-- Map -->
    <iframe
        src="https://www.google.com/maps?q=<?= $bus['latitude']; ?>,<?= $bus['longitude']; ?>&z=15&output=embed"
        loading="lazy">
    </iframe>

</div>

</body>
</html>
