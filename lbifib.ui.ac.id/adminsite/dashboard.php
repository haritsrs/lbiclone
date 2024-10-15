<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lbi";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

function getTotal($conn, $status = null) {
    $query = "SELECT COUNT(*) as total FROM registrations";
    if ($status !== null) {
        $query .= " WHERE status_pembayaran = '$status'";
    }
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total'] * 1620000;
}

$total_registrasi = getTotal($conn);
$total_lunas = getTotal($conn, 'LUNAS');
$total_tidak_lunas = getTotal($conn, 'TIDAK LUNAS');

// Jika status pembayaran diubah
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['status_pembayaran'])) {
    $id = $_POST['id'];
    $status_pembayaran = $_POST['status_pembayaran'];
    
    $sql = "UPDATE registrations SET status_pembayaran='$status_pembayaran' WHERE id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Status pembayaran berhasil diubah";
    } else {
        echo "Error: " . $conn->error;
    }
}

// Ambil data dari database
$sql = "SELECT r.id, u.username, u.email, u.handphone, r.lokasi, r.moda, r.ikuti_tes_penempatan, 
        r.jadwal_tes_penempatan, r.ajukan_keringanan, r.setuju_syarat_ketentuan, r.created_at, 
        r.kursus, r.level, r.status_test, r.status_pembayaran, r.id_kursus 
        FROM users u 
        JOIN registrations r ON u.username = r.username";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Admin Dashboard</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Handphone</th>
                <th>Lokasi</th>
                <th>Moda</th>
                <th>Ikuti Tes Penempatan</th>
                <th>Jadwal Tes Penempatan</th>
                <th>Ajukan Keringanan</th>
                <th>Setuju Syarat Ketentuan</th>
                <th>Created At</th>
                <th>Kursus</th>
                <th>Level</th>
                <th>Status Test</th>
                <th>Status Pembayaran</th>
                <th>ID Kursus</th>
                <th>Ubah Status Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['username']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['handphone']; ?></td>
                        <td><?php echo $row['lokasi']; ?></td>
                        <td><?php echo $row['moda']; ?></td>
                        <td><?php echo $row['ikuti_tes_penempatan']; ?></td>
                        <td><?php echo $row['jadwal_tes_penempatan'] ?: '-'; ?></td>
                        <td><?php echo $row['ajukan_keringanan']; ?></td>
                        <td><?php echo $row['setuju_syarat_ketentuan']; ?></td>
                        <td><?php echo $row['created_at']; ?></td>
                        <td><?php echo $row['kursus']; ?></td>
                        <td><?php echo $row['level']; ?></td>
                        <td><?php echo $row['status_test']; ?></td>
                        <td><?php echo $row['status_pembayaran']; ?></td>
                        <td><?php echo $row['id_kursus']; ?></td>
                        <td>
                            <form method="post" action="">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <select name="status_pembayaran" onchange="this.form.submit()">
                                    <option value="TIDAK LUNAS" <?php echo ($row['status_pembayaran'] == 'TIDAK LUNAS') ? 'selected' : ''; ?>>TIDAK LUNAS</option>
                                    <option value="LUNAS" <?php echo ($row['status_pembayaran'] == 'LUNAS') ? 'selected' : ''; ?>>LUNAS</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="17">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <h2>Laporan Keuangan</h2>
    <table>
        <tr>
            <th>Total yang Sudah Dibayar</th>
            <td><?php echo number_format($total_lunas); ?></td>
        </tr>
        <tr>
            <th>Total yang Belum Dibayar</th>
            <td><?php echo number_format($total_tidak_lunas); ?></td>
        </tr>
        <tr>
            <th>Total Registrasi</th>
            <td><?php echo number_format($total_registrasi); ?></td>
        </tr>
    </table>
    <form method="post" action="generate_pdf.php">
        <button type="submit">Print PDF</button>
    </form>
    <a href="logout.php">Logout</a>
</body>
</html>

<?php
$conn->close();
?>
