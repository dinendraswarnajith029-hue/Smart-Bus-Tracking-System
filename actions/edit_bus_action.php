<?php
include '../includes/db.php';

$sql = "UPDATE buses SET
bus_no=?, start_point=?, end_point=?, driver=?, contact=?,
departure_time=?, arrival_time=?, latitude=?, longitude=?
WHERE id=?";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    "ssssssdddi",
    $_POST['bus_no'],
    $_POST['start_point'],
    $_POST['end_point'],
    $_POST['driver'],
    $_POST['contact'],
    $_POST['departure_time'],
    $_POST['arrival_time'],
    $_POST['latitude'],
    $_POST['longitude'],
    $_POST['id']
);

mysqli_stmt_execute($stmt);
header("Location: ../admin/buses.php");
