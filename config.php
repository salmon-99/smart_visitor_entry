<?php
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db   = "smart_visitor_entry";
//$port = 3307;

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
?>