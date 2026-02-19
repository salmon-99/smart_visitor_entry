<?php
session_start();
if (!isset($_SESSION['security_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Gateway | Secure QR Scanner</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        :root {
            --brand: #00d2ff;
            --brand-deep: #3a7bd5;
            --bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
            overflow: hidden;
        }

        /* ===== BACKGROUND AMBIENCE ===== */
        body::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            background: var(--brand);
            filter: blur(150px);
            opacity: 0.15;
            z-index: -1;
        }

        /* ===== 3D SCANNER CARD ===== */
        .scanner-card {
            width: 450px;
            background: var(--glass);
            backdrop-filter: blur(25px);
            border: 1px solid var(--glass-border);
            border-radius: 35px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 40px 100px rgba(0,0,0,0.5), 
                        inset 0 1px 1px rgba(255,255,255,0.1);
            position: relative;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        .header-section { margin-bottom: 30px; }
        .header-section i { 
            font-size: 40px; 
            color: var(--brand); 
            margin-bottom: 15px;
            filter: drop-shadow(0 0 10px var(--brand));
        }

        h2 { 
            font-weight: 800; 
            letter-spacing: -1px; 
            font-size: 1.8rem;
            background: linear-gradient(to bottom, #fff, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        p { color: #94a3b8; font-size: 0.9rem; margin-top: 5px; }

        /* ===== SCANNER VIEWFINDER ===== */
        #reader-wrapper {
            position: relative;
            border-radius: 24px;
            overflow: hidden;
            border: 2px solid var(--glass-border);
            box-shadow: 0 0 30px rgba(0,0,0,0.3);
            background: #000;
        }

        #reader { width: 100%; border: none !important; }

        /* Advanced Scan Laser */
        .laser-container {
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            z-index: 10;
            pointer-events: none;
        }

        .laser-line {
            width: 100%;
            height: 2px;
            background: var(--brand);
            box-shadow: 0 0 20px var(--brand), 0 0 40px var(--brand);
            position: absolute;
            animation: laserMove 2.5s infinite ease-in-out;
        }

        @keyframes laserMove {
            0% { top: 10%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }

        /* ===== HUD CORNER ACCENTS ===== */
        .corner {
            position: absolute;
            width: 25px; height: 25px;
            border: 4px solid var(--brand);
            z-index: 20;
        }
        .top-left { top: 20px; left: 20px; border-right: 0; border-bottom: 0; border-radius: 8px 0 0 0; }
        .top-right { top: 20px; right: 20px; border-left: 0; border-bottom: 0; border-radius: 0 8px 0 0; }
        .bottom-left { bottom: 20px; left: 20px; border-right: 0; border-top: 0; border-radius: 0 0 0 8px; }
        .bottom-right { bottom: 20px; right: 20px; border-left: 0; border-top: 0; border-radius: 0 0 8px 0; }

        /* ===== BACK BUTTON ===== */
        .btn-back {
            display: inline-block;
            margin-top: 30px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 600;
            transition: 0.3s;
        }
        .btn-back:hover { color: var(--brand); }

        /* Status Indicator */
        .status-dot {
            display: inline-block;
            width: 8px; height: 8px;
            background: #10b981;
            border-radius: 50%;
            margin-right: 8px;
            box-shadow: 0 0 10px #10b981;
            animation: blink 1.5s infinite;
        }
        @keyframes blink { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

    </style>
</head>
<body>

<div class="scanner-card">
    <div class="corner top-left"></div>
    <div class="corner top-right"></div>
    <div class="corner bottom-left"></div>
    <div class="corner bottom-right"></div>

    <div class="header-section">
        <i class="fas fa-shield-halved"></i>
        <h2>Access Sentinel</h2>
        <p><span class="status-dot"></span> System Live • Awaiting Identification</p>
    </div>

    <div id="reader-wrapper">
        <div id="reader"></div>
        <div class="laser-container">
            <div class="laser-line"></div>
        </div>
    </div>

    <a href="index.php" class="btn-back">
        <i class="fas fa-arrow-left"></i> Return to Console
    </a>
</div>

<audio id="beep">
    <source src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3">
</audio>

<script>
    const beep = document.getElementById("beep");

    function onScanSuccess(decodedText) {
        // High-tech sound effect
        beep.play();
        
        // Brief delay for user to see the success state
        document.querySelector('.laser-line').style.background = "#10b981";
        document.querySelector('.laser-line').style.boxShadow = "0 0 20px #10b981";

        setTimeout(() => {
            // Check if URL or ID
            let visitorID;
            if(decodedText.includes("id=")) {
                visitorID = decodedText.split("id=")[1];
            } else {
                visitorID = decodedText;
            }
            window.location.href = "verify_qr.php?id=" + visitorID;
        }, 500);
    }

    // Configure Scanner with Professional Settings
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "reader", 
        { 
            fps: 20, // Faster tracking
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        }
    );

    html5QrcodeScanner.render(onScanSuccess);

    // Modernize the library's default UI elements via JS
    window.addEventListener('load', () => {
        const btn = document.getElementById('html5-qrcode-button-camera-permission');
        if(btn) {
            btn.style.padding = "12px 25px";
            btn.style.background = "var(--brand)";
            btn.style.color = "white";
            btn.style.border = "none";
            btn.style.borderRadius = "12px";
            btn.style.fontWeight = "bold";
            btn.style.cursor = "pointer";
        }
    });
</script>

</body>
</html>