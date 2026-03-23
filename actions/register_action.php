<?php
include "../includes/db.php";

$name     = $_POST['name'];
$email    = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name,email,password,role)
        VALUES ('$name','$email','$password','passenger')";

mysqli_query($conn, $sql);

header("Location: ../login.php?registered=1");
exit();
