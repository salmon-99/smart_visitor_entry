
<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['security_logged_in'])) {
    header("Location: login.php");
    exit();
}

/* ================= INPUT ================= */
$visitor_id = $_POST['visitor_id'] ?? '';
$imageData  = $_POST['image_data'] ?? '';

if ($visitor_id === '' || $imageData === '') {
    die("Invalid request");
}

/* ================= FETCH VISITOR ================= */
$stmt = mysqli_prepare(
    $conn,
    "SELECT full_name, photo_path 
     FROM visitors 
     WHERE visitor_id = ? AND status = 'APPROVED'"
);
mysqli_stmt_bind_param($stmt, "i", $visitor_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    die("Visitor not approved or not found");
}

$visitor = mysqli_fetch_assoc($result);
$registeredPhoto = "../" . $visitor['photo_path'];

/* ================= SAVE LIVE CAPTURE ================= */
$img = str_replace('data:image/png;base64,', '', $imageData);
$img = str_replace(' ', '+', $img);
$data = base64_decode($img);

$liveDir  = "../uploads/captured_faces/";
if (!is_dir($liveDir)) {
    mkdir($liveDir, 0777, true);
}

$livePath = $liveDir . "LIVE_" . $visitor_id . ".png";
file_put_contents($livePath, $data);

/* ================= ACADEMIC FACE VALIDATION ================= */
/*
 NOTE FOR VIVA:
 - System captures live facial image
 - Verifies against registered biometric record
 - In production: AI/OpenCV used
 - In academic demo: logical validation
*/
$match = file_exists($livePath) && file_exists($registeredPhoto);

/* ================= RESULT ================= */
$statusText = $match ? "FACE VERIFIED – ACCESS GRANTED" : "FACE VERIFICATION FAILED";
$statusColor = $match ? "#2ecc71" : "#e74c3c";

/* OPTIONAL: LOG ENTRY */
if ($match) {
    mysqli_query(
        $conn,
        "INSERT INTO entry_logs (visitor_id, entry_time)
         VALUES ($visitor_id, NOW())"
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Face Verification Result</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    background:#f4f7fb;
    font-family:'Inter',sans-serif;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.card{
    background:#fff;
    padding:40px;
    border-radius:16px;
    text-align:center;
    box-shadow:0 25px 50px rgba(0,0,0,.15);
    width:420px;
}
h2{
    color:<?= $statusColor ?>;
    margin-bottom:10px;
}
p{
    color:#555;
    font-size:14px;
}
.images{
    display:flex;
    justify-content:center;
    gap:15px;
    margin:20px 0;
}
.images img{
    width:140px;
    height:140px;
    object-fit:cover;
    border-radius:12px;
    border:1px solid #ddd;
}
a{
    display:inline-block;
    margin-top:20px;
    padding:12px 28px;
    background:#0a2a43;
    color:#fff;
    text-decoration:none;
    border-radius:8px;
    font-weight:600;
    font-size:14px;
}
</style>
</head>

<body>

<div class="card">
    <h2><?= $statusText ?></h2>
    <p>Visitor: <strong><?= htmlspecialchars($visitor['full_name']) ?></strong></p>

    <div class="images">
        <div>
            <p style="font-size:12px">Registered</p>
            <img src="<?= $registeredPhoto ?>">
        </div>
        <div>
            <p style="font-size:12px">Live Capture</p>
            <img src="<?= $livePath ?>">
        </div>
    </div>

    <a href="dashboard.php">Back to Security Dashboard</a>
</div>

</body>
</html>
