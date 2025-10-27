<?php
include '../config/config.php';
session_start();

// Pemeriksaan Sesi
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
  header("location:../login.php?pesan=akses_ditolak");
  exit(); // Hentikan script jika tidak valid
}

// Ambil nama user
$admin_name = $_SESSION['username'];

// LOGIKA HAPUS
if (isset($_GET['hapus'])) {
  // Tetap amankan variabel ID dari SQL Injection (sangat penting!)
  $id = mysqli_real_escape_string($konek, $_GET['hapus']);

  // Hapus gambar unggulan dari server terlebih dahulu (jika ada)
  $q_img = mysqli_query($konek, "SELECT photo FROM articles WHERE id='$id'");
  $data_img = mysqli_fetch_assoc($q_img);
  $path_gambar = $data_img['photo'] ?? '';

  if (!empty($path_gambar) && file_exists($path_gambar)) {
    unlink($path_gambar); // Hapus file gambar
  }

  $hapus = mysqli_query($konek, "DELETE FROM articles WHERE id='$id'");

  if ($hapus) {
    header("location: artikel.php?pesan=Artikel berhasil dihapus.");
    exit();
  } else {
    die("Gagal menghapus data: " . mysqli_error($konek));
  }
}

// LOGIKA NOTIFIKASI DARI SESSION
$pesan_notifikasi = '';
if (isset($_SESSION['notifikasi_pesan'])) {
  $pesan_notifikasi = $_SESSION['notifikasi_pesan'];
  unset($_SESSION['notifikasi_pesan']);
}
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
    /* BODY */
    body {
      background-color: #f3f4f6;
      font-family: sans-serif;
      margin: 0;
      /* Hapus margin default browser */
    }

    /* UTILITY */
    /* Wrapper baru untuk konten agar terpusat */
    .content-area-wrapper {
      max-width: 1200px;
      margin-left: auto;
      /* Pusatkan konten */
      margin-right: auto;
      padding: 32px 48px;
    }

    /* NAVBAR */
    .navbar {
      background-color: #1e3a8a;
      /* blue-900 */
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 22px 24px;
      position: sticky;
      top: 0;
      z-index: 10;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .navbar-brand-wrapper {
      display: flex;
      align-items: center;
      gap: 32px;
      /* Jarak antara logo dan menu */
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

    .nav-profile-img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }

    /* HEADER & ALERT */
    .page-title {
      font-size: 24px;
      font-weight: bold;
      color: #1f2937;
      margin-bottom: 24px;
      padding-bottom: 8px;
      /* Pusatkan judul di dalam content-area-wrapper */
      text-align: center;
    }

    .alert-success {
      background-color: #d1fae5;
      border-left: 4px solid #10b981;
      color: #065f46;
      padding: 16px;
      margin-bottom: 16px;
      border-radius: 4px;
      opacity: 1;
      transition: opacity 0.5s ease-out;
    }

    /* BUTTON */
    .btn-add {
      background-color: #2563eb;
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

    .btn-add .material-icons {
      margin-right: 8px;
      font-size: 20px;
    }

    /* TABLE CONTAINER (Card) */
    .table-container {
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
      padding: 24px;
      /* Padding di dalam card */
    }

    .table-wrapper {
      overflow-x: auto;
    }

    /* TABLE STYLES */
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

    /* ... (Sisa styling tabel tetap sama) ... */
    .td-title {
      font-weight: 500;
      max-width: 300px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .td-category {
      color: #2563eb;
      font-weight: 600;
    }

    .td-content {
      color: #6b7280;
      max-width: 400px;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
    }

    .action-link {
      text-align: center;
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

    .no-data {
      padding: 40px;
      text-align: center;
      color: #6b7280;
      font-size: 18px;
    }
  </style>
</head>

<body>

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
    // Tampilkan pesan jika ada
    if (!empty($pesan_notifikasi)) {
      echo '<div class="alert-success" id="successAlert">
                    <p>' . $pesan_notifikasi . '</p>
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
            $tampil = mysqli_query($konek, "SELECT id, title, category, content, published_at FROM articles ORDER BY published_at DESC");

            if (mysqli_num_rows($tampil) > 0):
              while ($data = mysqli_fetch_array($tampil)) { ?>
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
    const alertElement = document.getElementById('successAlert');

    if (alertElement) {
      setTimeout(() => {
        alertElement.style.opacity = '0';

        setTimeout(() => {
          alertElement.style.display = 'none';
        }, 500);

      }, 3000); // 3 detik
    }

    // --- SCRIPT LOGOUT KONFIRMASI ---
    const logoutBtn = document.getElementById('logoutButton');

    if (logoutBtn) {
      logoutBtn.addEventListener('click', function (e) {
        // Tampilkan popup konfirmasi
        const konfirmasi = confirm("Anda yakin ingin keluar (Logout)?");

        if (konfirmasi) {
          // Jika user menekan 'OK', arahkan ke logout.php
          // Sesuaikan path ke logout.php jika berbeda
          window.location.href = '../logout.php';
        }
      });
    }
  </script>
</body>

</html>