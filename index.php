<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Bus Monitoring System</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* ================= ROOT COLORS ================= */
:root {
    --overlay: rgba(0,0,0,0.55);
    --text: #ffffff;
    --nav-bg: rgba(0,0,0,0.4);
    --search-bg: rgba(255,255,255,0.15);
}
body.dark {
    --overlay: rgba(0,0,0,0.8);
    --text: #f1f1f1;
    --nav-bg: rgba(0,0,0,0.85);
    --search-bg: rgba(255,255,255,0.08);
}
body {
    font-family: "Segoe UI", sans-serif;
    color: var(--text);
    transition: all 0.3s ease;
}
/* ================= PRELOADER ================= */
#preloader {
    position: fixed;
    inset: 0;
    background: #ffffff;
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.road {
    width: 300px;
    height: 6px;
    background: #ccc;
    position: relative;
    overflow: hidden;
}
.bus {
    position: absolute;
    width: 80px;
    left: -90px;
    top: -45px;
    animation: drive 2s linear infinite;
}
@keyframes drive {
    from { left: -100px; }
    to { left: 320px; }
}
/* ================= HERO ================= */
.hero {
    min-height: 100vh;
    background:
        linear-gradient(var(--overlay), var(--overlay)),
        url("assets/img/new_bus.png") center / cover no-repeat fixed;
        center/cover no-repeat;
    display: flex;
    align-items: center;
}
/* ================= NAVBAR ================= */
.navbar {
    background: var(--nav-bg);
    backdrop-filter: blur(8px);
}
/* ================= SEARCH ================= */
.search-box {
    background: var(--search-bg);
    backdrop-filter: blur(12px);
    border-radius: 50px;
    padding: 18px;
}
.search-box input {
    border-radius: 30px;
}
/* ================= BUTTONS ================= */
.btn-search {
    border-radius: 30px;
}
/* ================= TEXT ================= */
.hero h1 {
    font-weight: 800;
    letter-spacing: 1px;
}
</style>
</head>
<body>
<!-- ================= PRELOADER ================= -->
<div id="preloader">
    <div class="road">
        <img src="https://cdn-icons-png.flaticon.com/512/61/61231.png" class="bus">
    </div>
</div>
<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">🚌 BUS SYSTEM</a>

        <div class="d-flex align-items-center gap-2 ms-auto">
            <button id="themeToggle" class="btn btn-outline-light btn-sm">🌙</button>
            <a href="login.php" class="btn btn-outline-light btn-sm">Login</a>
        </div>
    </div>
</nav>

<!-- ================= HERO ================= -->
<section class="hero text-center">
    <div class="container">

        <h1 class="display-4 mb-3">
            BOOK NOW <br> FOR A SEAMLESS JOURNEY
        </h1>

        <p class="lead mb-4">
            Effortless travel starts with our trusted service
        </p>

        <!-- SEARCH FORM -->
       <!-- SEARCH FORM -->
<form 
    class="search-box row g-2 justify-content-center"
    method="GET"
    action="passenger/bus_search.php"
>

    <div class="col-md-3">
        <input type="text" name="from" class="form-control" placeholder="From" required>
    </div>

    <div class="col-md-3">
        <input type="text" name="to" class="form-control" placeholder="To" required>
    </div>

    <div class="col-md-2">
        <input type="date" name="date" class="form-control" required>
    </div>

    <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary btn-search">
            <i class="bi bi-search"></i> Search
        </button>
    </div>
</form>
        <p class="mt-4 opacity-75">
            Convenient payments with all major cards and methods
        </p>

    </div>
</section>

<!-- ================= SCRIPTS ================= -->
<script>
// Preloader
window.addEventListener("load", () => {
    setTimeout(() => {
        document.getElementById("preloader").style.display = "none";
    }, 1800);
});

// Theme toggle
const toggle = document.getElementById("themeToggle");

if (localStorage.getItem("theme") === "dark") {
    document.body.classList.add("dark");
    toggle.innerText = "☀️";
}

toggle.addEventListener("click", () => {
    document.body.classList.toggle("dark");
    if (document.body.classList.contains("dark")) {
        localStorage.setItem("theme", "dark");
        toggle.innerText = "☀️";
    } else {
        localStorage.setItem("theme", "light");
        toggle.innerText = "🌙";
    }
});
</script>
</body>
</html>
