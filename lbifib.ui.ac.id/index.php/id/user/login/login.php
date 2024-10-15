<?php
session_start();

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT id, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if ($stmt->num_rows > 0 && password_verify($password, $hashed_password)) {
        // Set session variables
        $_SESSION['user_id'] = $user_id;
        
        // Redirect to index.html
        header("Location: http://localhost/lbi/lbifib.ui.ac.id/index.html");
        exit();
    } else {
        $_SESSION['login_error'] = "Invalid username or password."; // Simpan pesan error dalam variabel session
        
        // Redirect back to login page
        header("Location: login.html");
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>
