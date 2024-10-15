<?php
require_once('tcpdf/tcpdf.php');
include('admin_db.php');

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

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Laporan Keuangan');
$pdf->SetSubject('Laporan Keuangan');

// Add a page
$pdf->AddPage();

// Set content
$html = '
<h1 style="text-align: center;">Laporan Keuangan</h1>
<h2 style="text-align: center;">Lembaga Bahasa Internasional FIB UI</h2>
<br><br>
<table border="1" cellpadding="4">
    <tr>
        <th>Total yang Sudah Dibayar</th>
        <td>' . number_format($total_lunas) . '</td>
    </tr>
    <tr>
        <th>Total yang Belum Dibayar</th>
        <td>' . number_format($total_tidak_lunas) . '</td>
    </tr>
    <tr>
        <th>Total Registrasi</th>
        <td>' . number_format($total_registrasi) . '</td>
    </tr>
</table>
';

// Print text using writeHTMLCell()
$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

// Close and output PDF document
$pdf->Output('laporan_keuangan.pdf', 'I');
?>
