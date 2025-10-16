<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include("connect.php");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']); // Plain text password

    if (!$conn) {
        echo "<script>alert('Database connection failed: " . mysqli_connect_error() . "');</script>";
        exit;
    }

    // Fetch user by email
    $stmt = mysqli_prepare($conn, "SELECT id, name, email, password FROM register WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) === 1) {
        $user = mysqli_fetch_assoc($result);
        $dbPassword = $user['password'];

        if ($password === $dbPassword) {
            // Login successful
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            echo "<script>alert('Login successful! Redirecting...'); window.location.href='dashboard.html';</script>";
            exit;
        } else {
            echo "<script>alert('Invalid password. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('Email not registered. Please sign up first.');</script>";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - care records</title>
<style>
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

    #loginContainer {
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

    form input[type="email"],
    form input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.3s ease;
    }

    form input[type="email"]:focus,
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

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media(max-width: 480px) {
        #loginContainer {
            padding: 25px 20px;
        }
    }
</style>
</head>
<body>
<div id="loginContainer">
    <div id="headerSection">
        <h1>Login</h1>
    </div>
    <form action="" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <input type="submit" name="submit" value="Login">
        <p>New User? <a href="register.php">Register here</a></p>
    </form>
</div>
</body>
</html>
