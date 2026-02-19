
<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

date_default_timezone_set("Asia/Kolkata");

/* ===== FETCH STATS ===== */
$totalVisitors = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM visitors"))[0];
$pendingVisitors = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM visitors WHERE status='PENDING'"))[0];
$securityStaff = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM security_users"))[0];
$logs = mysqli_fetch_row(mysqli_query($conn,"SELECT COUNT(*) FROM entry_logs"))[0];

$adminName = $_SESSION['admin_username'] ?? "Administrator";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executive Command | Admin Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1; 
            --dark-bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.9);
            --sidebar-width: 280px;
            --card-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --3d-shadow: 0 10px 30px -10px rgba(99, 102, 241, 0.5);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.05) 0px, transparent 50%), 
                              radial-gradient(at 100% 100%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR NAVIGATION ===== */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--dark-bg);
            color: white;
            padding: 30px 20px;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            z-index: 1000;
            box-shadow: 10px 0 30px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 15px 40px;
        }

        .brand-logo {
            width: 40px; height: 40px;
            background: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: var(--3d-shadow);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s;
            margin-bottom: 5px;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.05);
            color: white;
        }

        .sidebar a i { font-size: 1.1rem; }

        /* ===== MAIN CONTENT AREA ===== */
        .main {
            margin-left: var(--sidebar-width);
            padding: 40px;
            width: 100%;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header h1 { 
            font-family: 'Outfit', sans-serif; 
            font-size: 2rem; 
            font-weight: 800;
            color: var(--dark-bg);
        }

        .profile-container {
            background: white;
            padding: 8px 20px;
            border-radius: 100px;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
        }

        .profile-container img { width: 40px; height: 40px; border-radius: 50%; }

        /* ===== 3D STATS CARDS ===== */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 30px;
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.7);
            box-shadow: var(--card-shadow);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .card:hover {
            transform: translateY(-10px) rotateX(5deg);
            box-shadow: 0 30px 60px -12px rgba(50, 50, 93, 0.15);
        }

        .card-icon {
            width: 50px; height: 50px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
        }

        .blue-icon { background: #eef2ff; color: #6366f1; }
        .amber-icon { background: #fffbeb; color: #d97706; }
        .green-icon { background: #ecfdf5; color: #10b981; }

        .card h3 { font-family: 'Outfit', sans-serif; font-size: 2.2rem; font-weight: 800; color: var(--dark-bg); }
        .card p { color: #64748b; font-weight: 600; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ===== SECTION STYLES ===== */
        .glass-section {
            margin-top: 40px;
            background: white;
            padding: 30px;
            border-radius: 30px;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .section-header h2 { font-family: 'Outfit'; font-weight: 800; font-size: 1.4rem; }

        /* PREMIUM TABLE */
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 15px 20px; color: #64748b; font-size: 0.8rem; text-transform: uppercase; border-bottom: 2px solid #f1f5f9; }
        td { padding: 18px 20px; border-bottom: 1px solid #f8fafc; font-size: 0.95rem; }

        .badge { padding: 5px 12px; border-radius: 8px; font-weight: 700; font-size: 0.75rem; }
        .badge-PENDING { background: #fff7ed; color: #c2410c; }
        .badge-APPROVED { background: #ecfdf5; color: #059669; }

        /* STAFF GRID */
        .staff-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .staff-card {
            background: #f8fafc;
            padding: 25px 15px;
            border-radius: 20px;
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: 0.3s;
        }

        .staff-card:hover { 
            background: white; 
            border-color: var(--primary);
            box-shadow: var(--card-shadow);
            transform: scale(1.05);
        }

        .staff-card img { width: 60px; height: 60px; border-radius: 50%; border: 3px solid white; box-shadow: 0 5px 15px rgba(0,0,0,0.1); margin-bottom: 15px; }
        .visitor-thumb { width: 45px; height: 45px; border-radius: 12px; object-fit: cover; }

        .btn-add {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: var(--3d-shadow);
            filter: brightness(1.1);
        }

    </style>
</head>

<body>

    <div class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-logo"><i class="fas fa-shield-virus" style="color: white;"></i></div>
            <h2 style="font-family: 'Outfit'; font-size: 1.4rem;">ADMIN</h2>
        </div>

        <a href="dashboard.php" class="active"><i class="fas fa-th-large"></i> Dashboard</a>
        <a href="manage_users.php"><i class="fas fa-user-shield"></i> Security Staff</a>
        <a href="reports.php"><i class="fas fa-chart-pie"></i> Activity Logs</a>
        <a href="visitors.php"><i class="fas fa-users-viewfinder"></i> Visitor Master</a>
        
        <div style="margin-top: auto; padding-top: 100px;">
            <a href="logout.php" style="color: #f87171;"><i class="fas fa-sign-out-alt"></i> Sign Out</a>
        </div>
    </div>

    <div class="main">

        <div class="header">
            <div>
                <p style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">COMMAND CENTER</p>
                <h1>Welcome back, <?php echo htmlspecialchars($adminName); ?></h1>
            </div>

            <div class="profile-container">
                <div style="text-align: right;">
                    <p style="font-size: 0.85rem; font-weight: 700;"><?php echo htmlspecialchars($adminName); ?></p>
                    <p style="font-size: 0.7rem; color: #64748b;">Super Admin</p>
                </div>
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($adminName); ?>&background=6366f1&color=fff">
            </div>
        </div>

        <div class="cards">
            <div class="card">
                <div class="card-icon blue-icon"><i class="fas fa-user-friends"></i></div>
                <p>Total Traffic</p>
                <h3><?php echo $totalVisitors; ?></h3>
            </div>

            <div class="card">
                <div class="card-icon amber-icon"><i class="fas fa-hourglass-half"></i></div>
                <p>Pending Review</p>
                <h3><?php echo $pendingVisitors; ?></h3>
            </div>

            <div class="card">
                <div class="card-icon blue-icon" style="background: #e0f2fe; color: #0ea5e9;"><i class="fas fa-user-lock"></i></div>
                <p>Active Staff</p>
                <h3><?php echo $securityStaff; ?></h3>
            </div>

            <div class="card">
                <div class="card-icon green-icon"><i class="fas fa-file-invoice"></i></div>
                <p>Access Logs</p>
                <h3><?php echo $logs; ?></h3>
            </div>
        </div>

        <div class="glass-section">
            <div class="section-header">
                <h2>Live Entry Stream</h2>
                <a href="visitors.php" style="color: var(--primary); text-decoration: none; font-size: 0.85rem; font-weight: 700;">View All <i class="fas fa-arrow-right"></i></a>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Profile</th>
                        <th>Full Name</th>
                        <th>Contact Number</th>
                        <th>Current Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = mysqli_query($conn,"SELECT * FROM visitors ORDER BY visitor_id DESC LIMIT 5");
                    while($v=mysqli_fetch_assoc($q)){
                    ?>
                    <tr>
                        <td><img class="visitor-thumb" src="../<?php echo $v['photo_path']; ?>"></td>
                        <td style="font-weight: 600; color: var(--dark-bg);"><?php echo htmlspecialchars($v['full_name']); ?></td>
                        <td style="font-family: 'Outfit'; color: #64748b;"><?php echo htmlspecialchars($v['mobile']); ?></td>
                        <td><span class="badge badge-<?php echo $v['status']; ?>"><?php echo $v['status']; ?></span></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="glass-section" style="margin-bottom: 50px;">
            <div class="section-header">
                <h2>On-Duty Security</h2>
                <a href="manage_users.php" class="btn-add">Add Staff <i class="fas fa-plus"></i></a>
            </div>

            <div class="staff-grid">
                <?php
                $s = mysqli_query($conn,"SELECT * FROM security_users LIMIT 6");
                while($staff=mysqli_fetch_assoc($s)){
                ?>
                <div class="staff-card">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($staff['username']); ?>&background=f1f5f9&color=6366f1&bold=true">
                    <h4><?php echo htmlspecialchars($staff['username']); ?></h4>
                    <p>Security Officer</p>
                    <div style="margin-top:10px; font-size: 0.7rem; color: #10b981;"><i class="fas fa-circle" style="font-size: 0.5rem;"></i> Active</div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
