<?php
session_start();
include 'admin_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $_SESSION['login_error'] = "Password salah";
            header("Location: admin_login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "Username tidak ditemukan";
        header("Location: admin_login.php");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: admin_login.php");
    exit();
}
?>
