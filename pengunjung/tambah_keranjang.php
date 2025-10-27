<?php
// pengunjung/tambah_keranjang.php
include '../config/config.php';
session_start();

// --- PENGAMANAN DAN CHECK LOGIN ---
if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
  header("Location: ../login.php?pesan=wajib_login_pesan");
  exit();
}

if (!isset($_POST['add_to_cart'])) {
  header("Location: produk_kami.php");
  exit();
}

$user_id = $_SESSION['id'];
$product_id = mysqli_real_escape_string($konek, $_POST['product_id']);
$quantity = (int) $_POST['quantity'];

if ($quantity <= 0)
  $quantity = 1;

// --- LOGIKA TAMBAH/UPDATE KERANJANG ---
$query_check = "SELECT * FROM cart WHERE user_id='$user_id' AND product_id='$product_id'";
$result_check = mysqli_query($konek, $query_check);

if (mysqli_num_rows($result_check) > 0) {
  // KASUS 1: ITEM SUDAH ADA (Update quantity)
  $data_cart = mysqli_fetch_assoc($result_check);
  $new_quantity = $data_cart['quantity'] + $quantity;

  $query_update = "UPDATE cart SET quantity='$new_quantity' WHERE user_id='$user_id' AND product_id='$product_id'";
  mysqli_query($konek, $query_update);
  $pesan = "Jumlah produk berhasil diperbarui di keranjang.";

} else {
  // KASUS 2: ITEM BARU (Insert)
  $query_insert = "INSERT INTO cart (user_id, product_id, quantity) 
                     VALUES ('$user_id', '$product_id', '$quantity')";
  mysqli_query($konek, $query_insert);
  $pesan = "Produk berhasil ditambahkan ke keranjang.";
}

// Redirect ke halaman keranjang (Langkah selanjutnya)
header("Location: keranjang.php?pesan=" . urlencode($pesan));
exit();
?>