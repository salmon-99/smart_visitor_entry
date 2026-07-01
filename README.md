index page 
http://localhost/smart_visitor_entry/index.php

ADMIN DASHBOARD
http://localhost/smart_visitor_entry/admin/dashboard.php

UserName: admin
Password :admin@123

ADD USER

http://localhost/smart_visitor_entry/admin/add_user.php


http://localhost/smart_visitor_entry/security/login.php
UserName: security
Password :security@123

 http://localhost/smart_visitor_entry/visitor/login.php
UserName: salmon
Password :salmon@123

http://localhost/smart_visitor_entry/visitor/dashboard.php
user dashboard 
user registration form

# 🏢 Smart Visitor Entry System

A secure and intelligent **Smart Visitor Entry System** developed using **HTML, CSS, JavaScript, PHP, Python, and MySQL**. The system digitalizes visitor registration and verification using **QR Code**, **Face Recognition**, **Email Notifications**, **PDF Report Generation**, and an **AI Chat Assistant**.

---

# 📑 Table of Contents

- Overview
- Features
- Technology Stack
- Folder Structure
- Installation
- Database Configuration
- Running the Project
- Modules
- Security Features
- Future Enhancements
- Author
- License

---

# 📌 Overview

The Smart Visitor Entry System replaces manual visitor registers with a secure digital platform. Visitors can register online, receive QR codes, verify their identity using facial recognition, and gain entry after admin approval. The system also provides AI-powered assistance, PDF report generation, and email notifications.

---

# ✨ Features

## 👤 Visitor Module

- Visitor Registration
- Visitor Login
- Secure Password Authentication
- Visitor Dashboard
- QR Code Generation
- Face Verification
- Download Visitor Pass
- Logout

---

## 👮 Security Module

- Security Login
- QR Code Scanner
- Face Scanner
- Verify Visitor Identity
- Approve Entry
- Reject Entry
- Entry Logs

---

## 👨‍💼 Admin Module

- Admin Login
- Dashboard
- Create Admin
- Add Users
- Manage Visitors
- Generate Reports
- View Visitor Records
- Logout

---

## 🤖 AI Features

- AI Chat Assistant
- Face Matching using Python
- AI-based Visitor Verification

---

## 📧 Email Features

- Visitor Registration Email
- Approval Notification
- QR Code Email

---

## 📄 Reports

- PDF Report Generation
- Visitor History
- Entry Logs
- Download Reports

---

# 🛠 Technology Stack

## Frontend

- HTML5
- CSS3
- JavaScript

## Backend

- PHP

## AI & Face Recognition

- Python
- OpenCV
- face_recognition Library

## Database

- MySQL

## Libraries

- PHPMailer
- PHP QR Code
- FPDF

## Server

- Apache (XAMPP)

## Development Tools

- Visual Studio Code
- phpMyAdmin
- Git & GitHub

---

# 📂 Project Structure

```
SMART_VISITOR_SYSTEM/
│
├── admin/
│   ├── dashboard.php
│   ├── login.php
│   ├── login_action.php
│   ├── logout.php
│   ├── create_admin.php
│   ├── add_admin_action.php
│   ├── add_user.php
│   ├── add_visitor_login.php
│   ├── manage_users.php
│   ├── reports.php
│   └── visitors.php
│
├── visitor/
│   ├── dashboard.php
│   ├── login.php
│   ├── login_action.php
│   ├── logout.php
│   ├── register.php
│   ├── register_action.php
│   ├── pass.php
│   └── success.php
│
├── security/
│   ├── dashboard.php
│   ├── login.php
│   ├── login_action.php
│   ├── logout.php
│   ├── approve.php
│   ├── reject.php
│   ├── scan_qr.php
│   ├── verify_qr.php
│   ├── scan_face.php
│   ├── verify_face.php
│   └── ai.py
│
├── api/
│   └── face_match.php
│
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
│
├── uploads/
├── logs/
│   └── entry_log.php
│
├── phpmailer/
├── phpqrcode/
├── fpdf/
│
├── ai_chat.php
├── config.php
├── generate_pdfs.php
├── hash_gen.php
├── send_email.php
├── index.php
├── test_db.php
└── README.md
```

---

# ⚙ Software Requirements

- PHP 8.0+
- Python 3.10+
- MySQL 8+
- Apache Server
- XAMPP
- phpMyAdmin
- Visual Studio Code

---

# 📦 Python Libraries

Install the required Python packages:

```bash
pip install opencv-python
pip install face_recognition
pip install numpy
pip install pillow
```

---

# 🚀 Installation

## Step 1

Clone the repository.

```bash
git clone https://github.com/your-username/smart-visitor-system.git
```

---

## Step 2

Move the project into:

```
C:\xampp\htdocs\smart_visitor_system
```

---

## Step 3

Start XAMPP.

Enable:

- Apache
- MySQL

---

## Step 4

Create Database

```
smart_visitor_system
```

---

## Step 5

Import

```
smart_visitor_system.sql
```

---

## Step 6

Configure Database

Open

```
config.php
```

```php
<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "smart_visitor_system"
);

if(!$conn){
    die("Database Connection Failed");
}

?>
```

---

# ▶ Run the Project

Open your browser.

```
http://localhost/smart_visitor_system/
```

---

# 🔐 User Roles

### Visitor

- Register
- Login
- View Dashboard
- Generate QR Pass
- Face Verification
- Download Pass

### Security

- Login
- Scan QR Code
- Verify Face
- Approve Visitor
- Reject Visitor
- View Entry Logs

### Admin

- Login
- Dashboard
- Add Admin
- Add Users
- Manage Visitors
- Generate Reports

---

# 🗄 Database Tables

- admins
- visitors
- users
- security_users
- visitor_logs
- qr_codes
- face_data
- reports

---

# 📷 Screenshots

- Home Page
- Visitor Registration
- Visitor Login
- Visitor Dashboard
- Admin Dashboard
- Security Dashboard
- QR Scanner
- Face Verification
- Reports
- AI Chat

---

# 🔒 Security Features

- Password Hashing
- Session Authentication
- QR Code Verification
- Face Recognition
- Entry Logging
- Email Verification

---

# 🚀 Future Enhancements

- Mobile Application
- Biometric Attendance
- Aadhaar Verification
- OTP Authentication
- SMS Notifications
- Cloud Deployment (AWS)
- Real-Time Camera Detection
- Analytics Dashboard
- Multi-Organization Support
- Visitor Appointment Scheduling

---

# 👨‍💻 Author

**Your Name**

B.Tech Final Year Major Project

---

# 📜 License

This project is licensed under the **MIT License**.

---

# 🙏 Acknowledgements

- PHP
- Python
- OpenCV
- face_recognition
- MySQL
- HTML5
- CSS3
- JavaScript
- PHPMailer
- PHP QR Code
- FPDF
- XAMPP
- Visual Studio Code
- GitHub

---

## ⭐ Support

If you found this project useful, please ⭐ **Star this repository** on GitHub.

**Thank you for visiting this project!**
