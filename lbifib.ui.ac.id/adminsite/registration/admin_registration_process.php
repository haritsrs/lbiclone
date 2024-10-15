<?php
include 'db.php'; // Sambungkan ke database

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Query SQL untuk menyimpan data admin ke database
    $sql = "INSERT INTO admin (username, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);

    // Eksekusi query
    if ($stmt->execute()) {
        // Registrasi berhasil, redirect ke halaman login admin
        header("Location: admin_login.php");
        exit();
    } else {
        // Registrasi gagal, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Tutup statement dan koneksi database
    $stmt->close();
    $conn->close();
} else {
    // Jika halaman diakses langsung, redirect ke halaman pendaftaran admin
    header("Location: admin_registration.html");
    exit();
}
?>
