<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
/* Only admin can access */
/* Bus type config */
$busTypeConfig = [
    'normal'      => ['label' => 'Normal Bus', 'seats' => 54],
    'semi'        => ['label' => 'Semi-Luxury Bus', 'seats' => 45],
    'super'       => ['label' => 'Super Coach Bus', 'seats' => 40],
    'semi_super'  => ['label' => 'Semi Super Coach Bus', 'seats' => 42],
];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    die("Invalid bus id");
}
/* Fetch bus */
$stmt = $conn->prepare("SELECT * FROM buses WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();
if (!$bus) {
    die("Bus not found");
}
/* Update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_number     = trim($_POST['bus_number'] ?? '');
    $bus_type       = $_POST['bus_type'] ?? 'normal';
    $start_point    = trim($_POST['start_point'] ?? '');
    $end_point      = trim($_POST['end_point'] ?? '');
    $departure_time = $_POST['departure_time'] ?? '';
    $arrival_time   = $_POST['arrival_time'] ?? '';
    $driver_name    = trim($_POST['driver_name'] ?? '');
    $driver_contact = trim($_POST['driver_contact'] ?? '');
    if ($bus_number === '' || $start_point === '' || $end_point === '' 
    || $departure_time === '' || $arrival_time === '') {
        $error = "Please fill all required fields.";
    } else {
        if (!isset($busTypeConfig[$bus_type])) {
            $bus_type = 'normal';
        }

        $seat_count = (int)$busTypeConfig[$bus_type]['seats'];
        $update = $conn->prepare("
            UPDATE buses SET
                bus_number = ?,
                bus_type = ?,
                seat_count = ?,
                start_point = ?,
                end_point = ?,
                departure_time = ?,
                arrival_time = ?,
                driver_name = ?,
                driver_contact = ?
            WHERE id = ?
        ");
        /* ✅ EXACT MATCH: 10 types for 10 values */
        $update->bind_param(
            "ssissssssi",
            $bus_number,
            $bus_type,
            $seat_count,
            $start_point,
            $end_point,
            $departure_time,
            $arrival_time,
            $driver_name,
            $driver_contact,
            $id
        );
        if ($update->execute()) {
            header("Location: buses.php?updated=1");
            exit();
        } else {
            $error = "Update failed: " . htmlspecialchars($conn->error);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Bus Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
    body{
        min-height:100vh;
        background:
            linear-gradient(rgba(0,0,0,0.55),rgba(0,0,0,0.55)),
            url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1600&q=80')
            center/cover no-repeat fixed;
        font-family: "Segoe UI", sans-serif;
    }
    .glass-card{
        max-width: 980px;
        margin: 40px auto;
        border-radius: 20px;
        background: rgba(255,255,255,0.95);
        box-shadow: 0 18px 55px rgba(0,0,0,0.35);
        overflow:hidden;
    }
    .card-top{
        padding: 18px 24px;
        color:#fff;
        background: linear-gradient(135deg, #0d6efd, #4facfe);
        display:flex;
        align-items:center;
        justify-content:space-between;
    }
    .card-top .title{
        font-size: 20px;
        font-weight: 700;
        margin:0;
        display:flex;
        align-items:center;
        gap:10px;
    }

    .form-control, .form-select{
        border-radius: 12px;
        padding: 12px;
    }
    .btn-back{
        border-radius: 12px;
        padding: 10px 18px;
    }
    .btn-save{
        border-radius: 12px;
        padding: 10px 20px;
        border:0;
        background: linear-gradient(135deg, #198754, #2ecc71);
        color:#fff;
        font-weight:600;
    }
    .badge-soft{
        background:#eef5ff;
        color:#0d6efd;
        padding:6px 12px;
        border-radius:999px;
        font-weight:600;
        font-size: 12px;
    }
</style>
</head>
<body>
<div class="glass-card">
    <div class="card-top">
        <h1 class="title">
            <i class="bi bi-pencil-square"></i>
            Edit Bus Details
        </h1>
        <span class="badge-soft">Bus ID: <?= (int)$id ?></span>
    </div>
    <div class="p-4 p-md-5">
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i><?= $error ?>
            </div>
        <?php endif; ?>
        <form method="post" class="row g-3">

            <div class="col-md-6">
                <label class="form-label fw-semibold">Bus Number <span class="text-danger">*</span></label>
                <input name="bus_number" class="form-control" value="<?= htmlspecialchars($bus['bus_number']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Bus Type <span class="text-danger">*</span></label>
                <select name="bus_type" class="form-select" required>
                    <?php foreach ($busTypeConfig as $k => $v): ?>
                        <option value="<?= $k ?>" <?= ($bus['bus_type'] === $k ? 'selected' : '') ?>>
                            <?= htmlspecialchars($v['label']) ?> (<?= (int)$v['seats'] ?> seats)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">Start Point <span class="text-danger">*</span></label>
                <input name="start_point" class="form-control" value="<?= htmlspecialchars($bus['start_point']) ?>" required>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-semibold">End Point <span class="text-danger">*</span></label>
                <input name="end_point" class="form-control" value="<?= htmlspecialchars($bus['end_point']) ?>" required>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-semibold">Departure Time <span class="text-danger">*</span></label>
                <input type="time" name="departure_time" class="form-control" value="<?= htmlspecialchars(substr($bus['departure_time'],0,5)) ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Arrival Time <span class="text-danger">*</span></label>
                <input type="time" name="arrival_time" class="form-control" value="<?= htmlspecialchars
                (substr($bus['arrival_time'],0,5)) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Driver Name</label>
                <input name="driver_name" class="form-control" value="<?= htmlspecialchars($bus['driver_name'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-semibold">Driver Contact</label>
                <input name="driver_contact" class="form-control" value="<?= htmlspecialchars($bus['driver_contact'] ?? '') ?>">
            </div>

            <div class="col-12 d-flex justify-content-between mt-3">
                <a href="buses.php" class="btn btn-secondary btn-back">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                <button class="btn-save">
                    <i class="bi bi-check2-circle me-1"></i> Update Bus
                </button>
            </div>

        </form>

    </div>
</div>

</body>
</html>
