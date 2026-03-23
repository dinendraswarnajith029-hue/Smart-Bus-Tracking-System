<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Require login */
function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /bus_monitoring/login.php");
        exit;
    }
}

/* Require admin role */
function require_admin() {
    require_login();

    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        session_destroy();
        header("Location: /bus_monitoring/login.php");
        exit;
    }
}

/* Require passenger role */
function require_passenger() {
    require_login();

    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'passenger') {
        session_destroy();
        header("Location: /bus_monitoring/login.php");
        exit;
    }
}
