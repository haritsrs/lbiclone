<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Pengguna sudah login, Anda dapat menggunakan $_SESSION['username'] untuk menampilkan nama pengguna atau melakukan operasi lainnya.
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
</head>
<body>
    <h1>Selamat datang, <?php echo $_SESSION['username']; ?>!</h1>
    <!-- Konten dashboard lainnya -->
</body>
</html>
