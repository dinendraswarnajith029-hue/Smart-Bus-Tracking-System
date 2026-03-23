<?php
session_start();

if (!isset($_GET['from'], $_GET['to'], $_GET['date'])) {
    header("Location: ../index.php");
    exit;
}

$from = urlencode(trim($_GET['from']));
$to   = urlencode(trim($_GET['to']));
$date = urlencode(trim($_GET['date']));

/*
 Redirect to passenger bus search page
*/
header("Location: ../passenger/bus_search.php?from=$from&to=$to&date=$date");
exit;
