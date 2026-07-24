<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

$busTypeConfig = [
    'normal'     => ['label' => 'Normal Bus',           'seats' => 54],
    'semi'       => ['label' => 'Semi-Luxury Bus',      'seats' => 45],
    'super'      => ['label' => 'Super Coach Bus',      'seats' => 40],
    'semi_super' => ['label' => 'Semi Super Coach Bus', 'seats' => 42],
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
    $stmt->bind_param("ssissssssd",
        $bus_number, $bus_type, $seat_count,
        $start, $end, $dep, $arr, $driver, $contact, $rate
    );
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add New Bus | WayFinder Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Inter', sans-serif;
        min-height: 100vh;
        display: flex;
        background: #0f172a;
    }

    /* ── Left Panel ── */
    .left-panel {
        width: 300px;
        background: linear-gradient(160deg, #1e3a5f 0%, #0f4c75 55%, #1b6ca8 100%);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 50px 32px;
        position: relative;
        overflow: hidden;
        flex-shrink: 0;
    }

    .left-panel::before {
        content: '';
        position: absolute;
        width: 320px; height: 320px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
        top: -80px; left: -80px;
    }

    .left-panel::after {
        content: '';
        position: absolute;
        width: 250px; height: 250px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
        bottom: -60px; right: -60px;
    }

    .brand-icon {
        width: 80px; height: 80px;
        background: rgba(255,255,255,0.15);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 36px;
        margin-bottom: 22px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }

    .left-panel h2 {
        color: #fff;
        font-size: 1.4rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 10px;
    }

    .left-panel p {
        color: rgba(255,255,255,0.65);
        font-size: 0.85rem;
        text-align: center;
        line-height: 1.6;
        margin-bottom: 32px;
    }

    .info-list {
        list-style: none;
        width: 100%;
    }

    .info-list li {
        color: rgba(255,255,255,0.8);
        font-size: 0.83rem;
        padding: 9px 0;
        display: flex;
        align-items: center;
        gap: 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
    }

    .info-list li i { color: #38bdf8; }

    .back-btn {
        margin-top: 36px;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: rgba(255,255,255,0.75);
        font-size: 0.85rem;
        text-decoration: none;
        background: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.15);
        border-radius: 10px;
        padding: 9px 18px;
        transition: background .2s;
    }

    .back-btn:hover {
        background: rgba(255,255,255,0.18);
        color: #fff;
    }

    /* ── Right Panel ── */
    .right-panel {
        flex: 1;
        background: #f8fafc;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    /* Top bar */
    .top-bar {
        background: #fff;
        border-bottom: 1px solid #e2e8f0;
        padding: 16px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .top-bar .page-title {
        font-size: 1rem;
        font-weight: 600;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .top-bar .page-title i { color: #3b82f6; }

    .top-bar .admin-badge {
        font-size: 0.8rem;
        background: #eff6ff;
        color: #3b82f6;
        border: 1px solid #bfdbfe;
        border-radius: 20px;
        padding: 4px 12px;
        font-weight: 600;
    }

    /* Form area */
    .form-area {
        flex: 1;
        padding: 36px 40px;
        max-width: 780px;
        width: 100%;
        margin: 0 auto;
    }

    .section-title {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: #94a3b8;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .section-title::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e2e8f0;
    }

    .form-card {
        background: #fff;
        border-radius: 16px;
        padding: 28px;
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.04);
    }

    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }

    .input-wrap {
        position: relative;
    }

    .input-wrap i.field-icon {
        position: absolute;
        left: 13px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.95rem;
        pointer-events: none;
    }

    .input-wrap input,
    .input-wrap select {
        width: 100%;
        padding: 11px 13px 11px 38px;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        font-size: 0.88rem;
        color: #0f172a;
        background: #f8fafc;
        transition: border-color .2s, box-shadow .2s;
        outline: none;
        appearance: none;
    }

    .input-wrap input:focus,
    .input-wrap select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        background: #fff;
    }

    /* Seat badge */
    .seat-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #eff6ff;
        color: #3b82f6;
        border: 1px solid #bfdbfe;
        border-radius: 8px;
        padding: 5px 12px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 8px;
    }

    /* Actions */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding: 0 0 40px;
    }

    .btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #1d4ed8, #3b82f6);
        border: none;
        border-radius: 11px;
        color: #fff;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        transition: transform .2s, box-shadow .2s;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59,130,246,0.35);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 22px;
        background: #fff;
        border: 1.5px solid #e2e8f0;
        border-radius: 11px;
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
        text-decoration: none;
        transition: border-color .2s, color .2s;
    }

    .btn-cancel:hover {
        border-color: #94a3b8;
        color: #374151;
    }

    @media (max-width: 768px) {
        .left-panel { display: none; }
        .form-area  { padding: 24px 20px; }
        .top-bar    { padding: 14px 20px; }
    }
</style>
</head>
<body>

<!-- Left Panel -->
<div class="left-panel">
    <div class="brand-icon">🚌</div>
    <h2>Add New Bus</h2>
    <p>Register a new bus to the WayFinder fleet management system.</p>
    <ul class="info-list">
        <li><i class="bi bi-hash"></i> Unique bus number required</li>
        <li><i class="bi bi-people-fill"></i> Seats auto-set by type</li>
        <li><i class="bi bi-clock-fill"></i> Set departure & arrival</li>
        <li><i class="bi bi-person-badge-fill"></i> Assign driver details</li>
        <li><i class="bi bi-currency-exchange"></i> Set ticket rate (LKR)</li>
    </ul>
    <a href="dashboard.php" class="back-btn">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<!-- Right Panel -->
<div class="right-panel">

    <!-- Top Bar -->
    <div class="top-bar">
        <span class="page-title">
            <i class="bi bi-plus-circle-fill"></i> Add New Bus
        </span>
        <span class="admin-badge">⚙ Admin Panel</span>
    </div>

    <!-- Form Area -->
    <div class="form-area">
        <form method="POST">

            <!-- Bus Info -->
            <div class="section-title"><i class="bi bi-bus-front"></i> Bus Information</div>
            <div class="form-card">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Bus Number</label>
                        <div class="input-wrap">
                            <i class="bi bi-hash field-icon"></i>
                            <input name="bus_number" placeholder="e.g. NB-1234" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Bus Type</label>
                        <div class="input-wrap">
                            <i class="bi bi-list-ul field-icon"></i>
                            <select name="bus_type" id="busType" onchange="updateSeats()" required>
                                <?php foreach ($busTypeConfig as $k => $v): ?>
                                    <option value="<?= $k ?>" data-seats="<?= $v['seats'] ?>">
                                        <?= $v['label'] ?> (<?= $v['seats'] ?> seats)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="seat-badge" id="seatBadge">
                            <i class="bi bi-people-fill"></i>
                            <span id="seatCount">54</span> seats available
                        </div>
                    </div>
                </div>
            </div>

            <!-- Route -->
            <div class="section-title"><i class="bi bi-map"></i> Route Details</div>
            <div class="form-card">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Start Point</label>
                        <div class="input-wrap">
                            <i class="bi bi-geo-alt field-icon"></i>
                            <input name="start_point" placeholder="e.g. Colombo" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">End Point</label>
                        <div class="input-wrap">
                            <i class="bi bi-geo-fill field-icon"></i>
                            <input name="end_point" placeholder="e.g. Kandy" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Departure Time</label>
                        <div class="input-wrap">
                            <i class="bi bi-clock field-icon"></i>
                            <input type="time" name="departure_time" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Arrival Time</label>
                        <div class="input-wrap">
                            <i class="bi bi-clock-history field-icon"></i>
                            <input type="time" name="arrival_time" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Driver & Fare -->
            <div class="section-title"><i class="bi bi-person-badge"></i> Driver & Fare</div>
            <div class="form-card">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Driver Name</label>
                        <div class="input-wrap">
                            <i class="bi bi-person field-icon"></i>
                            <input name="driver_name" placeholder="e.g. Mr. Silva">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Driver Contact</label>
                        <div class="input-wrap">
                            <i class="bi bi-telephone field-icon"></i>
                            <input name="driver_contact" placeholder="e.g. 0771234567">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ticket Price (LKR)</label>
                        <div class="input-wrap">
                            <i class="bi bi-currency-exchange field-icon"></i>
                            <input type="number" step="0.01" name="rate" placeholder="e.g. 1500.00" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="form-actions">
                <a href="dashboard.php" class="btn-cancel">
                    <i class="bi bi-x-circle"></i> Cancel
                </a>
                <button type="submit" class="btn-save">
                    <i class="bi bi-check-circle-fill"></i> Save Bus
                </button>
            </div>

        </form>
    </div>
</div>

<script>
function updateSeats() {
    const sel = document.getElementById('busType');
    const seats = sel.options[sel.selectedIndex].dataset.seats;
    document.getElementById('seatCount').textContent = seats;
}
</script>
</body>
</html>
