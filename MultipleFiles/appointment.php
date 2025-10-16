<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $doctor = trim($_POST['doctor'] ?? '');
    $symtoms = trim($_POST['symtoms'] ?? '');

    if ($name && $date && $time) {
        $sql = "INSERT INTO appointment (user_email, name, email, phone, date, time, doctor, symtoms) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ssssssss", $user_email, $name, $email, $phone, $date, $time, $doctor, $symtoms);
            if (mysqli_stmt_execute($stmt)) {
                $message = "Appointment booked successfully!";
            } else {
                $error = "Error inserting data: " . mysqli_error($conn);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = "Prepare failed: " . mysqli_error($conn);
        }
    } else {
        $error = "Please fill all required fields (Name, Date, Time).";
    }
}

$appointments = [];
$sql = "SELECT * FROM appointment WHERE user_email = ? ORDER BY date DESC, time DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) $appointments = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Appointment - care records</title>
<style>
body { font-family: "Segoe UI", sans-serif; background: #f0f7ff; margin:0; padding:0; }
header { background:#0077b6; color:white; padding:15px; text-align:center; }
nav ul { display:flex; justify-content:center; gap:20px; list-style:none; padding:0; margin:0; flex-wrap:wrap; }
nav a { color:white; text-decoration:none; font-weight:500; }
nav a:hover { text-decoration:underline; }

form { max-width:500px; margin:20px auto; padding:20px; background:white; border-radius:10px; box-shadow:0 2px 10px rgba(0,0,0,0.1); }
input, select, textarea { width:100%; padding:10px; margin-bottom:15px; border-radius:6px; border:1px solid #ccc; font-size:15px; box-sizing:border-box; }
input[type="submit"] { background:#0077b6; color:white; border:none; cursor:pointer; width:100%; font-size:16px; }
input[type="submit"]:hover { background:#0096c7; }

table { width:90%; margin:20px auto; border-collapse:collapse; background:white; display:block; overflow-x:auto; }
th, td { padding:10px; border:1px solid #ddd; text-align:center; white-space: nowrap; }
th { background:#0096c7; color:white; }
tr:hover { background:#f1f1f1; }

.message { text-align:center; color:green; }
.error { text-align:center; color:red; }

@media (max-width:768px) {
    header h1 { font-size:1.3rem; }
    nav ul { flex-direction:column; align-items:center; gap:10px; }
    form { padding:15px; }
    input, select, textarea { font-size:14px; padding:8px; }
    table th, table td { padding:6px; font-size:13px; }
}

@media (max-width:480px) {
    header { padding:12px; }
    form { padding:12px; }
    input, select, textarea { padding:6px; font-size:12px; }
}
</style>
</head>
<body>

<header><h1>Book Appointment</h1>
<nav>
    <ul>
        <li><a href="home.html">🏠 Home</a></li>
        <li><a href="patient.php">🧾 Add Patient</a></li>
        <li><a href="appointment.php">📅 Book Appointment</a></li>
    </ul>
</nav></header>

<?php if (!empty($message)): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<?php if (!empty($error)): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST">
    <input type="text" name="name" placeholder="Patient Name" required>
    <input type="email" name="email" placeholder="Patient Email">
    <input type="tel" name="phone" placeholder="Phone Number">
    <input type="date" name="date" required>
    <input type="time" name="time" required>
    <input type="text" name="doctor" placeholder="Doctor Name">
    <textarea name="symtoms" placeholder="Symptoms / Notes" rows="3"></textarea>
    <input type="submit" name="submit" value="Book Appointment">
</form>

<h2 style="text-align:center; color:#023e8a;">Your Appointments</h2>
<?php if (!empty($appointments)): ?>
    <table>
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Email</th><th>Phone</th>
                <th>Date</th><th>Time</th><th>Doctor</th><th>Symptoms</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($appointments as $a): ?>
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
    <p style="text-align:center; color:gray;">No appointments booked yet.</p>
<?php endif; ?>

</body>
</html>
