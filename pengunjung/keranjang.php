<?php
// pengunjung/keranjang.php - Single File untuk Tampilan, Update, Delete, dan Checkout
include '../config/config.php';
session_start();

// --- WAJIB LOGIN ---
if (!isset($_SESSION['id']) || $_SESSION['id'] <= 0) {
  header("Location: ../login.php?pesan=wajib_login_keranjang");
  exit();
}

$user_id = $_SESSION['id'];
$pesan = isset($_GET['pesan']) ? htmlspecialchars($_GET['pesan']) : '';


// --- LOGIKA UPDATE / DELETE / CHECKOUT ---
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {

  $action = $_POST['action'];
  $success = true;

  if ($action == 'update' || $action == 'delete') {
    // --- LOGIKA UPDATE/DELETE ITEM ---
    $cart_id = mysqli_real_escape_string($konek, $_POST['cart_id']);

    if ($action == 'update') {
      $quantity = (int) $_POST['quantity'];
      if ($quantity <= 0) {
        $pesan = "Jumlah harus minimal 1.";
      } else {
        $query = "UPDATE cart SET quantity='$quantity' WHERE id='$cart_id' AND user_id='$user_id'";
        if (mysqli_query($konek, $query)) {
          $pesan = "Jumlah produk berhasil diperbarui.";
        } else {
          $pesan = "Gagal memperbarui: " . mysqli_error($konek);
        }
      }
    } elseif ($action == 'delete') {
      $query = "DELETE FROM cart WHERE id='$cart_id' AND user_id='$user_id'";
      if (mysqli_query($konek, $query)) {
        $pesan = "Produk berhasil dihapus dari keranjang.";
      } else {
        $pesan = "Gagal menghapus: " . mysqli_error($konek);
      }
    }

    // Redirect setelah Update/Delete
    header("Location: keranjang.php?pesan=" . urlencode($pesan));
    exit();

  } elseif ($action == 'checkout_submit') {
    // --- LOGIKA CHECKOUT (TRANSAKSI) ---

    $query_cart = "SELECT c.product_id, c.quantity, p.price, p.name FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$user_id'";
    $result_cart = mysqli_query($konek, $query_cart);
    $cart_items_checkout = mysqli_fetch_all($result_cart, MYSQLI_ASSOC);

    if (empty($cart_items_checkout)) {
      header("Location: keranjang.php?pesan=Keranjang_kosong_tidak_bisa_checkout");
      exit();
    }

    $total_amount = 0;
    foreach ($cart_items_checkout as $item) {
      $total_amount += $item['quantity'] * $item['price'];
    }

    mysqli_begin_transaction($konek);

    try {
      // A. BUAT RECORD BARU DI TABEL ORDERS
      $query_order = "INSERT INTO orders (user_id, total_amount, status) VALUES ('$user_id', '$total_amount', 'Pending')";
      if (!mysqli_query($konek, $query_order))
        throw new Exception("Gagal membuat order.");
      $order_id = mysqli_insert_id($konek);

      // B. PINDAHKAN ITEM DARI CART KE ORDER_ITEMS
      foreach ($cart_items_checkout as $item) {
        $product_id = $item['product_id'];
        $quantity = $item['quantity'];
        $price_at_order = $item['price'];
        $query_item = "INSERT INTO order_items (order_id, product_id, quantity, price_at_order) VALUES ('$order_id', '$product_id', '$quantity', '$price_at_order')";
        if (!mysqli_query($konek, $query_item))
          throw new Exception("Gagal memindahkan item.");
      }

      // C. KOSONGKAN KERANJANG
      $query_clear_cart = "DELETE FROM cart WHERE user_id = '$user_id'";
      if (!mysqli_query($konek, $query_clear_cart))
        throw new Exception("Gagal mengosongkan keranjang.");

      mysqli_commit($konek);

      // Redirect ke halaman struk jika sukses
      header("Location: struk.php?order_id=$order_id");
      exit();

    } catch (Exception $e) {
      mysqli_rollback($konek);
      $success = false;
      $pesan = "Checkout gagal total: " . $e->getMessage();

      // Redirect kembali ke keranjang jika transaksi gagal
      header("Location: keranjang.php?pesan=" . urlencode($pesan));
      exit();
    }
  }
}


// --- LOGIKA MENAMPILKAN ISI KERANJANG (READ) ---
$query = "SELECT c.id AS cart_id, c.quantity, p.name, p.price, p.photo
          FROM cart c
          JOIN products p ON c.product_id = p.id
          WHERE c.user_id = '$user_id'";

$result = mysqli_query($konek, $query);
$cart_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

$grand_total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Keranjang Belanja</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      font-family: sans-serif;
      background-color: #f3f4f6;
      padding: 40px;
    }

    .container {
      max-width: 1000px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #1e3a8a;
      border-bottom: 2px solid #e5e7eb;
      padding-bottom: 10px;
      margin-bottom: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    th,
    td {
      padding: 12px;
      border-bottom: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f0f0f0;
    }

    .total {
      font-size: 1.5rem;
      font-weight: bold;
      color: #dc2626;
      text-align: right;
      padding-right: 12px;
    }

    .btn-checkout {
      background-color: #2563eb;
      color: white;
      padding: 12px 25px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 15px;
    }

    .btn-checkout:hover {
      background-color: #1d4ed8;
    }

    .item-image {
      width: 50px;
      height: 50px;
      object-fit: cover;
      border-radius: 4px;
    }

    /* Style untuk Tombol Aksi */
    .btn-update {
      background-color: #10b981;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.9rem;
      margin-right: 5px;
    }

    .btn-hapus {
      background-color: #ef4444;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 0.9rem;
    }

    .qty-input {
      width: 60px;
      padding: 5px;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 4px;
      margin-right: 5px;
    }

    .btn-lanjut {
      background-color: #6b7280;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 6px;
      font-weight: bold;
      float: left;
      margin-top: 15px;
    }

    .btn-lanjut:hover {
      background-color: #4b5563;
    }
  </style>
</head>

<body>
  <div class="container">

    <a href="produk_kami.php"
      style="float: left; margin-bottom: 20px; color: #1e3a8a; text-decoration: none; font-weight: bold;">
      &larr; Lanjut Tambah Produk
    </a>

    <div style="clear: both;"></div>
    <h1><i class="fas fa-shopping-cart"></i> Keranjang Belanja Anda</h1>

    <?php if ($pesan): ?>
      <p style="color: #059669; background-color: #d1fae5; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
        <?php echo $pesan; ?>
      </p>
    <?php endif; ?>

    <?php if (empty($cart_items)): ?>
      <p style="color: #6b7280; text-align: center;">Keranjang Anda masih kosong. Yuk, tambahkan menu favorit!</p>
      <div style="text-align: center; margin-top: 30px;">
        <a href="produk_kami.php"
          style="color: white; background-color: #2563eb; padding: 10px 20px; text-decoration: none; border-radius: 6px;">Lihat
          Menu</a>
      </div>
    <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>Gambar</th>
            <th>Nama Produk</th>
            <th>Harga Satuan</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($cart_items as $item):
            $subtotal = $item['quantity'] * $item['price'];
            $grand_total += $subtotal;
            ?>
            <tr>
              <td><img src="../admin/<?php echo $item['photo']; ?>" alt="Foto" class="item-image"></td>
              <td><?php echo htmlspecialchars($item['name']); ?></td>
              <td>₩ <?php echo number_format($item['price'], 0, ',', '.'); ?></td>

              <td>
                <form action="keranjang.php" method="POST" style="display: flex; align-items: center;">
                  <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                  <input type="hidden" name="action" value="update">

                  <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="qty-input">

                  <button type="submit" class="btn-update" title="Perbarui Jumlah">
                    <i class="fas fa-sync-alt"></i>
                  </button>
                </form>
              </td>

              <td>₩ <?php echo number_format($subtotal, 0, ',', '.'); ?></td>

              <td>
                <form action="keranjang.php" method="POST" onsubmit="return confirm('Yakin ingin menghapus item ini?');">
                  <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                  <input type="hidden" name="action" value="delete">

                  <button type="submit" class="btn-hapus">
                    <i class="fas fa-trash-alt"></i> Hapus
                  </button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div style="overflow: auto;">
        <table style="width: 30%; float: right; margin-top: 20px;">
          <tr>
            <td class="total" style="border-bottom: none; background-color: transparent;">Total Pembelian:</td>
            <td class="total" style="border-bottom: none; background-color: transparent;">₩
              <?php echo number_format($grand_total, 0, ',', '.'); ?>
            </td>
          </tr>
        </table>

        <form action="keranjang.php" method="POST" style="float: right; margin-left: 20px;">
          <input type="hidden" name="action" value="checkout_submit">
          <button type="submit" name="checkout_submit" class="btn-checkout">
            Check Out <i class="fas fa-arrow-right"></i>
          </button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>

</html>