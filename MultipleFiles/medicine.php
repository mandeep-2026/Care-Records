<!DOCTYPE html>
<html>

<head>
    <title>Buy Medicine Online -Care records</title>
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
            <input type="number" id="quantity" name="quantity" min="1" value="1" required>

            <label for="delivery-address">Delivery Address:</label>
            <textarea id="delivery-address" name="delivery" required></textarea>

            <input type="submit" name="submit" value="PLACE ORDER">
        </form>
    </main>

    <footer>
        <p>&copy; 2025 E-Health Management System</p>
    </footer>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
        // Collect form data
        $medicine = htmlspecialchars(trim($_POST['medicine']));
        $quantity = htmlspecialchars(trim($_POST['quantity']));
        $delivery = htmlspecialchars(trim($_POST['delivery']));

        // ✅ Corrected connection include (must be inside quotes)
        include("connect.php");

        // Prepare SQL query
        $sql = "INSERT INTO medicine (medicine, quantity, delivery) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "sss", $medicine, $quantity, $delivery);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>
                    showCustomAlert('Medicine order successful! Your order for " . htmlspecialchars($quantity) . " x " . htmlspecialchars($medicine) . " will be delivered to " . htmlspecialchars($delivery) . ".', 'success');
                </script>";
            } else {
                echo "<script>
                    showCustomAlert('Error placing order: " . mysqli_error($conn) . "', 'error');
                </script>";
            }

            mysqli_stmt_close($stmt);
        } else {
            echo "<script>
                showCustomAlert('Failed to prepare the SQL statement.', 'error');
            </script>";
        }

        mysqli_close($conn);
    }
    ?>
</body>

</html>
