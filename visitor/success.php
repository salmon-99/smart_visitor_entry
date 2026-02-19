<?php 
session_start(); 
if (!isset($_SESSION['visitor_qr'])) { 
    header("Location: register.php"); 
    exit(); 
} 

$qr = $_SESSION['visitor_qr']; 
$name = $_SESSION['visitor_name']; 
$date = $_SESSION['visitor_date']; 
$purpose = $_SESSION['visitor_purpose']; 
$host = $_SESSION['visitor_host']; 
$mobile = $_SESSION['visitor_mobile']; 
$photo_filename = isset($_SESSION['visitor_photo']) ? $_SESSION['visitor_photo'] : ''; 

// --- PERMANENT PATH FIX ---
// This finds C:/xampp/htdocs automatically
$root = $_SERVER['DOCUMENT_ROOT']; 

// The physical location for PHP to check if the file exists
$check_path = $root . "/SMART_VISITOR_ENTRY/uploads/visitor_photos/" . $photo_filename; 

// The web URL for the browser to show the image
$img_src = "/SMART_VISITOR_ENTRY/uploads/visitor_photos/" . $photo_filename; 

$file_found = (!empty($photo_filename) && file_exists($check_path)); 
?> 
<!DOCTYPE html> 
<html lang="en"> 
<head> 
    <meta charset="UTF-8"> 
    <title>Visitor Portal | PP Savani University</title> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet"> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script> 
    <style> 
        :root { 
            --primary: #0a2a43; 
            --secondary: #7a123c; 
            --accent: #1e8449; 
            --pps-green: #4CAF50; 
            --qr-color: #f39c12;
        } 
        body { 
            margin: 0; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            align-items: center; 
            background: radial-gradient(circle at top,#0f3a5f,#071c2d); 
            font-family: 'Poppins', sans-serif; 
            padding: 40px 20px; 
        } 
        .ticket { 
            display: flex; 
            width: 780px; 
            background: #fff; 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: 0 30px 60px rgba(0,0,0,0.5); 
            margin-bottom: 30px; 
        } 
        .ticket-main { 
            flex: 3; 
            padding: 30px; 
            position: relative; 
            border-right: 2px dashed #eee; 
        } 
        .header { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            margin-bottom: 20px; 
        } 
        .logo { width: 80px; } 
        .content { 
            display: grid; 
            grid-template-columns: 140px 1fr; 
            gap: 20px; 
        } 
        .photo-box { 
            width: 140px; 
            height: 160px; 
            border-radius: 10px; 
            background: #f4f7f9; 
            overflow: hidden; 
            border: 1px solid #ddd; 
        } 
        .photo-box img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        } 
        .details b { 
            color: var(--primary); 
            width: 85px; 
            display: inline-block; 
        } 
        .ticket-strip { 
            flex: 1.2; 
            background: linear-gradient(180deg, var(--secondary), #4b0d26); 
            color: #fff; 
            padding: 20px; 
            text-align: center; 
            display: flex; 
            flex-direction: column; 
            justify-content: center; 
            align-items: center;
        } 
        .qr-box { 
            background: #fff; 
            padding: 8px; 
            border-radius: 10px; 
            margin: 15px 0; 
        } 
        .qr-box img { 
            width: 110px; 
            height: 110px; 
        } 
        #idCard { 
            width: 350px; 
            height: 540px; 
            background: #fff; 
            border-radius: 15px; 
            overflow: hidden; 
            position: absolute; 
            left: -9999px; 
            box-shadow: 0 20px 50px rgba(0,0,0,0.4); 
            border: 1px solid #ddd; 
        } 
        .id-sidebar { 
            position: absolute; 
            left: 0; 
            top: 100px; 
            bottom: 0; 
            width: 40px; 
            background: linear-gradient(to bottom, #79b433, #438a2a); 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            z-index: 2; 
        } 
        .id-sidebar span { 
            transform: rotate(-90deg); 
            color: #fff; 
            font-weight: 700; 
            white-space: nowrap; 
            letter-spacing: 5px; 
            font-size: 18px; 
            opacity: 0.8; 
        } 
        .id-header { 
            height: 120px; 
            padding: 20px; 
            text-align: center; 
            border-bottom: 4px solid var(--secondary); 
            background: #fff; 
        } 
        .id-header img { width: 180px; } 
        .id-photo-container { 
            margin-top: -50px; 
            display: flex; 
            justify-content: center; 
            z-index: 5; 
            position: relative; 
        } 
        .id-photo-circ { 
            width: 140px; 
            height: 140px; 
            border-radius: 50%; 
            border: 5px solid #fff; 
            overflow: hidden; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.2); 
            background: #eee; 
        } 
        .id-photo-circ img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        } 
        .id-content { 
            padding: 20px 20px 20px 60px; 
            text-align: center; 
        } 
        .id-content h2 { 
            margin: 10px 0 5px 0; 
            color: var(--primary); 
            font-size: 22px; 
            text-transform: uppercase; 
            font-weight: 700; 
        } 
        .id-role { 
            color: var(--secondary); 
            font-weight: 600; 
            font-size: 14px; 
            display: block; 
            margin-bottom: 20px; 
            letter-spacing: 1px; 
        } 
        .id-info-table { 
            width: 100%; 
            text-align: left; 
            font-size: 13px; 
            border-collapse: collapse; 
        } 
        .id-info-table td { 
            padding: 6px 0; 
            border-bottom: 1px solid #f0f0f0; 
        } 
        .id-info-table .label { 
            font-weight: 700; 
            color: var(--primary); 
            width: 80px; 
        } 
        .id-footer { 
            position: absolute; 
            bottom: 0; 
            width: 100%; 
            background: var(--primary); 
            color: #fff; 
            font-size: 10px; 
            padding: 8px 0; 
            text-align: center; 
            letter-spacing: 1px; 
        } 
        .actions { 
            display: flex; 
            gap: 10px; 
            margin-top: 20px; 
            flex-wrap: wrap; 
            justify-content: center; 
        } 
        .btn { 
            padding: 12px 24px; 
            border-radius: 8px; 
            border: none; 
            cursor: pointer; 
            font-size: 14px; 
            font-weight: 600; 
            color: #fff; 
            transition: 0.3s; 
            text-transform: uppercase; 
        } 
        .btn-print { background: var(--primary); } 
        .btn-id { background: var(--secondary); } 
        .btn-download { background: var(--accent); } 
        .btn-qr { background: #e67e22; } /* Orange color for QR */
        .btn:hover { 
            opacity: 0.9; 
            transform: translateY(-2px); 
        } 
        @media print { 
            body { background: #fff; padding: 0; } 
            .actions, .ticket, h2 { display: none !important; } 
            #idCard { 
                position: relative !important; 
                left: 0 !important; 
                box-shadow: none; 
                border: 1px solid #000; 
                margin: 0 auto; 
            } 
        } 
    </style> 
</head> 
<body> 
    <h2 style="color: #fff; margin-bottom: 20px;">Visitor Registration Success</h2> 
    <div class="ticket" id="visitorPass"> 
        <div class="ticket-main"> 
            <div class="header"> 
                <div> 
                    <h2 style="margin:0; color:var(--primary);">VISITOR GATE PASS</h2> 
                    <small>PP Savani University • Campus Security</small> 
                </div> 
                <img src="../assets/images/banners/image.jpg" class="logo"> 
            </div> 
            <div class="content"> 
                <div class="photo-box"> 
                    <?php if($file_found): ?> 
                        <img src="<?= $img_src ?>?v=<?= time() ?>" crossorigin="anonymous"> 
                    <?php else: ?> 
                        <div style="padding:10px; font-size:10px; color:#999; text-align:center; margin-top:50px;">NO PHOTO</div> 
                    <?php endif; ?> 
                </div> 
                <div class="details"> 
                    <p><b>Name:</b> <?= htmlspecialchars($name) ?></p> 
                    <p><b>Host:</b> <?= htmlspecialchars($host) ?></p> 
                    <p><b>Purpose:</b> <?= htmlspecialchars($purpose) ?></p> 
                    <p><b>Date:</b> <?= htmlspecialchars($date) ?></p> 
                    <p><b>Mobile:</b> <?= htmlspecialchars($mobile) ?></p> 
                </div> 
            </div> 
        </div> 
        <div class="ticket-strip"> 
            <div class="qr-box"> 
                <img src="../<?= $qr ?>" id="qrCodeImg" crossorigin="anonymous"> 
            </div> 
            <small>ENTRY AUTHORIZED</small> 
        </div> 
    </div> 
    <div id="idCard"> 
        <div class="id-sidebar"> 
            <span>VISITOR</span> 
        </div> 
        <div class="id-header"> 
            <img src="../assets/images/banners/image.jpg"> 
        </div> 
        <div class="id-photo-container"> 
            <div class="id-photo-circ"> 
                <?php if($file_found): ?> 
                    <img src="<?= $img_src ?>?v=<?= time() ?>" crossorigin="anonymous"> 
                <?php else: ?> 
                    <div style="height:100%; background:#ccc;"></div> 
                <?php endif; ?> 
            </div> 
        </div> 
        <div class="id-content"> 
            <h2><?= htmlspecialchars($name) ?></h2> 
            <span class="id-role">OFFICIAL VISITOR</span> 
            <table class="id-info-table"> 
                <tr> 
                    <td class="label">Host</td> <td>: <?= htmlspecialchars($host) ?></td> 
                </tr> 
                <tr> 
                    <td class="label">Purpose</td> <td>: <?= htmlspecialchars($purpose) ?></td> 
                </tr> 
                <tr> 
                    <td class="label">Date</td> <td>: <?= htmlspecialchars($date) ?></td> 
                </tr> 
                <tr> 
                    <td class="label">Validity</td> <td>: Single Entry</td> 
                </tr> 
            </table> 
        </div> 
        <div class="id-footer"> www.ppsu.ac.in • PP Savani University </div> 
    </div> 
    <div class="actions"> 
        <button class="btn btn-print" onclick="window.print()">Print Gate Pass</button> 
        <button class="btn btn-id" onclick="printID()">Print ID Card</button> 
        <button class="btn btn-qr" onclick="downloadQR()">Download QR Code</button>
        <button class="btn btn-download" onclick="downloadElement('idCard', 'Visitor_ID')">Download ID Card</button> 
        <button class="btn btn-download" onclick="downloadElement('visitorPass', 'Gate_Pass')">Download Pass</button> 
    </div> 
    <script> 
        function printID() { 
            const pass = document.getElementById('visitorPass'); 
            pass.style.display = 'none'; 
            window.print(); 
            pass.style.display = 'flex'; 
        } 

        function downloadQR() {
            const qrImg = document.getElementById('qrCodeImg');
            const link = document.createElement('a');
            link.download = 'QR_<?= str_replace(' ', '_', $name) ?>.png';
            link.href = qrImg.src;
            link.click();
        }

        function downloadElement(elementId, fileName) { 
            const element = document.getElementById(elementId); 
            const originalPos = element.style.position; 
            const originalLeft = element.style.left; 
            if(elementId === 'idCard') { 
                element.style.position = 'relative'; 
                element.style.left = '0'; 
            } 
            html2canvas(element, { scale: 3, useCORS: true, backgroundColor: "#ffffff" }).then(canvas => { 
                const link = document.createElement('a'); 
                link.download = fileName + '_<?= str_replace(' ', '_', $name) ?>.png'; 
                link.href = canvas.toDataURL("image/png"); 
                link.click(); 
                element.style.position = originalPos; 
                element.style.left = originalLeft; 
            }); 
        } 
    </script> 
</body> 
</html>