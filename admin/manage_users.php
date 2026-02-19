<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM security_users ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel Command | Secure Management</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.4);
            --dark-bg: #0f172a;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-sub: #64748b;
            --success: #10b981;
            --danger: #ef4444;
            --glass: rgba(255, 255, 255, 0.95);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: #f1f5f9;
            background-image: radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.03) 0px, transparent 50%);
            display: flex;
            min-height: 100vh;
            color: var(--text-main);
        }

        .sidebar {
            width: 280px;
            background: var(--dark-bg);
            padding: 40px 20px;
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            box-shadow: 10px 0 40px rgba(0,0,0,0.1);
            z-index: 100;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 0 15px 40px;
        }

        .logo-box {
            width: 42px; height: 42px;
            background: var(--primary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 8px 20px var(--primary-glow);
        }

        .sidebar a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            color: #94a3b8;
            text-decoration: none;
            border-radius: 14px;
            font-weight: 500;
            transition: all 0.3s;
            margin-bottom: 8px;
        }

        .sidebar a:hover, .sidebar a.active {
            background: rgba(255,255,255,0.05);
            color: #fff;
            transform: translateX(5px);
        }

        .sidebar a.active {
            background: var(--primary);
            box-shadow: 0 10px 20px var(--primary-glow);
        }

        .main {
            margin-left: 280px;
            padding: 50px;
            width: 100%;
        }

        .page-header {
            margin-bottom: 40px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .page-header h1 { font-size: 2.2rem; font-weight: 800; letter-spacing: -1px; }

        .form-container {
            background: var(--surface);
            padding: 30px;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.05);
            margin-bottom: 40px;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .input-group { flex: 1; position: relative; }

        .input-group i {
            position: absolute;
            left: 18px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-sub);
        }

        input, select {
            width: 100%;
            padding: 14px 14px 14px 45px;
            border-radius: 12px;
            border: 2px solid #f1f5f9;
            background: #f8fafc;
            font-family: inherit;
            font-weight: 600;
            transition: 0.3s;
            outline: none;
        }

        input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px var(--primary-glow); }

        .btn-add {
            background: var(--primary);
            color: white;
            padding: 14px 30px;
            border-radius: 12px;
            border: none;
            font-weight: 800;
            cursor: pointer;
            box-shadow: 0 10px 20px var(--primary-glow);
            transition: 0.3s;
            display: flex; align-items: center; gap: 10px;
        }

        .btn-add:hover { transform: translateY(-3px); box-shadow: 0 15px 30px var(--primary-glow); }

        .table-card {
            background: var(--surface);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }

        table { width: 100%; border-collapse: collapse; }
        th { 
            background: #f8fafc;
            padding: 20px; 
            text-align: left; 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 1px;
            color: var(--text-sub);
            border-bottom: 1px solid #e2e8f0;
        }

        td { padding: 20px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }

        .user-meta { display: flex; align-items: center; gap: 12px; }
        .user-icon { 
            width: 40px; height: 40px; 
            background: #eef2ff; color: var(--primary); 
            border-radius: 10px; display: flex; 
            align-items: center; justify-content: center; font-weight: 800;
        }

        .role-badge {
            font-family: 'JetBrains Mono'; font-size: 0.7rem;
            background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-weight: 600;
        }

        .status-pill {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 6px 14px; border-radius: 100px; font-size: 0.75rem; font-weight: 700;
        }
        .active-pill { background: #ecfdf5; color: var(--success); }
        .inactive-pill { background: #fef2f2; color: var(--danger); }

        .action-btn {
            color: var(--text-sub);
            padding: 8px; border-radius: 8px;
            transition: 0.2s; cursor: pointer;
        }
        .action-btn:hover { background: #f1f5f9; color: var(--danger); }

    </style>
</head>

<body>

<div class="sidebar">
    <div class="sidebar-header">
        <div class="logo-box"><i class="fas fa-shield-halved" style="color: white;"></i></div>
        <h2 style="font-size: 1.2rem; font-weight: 800; letter-spacing: 1px;">ADMIN</h2>
    </div>
    
    <a href="dashboard.php"><i class="fas fa-chart-pie"></i> Dashboard</a>
    <a href="manage_users.php" class="active"><i class="fas fa-user-gear"></i> Manage Security</a>
    <a href="reports.php"><i class="fas fa-file-contract"></i> Incident Reports</a>
    
    <div style="position: absolute; bottom: 40px; width: calc(100% - 40px);">
        <a href="logout.php" style="color: #f87171;"><i class="fas fa-power-off"></i> System Logout</a>
    </div>
</div>

<div class="main">
    <div class="page-header">
        <div>
            <p style="color: var(--primary); font-weight: 700; font-size: 0.9rem;">SECURITY PERSONNEL</p>
            <h1>Manage Users</h1>
        </div>
        <div style="text-align: right; color: var(--text-sub); font-size: 0.85rem;">
            Total Officers: <span style="color: var(--dark-bg); font-weight: 800;"><?= mysqli_num_rows($result) ?></span>
        </div>
    </div>

    <div class="form-container">
        <div style="padding-right: 20px; border-right: 2px solid #f1f5f9;">
            <p style="font-weight: 800; font-size: 0.8rem; color: var(--text-sub);">NEW OFFICER</p>
        </div>
        <form method="POST" action="add_user.php" style="display: flex; flex: 1; gap: 15px;">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="username" placeholder="Officer Username" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Access Password" required>
            </div>
            <div class="input-group" style="flex: 0.5;">
                <i class="fas fa-id-badge"></i>
                <select name="role">
                    <option value="GUARD">Guard</option>
                    <option value="SUPERVISOR">Supervisor</option>
                </select>
            </div>
            <button class="btn-add">
                <i class="fas fa-plus"></i> PROVISION USER
            </button>
        </form>
    </div>

    <div class="table-card">
        <table>
            <thead>
                <tr>
                    <th>Access Identity</th>
                    <th>Classification</th>
                    <th>Current Status</th>
                    <th>Provisioned Date</th>
                    <th style="text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td>
                        <div class="user-meta">
                            <div class="user-icon"><?= substr($row['username'], 0, 1) ?></div>
                            <div>
                                <p style="font-weight: 700;"><?= $row['username'] ?></p>
                                <p style="font-size: 0.75rem; color: var(--text-sub);">ID: #<?= str_pad($row['id'] ?? 0, 4, '0', STR_PAD_LEFT) ?></p>
                            </div>
                        </div>
                    </td>
                    <td><span class="role-badge"><?= $row['role'] ?></span></td>
                    <td>
                        <?php if(($row['status'] ?? 'OFFLINE') == 'ACTIVE'): ?>
                            <span class="status-pill active-pill"><i class="fas fa-circle" style="font-size: 6px;"></i> ONLINE</span>
                        <?php else: ?>
                            <span class="status-pill inactive-pill"><i class="fas fa-circle" style="font-size: 6px;"></i> OFFLINE</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-family: 'JetBrains Mono'; font-size: 0.85rem; color: var(--text-sub);">
                        <?= date('M d, Y', strtotime($row['created_at'])) ?>
                    </td>
                    <td style="text-align: right;">
                        <span class="action-btn"><i class="fas fa-trash-can"></i></span>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>