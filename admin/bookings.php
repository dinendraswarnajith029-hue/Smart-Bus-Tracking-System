<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
/* Fetch booking records */
$sql = "
SELECT 
    b.id,
    u.name AS user_name,
    bu.bus_number,
    b.seats,
    b.travel_date,
    b.booking_date
FROM bookings b
JOIN users u ON b.user_id = u.id
JOIN buses bu ON b.bus_id = bu.id
ORDER BY b.booking_date DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bookings | Admin</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
    min-height: 100vh;
    background: linear-gradient(
        rgba(0,0,0,0.65),
        rgba(0,0,0,0.65)
    ),
    url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee')
    center / cover no-repeat fixed;
}
.page-title {
    color: #fff;
    font-weight: 700;
}
.card {
    border-radius: 18px;
    background: rgba(255,255,255,0.97);
}
.table th {
    background: #0d6efd;
    color: #fff;
    text-align: center;
}
.table td {
    vertical-align: middle;
    text-align: center;
}
.badge-travel {
    background: #198754;
}
</style>
</head>
<body>
<div class="container py-5">
    <h2 class="page-title text-center mb-4">🧾 Bus Booking Records</h2>
    <div class="card shadow-lg p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Passenger</th>
                        <th>Bus Number</th>
                        <th>Seats</th>
                        <th>Travel Date</th>
                        <th>Booked On</th>
                    </tr>
                </thead>

                <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php $i = 1; while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($row['user_name']) ?></td>
                            <td><?= htmlspecialchars($row['bus_number']) ?></td>
                            <td><?= htmlspecialchars($row['seats']) ?></td>
                            <!-- Travel Date -->
                            <td>
                                <span class="badge badge-travel px-3 py-2">
                                    <?= date("Y-m-d", strtotime($row['travel_date'])) ?>
                                </span>
                            </td>
                            <!-- Booking Date & Time -->
                            <td>
                                <?= date("Y-m-d h:i A", strtotime($row['booking_date'])) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            No bookings found
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>

            </table>
        </div>

    </div>

</div>

</body>
</html>
