<?php
// logout.php
session_start();

// Hapus semua variabel sesi
$_SESSION = array();

// Hancurkan sesi
session_destroy();

// Alihkan kembali ke halaman login (sesuaikan path jika perlu)
header("Location: login.php");
exit();
?>