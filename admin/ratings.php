<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
$sql = "
SELECT 
    r.id AS rating_id,
    r.rating,
    r.comment,
    r.created_at,
    b.bus_number,
    b.start_point,
    b.end_point
FROM ratings r
JOIN buses b ON r.bus_id = b.id
ORDER BY r.created_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Passenger Ratings</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{
    min-height:100vh;
    background:
        linear-gradient(rgba(0,0,0,.65),rgba(0,0,0,.65)),
        url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee')
        center/cover no-repeat fixed;
}
/* Container */
.rating-card{
    background:#fff;
    border-radius:20px;
    padding:30px;
}

/* Title */
.page-title{
    color:#fff;
    font-weight:700;
    margin-bottom:30px;
}

/* Stars */
.star{
    color:#ffc107;
    font-size:18px;
}

/* Bus badge */
.bus-badge{
    background:#0d6efd;
    color:#fff;
    border-radius:20px;
    padding:5px 14px;
    font-size:14px;
}

/* Action buttons */
.btn-edit{
    background:#ffc107;
    border:none;
    color:#000;
}
.btn-delete{
    background:#dc3545;
    border:none;
    color:#fff;
}
</style>
</head>
<body>
<div class="container py-5">
    <h2 class="page-title text-center">
        ⭐ Passenger Ratings & Feedback
    </h2>
    <div class="rating-card shadow-lg">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-warning text-center">
                <tr>
                    <th>#</th>
                    <th>Bus</th>
                    <th>Rating</th>
                    <th>Passenger Comment</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php $i=1; while($row=$result->fetch_assoc()): ?>
                <tr>
                    <td class="text-center"><?= $i++ ?></td>

                    <!-- Bus Number + Route -->
                    <td class="text-center">
                        <span class="bus-badge">
                            <?= htmlspecialchars($row['bus_number']) ?>
                        </span>
                        <div class="small text-muted mt-1">
                            <?= htmlspecialchars($row['start_point']) ?> →
                            <?= htmlspecialchars($row['end_point']) ?>
                        </div>
                    </td>

                    <!-- Rating -->
                    <td class="text-center">
                        <?php
                        for($s=1;$s<=5;$s++){
                            echo $s <= $row['rating']
                                ? '<i class="bi bi-star-fill star"></i>'
                                : '<i class="bi bi-star star"></i>';
                        }
                        ?>
                        <div class="small text-muted">
                            <?= $row['rating'] ?>/5
                        </div>
                    </td>
                    <!-- Comment -->
                    <td>
                        <?= $row['comment'] ?: '<span class="text-muted">No comment</span>' ?>
                    </td>
                    <!-- Actions -->
                    <td class="text-center">
                        <a href="edit_rating.php?id=<?= $row['rating_id'] ?>"
                           class="btn btn-sm btn-edit me-1">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="delete_rating.php?id=<?= $row['rating_id'] ?>"
                           class="btn btn-sm btn-delete"
                           onclick="return confirm('Delete this rating?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted">
                        No ratings available
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>

        </table>

    </div>
</div>

</body>
</html>
