<!DOCTYPE html>
<html>

<head>
    <title>Buy Medicine Online - E-Health Management</title>
    <link rel="stylesheet" href="a.css">
    <script src="script.js"></script> <!-- Link script.js -->
</head>

<body>
    <header>
        <h1>Buy Your Medicine Online</h1>
    </header>

    <main>
    <form method="POST" action="">
            <label for="medicine-name">Medicine Name:</label>
            <input type="text" id="medicine-name" name="medicine" required>

            <label for="quantity">Quantity:</label>
            <input type="number" id="quantity" name="quantity" min="1" value="1" required> <!-- Added default value -->

            <label for="delivery-address">Delivery Address:</label>
            <textarea id="delivery-address" name="delivery" required></textarea>

            <input type="submit" name="submit" value="PLACE ORDER">
        </form>
    </main>

    <footer>
        <p>&copy; 2023 Your Pharmacy</p>
    </footer>
    <?php
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Collect form data
        $medicine = htmlspecialchars(trim($_POST['medicine']));
        $quantity = htmlspecialchars(trim($_POST['quantity']));
        $delivery = htmlspecialchars(trim($_POST['delivery']));
       

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
        $sql = "INSERT INTO medicine (medicine, quantity, delivery) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            // Bind parameters to the query
            mysqli_stmt_bind_param($stmt, "sss", $medicine, $quantity, $delivery);

            // Execute the query
            if (mysqli_stmt_execute($stmt)) {
                echo "<script>showCustomAlert('Medicine order successful! Your order for " . htmlspecialchars($quantity) . " x " . htmlspecialchars($medicine) . " will be delivered to " . htmlspecialchars($delivery) . ".', 'success');</script>";
            } else {
                echo "<script>showCustomAlert('Error placing order: " . mysqli_error($conn) . "', 'error');</script>";
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