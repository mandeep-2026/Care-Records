<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Patient Details - E-Health Management</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f4f9f9;
      margin: 0;
      padding: 0;
    }

    .patient-details {
      max-width: 500px;
      margin: 40px auto;
      background: #ffffff;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }

    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #00796b;
    }

    form label {
      display: block;
      margin-top: 10px;
      font-weight: bold;
      color: #333;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="email"],
    form input[type="tel"],
    form input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 6px;
      font-size: 14px;
    }

    form input[type="radio"] {
      margin-left: 10px;
    }

    form input[type="submit"] {
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

    form input[type="submit"]:hover {
      background: #004d40;
    }

    .patient-display-section {
      max-width: 500px;
      margin: 20px auto;
      background: #e0f2f1;
      padding: 15px;
      border-radius: 8px;
      border: 1px solid #b2dfdb;
    }

    .patient-display-section h3 {
      margin-bottom: 10px;
      color: #004d40;
    }

    .patient-display-section p {
      margin: 6px 0;
    }

    button {
      background: #009688;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      font-size: 16px;
      cursor: pointer;
      margin: 20px auto;
      display: block;
    }

    button:hover {
      background: #00695c;
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
      .patient-details,
      .patient-display-section {
        margin: 20px;
        padding: 15px;
      }

      form input,
      form input[type="submit"] {
        font-size: 14px;
        padding: 10px;
      }

      button {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="patient-details">
    <h2>Patient Details Form</h2>
    <form method="POST" action="" enctype="multipart/form-data">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>

      <label for="age">Age:</label>
      <input type="number" id="age" name="age" required>

      <label for="gender">Gender:</label>
      <input type="radio" id="male" name="gender" value="Male" required>
      <label for="male" style="display:inline;">Male</label>
      <input type="radio" id="female" name="gender" value="Female" required>
      <label for="female" style="display:inline;">Female</label>

      <label for="bloodGroup">Blood Group:</label>
      <input type="text" id="bloodGroup" name="bloodgroup">

      <label for="address">Address:</label>
      <input type="text" id="address" name="address">

      <label for="phoneNumber">Phone Number:</label>
      <input type="tel" id="phoneNumber" name="number" required>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email">

      <label for="photo">Upload Document:</label>
      <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png,.pdf">

      <input type="submit" name="submit" value="SUBMIT">
    </form>
  </div>

  <?php
  if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
      $name = htmlspecialchars(trim($_POST['name']));
      $age = htmlspecialchars(trim($_POST['age']));
      $gender = htmlspecialchars(trim($_POST['gender']));
      $bloodgroup = htmlspecialchars(trim($_POST['bloodgroup']));
      $address = htmlspecialchars(trim($_POST['address']));
      $number = htmlspecialchars(trim($_POST['number']));
      $email = htmlspecialchars(trim($_POST['email']));

      $photoName = null;
      if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
          $uploadDir = 'uploads/';
          if (!is_dir($uploadDir)) {
              mkdir($uploadDir, 0777, true);
          }

          $originalName = basename($_FILES['photo']['name']);
          $photoName = time() . "_" . $originalName;
          $uploadPath = $uploadDir . $photoName;

          if (!move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
              echo "<script>alert('Failed to upload file.');</script>";
              $photoName = null;
          }
      }

      $conn = mysqli_connect('localhost', 'root', '', 'health');
      if (!$conn) {
          echo "<script>alert('Database connection failed.');</script>";
          exit;
      }

      $sql = "INSERT INTO patient (name, age, gender, bloodgroup, address, number, email, photo) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
      $stmt = mysqli_prepare($conn, $sql);

      if ($stmt) {
          mysqli_stmt_bind_param($stmt, "ssssssss", $name, $age, $gender, $bloodgroup, $address, $number, $email, $photoName);

          if (mysqli_stmt_execute($stmt)) {
              echo "<script>alert('Patient details saved successfully!');</script>";

              $last_id = mysqli_insert_id($conn);
              $result = mysqli_query($conn, "SELECT * FROM patient WHERE id = $last_id");

              if ($row = mysqli_fetch_assoc($result)) {
                  echo "<div class='patient-display-section'>";
                  echo "<h3>Your Recently Submitted Details:</h3>";
                  echo "<p><strong>Name:</strong> " . htmlspecialchars($row['name']) . "</p>";
                  echo "<p><strong>Age:</strong> " . htmlspecialchars($row['age']) . "</p>";
                  echo "<p><strong>Gender:</strong> " . htmlspecialchars($row['gender']) . "</p>";
                  echo "<p><strong>Blood Group:</strong> " . htmlspecialchars($row['bloodgroup']) . "</p>";
                  echo "<p><strong>Address:</strong> " . htmlspecialchars($row['address']) . "</p>";
                  echo "<p><strong>Phone:</strong> " . htmlspecialchars($row['number']) . "</p>";
                  echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
                  if (!empty($row['photo'])) {
                      echo "<p><strong>Uploaded File:</strong> <a href='uploads/" . htmlspecialchars($row['photo']) . "' target='_blank'>View</a></p>";
                  }
                  echo "</div>";
              }
          } else {
              echo "<script>alert('Failed to save patient data.');</script>";
          }

          mysqli_stmt_close($stmt);
      } else {
          echo "<script>alert('Failed to prepare SQL statement.');</script>";
      }

      mysqli_close($conn);
  }
  ?>

  <div style="text-align: center;">
    <button onclick="window.location.href='record.php'">View All My Health Records</button>
  </div>

  <footer>
    <p>© 2025 E-Health Management System. All rights reserved.</p>
    <p>Ensuring better healthcare through technology.</p>
  </footer>
</body>

</html>
