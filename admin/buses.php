<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
/* Fetch buses */
$result = $conn->query("
    SELECT id, bus_number, start_point, end_point, driver_name, driver_contact
    FROM buses
    ORDER BY id DESC
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Buses | Admin</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
/* ===== BACKGROUND ===== */
body{
    min-height:100vh;
    background:
        linear-gradient(rgba(0,0,0,.55), rgba(0,0,0,.55)),
        url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957') 
        center / cover no-repeat fixed;
    font-family: "Segoe UI", sans-serif;
}
/* ===== HEADER ===== */
.page-header{
    text-align:center;
    color:#fff;
    margin-bottom:40px;
}
.page-header .icon{
    width:90px;
    height:90px;
    border-radius:50%;
    background:linear-gradient(135deg,#4facfe,#00f2fe);
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:40px;
    margin:0 auto 15px;
}
/* ===== CARD ===== */
.glass-card{
    background:rgba(255,255,255,0.96);
    border-radius:18px;
    box-shadow:0 20px 45px rgba(0,0,0,.2);
    padding:30px;
}
/* ===== SEARCH ===== */
.search-box{
    max-width:300px;
    border-radius:30px;
}
/* ===== TABLE ===== */
.table th{
    background:#0d6efd;
    color:#fff;
    vertical-align:middle;
}
.bus-badge{
    background:#6f7bf7;
    padding:6px 12px;
    border-radius:20px;
    color:#fff;
    font-weight:600;
}
/* ===== BUTTONS ===== */
.btn-add{
    background:linear-gradient(135deg,#00b09b,#96c93d);
    color:#fff;
    border-radius:30px;
}

.btn-add:hover{opacity:.9}
.action-btn{
    border-radius:20px;
    padding:6px 14px;
    font-size:14px;
}
</style>
</head>
<body>
<div class="container py-5">

    <!-- HEADER -->
    <div class="page-header">
        <div class="icon">🚌</div>
        <h2 class="fw-bold">Manage Buses</h2>
        <p class="opacity-75">Add, search, and manage bus details easily</p>
    </div>

    <!-- CARD -->
    <div class="glass-card">

        <!-- TOP BAR -->
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
            <input 
                type="text" 
                class="form-control search-box"
                placeholder="🔍 Search bus number"
                onkeyup="filterTable(this.value)"
            >

            <a href="add_bus.php" class="btn btn-add">
                <i class="bi bi-plus-circle"></i> Add Bus
            </a>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="table table-hover align-middle" id="busTable">
                <thead>
                    <tr>
                        <th>Bus No</th>
                        <th>Route</th>
                        <th>Driver</th>
                        <th>Contact</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>

                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>
                            <span class="bus-badge">
                                <?= htmlspecialchars($row['bus_number']) ?>
                            </span>
                        </td>

                        <td>
                            <?= htmlspecialchars($row['start_point']) ?> →
                            <?= htmlspecialchars($row['end_point']) ?>
                        </td>

                        <td><?= htmlspecialchars($row['driver_name']) ?></td>

                        <td><?= htmlspecialchars($row['driver_contact']) ?></td>

                        <td class="text-center">
                            <a href="edit_bus.php?id=<?= $row['id'] ?>"
                               class="btn btn-warning action-btn me-1">
                               <i class="bi bi-pencil"></i> Edit
                            </a>

                            <a href="delete_bus.php?id=<?= $row['id'] ?>"
                               onclick="return confirm('Delete this bus?')"
                               class="btn btn-danger action-btn">
                               <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            No buses found
                        </td>
                    </tr>
                <?php endif; ?>

                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- SEARCH SCRIPT -->
<script>
function filterTable(value){
    value = value.toLowerCase();
    document.querySelectorAll("#busTable tbody tr").forEach(row=>{
        row.style.display = row.innerText.toLowerCase().includes(value)
            ? "" : "none";
    });
}
</script>
</body>
</html>
