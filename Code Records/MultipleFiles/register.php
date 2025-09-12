<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Health Management</title>
    <link rel="stylesheet" href="a.css">
    <script src="script.js"></script> <!-- Link script.js -->
</head>

<body>
    <style>
        /* These styles are better placed in a.css or removed if not used */
        #address {
            width: 60%;
        }
        
        #imagepart {
            border: 2px solid black;
            border-radius: 5px;
            padding: 10px;
            width: 60%;
        }
        
        #role {
            border: 2px solid black;
            border-radius: 5px;
            padding: 5px;
            width: 60%;
        }
    </style>
    <div id="headerSection">
        <h1><em>REGISTER NOW</em></h1>
    </div>
    <hr>
    <div id="bodySection">
        <form action="" method="POST">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" placeholder="Enter name" required>

            <label for="mobile">Mobile:</label>
            <input type="tel" id="mobile" name="mobile" maxlength="10" placeholder="Enter Mobile" required><br><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Enter email" required><br><br>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" placeholder="Password" required><br><br>

            <input type="submit" name="submit" value="Register">
            <br><br>
            Already a User? <a href="k.php">Login here</a>
        </form>
    </div>
    <?php
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Collect form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
        $password = $_POST['password'];

        // Database connection details
        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'health';

        // Connect to MySQL
        $conn = mysqli_connect($host, $user, $pass, $dbname);

        // Check connection
        if (!$conn) {
            echo "<script>showCustomAlert('Connection failed: " . mysqli_connect_error() . "', 'error');</script>";
            exit;
        }

        // Prepare SQL query
        $sql = "INSERT INTO register (name, email, mobile, password) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters to the query
            mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $mobile, $password);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>showCustomAlert('Registration successful! Welcome, " . htmlspecialchars($name) . ".', 'success');</script>";
            } else {
                echo "<script>showCustomAlert('Error inserting data: " . mysqli_error($conn) . "', 'error');</script>";
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo "<script>showCustomAlert('Failed to prepare the SQL statement.', 'error');</script>";
        }

        // Close the connection
        mysqli_close($conn);
    }
    ?>
</body>

</html>