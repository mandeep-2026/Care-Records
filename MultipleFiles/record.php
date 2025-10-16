
<?php
session_start();

// Make sure user is logged in
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

include 'connect.php';

// ---------- FETCH PATIENT RECORDS FOR LOGGED-IN USER ----------
$patient_records = [];
$sql_patient = "SELECT * FROM patient WHERE user_email = ? ORDER BY id DESC";
$stmt_patient = mysqli_prepare($conn, $sql_patient);
if ($stmt_patient) {
    mysqli_stmt_bind_param($stmt_patient, "s", $user_email);
    mysqli_stmt_execute($stmt_patient);
    $result_patient = mysqli_stmt_get_result($stmt_patient);
    if ($result_patient) {
        $patient_records = mysqli_fetch_all($result_patient, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt_patient);
}

// ---------- FETCH APPOINTMENT RECORDS FOR LOGGED-IN USER ----------
$appointment_records = [];
$sql_appointment = "SELECT * FROM appointment WHERE user_email = ? ORDER BY date DESC, time DESC";
$stmt_appointment = mysqli_prepare($conn, $sql_appointment);
if ($stmt_appointment) {
    mysqli_stmt_bind_param($stmt_appointment, "s", $user_email);
    mysqli_stmt_execute($stmt_appointment);
    $result_appointment = mysqli_stmt_get_result($stmt_appointment);
    if ($result_appointment) {
        $appointment_records = mysqli_fetch_all($result_appointment, MYSQLI_ASSOC);
    }
    mysqli_stmt_close($stmt_appointment);
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Health Records</title>
<link rel="stylesheet" href="a.css">
<style>
    body { font-family: "Segoe UI", sans-serif; background: #f5f9ff; margin:0; padding:0; }
    header { background:#0077b6; color:white; padding:15px; text-align:center; }
    h2 { color:#023e8a; text-align:center; margin-top:30px; }
    table { width:90%; margin:20px auto; border-collapse: collapse; background:white; }
    th, td { padding:10px; border:1px solid #ddd; text-align:center; }
    th { background:#0096c7; color:white; }
    tr:hover { background:#f1f1f1; }
    .message { text-align:center; color:green; }
    .error { text-align:center; color:red; }
</style>
</head>
<body>

<header><h1>My Health Records</h1></header>

<!-- PATIENT RECORDS -->
<h2>My Patients</h2>
<?php if (!empty($patient_records)): ?>
<table>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Blood Group</th>
            <th>Address</th><th>Phone</th><th>Email</th><th>Photo</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($patient_records as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['id']) ?></td>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td><?= htmlspecialchars($p['age']) ?></td>
            <td><?= htmlspecialchars($p['gender']) ?></td>
            <td><?= htmlspecialchars($p['bloodgroup']) ?></td>
            <td><?= htmlspecialchars($p['address']) ?></td>
            <td><?= htmlspecialchars($p['number']) ?></td>
            <td><?= htmlspecialchars($p['email']) ?></td>
            <td><?= !empty($p['photo']) ? "<a href='uploads/".htmlspecialchars($p['photo'])."' target='_blank'>View</a>" : "N/A" ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align:center; color:gray;">No patient records found.</p>
<?php endif; ?>

<!-- APPOINTMENT RECORDS -->
<h2>My Appointments</h2>
<?php if (!empty($appointment_records)): ?>
<table>
    <thead>
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
            <th>Date</th><th>Time</th><th>Doctor</th><th>Symptoms</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($appointment_records as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['id']) ?></td>
            <td><?= htmlspecialchars($a['name']) ?></td>
            <td><?= htmlspecialchars($a['email']) ?></td>
            <td><?= htmlspecialchars($a['phone']) ?></td>
            <td><?= htmlspecialchars($a['date']) ?></td>
            <td><?= htmlspecialchars($a['time']) ?></td>
            <td><?= htmlspecialchars($a['doctor']) ?></td>
            <td><?= htmlspecialchars($a['symtoms']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p style="text-align:center; color:gray;">No appointments found.</p>
<?php endif; ?>

</body>
</html>
