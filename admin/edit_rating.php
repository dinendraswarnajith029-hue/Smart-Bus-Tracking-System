<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';
$id = $_GET['id'] ?? 0;
/* Fetch rating */
$stmt = $conn->prepare("SELECT * FROM ratings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$ratingData = $stmt->get_result()->fetch_assoc();
if (!$ratingData) {
    die("Rating not found");
}
/* Update rating */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rating  = (int)$_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("
        UPDATE ratings 
        SET rating = ?, comment = ?
        WHERE id = ?
    ");

    /* EXACT MATCH: i s i */
    $stmt->bind_param("isi", $rating, $comment, $id);
    $stmt->execute();

    header("Location: ratings.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Rating</title>
<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
    min-height: 100vh;
    background: linear-gradient(
        rgba(0,0,0,0.6),
        rgba(0,0,0,0.6)
    ),
    url('https://images.unsplash.com/photo-1500530855697-b586d89ba3ee')
    center / cover no-repeat fixed;
}
/* Card */
.edit-card {
    max-width: 480px;
    background: rgba(189, 170, 170, 0.97);
    border-radius: 20px;
    padding: 30px;
}
/* Title */
.title-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #fbc531, #f5a623);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: #867c7c;
    margin: 0 auto 15px;
}

/* Buttons */
.btn-update {
    background: linear-gradient(135deg, #198754, #28c76f);
    border: none;
    border-radius: 12px;
    padding: 10px 22px;
    color: #fff;
}

.btn-back {
    background: #6c757d;
    border-radius: 12px;
    color: #fff;
    padding: 10px 22px;
}
</style>
</head>
<body>
<div class="container py-5 d-flex justify-content-center align-items-center">

    <div class="edit-card shadow-lg w-100">

        <!--<div class="text-center mb-4">
            <div class="title-icon">
                ⭐
            </div>
            <h4 class="fw-bold">Edit Passenger Rating</h4>
            <p class="text-muted mb-0">Update rating and feedback</p>
        </div>-->
        <div class="text-center mb-4">
            <div class="title-icon">
                ⭐
            </div>
            <h4 class="fw-bold">Edit Passenger Rating</h4>
            <p class="text-muted mb-0">Update rating and feedback</p>
        </div>

        <form method="post">

            <!-- Rating -->
            <div class="mb-3">
                <label class="form-label fw-semibold">Rating</label>
                <select name="rating" class="form-control" required>
                    <?php for ($i=1; $i<=5; $i++): ?>
                        <option value="<?= $i ?>" <?= $ratingData['rating']==$i?'selected':'' ?>>
                            <?= $i ?> Star<?= $i>1?'s':'' ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Comment -->
            <div class="mb-4">
                <label class="form-label fw-semibold">Passenger Comment</label>
                <textarea name="comment" class="form-control" rows="4"><?= htmlspecialchars($ratingData['comment']) ?></textarea>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-between">
                <a href="ratings.php" class="btn btn-back">
                    <i class="bi bi-arrow-left"></i> Back
                </a>

                <button class="btn btn-update">
                    <i class="bi bi-check-circle"></i> Update
                </button>
            </div>

        </form>

    </div>

</div>

</body>
</html>
