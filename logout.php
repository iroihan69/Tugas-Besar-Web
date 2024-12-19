<?php
session_start();
session_unset(); // Hapus semua variabel sesi
session_destroy();

// Nonaktifkan cache
header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");
setcookie(session_name(), '', time() - 3600, '/'); // Hapus cookie sesi

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
