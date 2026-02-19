
<?php
session_start();
require_once("../config.php");
//require_once('../phpqrcode/qrlib.php');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: register.php");
    exit();
}

/* ===== VALIDATION ===== */

$required = ['full_name','mobile','host_name','visit_date','purpose'];

foreach($required as $field){
    if(empty($_POST[$field])){
        die("Required fields missing");
    }
}

/* ===== GET FORM DATA ===== */

$name    = mysqli_real_escape_string($conn,$_POST['full_name']);
$mobile  = mysqli_real_escape_string($conn,$_POST['mobile']);
$email   = mysqli_real_escape_string($conn,$_POST['email'] ?? '');
$host    = mysqli_real_escape_string($conn,$_POST['host_name']);
$date    = $_POST['visit_date'];
$time    = $_POST['visit_time'] ?? '';
$purpose = mysqli_real_escape_string($conn,$_POST['purpose']);


/* ===== PHOTO UPLOAD ===== */

if(!isset($_FILES['photo']) || $_FILES['photo']['error'] !== 0){
    die("Photo upload required");
}

$uploadDir = "../uploads/visitor_photos/";

if(!file_exists($uploadDir)){
    mkdir($uploadDir,0777,true);
}

$photoName = time()."_".basename($_FILES['photo']['name']);
$photoPath = "uploads/visitor_photos/".$photoName;

move_uploaded_file($_FILES['photo']['tmp_name'],$uploadDir.$photoName);


/* ===== INSERT VISITOR ===== */

$stmt = mysqli_prepare($conn,"
INSERT INTO visitors
(full_name,phone_number,email,host_name,visit_date,visit_time,purpose,photo_path,status)
VALUES (?,?,?,?,?,?,?,?,'PENDING')
");

mysqli_stmt_bind_param($stmt,"ssssssss",
$name,$mobile,$email,$host,$date,$time,$purpose,$photoPath
);

mysqli_stmt_execute($stmt);

$visitor_id = mysqli_insert_id($conn);


/* ===== QR GENERATION ===== */

$qrDir = "../uploads/qrcodes/";

if(!file_exists($qrDir)){
    mkdir($qrDir,0777,true);
}

$qrData = "VISITOR_ID=".$visitor_id;
$qrFile = $qrDir."VISITOR_".$visitor_id.".png";

//QRcode::png($qrData,$qrFile,QR_ECLEVEL_H,6);


/* ===== SAVE QR PATH ===== */

$qrDbPath = "uploads/qrcodes/VISITOR_".$visitor_id.".png";

// mysqli_query($conn,"
// UPDATE visitors
// SET qr_code='$qrDbPath'
// WHERE visitor_id='$visitor_id'
// ");
mysqli_query($conn, 
"UPDATE visitors SET qr_code='$qrFile' WHERE id=$visitor_id");



/* ===== STORE SESSION ===== */

$_SESSION['visitor_qr'] = $qrDbPath;
$_SESSION['visitor_name'] = $name;
$_SESSION['visitor_date'] = $date;
$_SESSION['visitor_purpose'] = $purpose;
$_SESSION['visitor_host'] = $host;
$_SESSION['visitor_mobile'] = $mobile;

/* ⭐ IMPORTANT FIX */
$_SESSION['visitor_photo'] = $photoName;


/* ===== REDIRECT ===== */

header("Location: success.php");
exit();
?>
