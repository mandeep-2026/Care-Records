<?php
session_start();
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$user_email = $_SESSION['user_email'];

include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name       = trim($_POST['name'] ?? '');
    $age        = trim($_POST['age'] ?? '');
    $gender     = trim($_POST['gender'] ?? '');
    $bloodgroup = trim($_POST['bloodgroup'] ?? '');
    $address    = trim($_POST['address'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $email      = trim($_POST['email'] ?? '');

    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $target_dir = "uploads/";
        $photo = time() . "_" . basename($_FILES["photo"]["name"]);
        move_uploaded_file($_FILES["photo"]["tmp_name"], $target_dir . $photo);
    }

    $sql = "INSERT INTO patient (user_email, name, age, gender, bloodgroup, address, number, email, photo)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssssssss", $user_email, $name, $age, $gender, $bloodgroup, $address, $phone, $email, $photo);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    header("Location: patient.php");
    exit();
}

$patients = [];
$sql = "SELECT * FROM patient WHERE user_email = ? ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "s", $user_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($result) $patients = mysqli_fetch_all($result, MYSQLI_ASSOC);
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Patient Records</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body { font-family: "Segoe UI", sans-serif; background: #f5f9ff; margin:0; padding:0; }
header { background:#0077b6; color:white; padding:15px; text-align:center; }
nav ul { display:flex; justify-content:center; gap:20px; list-style:none; padding:0; margin:0; flex-wrap:wrap; }
nav a { color:white; text-decoration:none; font-weight:500; }
nav a:hover { text-decoration:underline; }
.container { max-width:900px; margin:20px auto; padding:0 15px; }
form { background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1); margin-bottom:30px; }
form input, form select { width:100%; padding:10px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px; font-size:14px; box-sizing:border-box; }
form input[type="submit"] { background:#0077b6; color:#fff; border:none; cursor:pointer; width:auto; min-width:120px; }
form input[type="submit"]:hover { background:#0096c7; }
table { width:100%; border-collapse:collapse; background:#fff; border-radius:8px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.1); display:block; overflow-x:auto; }
th, td { padding:10px; border-bottom:1px solid #ddd; text-align:center; white-space: nowrap; }
th { background:#0096c7; color:#fff; }
tr:hover { background:#f1f1f1; }
h2 { text-align:center; color:#023e8a; margin-bottom:20px; font-size:1.5rem; }
@media (max-width:768px) {
    header h1 { font-size:1.3rem; }
    nav ul { flex-direction:column; align-items:center; gap:10px; }
    form { padding:15px; }
    form input, form select { font-size:13px; padding:8px; }
    table th, table td { padding:6px; font-size:13px; }
}
@media (max-width:480px) {
    header { padding:12px; }
    h2 { font-size:1.3rem; }
    form input, form select { padding:6px; font-size:12px; }
}
</style>
</head>
<body>

<header><h1>Patient Management</h1>
<nav>
    <ul>
        <li><a href="home.html">🏠 Home</a></li>
        <li><a href="patient.php">🧾 Add Patient</a></li>
        <li><a href="appointment.php">📅 Book Appointment</a></li>
    </ul>
</nav></header>

<div class="container">
    <form method="post" enctype="multipart/form-data">
        <h2>Add Patient Details</h2>
        <input type="text" name="name" placeholder="Patient Name" required>
        <input type="number" name="age" placeholder="Age" required>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
        <input type="text" name="bloodgroup" placeholder="Blood Group">
        <input type="text" name="address" placeholder="Address">
        <input type="tel" name="phone" placeholder="Phone">
        <input type="email" name="email" placeholder="Email">
        <input type="file" name="photo">
        <input type="submit" name="submit" value="Add Patient">
    </form>

    <h2>View Patient Records</h2>
    <?php if (!empty($patients)): ?>
    <table>
        <tr>
            <th>ID</th><th>Name</th><th>Age</th><th>Gender</th><th>Blood Group</th>
            <th>Address</th><th>Phone</th><th>Email</th><th>Photo</th>
        </tr>
        <?php foreach ($patients as $p): ?>
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
    </table>
    <?php else: ?>
    <p style="text-align:center; color:gray;">No patients found.</p>
    <?php endif; ?>
</div>

</body>
</html>
