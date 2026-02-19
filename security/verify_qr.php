
<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['security_logged_in'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Kolkata");

/* ===== SMART VALIDATE QR ===== */
$raw_id = isset($_GET['id']) ? $_GET['id'] : '';
if (preg_match('/(\d+)/', $raw_id, $matches)) {
    $id = intval($matches[1]);
} else {
    die("<div style='text-align:center; margin-top:100px; font-family:Poppins;'><h2>Invalid Scan</h2><a href='scan_qr.php'>Back</a></div>");
}

/* ===== FETCH VISITOR ===== */
$stmt = mysqli_prepare($conn, "SELECT * FROM visitors WHERE visitor_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if (mysqli_num_rows($result) == 0) { die("Visitor Not Found"); }
$row = mysqli_fetch_assoc($result);

/* ===== VALIDATION LOGIC ===== */
$today = date("Y-m-d");
$tomorrow = date("Y-m-d", strtotime("+1 day"));
$visitDate = $row['visit_date'];

$message = "ACCESS DENIED";
$status_label = strtoupper($row['status']);
$theme_color = "#ff4757"; // Coral Red for Denied

if ($row['status'] == "APPROVED") {
    if ($visitDate == $today || $visitDate == $tomorrow) {
        $checkLog = mysqli_query($conn, "SELECT * FROM entry_logs WHERE visitor_id = '$id'");
        if (mysqli_num_rows($checkLog) == 0) {
            $message = "ACCESS GRANTED";
            $status_label = "VERIFIED";
            $theme_color = "#000000"; // Black for a very stylish, high-end look
            mysqli_query($conn, "INSERT INTO entry_logs (visitor_id, entry_time) VALUES ('$id', NOW())");
        } else { $status_label = "ALREADY ENTERED"; }
    } else { $status_label = "PASS EXPIRED"; }
}
$photo = !empty($row['photo_path']) ? "../" . $row['photo_path'] : "../assets/images/default-user.png";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #1a1a1a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
            text-align: center;
        }

        /* --- Stylish Header --- */
        .status-box {
            margin-bottom: 40px;
        }

        .status-text {
            font-size: 12px;
            letter-spacing: 4px;
            font-weight: 800;
            color: <?= $theme_color ?>;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .main-title {
            font-size: 32px;
            font-weight: 800;
            margin: 0;
            letter-spacing: -1px;
        }

        /* --- Profile Image Section --- */
        .profile-section {
            position: relative;
            display: inline-block;
            margin: 40px 0;
        }

        .image-holder {
            width: 180px;
            height: 180px;
            border-radius: 50%;
            padding: 10px;
            background: #fff;
            box-shadow: 0 20px 40px rgba(0,0,0,0.08);
            border: 1px solid #f0f0f0;
        }

        .profile-img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .check-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: <?= $theme_color ?>;
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border: 4px solid #fff;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* --- Information Section --- */
        .info-grid {
            text-align: left;
            margin-top: 20px;
            border-top: 1px solid #eee;
        }

        .info-row {
            padding: 20px 0;
            border-bottom: 1px solid #f8f8f8;
            display: flex;
            flex-direction: column;
        }

        .label {
            font-size: 10px;
            font-weight: 700;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .value {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
        }

        /* --- Stylish Footer Button --- */
        .btn-footer {
            margin-top: 50px;
            display: inline-block;
            width: 100%;
            padding: 22px;
            background: #000;
            color: #fff;
            text-decoration: none;
            border-radius: 100px; /* Capsule shape */
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: 0.3s;
        }

        .btn-footer:hover {
            transform: scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

    </style>
</head>
<body>

    <div class="container">
        <div class="status-box">
            <div class="status-text"><?= $status_label ?></div>
            <h1 class="main-title"><?= $message ?></h1>
        </div>

        <div class="profile-section">
            <div class="image-holder">
                <img src="<?= $photo ?>" class="profile-img">
            </div>
            <div class="check-icon"><?= ($message == "ACCESS GRANTED") ? "✓" : "!" ?></div>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <span class="label">Full Name</span>
                <span class="value"><?= htmlspecialchars($row['full_name']) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Assigned Host</span>
                <span class="value"><?= htmlspecialchars($row['host_name']) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Visit Purpose</span>
                <span class="value"><?= htmlspecialchars($row['purpose']) ?></span>
            </div>
            <div class="info-row">
                <span class="label">Mobile Number</span>
                <span class="value"><?= $row['mobile'] ?></span>
            </div>
        </div>

        <a href="scan_qr.php" class="btn-footer">CONTINUE TO SCANNER</a>
    </div>

</body>
</html>
