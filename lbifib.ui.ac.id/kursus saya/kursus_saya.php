<?php
session_start();
include 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data kursus yang diikuti oleh pengguna dari tabel registrations menggunakan JOIN dengan tabel courses
$sql = "SELECT c.kursus, c.lokasi, c.moda, c.hari, c.jam
        FROM registrations r
        JOIN courses c ON r.id_kursus = c.id_kursus
        WHERE r.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($kursus, $lokasi, $moda, $hari, $jam);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kursus Saya</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
<a id="home-link" href="http://localhost/lbi/lbifib.ui.ac.id/index.html"><img src="logo.png" alt="Beranda" width="50" height="50"></a>
    <h2>Kursus Saya</h2>
    <table>
        <tr>
            <th>Kursus</th>
            <th>Lokasi</th>
            <th>Moda</th>
            <th>Hari</th>
            <th>Jam</th>
        </tr>
        <?php
        // Tampilkan data kursus yang diikuti oleh pengguna dalam tabel
        while ($stmt->fetch()) {
            echo "<tr>";
            echo "<td>$kursus</td>";
            echo "<td>$lokasi</td>";
            echo "<td>$moda</td>";
            echo "<td>$hari</td>";
            echo "<td>$jam</td>";
            echo "</tr>";
        }
        $stmt->close();
        ?>
    </table>
</body>
</html>
