<?php
session_start();
session_unset();
session_destroy();

// Nonaktifkan cache
header("Cache-Control: no-cache, must-revalidate, max-age=0");
header("Expires: 0");
header("Pragma: no-cache");

// Redirect ke halaman login
header("Location: login.php");
exit();
?>
