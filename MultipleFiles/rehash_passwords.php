<?php
include 'connect.php';

$result = mysqli_query($conn, "SELECT id, password FROM register");
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $id = $row['id'];
    $oldPass = $row['password'];

    // Skip already-hashed passwords
    if (substr($oldPass, 0, 4) === '$2y$') continue;

    // Hash plain text ones
    $newHash = password_hash($oldPass, PASSWORD_BCRYPT);
    mysqli_query($conn, "UPDATE register SET password='$newHash' WHERE id=$id");
    $count++;
}

echo "✅ Rehashed $count old passwords successfully!";
?>
