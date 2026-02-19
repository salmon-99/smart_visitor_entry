<?php
session_start();
require_once("../config.php");

if (isset($_SESSION['security_id'])) {

    $id = $_SESSION['security_id'];

    // ✅ SET STATUS OFFLINE
    mysqli_query($conn, "UPDATE security_users SET status='OFFLINE' WHERE id=$id");
}

session_destroy();
header("Location: login.php");
exit();
?>