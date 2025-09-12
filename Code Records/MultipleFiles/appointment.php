<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Hospital Appointment Form - E-Health Management</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f9f9;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #ffffff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    h1 {
      text-align: center;
      margin-bottom: 20px;
      color: #00796b;
    }

    label {
      display: block;
      margin-top: 12px;
      font-weight: bold;
      color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"],
    input[type="date"],
    input[type="time"],
    select,
    textarea {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    textarea {
      resize: vertical;
    }

    input[type="submit"] {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background: #00796b;
      border: none;
      color: #fff;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
      transition: background 0.3s;
    }

    input[type="submit"]:hover {
      background: #004d40;
    }

    .appointment-display-section {
      max-width: 600px;
      margin: 20px auto;
      background: #e0f2f1;
      padding: 15px;
      border-radius: 8px;
      border: 1px solid #b2dfdb;
    }

    .appointment-display-section h3 {
      margin-bottom: 10px;
      color: #004d40;
    }

    .appointment-display-section table {
      width: 100%;
      border-collapse: collapse;
    }

    .appointment-display-section th,
    .appointment-display-section td {
      border: 1px solid #ccc;
      padding: 10px;
      text-align: center;
    }

    .appointment-display-section th {
      background: #00796b;
      color: white;
    }

    footer {
      background: #263238;
      color: white;
      text-align: center;
      padding: 15px;
      margin-top: 40px;
    }

    footer p {
      margin: 5px 0;
      font-size: 14px;
    }

    /* Responsive Design */
    @media (max-width: 600px) {
      .container,
      .appointment-display-section {
        margin: 20px;
        padding: 15px;
      }

      input,
      select,
      textarea,
      input[type="submit"] {
        font-size: 14px;
        padding: 10px;
      }
    }
  </style>
  <script src="script.js"></script>
</head>

<body>
  <div class="container">
    <h1>Appointment Form</h1>
    <form method="post">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required>

      <label for="phone">Phone Number:</label>
      <input type="tel" id="phone" name="phone" required>

      <label for="date">Appointment Date:</label>
      <input type="date" id="date" name="date" required>

      <label for="time">Appointment Time:</label>
      <input type="time" id="time" name="time" required>

      <label for="doctor">Choose Doctor:</label>
      <select id="doctor" name="doctor" required>
        <option value="">-- Select a Doctor --</option>
        <option value="Dr. Smith">Dr. Smith (General Physician)</option>
        <option value="Dr. Patel">Dr. Patel (Cardiologist)</option>
        <option value="Dr. Lee">Dr. Lee (Orthopedic Surgeon)</option>
        <option value="Dr. Shivendra">Dr. Shivendra (Orthopedics)</option>
        <option value="Dr. Ashish Jain">Dr. Ashish Jain (Orthopedics)</option>
        <option value="Dr. Gaurav Agarwal">Dr. Gaurav Agarwal (Orthopedics)</option>
        <option value="Dr. Ritwiz Bihari">Dr. Ritwiz Bihari (Neurology)</option>
        <option value="Dr. A M Kar">Dr. A M Kar (Neurology)</option>
        <option value="Dr. Hariom Singh">Dr. Hariom Singh (Neurology)</option>
        <option value="Dr. Naveen Chandra">Dr. Naveen Chandra (Cardiology)</option>
        <option value="Dr. Satyendra Tiwari">Dr. Satyendra Tiwari (Cardiology)</option>
        <option value="Dr. Atul Agrawal">Dr. Atul Agrawal (Cardiology)</option>
      </select>

      <label for="symptoms">Symptoms/Reason for Visit:</label>
      <textarea id="symptoms" name="symptoms" rows="5" required></textarea>

      <input type="submit" value="Book Appointment" name="submit">
    </form>
  </div>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $name = htmlspecialchars(trim($_POST['name']));
      $email = htmlspecialchars(trim($_POST['email']));
      $phone = htmlspecialchars(trim($_POST['phone']));
      $date = htmlspecialchars(trim($_POST['date']));
      $time = htmlspecialchars(trim($_POST['time']));
      $doctor = htmlspecialchars(trim($_POST['doctor']));
      $symptoms = htmlspecialchars(trim($_POST['symptoms']));

      $host = 'localhost';
      $user = 'root';
      $pass = '';
      $dbname = 'health';

      $conn = mysqli_connect($host, $user, $pass, $dbname);

      if (!$conn) {
          echo "<script>alert('Connection failed: " . mysqli_connect_error() . "');</script>";
          exit;
      }

      $sql = "INSERT INTO appointment (name, email, phone, date, time, doctor, symptoms) 
              VALUES (?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);

      if ($stmt) {
          mysqli_stmt_bind_param($stmt, "sssssss", $name, $email, $phone, $date, $time, $doctor, $symptoms);

          if (mysqli_stmt_execute($stmt)) {
              echo "<script>alert('Appointment booked successfully!');</script>";

              $result = mysqli_query($conn, "SELECT * FROM appointment WHERE email='" . mysqli_real_escape_string($conn, $email) . "' ORDER BY date DESC, time DESC");

              if (mysqli_num_rows($result) > 0) {
                  echo "<div class='appointment-display-section'>";
                  echo "<h3>Your Appointments:</h3>";
                  echo "<table>";
                  echo "<thead><tr><th>Date</th><th>Time</th><th>Doctor</th><th>Symptoms</th></tr></thead>";
                  echo "<tbody>";
                  while ($row = mysqli_fetch_assoc($result)) {
                      echo "<tr>";
                      echo "<td>" . htmlspecialchars($row['date']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['time']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['doctor']) . "</td>";
                      echo "<td>" . htmlspecialchars($row['symptoms']) . "</td>";
                      echo "</tr>";
                  }
                  echo "</tbody>";
                  echo "</table>";
                  echo "</div>";
              } else {
                  echo "<div class='appointment-display-section'><p>No previous appointments found for this email.</p></div>";
              }
          } else {
              echo "<script>alert('Error booking appointment.');</script>";
          }

          mysqli_stmt_close($stmt);
      } else {
          echo "<script>alert('Failed to prepare SQL statement.');</script>";
      }

      mysqli_close($conn);
  }
  ?>

  <footer>
    <p>© 2025 E-Health Management System. All rights reserved.</p>
    <p>Book appointments with trusted doctors quickly and easily.</p>
  </footer>
</body>

</html>
