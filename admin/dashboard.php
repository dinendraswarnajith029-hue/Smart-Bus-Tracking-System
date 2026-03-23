<?php
require_once __DIR__ . '/../includes/auth_check.php';

/* Admin protection */
require_admin();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
/* ===============================
   GLOBAL
================================ */
body {
    min-height: 100vh;
    background:
        linear-gradient(rgba(0,0,0,.65), rgba(0,0,0,.65)),
        url("https://images.unsplash.com/photo-1500530855697-b586d89ba3ee") 
        center / cover no-repeat fixed;
    font-family: "Segoe UI", sans-serif;
    color: #fff;
}

/* ===============================
   HEADER
================================ */
.admin-header {
    text-align: center;
    margin-bottom: 60px;
}

.admin-avatar {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg,#0d6efd,#20c997);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    margin: auto;
    box-shadow: 0 10px 30px rgba(0,0,0,.4);
}

.admin-header h2 {
    font-weight: 800;
    margin-top: 20px;
}

.admin-header p {
    opacity: .85;
}

/* ===============================
   DASHBOARD CARDS
================================ */
.dashboard-card {
    border-radius: 20px;
    padding: 40px 30px;
    text-align: center;
    color: #fff;
    text-decoration: none;
    display: block;
    transition: all .35s ease;
    box-shadow: 0 15px 40px rgba(0,0,0,.25);
    position: relative;
    overflow: hidden;
}

.dashboard-card i {
    font-size: 55px;
    margin-bottom: 15px;
}

.dashboard-card h5 {
    font-weight: 700;
}

.dashboard-card p {
    font-size: 14px;
    opacity: .9;
}

/* Hover animation */
.dashboard-card:hover {
    transform: translateY(-12px) scale(1.03);
    box-shadow: 0 30px 60px rgba(0,0,0,.45);
}

/* Card Colors */
.card-bus {
    background: linear-gradient(135deg,#36d1dc,#5b86e5);
}

.card-booking {
    background: linear-gradient(135deg,#11998e,#38ef7d);
}

.card-rating {
    background: linear-gradient(135deg,#f7971e,#ffd200);
}

/* ===============================
   FOOTER TEXT
================================ */
.footer-text {
    text-align: center;
    margin-top: 70px;
    font-size: 14px;
    opacity: .7;
}
</style>
</head>

<body>

<div class="container py-5">

    <!-- HEADER -->
    <div class="admin-header">
        <div class="admin-avatar">🧑‍💼</div>
        <h2>Admin Dashboard</h2>
        <p>Manage buses, bookings and passenger feedback efficiently</p>
    </div>

    <!-- DASHBOARD MENU -->
    <div class="row g-4 justify-content-center">

        <div class="col-md-4">
            <a href="buses.php" class="dashboard-card card-bus">
                <i class="bi bi-bus-front-fill"></i>
                <h5>Manage Buses</h5>
                <p>Add, edit and manage all bus details</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="bookings.php" class="dashboard-card card-booking">
                <i class="bi bi-journal-check"></i>
                <h5>View Bookings</h5>
                <p>Monitor passenger reservations and dates</p>
            </a>
        </div>

        <div class="col-md-4">
            <a href="ratings.php" class="dashboard-card card-rating">
                <i class="bi bi-star-fill"></i>
                <h5>View Ratings</h5>
                <p>Analyze passenger feedback and service quality</p>
            </a>
        </div>

    </div>

    <!-- FOOTER -->
    <div class="footer-text">
        © <?= date("Y") ?> Bus Monitoring System | Admin Panel
    </div>

</div>

</body>
</html>