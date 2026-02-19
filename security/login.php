<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Security Login | Smart Visitor System</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
/* ===== PROFESSIONAL DESIGN SYSTEM ===== */
:root {
    --primary: #1abc9c;
    --primary-glow: rgba(26, 188, 156, 0.4);
    --bg-dark: #060b10;
    --card-bg: rgba(13, 25, 36, 0.75);
    --border: rgba(255, 255, 255, 0.1);
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Plus Jakarta Sans', sans-serif;
}

body {
    height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--bg-dark);
    overflow: hidden;
}

/* ===== STATIC PROFESSIONAL BACKGROUND ===== */
.background-container {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    z-index: -1;
    background: 
        linear-gradient(135deg, rgba(6, 11, 16, 0.95), rgba(10, 42, 67, 0.8)),
        url("../assets/images/banners/ppsu_banner_2.jpg") center/cover;
}

/* ===== LOGIN CARD (No Rotation) ===== */
.login-card {
    width: 420px;
    padding: 45px;
    border-radius: 24px;
    background: var(--card-bg);
    backdrop-filter: blur(20px);
    border: 1px solid var(--border);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
}

/* Subtle glow line at the top */
.login-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; width: 100%; height: 3px;
    background: linear-gradient(90deg, transparent, var(--primary), transparent);
}

.logo-section {
    text-align: center;
    margin-bottom: 30px;
}

.logo-section img {
    width: 80px;
    filter: drop-shadow(0 0 10px rgba(255,255,255,0.2));
    border-radius: 12px;
}

h2 {
    color: #ffffff;
    font-size: 24px;
    font-weight: 700;
    text-align: center;
    letter-spacing: -0.5px;
    margin-bottom: 10px;
}

.subtitle {
    text-align: center;
    color: #94a3b8;
    font-size: 14px;
    margin-bottom: 35px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

/* ===== FORM ELEMENTS ===== */
.input-field {
    position: relative;
    margin-bottom: 25px;
}

.input-field i {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    transition: 0.3s;
}

.input-field input {
    width: 100%;
    padding: 14px 16px 14px 48px;
    background: rgba(255, 255, 255, 0.03);
    border: 1px solid var(--border);
    border-radius: 12px;
    color: #fff;
    font-size: 15px;
    transition: all 0.3s ease;
    outline: none;
}

.input-field input:focus {
    border-color: var(--primary);
    background: rgba(26, 188, 156, 0.05);
    box-shadow: 0 0 0 4px var(--primary-glow);
}

.input-field label {
    position: absolute;
    left: 48px;
    top: 50%;
    transform: translateY(-50%);
    color: #64748b;
    font-size: 14px;
    pointer-events: none;
    transition: 0.3s;
}

/* Floating Label Logic */
.input-field input:focus ~ label,
.input-field input:not(:placeholder-shown) ~ label {
    top: -12px;
    left: 12px;
    font-size: 12px;
    font-weight: 600;
    color: var(--primary);
    background: #0d1924;
    padding: 0 8px;
}

.input-field input:focus ~ i {
    color: var(--primary);
}

/* ===== ACTION BUTTON ===== */
.login-btn {
    width: 100%;
    padding: 14px;
    border: none;
    border-radius: 12px;
    background: var(--primary);
    color: #fff;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.login-btn:hover {
    background: var(--primary-dark);
    box-shadow: 0 8px 20px var(--primary-glow);
}

/* ===== 3D OVERLAY ACCESS ===== */
.view-3d-link {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 25px;
    color: #94a3b8;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    transition: 0.3s;
}

.view-3d-link:hover {
    color: var(--primary);
}

.view-3d-link i {
    font-size: 16px;
}

.footer-text {
    margin-top: 35px;
    text-align: center;
    font-size: 11px;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Fullscreen 3D Overlay */
#overlay3D {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.95);
    z-index: 1000;
    display: none;
    animation: fadeIn 0.4s;
}

#overlay3D iframe {
    width: 100%; height: 100%; border: none;
}

.close-btn {
    position: absolute;
    top: 25px; right: 35px;
    color: white; font-size: 40px;
    cursor: pointer; z-index: 1001;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>
</head>

<body>

<div class="background-container"></div>

<div class="login-card">
    <div class="logo-section">
        <img src="../assets/images/banners/image.jpg" alt="University Logo">
    </div>

    <h2>Security Login</h2>
    <div class="subtitle">
        <i class="fas fa-fingerprint"></i> Smart Authentication System
    </div>

    <form action="login_action.php" method="POST">
        <div class="input-field">
            <i class="fas fa-user-lock"></i>
            <input type="text" name="username" placeholder=" " required>
            <label>Security ID</label>
        </div>

        <div class="input-field">
            <i class="fas fa-key"></i>
            <input type="password" name="password" placeholder=" " required>
            <label>Password</label>
        </div>

        <button type="submit" class="login-btn">
            Login to Console
            <i class="fas fa-shield-alt"></i>
        </button>
    </form>

    <a href="javascript:void(0)" class="view-3d-link" onclick="open3D()">
        <i class="fas fa-vr-cardboard"></i> Explore Campus 3D View
    </a>

    <div class="footer-text">
        Powered by Smart Security Framework <br>
        PP Savani University
    </div>
</div>

<div id="overlay3D">
    <span class="close-btn" onclick="close3D()">&times;</span>
    <iframe src="https://ppsu.ac.in/360-view"></iframe>
</div>

<script>
    function open3D() {
        document.getElementById('overlay3D').style.display = 'block';
    }
    function close3D() {
        document.getElementById('overlay3D').style.display = 'none';
    }

    // Gentle parallax for background only (Card remains 100% stable)
    document.addEventListener('mousemove', (e) => {
        const bg = document.querySelector('.background-container');
        let moveX = (e.clientX - window.innerWidth / 2) * 0.01;
        let moveY = (e.clientY - window.innerHeight / 2) * 0.01;
        bg.style.transform = `scale(1.05) translate(${moveX}px, ${moveY}px)`;
    });
</script>

</body>
</html>