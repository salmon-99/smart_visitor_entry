
<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Login | PP Savani University</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- GOOGLE FONT -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
:root{
    --primary:#0a2a43;
    --secondary:#174e7a;
    --accent:#1abc9c;
    --bg:#f4f7fb;
    --border:#dfe6ef;
}

*{
    box-sizing:border-box;
    margin:0;
    padding:0;
    font-family:'Inter',sans-serif;
}

body{
    min-height:100vh;
    background:
        linear-gradient(rgba(10,42,67,0.75), rgba(10,42,67,0.85)),
        url("../assets/images/banners/banner1.jpg") center/cover no-repeat;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ===== LOGIN CARD ===== */
.login-card{
    width:100%;
    max-width:420px;
    background:#ffffff;
    padding:45px 40px;
    border-radius:14px;
    box-shadow:0 40px 80px rgba(0,0,0,0.25);
}

/* ===== HEADER ===== */
.login-header{
    text-align:center;
    margin-bottom:30px;
}

.login-header img{
    width:70px;
    margin-bottom:12px;
}

.login-header h2{
    color:var(--primary);
    font-size:24px;
    margin-bottom:6px;
}

.login-header p{
    font-size:13px;
    color:#555;
}

/* ===== FORM ===== */
.form-group{
    margin-bottom:18px;
}

label{
    font-size:13px;
    color:#444;
    margin-bottom:6px;
    display:block;
    font-weight:500;
}

input{
    width:100%;
    padding:12px 14px;
    border-radius:6px;
    border:1px solid var(--border);
    font-size:14px;
    transition:0.3s;
}

input:focus{
    outline:none;
    border-color:var(--accent);
    box-shadow:0 0 0 3px rgba(26,188,156,0.15);
}

/* ===== BUTTON ===== */
.login-btn{
    width:100%;
    padding:14px;
    background:linear-gradient(135deg, var(--secondary), var(--primary));
    border:none;
    border-radius:6px;
    color:#fff;
    font-size:15px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    margin-top:8px;
}

.login-btn:hover{
    opacity:0.95;
    transform:translateY(-1px);
}

/* ===== FOOTER TEXT ===== */
.login-footer{
    margin-top:22px;
    text-align:center;
    font-size:12px;
    color:#666;
}

/* ===== SECURITY NOTE ===== */
.security-note{
    margin-top:12px;
    text-align:center;
    font-size:12px;
    color:#888;
}

/* ===== RESPONSIVE ===== */
@media(max-width:480px){
    .login-card{
        padding:35px 28px;
    }
}
</style>
</head>

<body>

<div class="login-card">

    <div class="login-header">
        <img src="../assets/images/banners/image.jpg" alt="PP Savani University">
        <h2>System Administration</h2>
        <p>Authorized University Personnel Only</p>
    </div>

    <form method="POST" action="login_action.php">

        <div class="form-group">
            <label>Admin Username</label>
            <input type="text" name="username" placeholder="Enter admin username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password" required>
        </div>

        <button type="submit" class="login-btn">
            Secure Login
        </button>

    </form>

    <div class="security-note">
        Access is logged and monitored for security compliance.
    </div>

    <div class="login-footer">
        © <?php echo date("Y"); ?> PP Savani University
    </div>

</div>

</body>
</html>
