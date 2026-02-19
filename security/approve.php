<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['security_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid Visitor");
}

$id = intval($_GET['id']);

mysqli_query($conn,"
UPDATE visitors 
SET status='APPROVED' 
WHERE visitor_id='$id'
");
?>
<!DOCTYPE html>
<html>
<head>
<title>Visitor Approved</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;500;700&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:Inter;
    height:100vh;
    background:linear-gradient(135deg,#f4f7fb,#e6edf7);
    display:flex;
    justify-content:center;
    align-items:center;
}

.card{
    width:420px;
    background:rgba(255,255,255,0.75);
    backdrop-filter:blur(14px);
    padding:45px;
    border-radius:22px;
    text-align:center;
    box-shadow:0 20px 60px rgba(0,0,0,.08);
    animation:fade .4s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateY(20px);}
    to{opacity:1;}
}

.icon{
    width:70px;
    height:70px;
    border-radius:50%;
    background:#eafaf1;
    color:#27ae60;
    display:flex;
    justify-content:center;
    align-items:center;
    font-size:32px;
    margin:0 auto 18px auto;
}

h2{
    margin:0;
    font-weight:600;
    color:#2c3e50;
}

.sub{
    font-size:14px;
    color:#7f8c8d;
    margin-top:10px;
}

.btn{
    margin-top:28px;
    display:inline-block;
    padding:13px 28px;
    background:#0a2a43;
    color:#fff;
    text-decoration:none;
    border-radius:10px;
    font-size:14px;
    transition:.3s;
}

.btn:hover{
    background:#174e7a;
}

</style>
</head>

<body>

<div class="card">

<div class="icon">✔</div>

<h2>Visitor Approved</h2>

<div class="sub">
The visitor has been successfully approved.
</div>

<a href="dashboard.php" class="btn">
Return to Dashboard
</a>

</div>

</body>
</html>