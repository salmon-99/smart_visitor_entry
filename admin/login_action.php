
<?php
session_start();

$ADMIN_USER = "admin";
$ADMIN_PASS = "admin@123";

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if ($username === $ADMIN_USER && $password === $ADMIN_PASS) {

    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_user'] = $username;

    header("Location: dashboard.php");
    exit();

} else {
    echo "<script>
        alert('Invalid admin credentials');
        window.location='login.php';
    </script>";
}
