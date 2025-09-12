<?php
session_start();
// In a real application, you would use $_SESSION['user_id'] to filter records
// For demonstration, I'll fetch all records.
// if (!isset($_SESSION['user_id'])) {
//     header("Location: k.php");
//     exit();
// }

$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'health';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("<script>showCustomAlert('Database connection failed: " . mysqli_connect_error() . "', 'error');</script>");
}

// Fetch Patient Records
$patient_records = [];
$patient_sql = "SELECT * FROM patient ORDER BY id DESC"; // Order by ID for latest first
$patient_result = mysqli_query($conn, $patient_sql);
if ($patient_result) {
    while ($row = mysqli_fetch_assoc($patient_result)) {
        $patient_records[] = $row;
    }
} else {
    echo "<script>showCustomAlert('Error fetching patient records: " . mysqli_error($conn) . "', 'error');</script>";
}

// Fetch Appointment Records
$appointment_records = [];
$appointment_sql = "SELECT * FROM appointment ORDER BY date DESC, time DESC"; // Order by date/time for latest first
$appointment_result = mysqli_query($conn, $appointment_sql);
if ($appointment_result) {
    while ($row = mysqli_fetch_assoc($appointment_result)) {
        $appointment_records[] = $row;
    }
} else {
    echo "<script>showCustomAlert('Error fetching appointment records: " . mysqli_error($conn) . "', 'error');</script>";
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Your Health Records - E-Health Management</title>
    <link rel="stylesheet" href="a.css">
    <script src="script.js"></script> <!-- Link script.js -->
</head>

<body>
    <header>
        <h1>Your Health Records</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="patient.php">Submit New Patient Details</a></li>
                <li><a href="appointment.php">Book New Appointment</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="patient-records-section">
            <h2>Patient Details History</h2>
            <?php if (!empty($patient_records)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Gender</th>
                            <th>Blood Group</th>
                            <th>Address</th>
                            <th>Phone</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($patient_records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                <td><?php echo htmlspecialchars($record['age']); ?></td>
                                <td><?php echo htmlspecialchars($record['gender']); ?></td>
                                <td><?php echo htmlspecialchars($record['bloodgroup']); ?></td>
                                <td><?php echo htmlspecialchars($record['address']); ?></td>
                                <td><?php echo htmlspecialchars($record['number']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No patient details records found.</p>
            <?php endif; ?>
        </section>

        <section class="appointment-records-section">
            <h2>Appointment History</h2>
            <?php if (!empty($appointment_records)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Doctor</th>
                            <th>Symptoms</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($appointment_records as $record): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($record['id']); ?></td>
                                <td><?php echo htmlspecialchars($record['name']); ?></td>
                                <td><?php echo htmlspecialchars($record['email']); ?></td>
                                <td><?php echo htmlspecialchars($record['phone']); ?></td>
                                <td><?php echo htmlspecialchars($record['date']); ?></td>
                                <td><?php echo htmlspecialchars($record['time']); ?></td>
                                <td><?php echo htmlspecialchars($record['doctor']); ?></td>
                                <td><?php echo htmlspecialchars($record['symptoms']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No appointment records found.</p>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 Your Healthcare Provider</p>
    </footer>
</body>

</html>