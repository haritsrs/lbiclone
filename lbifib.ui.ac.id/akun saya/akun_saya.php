<?php
session_start();
include 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: http://localhost/lbi/lbifib.ui.ac.id/index.php/id/user/login/login.html');
    exit();
}

$user_id = $_SESSION['user_id'];
$nama_panjang = ""; // Definisikan variabel $nama_panjang dengan nilai default kosong

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        // Hapus semua data kecuali nama, email, dan password
        $sql = "UPDATE users SET jenis_kelamin=NULL, tanggal_lahir=NULL, handphone=NULL, nik=NULL, pendidikan_terakhir=NULL, negara=NULL, alamat=NULL, kode_pos=NULL, sumber_informasi=NULL WHERE id=?";
    } else {
        // Simpan perubahan data
        $nama_panjang = $_POST['nama_panjang'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $handphone = $_POST['handphone'];
        $nik = $_POST['nik'];
        $pendidikan_terakhir = $_POST['pendidikan_terakhir'];
        $negara = $_POST['negara'];
        $alamat = $_POST['alamat'];
        $kode_pos = $_POST['kode_pos'];
        $sumber_informasi = $_POST['sumber_informasi'];

        $sql = "UPDATE users SET nama_panjang=?, jenis_kelamin=?, tanggal_lahir=?, handphone=?, nik=?, pendidikan_terakhir=?, negara=?, alamat=?, kode_pos=?, sumber_informasi=? WHERE id=?";
    }
    
    $stmt = $conn->prepare($sql);
    if (isset($_POST['delete'])) {
        $stmt->bind_param("i", $user_id);
    } else {
        $stmt->bind_param("ssssssssssi", $nama_panjang, $jenis_kelamin, $tanggal_lahir, $handphone, $nik, $pendidikan_terakhir, $negara, $alamat, $kode_pos, $sumber_informasi, $user_id);
    }
    $stmt->execute();
    $stmt->close();
}

// Ambil data pengguna
$sql = "SELECT username, email, nama_panjang, jenis_kelamin, tanggal_lahir, handphone, nik, pendidikan_terakhir, negara, alamat, kode_pos, sumber_informasi FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $email, $nama_panjang, $jenis_kelamin, $tanggal_lahir, $handphone, $nik, $pendidikan_terakhir, $negara, $alamat, $kode_pos, $sumber_informasi);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Saya</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
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
    <h2>Akun Saya</h2>
    <form method="POST" action="akun_saya.php">

        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" value="<?php echo $username; ?>" readonly><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?php echo $email; ?>" readonly><br><br>

        <label for="nama_panjang">Nama Panjang:</label><br>
        <input type="text" id="nama_panjang" name="nama_panjang" value="<?php echo $nama_panjang; ?>"><br><br>

        <label for="jenis_kelamin">Jenis Kelamin:</label><br>
        <select id="jenis_kelamin" name="jenis_kelamin">
            <option value="Male" <?php if ($jenis_kelamin == 'Male') echo 'selected'; ?>>Male</option>
            <option value="Female" <?php if ($jenis_kelamin == 'Female') echo 'selected'; ?>>Female</option>
        </select><br><br>

        <label for="tanggal_lahir">Tanggal Lahir:</label><br>
        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $tanggal_lahir; ?>"><br><br>

        <label for="handphone">Handphone:</label><br>
        <input type="text" id="handphone" name="handphone" value="<?php echo $handphone; ?>"><br><br>

        <label for="nik">NIK:</label><br>
        <input type="text" id="nik" name="nik" value="<?php echo $nik; ?>"><br><br>

        <label for="pendidikan_terakhir">Pendidikan Terakhir:</label><br>
        <select id="pendidikan_terakhir" name="pendidikan_terakhir">
            <option value="SMA/SMK" <?php if ($pendidikan_terakhir == 'SMA/SMK') echo 'selected'; ?>>SMA/SMK</option>
            <option value="S1" <?php if ($pendidikan_terakhir == 'S1') echo 'selected'; ?>>S1</option>
            <option value="D3/D4" <?php if ($pendidikan_terakhir == 'D3/D4') echo 'selected'; ?>>D3/D4</option>
            <option value="S2" <?php if ($pendidikan_terakhir == 'S2') echo 'selected'; ?>>S2</option>
            <option value="Lainnya" <?php if ($pendidikan_terakhir == 'Lainnya') echo 'selected'; ?>>Lainnya</option>
        </select><br><br>

        <label for="negara">Negara:</label><br>
        <select id="negara" name="negara">
            <option value="WNI" <?php if ($negara == 'WNI') echo 'selected'; ?>>WNI</option>
            <option value="WNA" <?php if ($negara == 'WNA') echo 'selected'; ?>>WNA</option>
        </select><br><br>

        <label for="alamat">Alamat:</label><br>
        <input type="text" id="alamat" name="alamat" value="<?php echo $alamat; ?>"><br><br>

        <label for="kode_pos">Kode Pos:</label><br>
        <input type="text" id="kode_pos" name="kode_pos" value="<?php echo $kode_pos; ?>"><br><br>

        <label for="sumber_informasi">Sumber Informasi:</label><br>
        <select id="sumber_informasi" name="sumber_informasi">
            <option value="Brochure" <?php if ($sumber_informasi == 'Brochure') echo 'selected'; ?>>Brochure</option>
            <option value="Banner" <?php if ($sumber_informasi == 'Banner') echo 'selected'; ?>>Banner</option>
            <option value="Newspaper" <?php if ($sumber_informasi == 'Newspaper') echo 'selected'; ?>>Newspaper</option>
            <option value="Friend" <?php if ($sumber_informasi == 'Friend') echo 'selected'; ?>>Friend</option>
            <option value="Family" <?php if ($sumber_informasi == 'Family') echo 'selected'; ?>>Family</option>
            <option value="Website" <?php if ($sumber_informasi == 'Website') echo 'selected'; ?>>Website</option>
            <option value="Social Media" <?php if ($sumber_informasi == 'Social Media') echo 'selected'; ?>>Social Media</option>
            <option value="Others" <?php if ($sumber_informasi == 'Others') echo 'selected'; ?>>Others</option>
        </select><br><br>

        <button type="submit" name="save">Simpan</button>
        <button type="submit" name="delete" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
    </form>
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