<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
$busTypeConfig = [
    'normal'      => ['label' => 'Normal Bus', 'seats' => 54],
    'semi'        => ['label' => 'Semi-Luxury Bus', 'seats' => 45],
    'super'       => ['label' => 'Super Coach Bus', 'seats' => 40],
    'semi_super'  => ['label' => 'Semi Super Coach Bus', 'seats' => 42],
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_number = $_POST['bus_number'];
    $bus_type   = $_POST['bus_type'];
    $seat_count = $busTypeConfig[$bus_type]['seats'];
    $start      = $_POST['start_point'];
    $end        = $_POST['end_point'];
    $dep        = $_POST['departure_time'];
    $arr        = $_POST['arrival_time'];
    $driver     = $_POST['driver_name'];
    $contact    = $_POST['driver_contact'];
    $rate       = $_POST['rate'];
    $stmt = $conn->prepare("
        INSERT INTO buses
        (bus_number, bus_type, seat_count, start_point, end_point,
         departure_time, arrival_time, driver_name, driver_contact, rate)
        VALUES (?,?,?,?,?,?,?,?,?,?)
    ");
    $stmt->bind_param(
        "ssissssssd",
        $bus_number,
        $bus_type,
        $seat_count,
        $start,
        $end,
        $dep,
        $arr,
        $driver,
        $contact,
        $rate
    );

    $stmt->execute();

    /* ✅ Redirect to dashboard (NOT index.php) */
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New Bus | Admin</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* PAGE BACKGROUND */
body {
    min-height: 100vh;
    background: linear-gradient(
        rgba(0,0,0,0.45),
        rgba(0,0,0,0.45)
    ),
    url("https://images.unsplash.com/photo-1500530855697-b586d89ba3ee")
    center / cover no-repeat fixed;
    font-family: "Segoe UI", sans-serif;
}

/* CARD 
.add-card {
    max-width: 520px;
    background: rgba(255,255,255,0.95);
    border-radius: 22px;
    padding: 35px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.25);
}
*/
.add-card {
    max-width: 520px;
    background: rgba(255,255,255,0.95);
    border-radius: 22px;
    padding: 35px;
    box-shadow: 0 25px 60px rgba(0,0,0,0.25);
}

/* ICON */
.icon-circle {
    width: 100px;
    height: 90px;
    background: linear-gradient(135deg,#4facfe,#00f2fe);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 42px;
    color: #fff;
}
/* INPUTS */
.form-control, .form-select {
    border-radius: 14px;
    padding: 12px 14px;
}
/* BUTTONS */
.btn-save {
    background: linear-gradient(135deg,#00b09b,#96c93d);
    border: none;
    color: #fff;
    border-radius: 14px;
    padding: 12px 26px;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}
.btn-back {
    border-radius: 14px;
    padding: 12px 22px;
}
</style>
</head>
<body>

<div class="container min-vh-100 d-flex align-items-center justify-content-center">
    <div class="add-card w-100">
        <!-- HEADER -->
        <div class="text-center mb-4">
            <div class="icon-circle mx-auto mb-3">
                <i class="bi bi-bus-front-fill"></i>
            </div>
            <h4 class="fw-bold">Add New Bus</h4>
            <p class="text-muted">Fill bus details carefully</p>
        </div>
        <!-- FORM -->
        <form method="post" class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Bus Number</label>
                <input name="bus_number" class="form-control" placeholder="NB-1234" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Bus Type</label>
                <select name="bus_type" class="form-select" required>
                    <?php foreach ($busTypeConfig as $k => $v): ?>
                        <option value="<?= $k ?>">
                            <?= $v['label'] ?> (<?= $v['seats'] ?> seats)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Start Point</label>
                <input name="start_point" class="form-control" placeholder="Colombo" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">End Point</label>
                <input name="end_point" class="form-control" placeholder="Kandy" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Departure Time</label>
                <input type="time" name="departure_time" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Arrival Time</label>
                <input type="time" name="arrival_time" class="form-control" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Driver Name</label>
                <input name="driver_name" class="form-control" placeholder="Mr. Silva">
            </div>
            <div class="col-md-6">
                <label class="form-label">Driver Contact</label>
                <input name="driver_contact" class="form-control" placeholder="0771234567">
            </div>
            <div class="col-md-12">
                <label class="form-label">Ticket Price (LKR)</label>
                <input type="number" step="0.01" name="rate" class="form-control" placeholder="1500.00" required>
            </div>
            <!-- ACTIONS -->
            <div class="col-12 d-flex justify-content-between mt-4">
                <a href="index.php" class="btn btn-back btn-light">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <button class="btn btn-save">
                    <i class="bi bi-check-circle"></i> Save Bus
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
