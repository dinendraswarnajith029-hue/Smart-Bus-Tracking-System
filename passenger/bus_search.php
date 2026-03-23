<?php
session_start();
/* ===============================
   DATABASE CONNECTION
   =============================== */
$conn = new mysqli("localhost", "root", "", "bus_system");
if ($conn->connect_error) {
    die("Database connection failed");
}
/* ===============================
   SEARCH VALUES
   =============================== */
$from = trim($_GET['from'] ?? "");
$to   = trim($_GET['to'] ?? "");
$type = trim($_GET['bus_type'] ?? "");

/* ===============================
   MAIN QUERY (WITH RATINGS)
   =============================== */
$sql = "
SELECT 
    b.id,
    b.bus_number,
    b.bus_type,
    b.seat_count,
    b.rate,
    b.start_point,
    b.end_point,
    b.departure_time,
    b.arrival_time,
    b.driver_contact,
    IFNULL(ROUND(AVG(r.rating),1), 0) AS avg_rating,
    COUNT(r.id) AS total_reviews
FROM buses b
LEFT JOIN ratings r ON b.id = r.bus_id
WHERE 1=1
";

$params = [];
$types  = "";
/* Route filter */
if ($from !== "") {
    $sql .= " AND b.start_point LIKE ? ";
    $params[] = "%$from%";
    $types .= "s";
}
if ($to !== "") {
    $sql .= " AND b.end_point LIKE ? ";
    $params[] = "%$to%";
    $types .= "s";
}
/* Bus type filter */
if ($type !== "") {
    $sql .= " AND b.bus_type = ? ";
    $params[] = $type;
    $types .= "s";
}
$sql .= " GROUP BY b.id ORDER BY b.departure_time ASC";
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$buses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

/* Bus type labels */
$busTypeLabel = [
  'normal' => 'Normal Bus',
  'semi' => 'Semi Bus',
  'super' => 'Super Coach Bus',
  'semi_super' => 'Semi Super Coach Bus',
];
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Buses</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* ================= BACKGROUND ================= */
body {
    min-height: 100vh;
    background:
        linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
        url("../assets/img/new_bus.png") center / cover no-repeat fixed;
    font-family: "Segoe UI", sans-serif;
}
/* ================= PAGE TITLE ================= */
.page-title {
    color: #fff;
    font-weight: 800;
    letter-spacing: 1px;
}
/* ================= SEARCH BAR ================= */
.search-box {
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(14px);
    border-radius: 50px;
    padding: 18px;
}
/* ================= BUS CARD ================= */
.bus-card {
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 18px 40px rgba(0,0,0,.25);
    transition: transform .3s ease;
}
.bus-card:hover {
    transform: translateY(-4px);
}

/* ================= TOP BAR ================= */
.topbar {
    background: linear-gradient(135deg,#0d6efd,#20c997);
    color: #bee4d1;
    padding: 16px 22px;
}
/* ================= BADGES ================= */
.badge-type {
    font-size: .8rem;
}
/* ================= ICON ROW ================= */
.info-row i {
    margin-right: 6px;
}
/* ================= BUTTONS ================= */
.btn-live {
    background: #0d6efd;
    color: #fff;
}
.btn-book {
    background: #198754;
    color: #fff;
}
/* ================= BUS ANIMATION ================= */
.bus-anim {
    font-size: 48px;
    animation: drive 12s linear infinite;
    opacity: .7;
}
@keyframes drive {
    from { transform: translateX(-120%); }
    to { transform: translateX(120%); }
}
</style>
</head>

<body>
<div class="container py-5">
    <!-- Animated Bus -->
    <div class="text-center mb-3 overflow-hidden">
        <div class="bus-anim">🚌</div>
    </div>
    <!-- Title -->
    <h2 class="page-title text-center mb-4">
        🔍 Available Buses
    </h2>
    <!-- SEARCH FILTER (UNCHANGED LOGIC) -->
    <form class="search-box row g-3 mb-5">
        <div class="col-md-3">
            <input name="from" value="<?= htmlspecialchars($from) ?>"
                   class="form-control" placeholder="From">
        </div>
        <div class="col-md-3">
            <input name="to" value="<?= htmlspecialchars($to) ?>"
                   class="form-control" placeholder="To">
        </div>
        <div class="col-md-3">
            <select name="bus_type" class="form-control">
                <option value="">All Bus Types</option>
                <?php foreach ($busTypeLabel as $k=>$v): ?>
                    <option value="<?= $k ?>" <?= $type===$k?'selected':'' ?>>
                        <?= $v ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 d-grid">
            <button class="btn btn-primary">
                <i class="bi bi-search"></i> Search
            </button>
        </div>
    </form>
    <!-- BUS RESULTS -->
    <?php foreach ($buses as $bus): ?>
    <div class="card bus-card mb-4">
        <div class="topbar">
            🚌 <b><?= htmlspecialchars($bus['bus_number']) ?></b><br>
            <?= htmlspecialchars($bus['start_point']) ?> → <?= htmlspecialchars($bus['end_point']) ?>
        </div>
        <div class="p-4 bg-white">
            <span class="badge bg-dark badge-type mb-2">
                <?= $busTypeLabel[$bus['bus_type']] ?>
            </span>
            <div class="row info-row g-3 mt-2">
                <div class="col-md-3">
                    ⏰ Departure: <b><?= $bus['departure_time'] ?></b>
                </div>
                <div class="col-md-3">
                    🕒 Arrival: <b><?= $bus['arrival_time'] ?></b>
                </div>
                <div class="col-md-3">
                    💺 Seats: <b><?= $bus['seat_count'] ?></b>
                </div>
                <div class="col-md-3">
                    💰 Price: <b>LKR <?= number_format($bus['rate'],2) ?></b>
                </div>
            </div>
            <div class="mt-3">
                ⭐ Rating:
                <?php if ($bus['total_reviews'] > 0): ?>
                    <span class="text-warning fw-bold">
                        <?= $bus['avg_rating'] ?>/5
                    </span>
                    <small>(<?= $bus['total_reviews'] ?> reviews)</small>
                <?php else: ?>
                    <span class="text-muted">No ratings yet</span>
                <?php endif; ?>
            </div>

            <div class="mt-4 d-flex gap-2">
                <a class="btn btn-live"
                   href="live_tracking.php?id=<?= $bus['id'] ?>">
                   📍 Live Location
                </a>
                <a class="btn btn-book"
                   href="bus_details.php?id=<?= $bus['id'] ?>">
                   View & Book
                </a>
            </div>

        </div>
    </div>
    <?php endforeach; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
