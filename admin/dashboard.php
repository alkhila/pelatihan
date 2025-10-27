<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
  header("location:../login.php?pesan=akses_ditolak");
  exit(); // Hentikan script jika tidak valid
}

// Ambil username (nama admin) dari session
$admin_name = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin | Dolhareubang</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
  </style>
</head>

<body class="bg-gray-100 flex">
  <div class="flex-1 flex flex-col h-screen">
    <nav class="navbar">
      <div class="navbar-brand-wrapper">
        <div style="display: flex; align-items: center; gap: 8px;">
          <img src="../img/logo.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%;" />
          <span style="font-weight: bold; font-size: 20px;">Dolhareubang</span>
        </div>
        <div style="display: flex; gap: 8px;">
          <a href="dashboard.php" class="nav-link active">Home</a>
          <a href="artikel.php" class="nav-link">Artikel</a>
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

    <main class="p-6 flex-1 overflow-y-auto">

      <section id="home" class="h-screen flex flex-col items-start justify-start p-10 bg-white shadow-lg rounded-xl">
        <h1 class="text-4xl font-extrabold text-blue-900 mb-4">Selamat Datang,
          <?php echo htmlspecialchars($admin_name); ?>!
        </h1>
        <p class="text-xl text-gray-600">Ini adalah halaman *home* khusus untuk Anda. Anda bisa mulai mengelola konten
          dari sini.</p>
      </section>

    </main>
  </div>
  <script>
    // logout
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