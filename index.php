<?php
session_start();
date_default_timezone_set("Asia/Kolkata");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Smart Visitor Login System | PP Savani University</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --primary:#0a2a43;
    --secondary:#174e7a;
    --accent:#1abc9c;
    --dark:#051622;
    --bg:#f4f7fb;
    --glass: rgba(255, 255, 255, 0.1);
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Inter',sans-serif;
    scroll-behavior: smooth;
}

body{
    background:var(--bg);
    color:var(--dark);
}

/* ===== TOP BAR ===== */
.top-bar{
    background:#081f33;
    color:#fff;
    padding:8px 50px;
    font-size:13px;
    display:flex;
    justify-content:space-between;
}

/* ===== HEADER ===== */
.header{
    background:var(--primary);
    padding:18px 50px;
    display:flex;
    align-items:center;
    gap:15px;
}

.header img{
    width:60px;
    background:#fff;
    padding:6px;
    border-radius:6px;
}

.header h1{
    font-size:22px;
    color:#fff;
}

.header span{
    font-size:13px;
    color:#cfdceb;
}

/* ===== NAVBAR ===== */
.navbar{
    background:#fff;
    border-bottom:1px solid #e1e6ef;
    padding:14px 50px;
}

.navbar ul{
    list-style:none;
    display:flex;
    gap:35px;
}

.navbar a{
    text-decoration:none;
    font-size:14px;
    font-weight:600;
    color:var(--primary);
}

/* ===== HERO SLIDER ===== */
.hero-slider{
    position:relative;
    height:90vh;
    overflow:hidden;
}

.slide{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    opacity:0;
    transition:opacity 1.2s ease-in-out;
}

.slide.active{
    opacity:1;
}

.overlay{
    position:absolute;
    inset:0;
    background:linear-gradient(rgba(8,31,51,0.7), rgba(8,31,51,0.85));
    z-index:1;
}

.hero-content{
    position:relative;
    z-index:2;
    max-width:900px;
    padding:120px 80px;
    color:#fff;
}

.hero-content h2{
    font-size:46px;
    font-weight:700;
    line-height:1.2;
    margin-bottom:18px;
}

.hero-content p{
    font-size:17px;
    line-height:1.8;
    max-width:780px;
}

.hero-actions{
    margin-top:35px;
    display:flex;
    gap:20px;
}

.btn-primary{
    background:var(--accent);
    padding:15px 36px;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    font-weight:600;
}

.btn-outline{
    border:2px solid #fff;
    padding:13px 36px;
    color:#fff;
    text-decoration:none;
    border-radius:6px;
    font-weight:600;
}

/* ===== ENTERPRISE CAPABILITIES SECTION ===== */
.section{
    max-width:1300px;
    margin:90px auto;
    padding:0 40px;
}

.section h3{
    font-size:32px;
    color:var(--primary);
    margin-bottom:15px;
}

.section .subtitle {
    font-size:15px;
    line-height:1.6;
    color:#555;
    margin-bottom: 40px;
    max-width:900px;
}

.features{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(350px,1fr));
    gap:25px;
}

.feature{
    background:#fff;
    padding:35px;
    border-radius:14px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    transition:0.3s;
}

.feature h4{
    color:var(--primary);
    margin-bottom:12px;
    font-size: 18px;
}

.feature p{
    font-size:14px;
    color: #666;
    line-height:1.6;
}

/* ===== ROADMAP (How It Works) ===== */
.roadmap-container {
    background: var(--dark);
    padding: 100px 50px;
    color: #fff;
    text-align: center;
}

.roadmap-header h3 { font-size: 38px; color: #fff; margin-bottom: 20px; }

.roadmap-desc {
    max-width: 800px;
    margin: 0 auto 30px;
    color: #94a3b8;
    font-size: 16px;
    line-height: 1.6;
}

.timeline {
    position: relative;
    max-width: 1000px;
    margin: 60px auto 0;
}

.timeline::after {
    content: '';
    position: absolute;
    width: 2px;
    background: var(--accent);
    top: 0; bottom: 0; left: 50%;
    margin-left: -1px;
}

.container {
    padding: 10px 40px;
    position: relative;
    background-color: inherit;
    width: 50%;
}

.container::after {
    content: '';
    position: absolute;
    width: 20px;
    height: 20px;
    right: -10px;
    background-color: var(--accent);
    border: 4px solid var(--dark);
    top: 15px;
    border-radius: 50%;
    z-index: 1;
}

.left { left: 0; text-align: right; }
.right { left: 50%; text-align: left; }
.right::after { left: -10px; }

.content {
    padding: 20px 30px;
    background: rgba(255,255,255,0.05);
    position: relative;
    border-radius: 15px;
    border: 1px solid rgba(255,255,255,0.1);
    transition: 0.3s;
}

.content h2 { font-size: 20px; color: var(--accent); margin-bottom: 10px; }
.content p { font-size: 14px; color: #cbd5e1; }

/* ===== STATS ===== */
.stats{
    background:var(--primary);
    color:#fff;
    padding:70px 40px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:40px;
    text-align:center;
}

.stats h2{ font-size:36px; }

/* ===== FAQ SECTION ===== */
.image-faq-section {
    padding: 100px 20px;
    background: #fdfdfd;
    text-align: center;
}

.faq-title { font-size: 42px; font-weight: 700; margin-bottom: 10px; color: #000; }
.faq-subtitle { font-size: 16px; color: #444; margin-bottom: 60px; line-height: 1.6; }

.chat-box-container {
    max-width: 750px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.chat-item { display: flex; align-items: flex-end; width: 100%; position: relative; }
.chat-item.user-side { justify-content: flex-end; margin-bottom: 10px; }
.chat-item.bot-side { justify-content: flex-start; margin-bottom: 25px; }

.bubble {
    padding: 18px 25px;
    font-size: 15px;
    max-width: 75%;
    line-height: 1.5;
}

.user-bubble-style { 
    background: #18181b; 
    color: #fff; 
    border-radius: 25px 25px 5px 25px; 
}

.bot-bubble-style { 
    background: #fff; 
    color: #111; 
    border: 1px solid #e4e4e7; 
    box-shadow: 0 4px 12px rgba(0,0,0,0.03); 
    border-radius: 25px 25px 25px 5px; 
    text-align: left;
    margin-left: 55px;
}

.profile-img { 
    width: 40px; height: 40px; 
    border-radius: 50%; 
    position: absolute; 
    left: 0; bottom: 0;
    border: 1px solid #ddd;
}

.trigger-btn { 
    width: 34px; height: 34px; 
    background: #18181b; 
    color: #fff; 
    border-radius: 50%; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    cursor: pointer; 
    margin-right: 12px; 
    font-size: 12px;
}

.trigger-btn.inactive { background: #f4f4f5; color: #71717a; }

/* ===== FOOTER SECTION ===== */
footer {
    background: #051622;
    color: #fff;
    padding: 80px 50px 30px;
}

.footer-top {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 40px;
    text-align: left;
    margin-bottom: 50px;
    max-width: 1300px;
    margin-left: auto;
    margin-right: auto;
}

.footer-col h4 {
    color: var(--accent);
    margin-bottom: 25px;
    font-size: 18px;
    font-weight: 700;
}

.footer-col p {
    font-size: 14px;
    line-height: 1.6;
    color: #cbd5e1;
    margin-bottom: 15px;
}

.footer-col a {
    color: #cbd5e1;
    text-decoration: none;
    display: block;
    margin-bottom: 12px;
    font-size: 14px;
    transition: 0.3s;
}

.footer-col a:hover {
    color: var(--accent);
    padding-left: 5px;
}

.social-links {
    display: flex;
    gap: 15px;
    margin-top: 15px;
}

.social-links a {
    background: rgba(255,255,255,0.1);
    width: 38px;
    height: 38px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-bottom: 0;
    font-size: 16px;
}

.social-links a:hover {
    background: var(--accent);
    color: #fff;
    padding-left: 0;
    transform: translateY(-3px);
}

.map-container {
    width: 100%;
    height: 120px;
    border-radius: 8px;
    overflow: hidden;
    margin-top: 15px;
    border: 1px solid rgba(255,255,255,0.1);
}

.copyright {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.1);
    font-size: 13px;
    color: #94a3b8;
}

@media (max-width: 768px) {
    .timeline::after { left: 31px; }
    .container { width: 100%; padding-left: 70px; text-align: left; }
    .right { left: 0%; }
    .container::after { left: 21px; }
    footer { padding: 50px 20px 30px; }
}
</style>
</head>

<body>

<div class="top-bar">
    <div>PP Savani University – Campus Security Automation</div>
    <div><i class="far fa-clock"></i> <?php echo date("d M Y | h:i A"); ?></div>
</div>

<div class="header">
    <img src="assets/images/banners/image.jpg" alt="PPSU">
    <div>
        <h1>PP Savani University</h1>
        <span>Smart Visitor Login System</span>
    </div>
</div>

<div class="navbar">
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="visitor/register.php">Visitor Registration</a></li>
        <li><a href="security/login.php">Security Login</a></li>
        <li><a href="admin/login.php">System Administration</a></li>
    </ul>
</div>

<section class="hero-slider">
    <div class="slide active" style="background-image:url('assets/images/banners/banner1.jpg')"></div>
    <div class="overlay"></div>
    <div class="hero-content">
        <h2>PP SAVANI UNIVERSITY <br>Smart Visitor Entry System</h2>
        <p>A next-generation campus security platform that enables digital visitor registration, approval, and biometric face verification.</p>
        <div class="hero-actions">
            <a href="visitor/register.php" class="btn-primary">Register as Visitor</a>
            <a href="security/login.php" class="btn-outline">Security Access</a>
        </div>
    </div>
</section>

<section class="section">
    <h3>Enterprise-Grade Capabilities</h3>
    <p class="subtitle">The Smart Visitor Login System modernizes traditional campus entry processes by combining web technologies with biometric authentication and role-based security workflows.</p>
    <div class="features">
        <div class="feature"><h4>Pre-Visit Registration</h4><p>Visitors register online before arrival for faster approval.</p></div>
        <div class="feature"><h4>Security Approval Workflow</h4><p>Authorized security staff review and approve visitor requests.</p></div>
        <div class="feature"><h4>Face Recognition Verification</h4><p>Biometric authentication ensures secure and ID-free entry.</p></div>
        <div class="feature"><h4>Central Visitor Database</h4><p>All visitor records are stored securely with audit logs.</p></div>
        <div class="feature"><h4>Live Entry Monitoring</h4><p>Real-time tracking of visitor entry and exit activities.</p></div>
        <div class="feature"><h4>Institutional Compliance</h4><p>Designed to meet university security and privacy standards.</p></div>
    </div>
</section>

<section class="roadmap-container" id="roadmap">
    <div class="roadmap-header"><h3>How It Works</h3></div>
    <p class="roadmap-desc">Our automated workflow is designed to eliminate manual paperwork and long waiting queues at the university gates.</p>

    <div class="timeline">
        <div class="container left"><div class="content"><h2>01. Sign Up</h2><p>Register identity via our portal.</p></div></div>
        <div class="container right"><div class="content"><h2>02. AI Assessment</h2><p>System routes to security desk.</p></div></div>
        <div class="container left"><div class="content"><h2>03. Security Review</h2><p>Authorized security staff review and approve requests.</p></div></div>
        <div class="container right"><div class="content"><h2>04. Face Recognition</h2><p>Biometric authentication ensures secure, hands-free check-in.</p></div></div>
        <div class="container left"><div class="content"><h2>05. Institutional Log</h2><p>Entry records are securely archived for audit and compliance.</p></div></div>
    </div>
</section>

<section class="stats">
    <div><h2>100%</h2><span>Paperless Entry</span></div>
    <div><h2>AI</h2><span>Face Recognition</span></div>
    <div><h2>24×7</h2><span>Security Monitoring</span></div>
</section>

<section class="image-faq-section">
    <h2 class="faq-title">Smart Visitor Login System</h2>
    <p class="faq-subtitle">We Get It—Curiosity Leads to Success! Got questions? That’s a great sign.</p>

    <div class="chat-box-container">
        <div class="chat-item user-side">
            <div class="trigger-btn inactive" onclick="toggleFaq(1, this)"><i class="fas fa-plus"></i></div>
            <div class="bubble user-bubble-style" style="background:#f4f4f5; color:#111;"> What is the Smart Visitor Login System?</div>
        </div>
        <div class="chat-item bot-side" id="ans-1" style="display:none;">
            <img src="assets/images/banners/images2.jpg" class="profile-img" alt="Staff">
            <div class="bubble bot-bubble-style">Iam RAFI SHAIK, The Smart Visitor Login System is a digital campus security solution designed to manage visitor entry using online registration, QR code verification, and AI-based face recognition, ensuring secure, paperless, and controlled access to the university campus. </div>
        </div>

        <div class="chat-item user-side">
            <div class="trigger-btn inactive" onclick="toggleFaq(2, this)"><i class="fas fa-plus"></i></div>
            <div class="bubble user-bubble-style" style="background:#f4f4f5; color:#111;"> Why should visitors choose this system?</div>
        </div>
        <div class="chat-item bot-side" id="ans-2" style="display:none;">
            <img src="assets/images/banners/images3.jpg" class="profile-img" alt="Staff">
            <div class="bubble bot-bubble-style">Iam GANTA PRAVEEN KUMAR, Visitors choose this system because it offers faster entry, enhanced security, and zero paperwork. It eliminates long waiting times at security gates and ensures a smooth, professional campus experience.</div>
        </div>

        <div class="chat-item user-side">
            <div class="trigger-btn inactive" id="btn-3" onclick="toggleFaq(3, this)"><i class="fas fa-plus"></i></div>
            <div class="bubble user-bubble-style" style="background:#f4f4f5; color:#111;">Is the visitor registration process safe?</div>
        </div>
        <div class="chat-item bot-side" id="ans-3" style="display:none;">
            <img src="assets/images/banners/image4.jpg" class="profile-img" alt="Staff">
            <div class="bubble bot-bubble-style">Iam PAMIDI SALMON RAJU, Yes. All visitor data is stored securely in a protected database with restricted access. The system follows institutional privacy standards and prevents misuse of personal information.</div>
        </div>
    </div>
</section>

<footer>
    <div class="footer-top">
        <div class="footer-col">
            <h4>Campus Location</h4>
            <p>NH 8, GETCO, Near-Dhamdod, Surat, Gujarat 394125</p>
            <a href="https://maps.google.com" target="_blank"><i class="fas fa-directions"></i> Get Directions</a>
            <div class="map-container">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.123!2d72.99!3d21.45!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjHCsDI3JzAwLjAiTiA3MsKwNTknMjQuMCJF!5e0!3m2!1sen!2sin!4v1625000000000!5m2!1sen!2sin" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            </div>
        </div>
        
        <div class="footer-col">
            <h4>Quick Links</h4>
            <a href="https://ppsu.ac.in/360-view" target="_blank"><i class="fas fa-cube"></i> Campus 3D View</a>
            <a href="index.php">Home</a>
            <a href="visitor/register.php">Visitor Portal</a>
            <a href="security/login.php">Staff Login</a>
        </div>

        <div class="footer-col">
            <h4>Contact Info</h4>
            <a href="tel:+919512035611"><i class="fas fa-phone"></i> +91 95120 35611</a>
            <a href="mailto:info@ppsu.ac.in"><i class="fas fa-envelope"></i> info@ppsu.ac.in</a>
            <p style="margin-top:20px;">Working Hours:<br>Mon - Sat: 9:00 AM - 5:00 PM</p>
        </div>

        <div class="footer-col">
            <h4>Follow PPSU</h4>
            <p>Connect with us on our official social handles.</p>
            <div class="social-links">
                <a href="https://www.instagram.com/pp_savani_university/" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.linkedin.com/school/pp-savani-university/" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                <a href="https://www.facebook.com/PPSavaniUniversity/" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://twitter.com/ppsavaniuni" target="_blank"><i class="fab fa-twitter"></i></a>
            </div>
        </div>
    </div>
    
    <div class="copyright">
        © <?php echo date("Y"); ?> PP Savani University | Smart Visitor Login System
    </div>
</footer>

<script>
function toggleFaq(id, btn) {
    const ans = document.getElementById('ans-' + id);
    const bubble = btn.nextElementSibling;
    const icon = btn.querySelector('i');
    
    if (ans.style.display === 'none' || ans.style.display === '') {
        ans.style.display = 'flex';
        icon.className = 'fas fa-minus';
        btn.classList.remove('inactive');
        bubble.style.background = '#18181b';
        bubble.style.color = '#fff';
    } else {
        ans.style.display = 'none';
        icon.className = 'fas fa-plus';
        btn.classList.add('inactive');
        bubble.style.background = '#f4f4f5';
        bubble.style.color = '#111';
    }
}
</script>

</body>
</html>