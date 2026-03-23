<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Payment Successful</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5 text-center">
    <div class="card p-5 mx-auto" style="max-width:500px;">
        <h3 class="text-success">✅ Payment Successful</h3>
        <p>Your payment has been completed.</p>

        <a href="my_bookings.php" class="btn btn-primary mt-3">
            View Receipt
        </a>
    </div>
</div>

</body>
</html>
