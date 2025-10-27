<?php
// pengunjung/produk_kami_detail.php
include '../config/config.php';
session_start();

$user_id = $_SESSION['id'] ?? 0;
$is_logged_in = $user_id > 0;
$pesan = '';

// --- 1. AMBIL DETAIL PRODUK ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: produk_kami.php");
  exit();
}
$product_id = mysqli_real_escape_string($konek, $_GET['id']);
$query = "SELECT * FROM products WHERE id='$product_id'";
$result = mysqli_query($konek, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
  header("Location: produk_kami.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Detail <?php echo htmlspecialchars($product['name']); ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* CSS Sederhana untuk Detail Produk */
    body {
      font-family: sans-serif;
      background-color: #f3f4f6;
      padding: 40px;
    }

    .container {
      max-width: 900px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      display: flex;
      gap: 30px;
    }

    .product-image {
      width: 400px;
      height: 350px;
      object-fit: cover;
      border-radius: 8px;
    }

    .product-details {
      flex-grow: 1;
    }

    .product-title {
      font-size: 2rem;
      font-weight: bold;
      color: #1e3a8a;
      margin-bottom: 10px;
    }

    .product-price {
      font-size: 1.5rem;
      color: #dc2626;
      font-weight: bold;
      margin-bottom: 20px;
    }

    .product-desc {
      margin-bottom: 20px;
      line-height: 1.6;
      color: #374151;
    }

    .btn-keranjang {
      background-color: #10b981;
      color: white;
      padding: 12px 20px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      transition: background-color 0.2s;
      font-weight: bold;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .btn-keranjang:hover {
      background-color: #059669;
    }

    .text-error {
      color: #dc2626;
      font-weight: bold;
      margin-top: 15px;
    }
  </style>
</head>

<body>
  <a href="produk_kami.php" style="margin-bottom: 20px; display: block; color: #1e3a8a;">&larr; Kembali ke Daftar
    Menu</a>

  <div class="container">
    <img src="../admin/<?php echo $product['photo'] ?: 'https://via.placeholder.com/400x350?text=Menu'; ?>"
      alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">

    <div class="product-details">
      <p style="font-size: 0.9rem; color: #6b7280;"><?php echo htmlspecialchars($product['category']); ?></p>
      <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
      <p class="product-price">₩ <?php echo number_format($product['price'], 0, ',', '.'); ?></p>

      <h2 style="font-size: 1.2rem; margin-bottom: 10px; color: #1f2937;">Deskripsi:</h2>
      <p class="product-desc"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

      <h2 style="font-size: 1.2rem; margin-bottom: 10px; color: #1f2937;">Pesan:</h2>

      <?php if ($is_logged_in): ?>
        <form action="tambah_keranjang.php" method="POST" style="display: flex; gap: 10px; align-items: center;">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <input type="number" name="quantity" value="1" min="1"
            style="width: 80px; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

          <button type="submit" name="add_to_cart" class="btn-keranjang">
            <i class="fas fa-cart-plus"></i> Tambah ke Keranjang
          </button>
        </form>
        <p style="margin-top: 10px; color: #10b981; font-size: 0.9rem;">(Anda sudah login sebagai
          <?php echo htmlspecialchars($_SESSION['username']); ?>)</p>
      <?php else: ?>
        <p class="text-error">Silakan <a href="../login.php" style="color: #2563eb; text-decoration: underline;">Login</a>
          untuk memesan produk ini.</p>
      <?php endif; ?>
    </div>
  </div>
</body>

</html>