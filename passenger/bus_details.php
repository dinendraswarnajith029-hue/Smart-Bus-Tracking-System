<?php
$conn = new mysqli("localhost", "root", "", "bus_system");
if ($conn->connect_error) {
    die("DB Error");
}
$bus_id = intval($_GET['id'] ?? 0);

$sql = "
SELECT 
    b.bus_number,
    b.bus_type,
    b.seat_count,
    b.rate,
    b.driver_name,
    b.driver_contact,
    IFNULL(ROUND(AVG(r.rating),1), 0) AS avg_rating,
    COUNT(r.id) AS total_reviews
FROM buses b
LEFT JOIN ratings r ON b.id = r.bus_id
WHERE b.id = ?
GROUP BY b.id
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
if (!$bus) {
    die("Bus not found");
}
$busTypeLabel = [
    'normal' => 'Normal Bus',
    'semi' => 'Semi Bus',
    'super' => 'Super Coach Bus',
    'semi_super' => 'Semi Super Coach Bus',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
/* ================= BACKGROUND ================= */
body{
    min-height:100vh;
    font-family:"Segoe UI",sans-serif;
    background:
        linear-gradient(rgba(0,0,0,.45),rgba(0,0,0,.55)),
        url("../assets/img/new_bus.png") center/cover fixed no-repeat;
}
/* ================= MAIN CARD ================= */
.card-main{
    border-radius:26px;
    background:rgba(255,255,255,.92);
    backdrop-filter: blur(12px);
    box-shadow:0 25px 60px rgba(0,0,0,.25);
    overflow:hidden;
    animation: fadeUp .8s ease;
}
@keyframes fadeUp{
    from{opacity:0; transform:translateY(40px)}
    to{opacity:1; transform:none}
}
/* ================= HEADER ================= */
.header{
    background:linear-gradient(135deg,#0d6efd,#20c997);
    color:#fff;
    padding:30px;
}

.header h2{
    font-weight:800;
}

/* ================= INFO ================= */
.info-row{
    display:flex;
    align-items:center;
    gap:12px;
    font-size:18px;
    margin-bottom:14px;
}

.icon{font-size:22px}

.badge-type{
    background:#0dcaf0;
    color:#000;
    padding:6px 14px;
    border-radius:18px;
    font-weight:700;
}
.rating-badge{
    background:#fff3cd;
    color:#856404;
    padding:8px 14px;
    border-radius:20px;
    font-weight:700;
}
/* ================= BUTTONS ================= */
.btn-book{
    background:#198754;
    color:#fff;
    border-radius:16px;
    padding:14px 22px;
    font-weight:600;
}
.btn-rate{
    background:#ffc107;
    color:#000;
    border-radius:16px;
    padding:14px 22px;
    font-weight:600;
}
.btn-book:hover,
.btn-rate:hover{
    transform:translateY(-2px);
}
/* ================= DATE INPUT ================= */
input[type=date]{
    border-radius:14px;
    padding:12px;
}
</style>
</head>

<body>

<div class="container py-5">
<div class="row justify-content-center">
<div class="col-lg-6 col-md-8">

<div class="card card-main">

    <!-- HEADER -->
    <div class="header">
        <h2>🚌 Bus <?= htmlspecialchars($bus['bus_number']) ?></h2>
        <div class="opacity-75">Safe • Comfortable • Reliable</div>
    </div>

    <!-- BODY -->
    <div class="p-4">

        <div class="info-row">
            <span class="icon">🚌</span>
            <span>
                <b>Bus Type:</b>
                <span class="badge-type">
                    <?= $busTypeLabel[$bus['bus_type']] ?? 'Unknown' ?>
                </span>
            </span>
        </div>

        <div class="info-row">
            <span class="icon">💺</span>
            <span><b>Total Seats:</b> <?= (int)$bus['seat_count'] ?></span>
        </div>

        <div class="info-row">
            <span class="icon">💰</span>
            <span><b>Ticket Price:</b> LKR <?= number_format($bus['rate'],2) ?></span>
        </div>

        <div class="info-row">
            <span class="icon">👨‍✈️</span>
            <span><b>Driver:</b> <?= htmlspecialchars($bus['driver_name']) ?></span>
        </div>
        <div class="info-row">
            <span class="icon">📞</span>
            <span>
                <b>Contact:</b>
                <a href="tel:<?= htmlspecialchars($bus['driver_contact']) ?>"
                   class="fw-bold text-primary text-decoration-none">
                    <?= htmlspecialchars($bus['driver_contact']) ?>
                </a>
            </span>
        </div>
        <div class="info-row">
            <span class="icon">⭐</span>
            <span>
                <b>Average Rating:</b>
                <?php if ($bus['total_reviews'] > 0): ?>
                    <span class="rating-badge">
                        <?= $bus['avg_rating'] ?>/5
                    </span>
                    <small>(<?= $bus['total_reviews'] ?> reviews)</small>
                <?php else: ?>
                    <span class="text-muted">No ratings yet</span>
                <?php endif; ?>
            </span>
        </div>
        <hr>
        <!-- BOOKING -->
        <form method="GET" action="seat_booking.php">
            <input type="hidden" name="id" value="<?= $bus_id ?>">

            <div class="mb-3">
                <label class="fw-bold mb-1">📅 Select Travel Date</label>
                <input type="date"
                       name="travel_date"
                       class="form-control"
                       min="<?= date('Y-m-d') ?>"
                       required>
            </div>

            <div class="d-flex gap-3 flex-wrap">
                <button class="btn btn-book">
                    🪑 Book Seat
                </button>

                <a href="rate_bus.php?id=<?= $bus_id ?>"
                   class="btn btn-rate">
                   ⭐ Rate This Bus
                </a>
            </div>
        </form>

    </div>
</div>

</div>
</div>
</div>

</body>
</html>
