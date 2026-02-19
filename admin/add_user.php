<?php
session_start();
require_once("../config.php");

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$status = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // ✅ Check user exists (use correct column name)
    $checkQuery = "SELECT * FROM security_users WHERE username='$username'";
    $check = mysqli_query($conn, $checkQuery);

    if (!$check) {
        die("SQL CHECK ERROR: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($check) > 0) {
        $status = "exists";
    } else {

        // ✅ Insert user
        $insertQuery = "INSERT INTO security_users (username, password, role)
                        VALUES ('$username', '$password', '$role')";

        if (mysqli_query($conn, $insertQuery)) {
            $status = "success";
        } else {
            die("SQL INSERT ERROR: " . mysqli_error($conn));
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add User | Admin Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

<style>
:root{
    --primary:#0a2a43;
    --secondary:#174e7a;
    --accent:#1abc9c;
    --bg:#f4f7fb;
}

*{box-sizing:border-box;font-family:Inter;}
body{margin:0;background:var(--bg);}

.sidebar{
    width:260px;
    background:linear-gradient(180deg,#0a2a43,#041726);
    height:100vh;
    position:fixed;
    color:#fff;
    padding:30px;
}
.sidebar h2{margin-bottom:30px;}
.sidebar a{
    display:block;
    color:#cfdceb;
    text-decoration:none;
    margin-bottom:14px;
    font-size:14px;
}
.sidebar a:hover{color:#fff;}

.main{
    margin-left:260px;
    padding:50px;
}

.page-title{
    font-size:28px;
    font-weight:700;
    color:var(--primary);
    margin-bottom:8px;
}
.page-sub{
    color:#666;
    margin-bottom:35px;
}

.card{
    max-width:520px;
    background:rgba(255,255,255,0.85);
    backdrop-filter:blur(15px);
    padding:40px;
    border-radius:18px;
    box-shadow:0 30px 60px rgba(0,0,0,.12);
}

.field{ margin-bottom:20px; }

label{
    font-size:13px;
    font-weight:600;
    color:#444;
    display:block;
    margin-bottom:6px;
}

input,select{
    width:100%;
    padding:14px;
    border-radius:8px;
    border:1px solid #dfe6ef;
    font-size:14px;
}

input:focus,select:focus{
    outline:none;
    border-color:var(--accent);
}

.password-box{ position:relative; }

.toggle{
    position:absolute;
    right:15px;
    top:14px;
    cursor:pointer;
    font-size:13px;
    color:#555;
}

.strength{
    height:6px;
    border-radius:6px;
    background:#eee;
    overflow:hidden;
    margin-top:8px;
}

.strength div{
    height:100%;
    width:0%;
    transition:.3s;
}

.role-tag{
    display:inline-block;
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.admin{background:#ffe6e6;color:#b30000;}
.security{background:#e6fff5;color:#008060;}

button{
    margin-top:25px;
    width:100%;
    padding:15px;
    border:none;
    border-radius:10px;
    background:linear-gradient(135deg,var(--accent),#16a085);
    color:#fff;
    font-weight:700;
    font-size:15px;
    cursor:pointer;
}

button:hover{opacity:.95}

.success{
    background:#e6fff5;
    color:#0b7a5c;
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
}

.error{
    background:#ffecec;
    color:#b30000;
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
}
</style>

<script>
function togglePass(){
    let p=document.getElementById("password");
    p.type = p.type==="password" ? "text" : "password";
}

function strengthCheck(val){
    let bar=document.getElementById("bar");
    let s=0;
    if(val.length>6) s+=25;
    if(/[A-Z]/.test(val)) s+=25;
    if(/[0-9]/.test(val)) s+=25;
    if(/[^A-Za-z0-9]/.test(val)) s+=25;
    bar.style.width=s+"%";
    bar.style.background = s<50?"#e74c3c":s<75?"#f1c40f":"#2ecc71";
}
</script>
</head>

<body>

<div class="sidebar">
    <h2>Admin Panel</h2>
    <a href="dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="reports.php">Reports</a>
    <a href="logout.php">Logout</a>
</div>

<div class="main">
    <div class="page-title">Add New User</div>
    <div class="page-sub">Create security or admin accounts with controlled access</div>

    <div class="card">

        <?php if($status==="success"): ?>
            <div class="success">User added successfully!</div>
        <?php elseif($status==="exists"): ?>
            <div class="error">Username already exists</div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="field">
                <label>Password</label>
                <div class="password-box">
                    <input type="password" name="password" id="password" onkeyup="strengthCheck(this.value)" required>
                    <span class="toggle" onclick="togglePass()">Show</span>
                </div>
                <div class="strength"><div id="bar"></div></div>
            </div>

            <div class="field">
                <label>Role</label>
                <select name="role">
                    <option value="SECURITY">Security Staff</option>
                    <option value="ADMIN">Administrator</option>
                </select>
                <br><br>
                <span class="role-tag security">SECURITY</span>
                <span class="role-tag admin">ADMIN</span>
            </div>

            <button type="submit">Create User</button>
        </form>
    </div>
</div>

</body>
</html>