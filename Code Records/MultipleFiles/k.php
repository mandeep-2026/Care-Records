<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Health Management</title>
    <link rel="stylesheet" href="a.css">
    <script src="script.js"></script> <!-- Link script.js -->
</head>

<body>
    <h1>LOGIN</h1>
    <form action="#" method="POST">
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" name="submit" value="Login">
        <br><br>
        New User? <a href="register.php">Register here</a>
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $host = 'localhost';
        $user = 'root';
        $pass = '';
        $dbname = 'health';

        $conn = mysqli_connect($host, $user, $pass, $dbname);

        if (!$conn) {
            echo "<script>showCustomAlert('Connection failed: " . mysqli_connect_error() . "', 'error');</script>";
        } else {
            $sql = "SELECT * FROM register WHERE email = ? AND password = ?";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $email, $password);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                if ($result && mysqli_num_rows($result) == 1) {
                    $user = mysqli_fetch_assoc($result);
                    $_SESSION['user_id'] = $user['id'];

                    echo "<script>showCustomAlert('Login successful! Redirecting...'); setTimeout(() => { window.location.href = 'home.html'; }, 1000);</script>";
                    // header("Refresh:1; url=index.html"); // Removed direct header redirect for JS alert to show
                    exit();
                } else {
                    echo "<script>showCustomAlert('Login failed: Invalid email or password.', 'error');</script>";
                }
                mysqli_stmt_close($stmt);
            } else {
                echo "<script>showCustomAlert('Failed to prepare the login statement.', 'error');</script>";
            }
            mysqli_close($conn);
        }
    }
    ?>
</body>
</html>