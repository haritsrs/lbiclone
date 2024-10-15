<?php
session_start();

// Include file db.php dengan path yang benar
include 'db.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username FROM users WHERE id = ?";
    // Periksa apakah koneksi telah dibuat dengan benar
    if ($conn) {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($username);
        $stmt->fetch();
        $stmt->close();
        // Tampilkan nama pengguna yang sedang login
        echo "Pengguna yang sedang aktif: " . $username;
    } else {
        echo "Gagal terhubung ke database.";
    }
} else {
    echo "Tidak ada pengguna yang sedang aktif.";
}
?>
