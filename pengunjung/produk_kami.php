<?php
// pengunjung/produk_kami.php
include '../config/config.php';
session_start();

$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";
$username = $_SESSION['username'] ?? '';
$user_role = $_SESSION['role'] ?? 'pengunjung';

if ($is_logged_in && $user_role == 'admin') {
  header("location: ../admin/dashboard.php");
  exit();
}

// Ambil semua produk
$query = "SELECT * FROM products ORDER BY category, price";
$result = mysqli_query($konek, $query);
$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Menu Cafe & Produk</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* CSS Sederhana untuk Produk Card */
    body {
      font-family: sans-serif;
      background-color: #f3f4f6;
      padding: 40px;
    }

    .grid-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      max-width: 1200px;
      margin: auto;
    }

    .product-card {
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      transition: transform 0.2s;
    }

    .product-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 12px rgba(0, 0, 0, 0.15);
    }

    .product-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
    }

    .product-info {
      padding: 15px;
    }

    .product-title {
      font-size: 1.5rem;
      font-weight: bold;
      margin-bottom: 5px;
      color: #1e3a8a;
    }

    .product-price {
      color: #dc2626;
      font-weight: bold;
      margin-bottom: 15px;
    }

    .btn-detail {
      display: block;
      text-align: center;
      background-color: #f0f0f0;
      color: #1e3a8a;
      padding: 10px;
      border-radius: 5px;
      text-decoration: none;
      font-weight: 500;
    }
  </style>
</head>

<body>

  <div class="grid-container">
    <?php if (empty($products)): ?>
      <p style="grid-column: 1 / -1; text-align: center;">Belum ada produk yang ditambahkan.</p>
    <?php endif; ?>

    <?php foreach ($products as $product): ?>
      <div class="product-card">
        <img src="../admin/<?php echo $product['photo'] ?: 'https://via.placeholder.com/300x200?text=Menu'; ?>"
          alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        <div class="product-info">
          <p style="font-size: 0.8rem; color: #6b7280; margin-bottom: 5px;">
            <?php echo htmlspecialchars($product['category']); ?></p>
          <h3 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h3>
          <p class="product-price">₩ <?php echo number_format($product['price'], 0, ',', '.'); ?></p>

          <a href="produk_kami_detail.php?id=<?php echo $product['id']; ?>" class="btn-detail">
            Liat Produk Lebih Detil dan Pesan
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

</body>

</html>