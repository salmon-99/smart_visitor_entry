
<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

/* FILTERS LOGIC */
$where = "1";
$status_filter = $_GET['status'] ?? '';
if (!empty($status_filter)) {
    $status = mysqli_real_escape_string($conn, $status_filter);
    $where .= " AND status='$status'";
}
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = mysqli_real_escape_string($conn, $_GET['from']);
    $to   = mysqli_real_escape_string($conn, $_GET['to']);
    $where .= " AND visit_date BETWEEN '$from' AND '$to'";
}

$query = mysqli_query($conn, "SELECT * FROM visitors WHERE $where ORDER BY created_at DESC");

/* DATA FOR MINI CHART */
$stats_q = mysqli_query($conn, "SELECT status, COUNT(*) as count FROM visitors GROUP BY status");
$chart_data = ['APPROVED' => 0, 'PENDING' => 0, 'REJECTED' => 0];
while($row = mysqli_fetch_assoc($stats_q)) {
    $chart_data[$row['status']] = $row['count'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Intelligence | Sentinel Reports</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #6366f1;
            --dark: #0f172a;
            --surface: #ffffff;
            --border: #e2e8f0;
            --bg: #f8fafc;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            display: flex;
            color: var(--dark);
        }

        /* SIDEBAR (Match Dashboard) */
        .sidebar {
            width: 280px;
            background: var(--dark);
            padding: 40px 20px;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 100;
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 14px;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.05);
            color: #fff;
        }

        .sidebar a.active { background: var(--primary); box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3); }

        /* MAIN CONTENT */
        .main {
            margin-left: 280px;
            padding: 40px;
            width: calc(100% - 280px);
        }

        .header-flex {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 30px;
        }

        h1 { font-weight: 800; font-size: 2rem; letter-spacing: -0.5px; }

        /* FILTER BAR - SLIM PROFESSIONAL */
        .filter-card {
            background: var(--surface);
            padding: 15px 25px;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .filter-group { display: flex; align-items: center; gap: 10px; }
        .filter-group label { font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase; }

        input, select {
            padding: 8px 12px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-family: inherit;
            outline: none;
            background: #f1f5f9;
        }

        .btn-filter {
            background: var(--dark);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-filter:hover { background: var(--primary); transform: translateY(-2px); }

        /* TABLE DESIGN */
        .report-container {
            background: var(--surface);
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border);
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }
        th { 
            background: #f8fafc; 
            padding: 18px 20px; 
            text-align: left; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            color: #64748b;
            border-bottom: 1px solid var(--border);
        }

        td { padding: 16px 20px; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }

        .status-badge {
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 0.7rem;
            font-weight: 800;
            display: inline-block;
        }
        .APPROVED { background: #dcfce7; color: #166534; }
        .PENDING { background: #fef9c3; color: #854d0e; }
        .REJECTED { background: #fee2e2; color: #991b1b; }

        .visitor-img {
            width: 40px; height: 40px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        /* PRINT BUTTON */
        .btn-print {
            background: white;
            border: 1px solid var(--border);
            padding: 10px 18px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; gap: 8px;
            transition: 0.2s;
        }
        .btn-print:hover { background: #f1f5f9; }

        @media print {
            .sidebar, .filter-card, .btn-print { display: none; }
            .main { margin: 0; padding: 0; width: 100%; }
        }
    </style>
</head>

<body>

<div class="sidebar">
    <div style="padding: 0 15px 40px; color: white;">
        <h2 style="font-weight: 800; letter-spacing: 1px;"><i class="fas fa-shield-halved"></i> ADMIN</h2>
    </div>
    <a href="dashboard.php"><i class="fas fa-th-large"></i> Dashboard</a>
    <a href="manage_users.php"><i class="fas fa-user-shield"></i> Manage Security</a>
    <a href="reports.php" class="active"><i class="fas fa-file-invoice"></i> Reports</a>
    <a href="logout.php" style="margin-top: 50px; color: #f87171;"><i class="fas fa-power-off"></i> Logout</a>
</div>

<div class="main">
    <div class="header-flex">
        <div>
            <h1>Visitor Intelligence</h1>
            <p style="color: #64748b; font-size: 0.9rem;">Analytical overview of gate access logs</p>
        </div>
        <button class="btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> Export Report
        </button>
    </div>

    <form class="filter-card" method="GET">
        <div class="filter-group">
            <label>Status</label>
            <select name="status">
                <option value="">All Logs</option>
                <option value="PENDING" <?= $status_filter == 'PENDING' ? 'selected' : '' ?>>Pending</option>
                <option value="APPROVED" <?= $status_filter == 'APPROVED' ? 'selected' : '' ?>>Approved</option>
                <option value="REJECTED" <?= $status_filter == 'REJECTED' ? 'selected' : '' ?>>Rejected</option>
            </select>
        </div>
        <div class="filter-group">
            <label>From</label>
            <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>">
        </div>
        <div class="filter-group">
            <label>To</label>
            <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>">
        </div>
        <button type="submit" class="btn-filter">Generate</button>
        <a href="reports.php" style="font-size: 0.8rem; color: #64748b; text-decoration: none;">Clear</a>
    </form>

    <div class="report-container">
        <table>
            <thead>
                <tr>
                    <th>Identification</th>
                    <th>Visitor Details</th>
                    <th>Visit Purpose</th>
                    <th>Scheduled Date</th>
                    <th>Status</th>
                    <th>Log Entry</th>
                </tr>
            </thead>
            <tbody>
                <?php if(mysqli_num_rows($query) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td style="width: 60px;">
                            <?php if($row['photo_path']): ?>
                                <img src="../<?= $row['photo_path'] ?>" class="visitor-img">
                            <?php else: ?>
                                <div class="visitor-img" style="background:#e2e8f0; display:flex; align-items:center; justify-content:center; font-size:10px;">NO IMG</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <p style="font-weight: 700;"><?= htmlspecialchars($row['full_name']) ?></p>
                            <p style="font-size: 0.75rem; color: #64748b;"><?= $row['mobile'] ?></p>
                        </td>
                        <td style="color: #475569; font-size: 0.85rem;"><?= htmlspecialchars($row['purpose']) ?></td>
                        <td style="font-family: 'JetBrains Mono'; font-weight: 500;"><?= $row['visit_date'] ?></td>
                        <td><span class="status-badge <?= $row['status'] ?>"><?= $row['status'] ?></span></td>
                        <td style="color: #94a3b8; font-size: 0.8rem;"><?= date('d M, H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                    <?php } ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 50px; color: #94a3b8;">No records found for the selected criteria.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
