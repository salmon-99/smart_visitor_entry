
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Portal | PP Savani University</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --brand-dark: #0a0a0b;
            --brand-gold: #c5a059;
            --border-light: #f1f5f9;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #fcfcfc;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        /* Top Navigation for Logo Only */
        .top-nav {
            width: 100%;
            max-width: 1100px;
            padding: 20px 0;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-section img {
            height: 50px;
            width: auto;
        }

        .logo-section h2 {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            letter-spacing: 1px;
            color: var(--brand-dark);
        }

        /* Main Container */
        .main-wrapper {
            width: 100%;
            max-width: 1100px;
            display: flex;
            background: var(--bg-white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 40px 100px rgba(0,0,0,0.08);
            border: 1px solid var(--border-light);
            height: 780px;
        }

        /* Left Side: Founder & Heading */
        .side-panel {
            flex: 1;
            background: var(--brand-dark);
            padding: 60px;
            display: flex;
            flex-direction: column;
            color: white;
            position: relative;
        }

        .founder-box {
            margin-bottom: 40px;
        }

        .founder-box span {
            display: block;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--brand-gold);
            letter-spacing: 2px;
            margin-bottom: 15px;
        }

        .founder-img {
            width: 140px;
            height: 140px;
            border-radius: 20px; /* Modern square-round look */
            border: 2px solid var(--brand-gold);
            object-fit: cover;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .side-content h1 {
            font-family: 'Syne', sans-serif;
            font-size: 44px;
            line-height: 1.1;
            margin-bottom: 20px;
            background: linear-gradient(to bottom right, #fff, #a1a1aa);
            -webkit-backgroundclip: text;
            -webkit-text-fill-color: transparent;
        }

        .side-content p {
            color: #888;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Right Side: Form */
        .form-panel {
            flex: 1.2;
            padding: 60px;
            background: #ffffff;
            overflow-y: auto;
        }

        .form-header h3 {
            font-size: 26px;
            font-weight: 800;
            color: var(--brand-dark);
            margin-bottom: 30px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .full-width { grid-column: span 2; }

        .input-box {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .input-box label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
        }

        .input-box input, 
        .input-box textarea {
            width: 100%;
            padding: 12px 0;
            border: none;
            border-bottom: 2px solid #edf2f7;
            background: transparent;
            font-size: 16px;
            font-weight: 500;
            transition: all 0.3s ease;
            outline: none;
            color: var(--brand-dark);
        }

        .input-box input:focus, 
        .input-box textarea:focus {
            border-bottom-color: var(--brand-gold);
        }

        /* File Upload Area */
        .upload-wrapper {
            margin-top: 10px;
            border: 2px dashed #edf2f7;
            border-radius: 16px;
            padding: 25px;
            display: flex;
            align-items: center;
            gap: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .upload-wrapper:hover {
            background: #f8fafc;
            border-color: var(--brand-gold);
        }

        .upload-circle {
            width: 50px;
            height: 50px;
            background: #f1f5f9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--brand-gold);
        }

        #preview-img {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            object-fit: cover;
            display: none;
        }

        /* Submit Button */
        .submit-btn {
            width: 100%;
            padding: 20px;
            background: var(--brand-dark);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            margin-top: 40px;
            cursor: pointer;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: var(--brand-gold);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(197, 160, 89, 0.2);
        }

        @media (max-width: 900px) {
            .main-wrapper { flex-direction: column; height: auto; }
            .side-panel { padding: 40px; }
            .form-panel { padding: 40px; }
            .form-grid { grid-template-columns: 1fr; }
            .full-width { grid-column: span 1; }
        }
    </style>
</head>
<body>

    <div class="top-nav">
        <div class="logo-section">
            <img src="../assets/images/banners/image.jpg" alt="Logo">
            <h2>PP Savani University</h2>
        </div>
    </div>

    <div class="main-wrapper">
        <div class="side-panel">
            <div class="founder-box">
                <span>Founder of PPSU</span>
                <img src="../assets/images/banners/photo.jpg" alt="Founder of PPSU" class="founder-img">
            </div>
            
            <div class="side-content">
                <h1>Smart <br>Visitor <br>Entry.</h1>
                <p>Ensuring campus safety through advanced pre-registration and verified security protocols.</p>
            </div>
        </div>

        <div class="form-panel">
            <div class="form-header">
                <h3>Visitor Registration</h3>
            </div>
            
            <form action="register_action.php" method="POST" enctype="multipart/form-data">
                <div class="form-grid">
                    
                    <div class="input-box full-width">
                        <label>Visitor Full Name</label>
                        <input type="text" name="full_name" placeholder="Full name as per ID" required>
                    </div>

                    <div class="input-box">
                        <label>Mobile Number</label>
                        <input type="tel" name="mobile" placeholder="+91" required>
                    </div>

                    <div class="input-box">
                        <label>Host / Department</label>
                        <input type="text" name="host_name" placeholder="Person you are visiting" required>
                    </div>

                    <div class="input-box">
                        <label>Visit Date</label>
                        <input type="date" name="visit_date" required>
                    </div>

                    <div class="input-box">
                        <label>Visit Time</label>
                        <input type="time" name="visit_time">
                    </div>

                    <div class="input-box full-width">
                        <label>Purpose</label>
                        <input type="text" name="purpose" placeholder="Brief reason for your visit" required>
                    </div>

                    <div class="full-width">
                        <label>Security Identification</label>
                        <div class="upload-wrapper" onclick="document.getElementById('file-input').click()">
                            <div class="upload-circle" id="icon-placeholder">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <img id="preview-img" src="">
                            <div>
                                <p style="font-weight: 700; font-size: 14px;">Identity Photo</p>
                                <p style="font-size: 12px; color: var(--text-muted);">Click to upload or capture</p>
                            </div>
                            <input type="file" name="photo" id="file-input" hidden accept="image/*" onchange="previewFile()">
                        </div>
                    </div>

                </div>

                <button class="submit-btn">
                    Complete Entry Request
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('preview-img');
            const file = document.getElementById('file-input').files[0];
            const icon = document.getElementById('icon-placeholder');
            const reader = new FileReader();

            reader.addEventListener("load", function () {
                preview.src = reader.result;
                preview.style.display = "block";
                icon.style.display = "none";
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>
</body>
</html>
