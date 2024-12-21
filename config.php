<?php
if (basename(__FILE__) == basename($_SERVER['PHP_SELF'])) {
    header('HTTP/1.0 403 Forbidden');
    exit('Direct access not allowed.');
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tubes";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
