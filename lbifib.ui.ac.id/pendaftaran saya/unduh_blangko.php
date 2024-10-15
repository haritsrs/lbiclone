<?php
// Load library TCPDF
require_once('tcpdf/tcpdf.php');

// Mulai sesi
session_start();
include 'db.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: http://localhost/lbi/lbifib.ui.ac.id/index.php/id/user/login/login.html');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pendaftaran pengguna dari tabel registrations dan users menggunakan JOIN
$sql = "SELECT r.lokasi, r.nama, r.moda, r.id_kursus, r.status_pembayaran, r.status_test 
        FROM registrations r
        JOIN users u ON r.username = u.username
        WHERE u.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($lokasi, $nama, $moda, $id_kursus, $status_pembayaran, $status_test);

if ($stmt->fetch()) {
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

    if (isset($kursus_level_map[$id_kursus])) {
        $kursus = $kursus_level_map[$id_kursus][0];
        $level = $kursus_level_map[$id_kursus][1];
    } else {
        $kursus = "Tidak Diketahui";
        $level = "Tidak Diketahui";
    }

    // Buat objek TCPDF baru
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set judul dokumen
    $pdf->SetTitle('Blangko Biaya Pendaftaran');

    // Tambahkan halaman baru
    $pdf->AddPage();

    // Dapatkan tanggal saat ini
    $tanggal_cetak = date('d-m-Y');

    // Tambahkan konten ke halaman
    $pdf->SetFont('times', '', 12);
    $html = "
        <h1>Blangko Biaya Pendaftaran</h1>
        <p><strong>Nama Lengkap:</strong> $nama</p>
        <p><strong>Lokasi:</strong> $lokasi</p>
        <p><strong>Moda:</strong> $moda</p>
        <p><strong>Kursus:</strong> $kursus</p>
        <p><strong>Level:</strong> $level</p>
        <p><strong>Status Pembayaran:</strong> $status_pembayaran</p>
        <p><strong>Status Test:</strong> $status_test</p>
        <br>
        <p><strong>Tanggal Cetak:</strong> $tanggal_cetak</p>
        <p><strong>Harga Blangko:</strong> Rp1.620.000,00</p>
        <p><strong>Virtual Account:</strong> 4954401148</p>
    ";
    $pdf->writeHTML($html, true, false, true, false, '');

    // Simpan PDF ke file dengan nama blangko_pendaftaran.pdf
    $pdf->Output('blangko_pendaftaran.pdf', 'D');

    // Tutup objek PDF
    $pdf->Close();
} else {
    echo "Data tidak ditemukan.";
}

$stmt->close();
$conn->close();
?>
