<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "db_sertifikasi";

$konek = new mysqli($hostname, $username, $password, $database);

if ($konek->connect_error) {
  die('Koneksi gagal: ' . $konek->connect_error);
}

?>