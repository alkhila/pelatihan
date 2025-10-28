<?php
include '../config/config.php';
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
  header("location:../login.php?pesan=akses_ditolak");
  exit();
}

$current_admin_id = $_SESSION['id'] ?? 0;
$admin_name = $_SESSION['username'] ?? 'Admin';
$pesan = "";

if (isset($_GET['hapus'])) {
  $id = mysqli_real_escape_string($konek, $_GET['hapus']);

  $q_img = mysqli_query($konek, "SELECT photo FROM articles WHERE id='$id'");
  $data_img = mysqli_fetch_assoc($q_img);
  $path_gambar = $data_img['photo'] ?? '';

  if (!empty($path_gambar) && file_exists($path_gambar)) {
    unlink($path_gambar);
  }

  $hapus = mysqli_query($konek, "DELETE FROM articles WHERE id='$id'");

  if ($hapus) {
    header("location: artikel.php?pesan=Artikel berhasil dihapus.");
    exit();
  } else {
    $error_message = "Gagal menghapus data: " . mysqli_error($konek);
    header("location: artikel.php?pesan=" . urlencode($error_message));
    exit();
  }
}

$pesan_notifikasi = '';
$is_error = false;

if (isset($_SESSION['notifikasi_pesan'])) {
  $pesan_notifikasi = $_SESSION['notifikasi_pesan'];
  unset($_SESSION['notifikasi_pesan']);
} elseif (isset($_GET['pesan'])) {
  $pesan_notifikasi = urldecode($_GET['pesan']);
  if (
    strpos(strtolower($pesan_notifikasi), 'gagal') !== false ||
    strpos(strtolower($pesan_notifikasi), 'tidak bisa') !== false ||
    strpos(strtolower($pesan_notifikasi), 'error') !== false
  ) {
    $is_error = true;
  }
}

$query = "SELECT id, title, category, content, published_at FROM articles ORDER BY published_at DESC";
$data_artikel = mysqli_query($konek, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manajemen Artikel</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body {
      background-color: #f3f4f6;
      font-family: sans-serif;
      margin: 0;
    }

    .content-area-wrapper {
      max-width: 1200px;
      margin-left: auto;
      margin-right: auto;
      padding: 32px 48px;
    }

    .navbar {
      background-color: #11224E;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 24px;
      position: sticky;
      top: 0;
      z-index: 10;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .navbar-brand-wrapper {
      display: flex;
      align-items: center;
      gap: 32px;
    }

    .nav-link {
      padding: 8px 12px;
      border-radius: 6px;
      font-size: 14px;
      font-weight: 500;
      color: #d1d5db;
      text-decoration: none;
      transition: background-color 0.15s;
    }

    .nav-link.active {
      color: white;
      background-color: rgba(255, 255, 255, 0.1);
    }

    .page-title {
      font-size: 24px;
      font-weight: bold;
      color: #11224E;
      margin-bottom: 24px;
      padding-bottom: 8px;
      text-align: center;
      border-bottom: 2px solid #e5e7eb;
    }

    .alert-success,
    .alert-error {
      padding: 16px;
      margin-bottom: 16px;
      border-radius: 4px;
      opacity: 1;
      transition: opacity 0.5s ease-out;
    }

    .alert-success {
      background-color: #d1fae5;
      border-left: 4px solid #10b981;
      color: #065f46;
    }

    .alert-error {
      background-color: #fee2e2;
      border-left: 4px solid #ef4444;
      color: #b91c1c;
    }

    .btn-add {
      background-color: #11224E;
      color: white;
      font-weight: bold;
      padding: 8px 16px;
      border-radius: 8px;
      text-decoration: none;
      display: flex;
      align-items: center;
      box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
      transition: background-color 0.15s;
    }

    .btn-add:hover {
      background-color: #0c1a38;
    }

    .table-container {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      padding: 24px;
    }

    .table-wrapper {
      overflow-x: auto;
    }

    .data-table {
      min-width: 100%;
      border-collapse: collapse;
      border: 1px solid #e5e7eb;
    }

    .table-header {
      background-color: #f9fafb;
    }

    .table-header th {
      padding: 12px 16px;
      text-align: left;
      font-size: 12px;
      font-weight: 600;
      color: #4b5563;
      text-transform: uppercase;
      border-bottom: 1px solid #e5e7eb;
    }

    .table-body td {
      padding: 12px 16px;
      font-size: 14px;
      color: #1f2937;
      border-bottom: 1px solid #e5e7eb;
      white-space: nowrap;
    }

    .td-category {
      color: #11224E;
      font-weight: 600;
    }

    .action-link a.edit {
      color: #d97706;
      margin-right: 12px;
    }

    .action-link a.delete {
      color: #dc2626;
    }

    .material-icons {
      font-size: 20px;
      vertical-align: middle;
    }
  </style>
</head>

<body class="bg-gray-100">

  <nav class="navbar">
    <div class="navbar-brand-wrapper">
      <div style="display: flex; align-items: center; gap: 8px;">
        <img src="../img/logo.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%;" />
        <span style="font-weight: bold; font-size: 20px;">Dolhareubang</span>
      </div>
      <div style="display: flex; gap: 8px;">
        <a href="dashboard.php" class="nav-link">Home</a>
        <a href="artikel.php" class="nav-link active">Artikel</a>
        <a href="manajemen_user.php" class="nav-link">Manajemen User</a>
      </div>
    </div>
    <div style="display: flex; align-items: center; gap: 16px;">
      <span style="font-size: 14px; font-weight: 500; display: block;">Halo,
        <strong><?php echo htmlspecialchars($admin_name); ?></strong></span>
      <button id="logoutButton" style="background: none; border: none; color: white; cursor: pointer; padding: 0 8px;"
        title="Logout">
        <span class="material-icons" style="vertical-align: middle;">logout</span>
      </button>
    </div>
  </nav>

  <div class="content-area-wrapper">
    <h1 class="page-title">Manajemen Artikel</h1>
    <?php
    if (!empty($pesan_notifikasi)) {
      $alert_class = $is_error ? 'alert-error' : 'alert-success';
      echo '<div class="' . $alert_class . '" id="statusAlert">
                    <p>' . htmlspecialchars($pesan_notifikasi) . '</p>
                  </div>';
    }
    ?>

    <div style="margin-bottom: 24px; display: flex; justify-content: flex-end;">
      <a href="form_artikel.php" class="btn-add">
        <span class="material-icons">add_circle</span> Tambah Artikel
      </a>
    </div>

    <div class="table-container">
      <div class="table-wrapper">
        <table class="data-table">
          <thead class="table-header">
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Kategori</th>
              <th>Content Preview</th>
              <th>Published At</th>
              <th style="text-align: center;">Aksi</th>
            </tr>
          </thead>
          <tbody class="table-body">
            <?php
            if (mysqli_num_rows($data_artikel) > 0):
              while ($data = mysqli_fetch_array($data_artikel)) { ?>
                <tr>
                  <td><?php echo $data['id']; ?></td>
                  <td class="td-title"><?php echo $data['title']; ?></td>
                  <td class="td-category"><?php echo $data['category']; ?></td>
                  <td class="td-content"><?php echo substr($data['content'], 0, 70) . '...'; ?></td>
                  <td><?php echo date('d M Y H:i', strtotime($data['published_at'])); ?></td>

                  <td class="action-link">
                    <a href="edit_artikel.php?id=<?php echo $data['id']; ?>" class="edit">
                      <span class="material-icons">edit</span>
                    </a>
                    <a href="?hapus=<?php echo $data['id']; ?>"
                      onclick="return confirm('Yakin ingin menghapus artikel: <?php echo $data['title']; ?>?')"
                      class="delete">
                      <span class="material-icons">delete</span>
                    </a>
                  </td>
                </tr>
              <?php }
            else: ?>
              <tr>
                <td colspan="6" class="no-data">Belum ada artikel yang tersedia.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script>
    const alertElement = document.getElementById('statusAlert');

    if (alertElement) {
      setTimeout(() => {
        alertElement.style.opacity = '0';

        setTimeout(() => {
          alertElement.style.display = 'none';
        }, 500);

      }, 3000);
    }

    const logoutBtn = document.getElementById('logoutButton');

    if (logoutBtn) {
      logoutBtn.addEventListener('click', function (e) {
        const konfirmasi = confirm("Anda yakin ingin keluar (Logout)?");

        if (konfirmasi) {
          window.location.href = '../logout.php';
        }
      });
    }
  </script>
</body>

</html>