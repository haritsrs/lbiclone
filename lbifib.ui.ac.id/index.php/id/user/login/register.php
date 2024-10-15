<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);

    if ($stmt->execute()) {
        // Display popup message for successful registration
        echo '<script type="text/javascript">';
        echo 'alert("Registration successful!");';
        echo 'window.location.href = "login.html";';
        echo '</script>';
    } else {
        echo '<script type="text/javascript">alert("Error: ' . $stmt->error . '");</script>';
    }

    $stmt->close();
    $conn->close();
}
?>
