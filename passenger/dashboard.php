<?php
session_start();

/* ===============================
   AUTH CHECK (UPDATED)
   =============================== */
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'passenger') {
    header("Location: ../index.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Passenger Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/css/passenger_dashboard.css">
</head>
<body>

<div class="dashboard-bg">

    <div class="dashboard-card shadow-lg">

        <div class="icon-circle">🚌</div>

        <h2 class="mt-3">Welcome, <?= htmlspecialchars($user['name']) ?></h2>
        <p class="subtitle">Choose an option to continue your journey</p>

        <div class="row mt-4 g-3">

            <!-- Search Bus -->
            <div class="col-md-4">
                <a href="search.php" class="action-card search">
                    <i class="bi bi-search"></i>
                    <h5>Search Bus</h5>
                    <small>Filter by route & bus type</small>
                </a>
            </div>

            <!-- My Bookings -->
            <div class="col-md-4">
                <a href="my_bookings.php" class="action-card booking">
                    <i class="bi bi-ticket"></i>
                    <h5>My Bookings</h5>
                    <small>View tickets & receipts</small>
                </a>
            </div>

            <!-- Logout -->
            <div class="col-md-4">
                <a href="../logout.php" class="action-card logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <h5>Logout</h5>
                    <small>End your session</small>
                </a>
            </div>

        </div>

    </div>

</div>

</body>
</html>
