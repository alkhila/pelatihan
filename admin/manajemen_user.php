<?php
include '../config/config.php';
session_start();

// --- PENGAMANAN KHUSUS ADMIN ---
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
  header("location:../login.php?pesan=akses_ditolak");
  exit(); // Hentikan script jika tidak valid
}

$current_admin_id = $_SESSION['id'];
$admin_name = $_SESSION['username'];
$pesan = "";

// Baris 16
// --- LOGIKA HAPUS USER ---
if (isset($_GET['hapus'])) {
  $id_hapus = mysqli_real_escape_string($konek, $_GET['hapus']);

  // Periksa: Pastikan admin TIDAK menghapus dirinya sendiri
  if ($id_hapus != $current_admin_id) {
    $hapus = mysqli_query($konek, "DELETE FROM users WHERE id='$id_hapus'");
    if ($hapus) {
      $pesan = "Pengguna berhasil dihapus.";
    } else {
      $pesan = "Gagal menghapus pengguna: " . mysqli_error($konek);
    }
  } else {
    $pesan = "Tidak bisa menghapus akun sendiri.";
  }
  header("location: manajemen_user.php?pesan=" . urlencode($pesan));
  exit();
}

// --- LOGIKA UBAH ROLE ---
if (isset($_POST['ubah_role'])) {
  $id_ubah = mysqli_real_escape_string($konek, $_POST['id_user']);
  $role_baru = mysqli_real_escape_string($konek, $_POST['role_baru']);

  // Periksa: Pastikan admin TIDAK mengubah role akunnya sendiri
  if ($id_ubah != $current_admin_id) {
    $update = mysqli_query($konek, "UPDATE users SET role='$role_baru' WHERE id='$id_ubah'");
    if ($update) {
      $pesan = "Role pengguna berhasil diubah menjadi " . $role_baru;
    } else {
      $pesan = "Gagal mengubah role.";
    }
  } else {
    $pesan = "Tidak bisa mengubah role akun sendiri.";
  }
  header("location: manajemen_user.php?pesan=" . urlencode($pesan));
  exit();
}

// ... (Lanjutan kode READ USER di bawah ini) ...

// --- LOGIKA READ USER ---
$query = "SELECT id, name, email, role FROM users ORDER BY role DESC, name";
$data_users = mysqli_query($konek, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Manajemen Pengguna</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <style>
    .navbar {
      background-color: #1e3a8a;
      /* blue-900 */
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 24px;
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

    body {
      background-color: #f3f4f6;
      font-family: sans-serif;
      margin: 0;
      /* Hapus margin default browser */
    }

    .content-area-wrapper {
      max-width: 1200px;
      margin-left: auto;
      /* Pusatkan konten */
      margin-right: auto;
      padding: 32px 48px;
    }

    .page-title {
      font-size: 24px;
      font-weight: bold;
      color: #1f2937;
      margin-bottom: 24px;
      padding-bottom: 8px;
      /* Pusatkan judul di dalam content-area-wrapper */
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    th,
    td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background-color: #e5e7eb;
    }

    .btn-action {
      background-color: #2563eb;
      color: white;
      padding: 6px 12px;
      border-radius: 4px;
      text-decoration: none;
      margin-right: 5px;
      border: none;
    }

    .btn-delete {
      background-color: #dc2626;
    }

    .btn-delete:hover {
      background-color: #b91c1c;
    }

    .role-form {
      display: flex;
      gap: 5px;
      align-items: center;
    }

    .user-self {
      background-color: #fffacd;
      font-weight: bold;
    }

    /* Menandai user yang sedang login */
  </style>
</head>

<body class="bg-gray-100 flex">
  <nav class="navbar">
    <div class="navbar-brand-wrapper">
      <div style="display: flex; align-items: center; gap: 8px;">
        <img src="../img/logo.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%;" />
        <span style="font-weight: bold; font-size: 20px;">Dolhareubang</span>
      </div>
      <div style="display: flex; gap: 8px;">
        <a href="dashboard.php" class="nav-link">Home</a>
        <a href="artikel.php" class="nav-link">Artikel</a>
        <a href="manajemen_user.php" class="nav-link active">Manajemen User</a>
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
    <h1 class="page-title">Manajemen Pengguna</h1>

    <?php
    if (isset($_GET['pesan'])) {
      echo "<p style='color: green; font-weight: bold; margin-top: 15px;'>" . htmlspecialchars($_GET['pesan']) . "</p>";
    }
    ?>

    <table>
      <thead>
        <tr>
          <th>Nama</th>
          <th>Email</th>
          <th>Role Saat Ini</th>
          <th>Ubah Role</th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($user = mysqli_fetch_assoc($data_users)):
          $is_self = ($user['id'] == $current_admin_id);
          ?>
          <tr class="<?php echo $is_self ? 'user-self' : ''; ?>">
            <td><?php echo htmlspecialchars($user['name']) . ($is_self ? ' (Anda)' : ''); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
            <td>
              <?php if (!$is_self): ?>
                <form method="POST" action="manajemen_user.php" class="role-form">
                  <input type="hidden" name="id_user" value="<?php echo $user['id']; ?>">
                  <select name="role_baru" style="padding: 6px; border-radius: 4px;">
                    <option value="pengunjung" <?php echo $user['role'] == 'pengunjung' ? 'selected' : ''; ?>>Pengunjung
                    </option>
                    <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                  </select>
                  <button type="submit" name="ubah_role" class="btn-action" style="background-color: #10b981;">Ubah</button>
                </form>
              <?php else: ?>
                <span style="color: #6b7280; font-style: italic;">Tidak dapat diubah</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if (!$is_self): ?>
                <a href="?hapus=<?php echo $user['id']; ?>"
                  onclick="return confirm('Yakin hapus pengguna <?php echo htmlspecialchars($user['name']); ?>?')"
                  class="btn-action btn-delete">
                  Hapus
                </a>
              <?php else: ?>
                <span style="color: #dc2626; font-style: italic;">—</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>

  <script>
    // Logic Logout Confirmation
    const logoutBtn = document.getElementById('logoutButton');

    if (logoutBtn) {
      logoutBtn.addEventListener('click', function (e) {
        const konfirmasi = confirm("Anda yakin ingin keluar (Logout)?");

        if (konfirmasi) {
          // Path ke logout.php harus keluar dari folder admin
          window.location.href = '../logout.php';
        }
      });
    }
  </script>
</body>

</html>