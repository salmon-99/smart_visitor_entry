
<?php
/**
 * ==========================================
 * Entry Log Viewer
 * Smart Visitor Login System
 * ==========================================
 */

session_start();
require_once("../config.php");

// Allow only logged-in security/admin
if (!isset($_SESSION['security_logged_in'])) {
    header("Location: ../security/login.php");
    exit();
}

$query = "SELECT e.*, v.full_name 
          FROM entry_logs e
          JOIN visitors v ON e.visitor_id = v.visitor_id
          ORDER BY e.entry_time DESC";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
<title>Entry Logs</title>
<style>
body{font-family:Segoe UI;background:#f4f7fb;}
.container{max-width:1000px;margin:40px auto;background:#fff;padding:30px;border-radius:10px;}
table{width:100%;border-collapse:collapse;}
th,td{padding:10px;border-bottom:1px solid #ddd;}
th{background:#eef2f6;}
.ALLOWED{color:green;font-weight:600;}
.DENIED{color:red;font-weight:600;}
</style>
</head>
<body>

<div class="container">
<h2>Visitor Entry Logs</h2><br>

<table>
<tr>
    <th>Visitor</th>
    <th>Gate</th>
    <th>Match %</th>
    <th>Status</th>
    <th>Date & Time</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?= htmlspecialchars($row['full_name']); ?></td>
    <td><?= $row['gate_name']; ?></td>
    <td><?= $row['match_score']; ?>%</td>
    <td class="<?= $row['entry_status']; ?>"><?= $row['entry_status']; ?></td>
    <td><?= $row['entry_time']; ?></td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
