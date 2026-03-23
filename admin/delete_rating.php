<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/db.php';

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("DELETE FROM ratings WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ratings.php");
exit;
