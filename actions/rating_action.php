<?php
session_start();
include "../includes/db.php";

$bus_id  = $_POST['bus_id'];
$rating  = $_POST['rating'];
$comment = $_POST['comment'];

$sql = "INSERT INTO ratings (bus_id,rating,comment)
        VALUES ($bus_id,$rating,'$comment')";

mysqli_query($conn, $sql);

header("Location: ../passenger/bus_details.php?id=$bus_id");
exit();
