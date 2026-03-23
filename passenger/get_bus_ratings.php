<?php
$conn = new mysqli("localhost", "root", "", "bus_system");
if ($conn->connect_error) die("DB Error");

$bus_id = intval($_GET['bus_id']);

$sql = "SELECT rating, comment, created_at FROM ratings WHERE bus_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $bus_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='text-muted'>No ratings yet.</p>";
    exit;
}

while ($row = $result->fetch_assoc()) {
    echo "
    <div class='border rounded p-3 mb-2'>
        <div class='fw-bold'>⭐ {$row['rating']}/5</div>
        <div>{$row['comment']}</div>
        <div class='small text-muted'>{$row['created_at']}</div>
    </div>
    ";
}
