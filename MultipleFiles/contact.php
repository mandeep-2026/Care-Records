<?php
include 'connect.php'; // Single connection file
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us - Care Records</title>
<style>
    body { font-family: "Segoe UI", sans-serif; margin:0; padding:0; background:#f0f7ff; }
    .container { max-width:600px; margin:50px auto; background:#fff; padding:25px 20px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
    h1 { text-align:center; color:#0077b6; margin-bottom:20px; }
    label { display:block; margin-bottom:5px; font-weight:500; }
    input[type="text"], input[type="email"], textarea {
        width:100%; padding:12px; margin-bottom:15px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; font-size:15px;
    }
    input[type="submit"] {
        width:100%; background:#0077b6; color:#fff; border:none; padding:12px; font-size:16px; cursor:pointer; border-radius:6px;
    }
    input[type="submit"]:hover { background:#0096c7; }
    .alert { padding:12px; margin-top:15px; border-radius:6px; text-align:center; font-weight:500; }
    .success { background:#d4edda; color:#155724; border:1px solid #c3e6cb; }
    .error { background:#f8d7da; color:#721c24; border:1px solid #f5c6cb; }

    @media(max-width:480px){
        .container { margin:20px; padding:20px 15px; }
        input[type="text"], input[type="email"], textarea { font-size:14px; padding:10px; }
        input[type="submit"] { font-size:15px; padding:10px; }
    }
</style>
</head>

<body>
<div class="container">
    <h1>Contact Us</h1>
    <form method="POST" action="">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="5" required></textarea>

        <input type="submit" name="submit" value="SUBMIT">
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $message = trim($_POST['message']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo '<div class="alert error">Invalid email address.</div>';
        } else {
            $sql = "INSERT INTO contact (name, email, message) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($conn, $sql);

            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "sss", $name, $email, $message);
                if (mysqli_stmt_execute($stmt)) {
                    echo '<div class="alert success">Thank you, '.htmlspecialchars($name).'! Your message has been submitted.</div>';
                } else {
                    echo '<div class="alert error">Failed to save your message. Please try again.</div>';
                }
                mysqli_stmt_close($stmt);
            } else {
                echo '<div class="alert error">Database error. Please try later.</div>';
            }
        }
        mysqli_close($conn);
    }
    ?>
</div>
</body>
</html>
