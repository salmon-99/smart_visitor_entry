<?php
session_start();
require_once("../config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM security_users WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            // ✅ SET SESSION
            $_SESSION['security_logged_in'] = true;
            $_SESSION['security_id'] = $user['id'];
            $_SESSION['security_username'] = $user['username'];

            // ✅ UPDATE STATUS TO ACTIVE
            mysqli_query($conn, "UPDATE security_users SET status='ACTIVE' WHERE id=".$user['id']);

            header("Location: dashboard.php");
            exit();

        } else {
            echo "<script>alert('Invalid credentials'); window.location='login.php';</script>";
        }

    } else {
        echo "<script>alert('Invalid credentials'); window.location='login.php';</script>";
    }
}
?>