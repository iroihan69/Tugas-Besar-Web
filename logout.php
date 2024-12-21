<?php
// Properly handle session termination and cache clearing
session_start();
session_unset();
session_destroy();
setcookie(session_name(), '', time() - 3600, '/');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Expires: Sat, 26 Jul 1997 05:00:00 GMT');
header('Pragma: no-cache');
header('Location: index.php');
exit();
?>
