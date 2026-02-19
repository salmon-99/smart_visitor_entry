<?php
session_start();
require_once("../config.php");

$visitor_id = $_GET['id'] ?? '';

$q = mysqli_query($conn,"SELECT * FROM visitors WHERE visitor_id='$visitor_id'");
$row = mysqli_fetch_assoc($q);

if(!$row){
    die("Visitor Not Found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Visitor Digital Pass</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Poppins',sans-serif;
    background:linear-gradient(135deg,#0a2a43,#174e7a);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* ===== PASS CONTAINER ===== */
.pass-card{
    width:420px;
    background:#fff;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 30px 60px rgba(0,0,0,0.4);
    position:relative;
}

/* ===== HEADER ===== */
.pass-header{
    background:linear-gradient(135deg,#0a2a43,#1abc9c);
    color:#fff;
    padding:25px;
    text-align:center;
    position:relative;
}

.pass-header h2{
    margin:0;
    font-weight:600;
    letter-spacing:1px;
}

.pass-header span{
    font-size:13px;
    opacity:0.9;
}

/* ===== BORDER DESIGN ===== */
.pass-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:6px;
    background:linear-gradient(90deg,#1abc9c,#3498db,#9b59b6);
}

/* ===== BODY ===== */
.pass-body{
    padding:30px;
    text-align:center;
}

/* PHOTO */
.visitor-photo{
    width:120px;
    height:120px;
    border-radius:50%;
    object-fit:cover;
    border:5px solid #1abc9c;
    margin-bottom:15px;
}

/* NAME */
.pass-body h3{
    margin:5px 0;
    font-weight:600;
    color:#0a2a43;
}

/* INFO GRID */
.info{
    margin-top:20px;
    text-align:left;
}

.info p{
    margin:8px 0;
    font-size:14px;
    border-bottom:1px dashed #ccc;
    padding-bottom:5px;
}

.info span{
    font-weight:600;
    color:#174e7a;
}

/* QR CODE */
.qr-box{
    margin-top:25px;
}

.qr-box img{
    width:150px;
    border:6px solid #f4f7fb;
    border-radius:10px;
    box-shadow:0 10px 20px rgba(0,0,0,0.2);
}

/* FOOTER */
.pass-footer{
    background:#f4f7fb;
    padding:15px;
    text-align:center;
    font-size:12px;
    color:#555;
}

/* STATUS BADGE */
.status{
    display:inline-block;
    padding:6px 14px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    margin-top:10px;
}

.pending{ background:#f39c12; color:#fff; }
.approved{ background:#2ecc71; color:#fff; }
.rejected{ background:#e74c3c; color:#fff; }

</style>
</head>

<body>

<div class="pass-card">

    <div class="pass-header">
        <h2>VISITOR PASS</h2>
        <span>PP Savani University</span>
    </div>

    <div class="pass-body">

        <img src="../<?= $row['photo_path'] ?>" class="visitor-photo">

        <h3><?= strtoupper($row['full_name']) ?></h3>

        <?php
        $statusClass = strtolower($row['status']);
        ?>

        <div class="status <?= $statusClass ?>">
            <?= $row['status'] ?>
        </div>

        <div class="info">
            <p><span>Mobile:</span> <?= $row['mobile'] ?></p>
            <p><span>Purpose:</span> <?= $row['purpose'] ?></p>
            <p><span>Host:</span> <?= $row['host_name'] ?></p>
            <p><span>Date:</span> <?= $row['visit_date'] ?></p>
            <p><span>Time:</span> <?= $row['visit_time'] ?></p>
        </div>

        <div class="qr-box">
            <img src="../<?= $row['qr_code'] ?>">
            <p style="font-size:12px;color:#777;">Scan at Entry Gate</p>
        </div>

    </div>

    <div class="pass-footer">
        Digital Visitor Authentication System
    </div>

</div>

</body>
</html>