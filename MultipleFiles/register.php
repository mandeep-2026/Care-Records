<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("connect.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name   = trim($_POST['name']);
    $email  = trim($_POST['email']);
    $mobile = trim($_POST['mobile']);
    $password = trim($_POST['password']); // Plain text password

    if (!$conn) {
        echo "<script>alert('Database connection failed: " . mysqli_connect_error() . "');</script>";
        exit;
    }

    // Check duplicate email
    $checkStmt = mysqli_prepare($conn, "SELECT id FROM register WHERE email = ?");
    mysqli_stmt_bind_param($checkStmt, "s", $email);
    mysqli_stmt_execute($checkStmt);
    mysqli_stmt_store_result($checkStmt);

    if (mysqli_stmt_num_rows($checkStmt) > 0) {
        echo "<script>alert('This email is already registered. Please login instead.');</script>";
    } else {
        $insertStmt = mysqli_prepare($conn, "INSERT INTO register (name, email, mobile, password) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($insertStmt, "ssss", $name, $email, $mobile, $password);

        if (mysqli_stmt_execute($insertStmt)) {
            echo "<script>alert('Registration successful! Welcome, " . addslashes($name) . ".'); window.location.href='index.php';</script>";
            exit;
        } else {
            echo "<script>alert('Error inserting data: " . mysqli_error($conn) . "');</script>";
        }
        mysqli_stmt_close($insertStmt);
    }

    mysqli_stmt_close($checkStmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - care records</title>
<style>
    /* Reset some default styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background: #f0f7ff;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    #registerContainer {
        background: #ffffff;
        padding: 30px 40px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 450px;
        animation: fadeIn 0.8s ease-in-out;
    }

    #headerSection {
        text-align: center;
        margin-bottom: 20px;
    }

    #headerSection h1 {
        color: #0077b6;
        font-size: 28px;
        font-weight: 700;
    }

    form label {
        display: block;
        margin-bottom: 6px;
        margin-top: 12px;
        color: #023e8a;
        font-weight: 500;
        font-size: 14px;
    }

    form input[type="text"],
    form input[type="email"],
    form input[type="tel"],
    form input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    form input[type="text"]:focus,
    form input[type="email"]:focus,
    form input[type="tel"]:focus,
    form input[type="password"]:focus {
        border-color: #0077b6;
        outline: none;
    }

    form input[type="submit"] {
        margin-top: 20px;
        width: 100%;
        padding: 12px;
        background-color: #0077b6;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    form input[type="submit"]:hover {
        background-color: #0096c7;
    }

    form p {
        text-align: center;
        margin-top: 16px;
        font-size: 14px;
    }

    form a {
        color: #0077b6;
        text-decoration: none;
        font-weight: 500;
    }

    form a:hover {
        text-decoration: underline;
    }

    /* Simple fade-in animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media(max-width: 480px) {
        #registerContainer {
            padding: 25px 20px;
        }
    }
</style>
</head>
<body>
<div id="registerContainer">
    <div id="headerSection">
        <h1>Register Now</h1>
    </div>
    <form action="" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>

        <label for="mobile">Mobile:</label>
        <input type="tel" id="mobile" name="mobile" maxlength="10" placeholder="Enter mobile number" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter password" required>

        <input type="submit" name="submit" value="Register">
        <p>Already a user? <a href="index.php">Login here</a></p>
    </form>
</div>
</body>
</html>
