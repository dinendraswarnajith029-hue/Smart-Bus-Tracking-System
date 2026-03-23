<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/header.php';
/* ===============================
   AUTH CHECK
   =============================== */
$is_logged_in = isset($_SESSION['user']) && $_SESSION['user']['role'] === 'passenger';
$user_id = $is_logged_in ? $_SESSION['user']['id'] : 0;
$bus_id = intval($_GET['id'] ?? 0);
/* ===============================
   FETCH BUS DETAILS
   =============================== */
$stmt = $conn->prepare("
    SELECT 
        bus_number,
        bus_type,
        driver_name
    FROM buses
    WHERE id = ?
");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$bus = $stmt->get_result()->fetch_assoc();

if (!$bus) {
    die("Bus not found");
}

/* ===============================
   AVERAGE RATING
   =============================== */
$stmt = $conn->prepare("
    SELECT ROUND(AVG(rating),1) AS avg_rating 
    FROM ratings 
    WHERE bus_id = ?
");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$avg = $stmt->get_result()->fetch_assoc();

/* ===============================
   PREVIOUS REVIEWS
   =============================== */
$stmt = $conn->prepare("
    SELECT rating, comment, created_at 
    FROM ratings 
    WHERE bus_id = ?
    ORDER BY created_at DESC
");
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$reviews = $stmt->get_result();

/* ===============================
   BUS TYPE LABELS
   =============================== */
$busTypeLabel = [
  'normal' => 'Normal Bus',
  'semi' => 'Semi Bus',
  'super' => 'Super Coach Bus',
  'semi_super' => 'Semi Super Coach Bus',
];
?>

<div class="container mt-4">

    <h3 class="mb-2">
        ⭐ Rate Bus <?= htmlspecialchars($bus['bus_number']) ?>
    </h3>

    <div class="card p-3 shadow mb-4">
        <p><b>Bus Type:</b> <?= $busTypeLabel[$bus['bus_type']] ?></p>
        <p><b>Driver:</b> <?= htmlspecialchars($bus['driver_name']) ?></p>
        <p><b>Average Rating:</b>
            <?= $avg['avg_rating'] ? $avg['avg_rating'] . " / 5 ⭐" : "No ratings yet"; ?>
        </p>
    </div>

    <!-- ===============================
         RATING FORM
         =============================== -->
    <?php if ($is_logged_in): ?>
    <div class="card p-4 shadow mb-4">
        <h5>Submit Your Rating</h5>
        <form method="POST" action="../api/rate_submit.php">
            <input type="hidden" name="bus_id" value="<?= $bus_id ?>">
            <input type="hidden" name="rating" id="ratingValue" required>
            <!-- Stars -->
            <div class="mb-3">
                <span class="star" data-value="1">★</span>
                <span class="star" data-value="2">★</span>
                <span class="star" data-value="3">★</span>
                <span class="star" data-value="4">★</span>
                <span class="star" data-value="5">★</span>
            </div>
            <textarea name="comment" class="form-control mb-3"
                placeholder="Write your experience (optional)"></textarea>
            <button class="btn btn-success">
                ✅ Submit Rating
            </button>
        </form>
    </div>
    <?php else: ?>
    <div class="card p-4 shadow mb-4 text-center border-warning">
        <h5 class="text-warning">Login Required</h5>
        <p class="mb-0">Please <a href="../login.php" class="fw-bold text-decoration-none">login to your account</a> to submit a new rating.</p>
    </div>
    <?php endif; ?>

    <!-- ===============================
         PREVIOUS REVIEWS
         =============================== -->
    <div class="card p-4 shadow">
        <h5>Previous Reviews</h5>

        <?php if ($reviews->num_rows > 0): ?>
            <?php while ($r = $reviews->fetch_assoc()): ?>
                <div class="border-bottom mb-2 pb-2">
                    <b>⭐ <?= $r['rating'] ?> / 5</b>
                    <p class="mb-1"><?= htmlspecialchars($r['comment']) ?></p>
                    <small class="text-muted">
                        <?= date("d M Y", strtotime($r['created_at'])) ?>
                    </small>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted">No reviews yet.</p>
        <?php endif; ?>
    </div>

</div>

<!-- Star Rating JS -->
<script src="../assets/js/rating.js"></script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
