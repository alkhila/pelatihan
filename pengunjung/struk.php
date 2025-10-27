<?php
// pengunjung/struk.php - Menampilkan struk pembelian
include '../config/config.php';
session_start();

// --- WAJIB LOGIN DAN CEK ORDER ID ---
if (!isset($_SESSION['id']) || !isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
  header("Location: keranjang.php");
  exit();
}

$order_id = mysqli_real_escape_string($konek, $_GET['order_id']);
$user_id = $_SESSION['id'];

// --- QUERY 1: Ambil Detail Pesanan (Header) ---
$query_order = "SELECT o.id, o.order_date, o.total_amount, u.name AS user_name
                FROM orders o
                JOIN users u ON o.user_id = u.id
                WHERE o.id = '$order_id' AND o.user_id = '$user_id'";
$result_order = mysqli_query($konek, $query_order);
$order = mysqli_fetch_assoc($result_order);

if (!$order) {
  header("Location: keranjang.php?pesan=Struk_tidak_ditemukan");
  exit();
}

// --- QUERY 2: Ambil Item Pesanan ---
$query_items = "SELECT oi.quantity, oi.price_at_order, p.name 
                FROM order_items oi
                JOIN products p ON oi.product_id = p.id
                WHERE oi.order_id = '$order_id'";
$result_items = mysqli_query($konek, $query_items);
$order_items = mysqli_fetch_all($result_items, MYSQLI_ASSOC);

// Format Tanggal
$order_date = date('d F Y H:i:s', strtotime($order['order_date']));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Struk Pembelian #<?php echo $order['id']; ?></title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    body {
      font-family: sans-serif;
      background-color: #f3f4f6;
      padding: 40px;
    }

    .struk-container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    h1 {
      color: #10b981;
      border-bottom: 3px solid #10b981;
      padding-bottom: 10px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 2rem;
    }

    .metadata {
      font-size: 0.9rem;
      margin-bottom: 20px;
      border-bottom: 1px dashed #ccc;
      padding-bottom: 10px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 8px 0;
      text-align: left;
      font-size: 0.9rem;
    }

    .item-row td {
      border-bottom: 1px dotted #eee;
    }

    .total-row {
      font-size: 1.1rem;
      font-weight: bold;
      border-top: 2px solid #333;
    }

    .btn-back {
      display: block;
      text-align: center;
      margin-top: 30px;
      color: #2563eb;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>

<body>
  <div class="struk-container">
    <h1>Struk Pembelian</h1>

    <div class="metadata">
      <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
      <p><strong>Tanggal:</strong> <?php echo $order_date; ?></p>
      <p><strong>Pelanggan:</strong> <?php echo htmlspecialchars($order['user_name']); ?></p>
    </div>

    <table>
      <thead>
        <tr>
          <th>Item</th>
          <th>Qty</th>
          <th>Harga @</th>
          <th style="text-align: right;">Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($order_items as $item): ?>
          <tr class="item-row">
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td>₩ <?php echo number_format($item['price_at_order'], 0, ',', '.'); ?></td>
            <td style="text-align: right;">₩
              <?php echo number_format($item['quantity'] * $item['price_at_order'], 0, ',', '.'); ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr class="total-row">
          <td colspan="3">TOTAL AKHIR</td>
          <td style="text-align: right;">₩ <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
        </tr>
      </tbody>
    </table>

    <p style="text-align: center; margin-top: 30px; font-size: 0.9rem; color: #6b7280;">Terima kasih telah berbelanja di
      DolceVita Cafe!</p>

    <a href="dashboardP.php" class="btn-back">Kembali ke Home</a>
  </div>
</body>

</html>