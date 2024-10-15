<?php
// Mulai session
session_start();

// Hapus semua data session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman utama
header("Location: http://localhost/lbi/lbifib.ui.ac.id/index.html");
exit();
?>
