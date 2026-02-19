<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['security_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Fetch stats
$total_v = mysqli_num_rows(mysqli_query($conn, "SELECT visitor_id FROM visitors"));
$pending_v = mysqli_num_rows(mysqli_query($conn, "SELECT visitor_id FROM visitors WHERE status='PENDING'"));
$approved_v = mysqli_num_rows(mysqli_query($conn, "SELECT visitor_id FROM visitors WHERE status='APPROVED'"));

// Mock security profile data (Replace with database variables if available)
$officer_name = $_SESSION['officer_name'] ?? "Chief Officer";
$officer_id = $_SESSION['officer_id'] ?? "PPSU-SEC-042";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Console | Smart Visitor System</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #1abc9c;
            --primary-dark: #16a085;
            --bg-body: #f4f7fa;
            --card-bg: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            background-color: var(--bg-body);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* ===== TOP NAVIGATION ===== */
        .header-nav {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }

        .brand-box { display: flex; align-items: center; gap: 12px; }
        .brand-box i { color: var(--primary); font-size: 26px; }
        .brand-box h2 { font-size: 18px; font-weight: 800; color: var(--text-dark); letter-spacing: -0.5px; }

        /* ===== IMPRESSIVE PROFILE UI ===== */
        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 6px 12px;
            background: #f8fafc;
            border-radius: 50px;
            border: 1px solid var(--border);
            cursor: pointer;
            transition: 0.3s;
        }

        .profile-trigger:hover { background: #f1f5f9; border-color: var(--primary); }

        .profile-img {
            width: 38px; height: 38px; border-radius: 50%;
            background: var(--primary); color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
            border: 2px solid white; box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .profile-info { text-align: left; line-height: 1.2; }
        .profile-info .name { display: block; font-size: 13px; font-weight: 700; color: var(--text-dark); }
        .profile-info .role { display: block; font-size: 11px; font-weight: 600; color: var(--primary); text-transform: uppercase; }

        /* ===== DASHBOARD CONTENT ===== */
        .wrapper { padding: 40px; max-width: 1400px; margin: 0 auto; }

        /* REFINED STATS */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .stat-widget {
            background: white;
            padding: 25px;
            border-radius: 20px;
            border: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: 0.3s;
        }

        .stat-widget:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }

        .stat-icon {
            width: 55px; height: 55px; border-radius: 15px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
        }

        .stat-widget.total .stat-icon { background: #ebfefb; color: var(--primary); }
        .stat-widget.pending .stat-icon { background: #fffbeb; color: #f59e0b; }
        .stat-widget.approved .stat-icon { background: #f0fdf4; color: #10b981; }

        .stat-val { font-size: 28px; font-weight: 800; display: block; }
        .stat-label { font-size: 13px; color: var(--text-muted); font-weight: 600; }

        /* ===== SEARCH & TABLE ===== */
        .action-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .search-wrapper { position: relative; }
        .search-wrapper i { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--text-muted); }
        .search-wrapper input {
            padding: 14px 20px 14px 50px;
            width: 400px; border-radius: 14px;
            border: 1px solid var(--border);
            outline: none; font-size: 14px;
            transition: 0.3s;
        }
        .search-wrapper input:focus { border-color: var(--primary); box-shadow: 0 0 0 4px rgba(26, 188, 156, 0.1); }

        .main-card {
            background: white;
            border-radius: 24px;
            border: 1px solid var(--border);
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        table { width: 100%; border-collapse: collapse; }
        th { 
            background: #f8fafc; padding: 18px 25px; text-align: left;
            font-size: 12px; font-weight: 700; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.5px;
        }
        td { padding: 20px 25px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; transition: 0.2s; }
        tr:hover td { background-color: #fcfdfe; }

        .visitor-thumb { width: 48px; height: 48px; border-radius: 12px; object-fit: cover; }
        .visitor-name { font-weight: 700; color: var(--text-dark); display: block; }
        .visitor-meta { font-size: 12px; color: var(--text-muted); }

        /* STATUSES */
        .badge {
            padding: 6px 14px; border-radius: 50px; font-size: 11px; font-weight: 800;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .badge.PENDING { background: #fef3c7; color: #92400e; }
        .badge.APPROVED { background: #dcfce7; color: #166534; }
        .badge.REJECTED { background: #fee2e2; color: #991b1b; }

        /* BUTTONS */
        .btn-circle {
            width: 38px; height: 38px; border-radius: 10px;
            display: inline-flex; align-items: center; justify-content: center;
            text-decoration: none; transition: 0.3s; margin-left: 5px;
        }
        .btn-check { background: #10b981; color: white; }
        .btn-close { background: #ef4444; color: white; }
        .btn-verify { 
            background: var(--primary); color: white; padding: 0 20px; 
            width: auto; border-radius: 10px; font-size: 12px; font-weight: 700; text-decoration: none;
            height: 38px; display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-circle:hover { transform: translateY(-3px); filter: brightness(1.1); }

        /* FLOAT SCANNER */
        .btn-scan {
            position: fixed; bottom: 40px; right: 40px;
            background: var(--text-dark); color: white;
            padding: 18px 30px; border-radius: 16px;
            font-weight: 700; text-decoration: none;
            display: flex; align-items: center; gap: 12px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
            transition: 0.3s;
        }
        .btn-scan:hover { background: var(--primary); transform: scale(1.05); }

        /* Logout Dropdown Simulation */
        .logout-link { text-decoration: none; color: #ef4444; font-size: 12px; font-weight: 700; margin-left: 10px; }
    </style>
</head>
<body>

<nav class="header-nav">
    <div class="brand-box">
        <i class="fas fa-shield-virus"></i>
        <h2>PPSU SECURITY</h2>
    </div>

    <div class="profile-trigger">
        <div class="profile-img">
            <?= substr($officer_name, 0, 1); ?>
        </div>
        <div class="profile-info">
            <span class="name"><?= $officer_name ?></span>
            <span class="role"><?= $officer_id ?></span>
        </div>
        <a href="logout.php" class="logout-link" title="Logout">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>

<div class="wrapper">
    <div class="stats-row">
        <div class="stat-widget total">
            <div>
                <span class="stat-label">DAILY VISITS</span>
                <span class="stat-val"><?= $total_v ?></span>
            </div>
            <div class="stat-icon"><i class="fas fa-users"></i></div>
        </div>
        <div class="stat-widget pending">
            <div>
                <span class="stat-label">PENDING REVIEW</span>
                <span class="stat-val"><?= $pending_v ?></span>
            </div>
            <div class="stat-icon"><i class="fas fa-user-clock"></i></div>
        </div>
        <div class="stat-widget approved">
            <div>
                <span class="stat-label">APPROVED ENTRIES</span>
                <span class="stat-val"><?= $approved_v ?></span>
            </div>
            <div class="stat-icon"><i class="fas fa-shield-check"></i></div>
        </div>
    </div>

    <div class="action-bar">
        <h3 style="font-weight: 800; font-size: 20px;">Visitor Control Log</h3>
        <div class="search-wrapper">
            <i class="fas fa-search"></i>
            <input type="text" id="search" placeholder="Search by name, phone or ID..." onkeyup="searchVisitor()">
        </div>
    </div>

    <div class="main-card">
        <table>
            <thead>
                <tr>
                    <th>Visitor Profile</th>
                    <th>Purpose of Visit</th>
                    <th>Date / Time</th>
                    <th>Security Status</th>
                    <th style="text-align: right;">Action Control</th>
                </tr>
            </thead>
            <tbody id="visitorTable">
                <?php
                $q = mysqli_query($conn,"SELECT * FROM visitors ORDER BY visitor_id DESC");
                while($row=mysqli_fetch_assoc($q)){
                ?>
                <tr>
                    <td>
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <img src="../<?= $row['photo_path'] ?>" class="visitor-thumb">
                            <div>
                                <span class="visitor-name"><?= htmlspecialchars($row['full_name']) ?></span>
                                <span class="visitor-meta"><?= $row['mobile'] ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight: 600; font-size: 14px;"><?= htmlspecialchars($row['purpose']) ?></span>
                    </td>
                    <td>
                        <span style="display: block; font-weight: 600; font-size: 14px;"><?= $row['visit_date'] ?></span>
                        <span class="visitor-meta">Scheduled Entry</span>
                    </td>
                    <td>
                        <span class="badge <?= $row['status'] ?>">
                            <i class="fas fa-dot-circle"></i> <?= $row['status'] ?>
                        </span>
                    </td>
                    <td style="text-align: right;">
                        <?php if($row['status']=="PENDING"){ ?>
                            <a class="btn-circle btn-check" href="approve.php?id=<?= $row['visitor_id'] ?>"><i class="fas fa-check"></i></a>
                            <a class="btn-circle btn-close" href="reject.php?id=<?= $row['visitor_id'] ?>"><i class="fas fa-times"></i></a>
                        <?php } ?>

                        <?php if($row['status']=="APPROVED"){ ?>
                            <a class="btn-verify" href="verify_qr.php?id=<?= $row['visitor_id'] ?>">
                                <i class="fas fa-qrcode"></i> VERIFY QR
                            </a>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<a href="scan_qr.php" class="btn-scan">
    <i class="fas fa-camera"></i>
    LAUNCH QR SCANNER
</a>

<script>
function searchVisitor(){
    let input = document.getElementById("search").value.toLowerCase();
    let rows = document.querySelectorAll("#visitorTable tr");
    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>

</body>
</html>