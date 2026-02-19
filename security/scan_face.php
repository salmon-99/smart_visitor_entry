
<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['security_logged_in'])) {
    header("Location: login.php");
    exit();
}

/* ================= VALIDATE VISITOR ================= */

$visitor_id = $_GET['visitor_id'] ?? '';

if ($visitor_id == '' || !is_numeric($visitor_id)) {
    die("Invalid Visitor");
}

$stmt = mysqli_prepare($conn,
    "SELECT * FROM visitors WHERE visitor_id=?"
);
mysqli_stmt_bind_param($stmt,"i",$visitor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($result)==0){
    die("Visitor Not Found");
}

$visitor = mysqli_fetch_assoc($result);

/* ===== CHECK APPROVAL ===== */
if(strtoupper(trim($visitor['status'])) != "APPROVED"){
    die("Visitor Not Approved Yet");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Live Face Verification</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>

body{
    margin:0;
    font-family:'Inter',sans-serif;
    background:#eef2f7;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

/* ===== MAIN CARD ===== */

.card{
    width:900px;
    background:#fff;
    padding:35px;
    border-radius:16px;
    box-shadow:0 20px 50px rgba(0,0,0,.15);
}

/* ===== HEADER ===== */

.header{
    text-align:center;
    margin-bottom:25px;
}

.header h2{
    color:#0a2a43;
    margin-bottom:5px;
}

/* ===== GRID ===== */

.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:30px;
}

/* ===== IMAGE BOX ===== */

.box{
    text-align:center;
}

.box img{
    width:220px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}

video{
    width:220px;
    border-radius:12px;
    box-shadow:0 10px 25px rgba(0,0,0,.1);
}

/* ===== VISITOR INFO ===== */

.info{
    margin-top:20px;
    background:#f4f7fb;
    padding:15px;
    border-radius:10px;
    font-size:14px;
    line-height:1.8;
}

/* ===== BUTTON ===== */

button{
    margin-top:25px;
    padding:14px 28px;
    background:#0a2a43;
    color:#fff;
    border:none;
    border-radius:8px;
    font-size:15px;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#174e7a;
}

</style>
</head>

<body>

<div class="card">

<div class="header">
    <h2>Live Face Verification</h2>
    <p>Visitor: <strong><?= htmlspecialchars($visitor['full_name']) ?></strong></p>
</div>

<div class="grid">

<!-- REGISTERED PHOTO -->
<div class="box">
    <h4>Registered Photo</h4>
    <img src="../<?= $visitor['photo_path'] ?>">
</div>

<!-- LIVE CAMERA -->
<div class="box">
    <h4>Live Camera</h4>
    <video id="video" autoplay></video>
    <canvas id="canvas" width="220" height="220" style="display:none;"></canvas>
</div>

</div>

<!-- VISITOR INFO -->
<div class="info">
    📞 Mobile: <?= htmlspecialchars($visitor['mobile']) ?><br>
    📅 Visit Date: <?= htmlspecialchars($visitor['visit_date']) ?><br>
    🏢 Host: <?= htmlspecialchars($visitor['host_name']) ?><br>
    📝 Purpose: <?= htmlspecialchars($visitor['purpose']) ?>
</div>

<!-- FORM -->
<form method="POST" action="verify_face.php">
    <input type="hidden" name="visitor_id" value="<?= $visitor_id ?>">
    <input type="hidden" name="image_data" id="image_data">

    <center>
        <button type="button" onclick="capture()">Capture & Verify Face</button>
    </center>
</form>

</div>

<script>

/* ===== START CAMERA ===== */

const video = document.getElementById("video");

navigator.mediaDevices.getUserMedia({video:true})
.then(stream=>{
    video.srcObject = stream;
})
.catch(()=>{
    alert("Camera permission denied");
});

/* ===== CAPTURE IMAGE ===== */

function capture(){

    const canvas = document.getElementById("canvas");
    const ctx = canvas.getContext("2d");

    ctx.drawImage(video,0,0,220,220);

    document.getElementById("image_data").value =
        canvas.toDataURL("image/png");

    document.forms[0].submit();
}

</script>

</body>
</html>
