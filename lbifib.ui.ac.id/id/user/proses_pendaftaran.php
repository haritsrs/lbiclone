<?php
// Sambungkan ke database
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $handphone = $_POST['handphone'];
    $lokasi = $_POST['lokasi'];
    $moda = $_POST['moda'];
    $kursus = $_POST['kursus'];
    $level = $_POST['level'];
    $ikuti_tes_penempatan = isset($_POST['tes_penempatan']) ? 1 : 0;
    $jadwal_tes_penempatan = isset($_POST['tes_penempatan']) ? $_POST['tanggal_tes'] : null;
    $ajukan_keringanan = isset($_POST['keringanan']) ? 1 : 0;
    $setuju_syarat_ketentuan = isset($_POST['syarat_ketentuan']) ? 1 : 0;
    $status_pembayaran = "TIDAK LUNAS";
    $status_test = isset($_POST['tes_penempatan']) ? "BELUM DIIKUTI" : "SELESAI";

    // Tentukan id_kursus berdasarkan kursus dan level yang dipilih
    $kursus_level_map = [
        "General English" => [1, 2, 3, 4, 5, 6, 7, 8, 9, 10],
        "Business English" => [11, 12, 13, 14],
        "Conversation" => [15, 16, 17, 18],
        "Academic Writing" => [19, 20, 21, 22],
        "Legal English" => [27, 28, 29, 30]
    ];

    if ($kursus === "General English") {
        $id_kursus = $level;
    } else {
        $id_kursus = $kursus_level_map[$kursus][$level - 1];
    }

    // Query SQL untuk menyimpan data pendaftaran ke database
    $sql = "INSERT INTO registrations (username, nama, email, handphone, lokasi, moda, ikuti_tes_penempatan, jadwal_tes_penempatan, ajukan_keringanan, setuju_syarat_ketentuan, status_pembayaran, status_test, id_kursus) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssisiissi", $username, $nama, $email, $handphone, $lokasi, $moda, $ikuti_tes_penempatan, $jadwal_tes_penempatan, $ajukan_keringanan, $setuju_syarat_ketentuan, $status_pembayaran, $status_test, $id_kursus);

    // Eksekusi query
    if ($stmt->execute()) {
        // Jika data berhasil disimpan, tampilkan pop-up
        echo '<script>alert("Pendaftaran berhasil!");</script>';
        // Redirect ke halaman awal
        echo '<script>window.location.href="http://localhost/lbi/lbifib.ui.ac.id/index.html";</script>';
        exit();
    } else {
        // Jika terjadi kesalahan, tampilkan pesan error
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Tutup statement dan koneksi database
    $stmt->close();
    $conn->close();
}
?>
