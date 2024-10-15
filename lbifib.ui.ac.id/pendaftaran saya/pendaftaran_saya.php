<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Saya</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<div class="header">
        <div class="logo">
            <a href="http://localhost/lbi/lbifib.ui.ac.id/index.html">
                <img src="logo.png" alt="LBI Logo"> <!-- Ganti dengan logo yang sesuai -->
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="#">Beranda</a></li>
                <li><a href="#">BIPA Program</a></li>
                <li><a href="#">Translation Program</a></li>
                <li><a href="#">Language Course</a></li>
                <li><a href="#">Language Test</a></li>
                <li><a href="#">Articles & Updates</a></li>
                <li><a href="#">About</a></li>
            </ul>
        </nav>
    </div>
    <h2>Pendaftaran Saya</h2>
    <table>
        <tr>
            <th>Lokasi</th>
            <th>Nama Lengkap</th>
            <th>Moda</th>
            <th>Kursus</th>
            <th>Level</th>
            <th>Status Pembayaran</th>
            <th>Status Test</th>
            <th>Blangko Biaya Pendaftaran</th>
        </tr>
        <?php
        session_start();
        include 'db.php';

        // Periksa apakah pengguna sudah login
        if (!isset($_SESSION['user_id'])) {
            header('Location: http://localhost/lbi/lbifib.ui.ac.id/index.php/id/user/login/login.html');
            exit();
        }

        $user_id = $_SESSION['user_id'];

        // Tentukan mapping kursus dan level berdasarkan id_kursus
        $kursus_level_map = [
            1 => ["General English", 1],
            2 => ["General English", 2],
            3 => ["General English", 3],
            4 => ["General English", 4],
            5 => ["General English", 5],
            6 => ["General English", 6],
            7 => ["General English", 7],
            8 => ["General English", 8],
            9 => ["General English", 9],
            10 => ["General English", 10],
            11 => ["Business English", 1],
            12 => ["Business English", 2],
            13 => ["Business English", 3],
            14 => ["Business English", 4],
            15 => ["Conversation", 1],
            16 => ["Conversation", 2],
            17 => ["Conversation", 3],
            18 => ["Conversation", 4],
            19 => ["Academic Writing", 1],
            20 => ["Academic Writing", 2],
            21 => ["Academic Writing", 3],
            22 => ["Academic Writing", 4],
            27 => ["Legal English", 1],
            28 => ["Legal English", 2],
            29 => ["Legal English", 3],
            30 => ["Legal English", 4]
        ];

        // Ambil data pendaftaran pengguna dari tabel registrations dan users menggunakan JOIN
        $sql = "SELECT r.lokasi, r.nama, r.moda, r.id_kursus, r.status_pembayaran, r.status_test 
                FROM registrations r
                JOIN users u ON r.username = u.username
                WHERE u.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($lokasi, $nama, $moda, $id_kursus, $status_pembayaran, $status_test);

        // Tampilkan data pendaftaran dalam tabel
        while ($stmt->fetch()) {
            if (isset($kursus_level_map[$id_kursus])) {
                $kursus = $kursus_level_map[$id_kursus][0];
                $level = $kursus_level_map[$id_kursus][1];
            } else {
                $kursus = "Tidak Diketahui";
                $level = "Tidak Diketahui";
            }
            echo "<tr>";
            echo "<td>$lokasi</td>";
            echo "<td>$nama</td>";
            echo "<td>$moda</td>";
            echo "<td>$kursus</td>";
            echo "<td>$level</td>";
            echo "<td>$status_pembayaran</td>";
            echo "<td>$status_test</td>";
            echo "<td><a href='unduh_blangko.php?id=$user_id'>Unduh</a></td>"; // Ganti unduh_blangko.php dengan skrip yang sesuai untuk mengunduh blangko biaya pendaftaran
            echo "</tr>";
        }
        $stmt->close();
        ?>
    </table>
    <footer>
        <div class="footer-content">
            <div class="footer-logo">
                <img src="logo.png" alt="LBI Logo"> <!-- Ganti dengan logo yang sesuai -->
            </div>
            <div class="footer-contact">
                <p>LBI SALEMBA</p>
                <p>Gd. I Ruvi UI</p>
                <p>Jl. Salemba Raya No. 4, Jakarta 10430</p>
                <p>Tel: (021) 319 02112, 31930235</p>
                <p>WhatsApp (Chat Only): +62 819-4574-7274</p>
            </div>
            <div class="footer-contact">
                <p>LBI DEPOK</p>
                <p>Faculty of Humanities Building, 1st Floor, University of Indonesia, Depok</p>
                <p>Phone: (+62-21) 7864089, 78849082</p>
                <p>Fax: (+62-21) 78849085</p>
                <p>WhatsApp (Chat Only): +62 819-4574-7274</p>
            </div>
            <div class="footer-email">
                <p>EMAIL US</p>
                <p>Customer Service LBI: informasi@lbifibui.com</p>
                <p>Program Penerjemahan: ppp@lbifibui.com</p>
                <p>Program BIPA: bipa@lbifibui.com</p>
            </div>
        </div>
    </footer>
</body>
</html>
