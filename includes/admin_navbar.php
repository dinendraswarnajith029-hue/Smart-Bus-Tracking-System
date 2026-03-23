<?php if (session_status() === PHP_SESSION_NONE) session_start(); ?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/bus_monitoring/admin/dashboard.php">🛠 Admin Panel</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="adminNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="/bus_monitoring/admin/buses.php">Buses</a></li>
                <li class="nav-item"><a class="nav-link" href="/bus_monitoring/admin/bookings.php">Bookings</a></li>
                <li class="nav-item"><a class="nav-link" href="/bus_monitoring/admin/ratings.php">Ratings</a></li>
                <li class="nav-item"><a class="nav-link" href="/bus_monitoring/logout.php">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
