<?php
session_start();

if (!isset($_SESSION['status']) || $_SESSION['status'] != "login" || $_SESSION['role'] != 'admin') {
  header("location:../login.php?pesan=akses_ditolak");
  exit();
}
$alert_message = '';
if (isset($_SESSION['alert_message'])) {
  $alert_message = $_SESSION['alert_message'];
  unset($_SESSION['alert_message']);
}

$admin_name = $_SESSION['username'] ?? 'admin';
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
      background-color: #11224E;
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
    }

    h1 {
      color: #11224E;
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
        <h1 class="text-4xl font-extrabold mb-4">Selamat Datang,
          <?php echo htmlspecialchars($admin_name); ?>!
        </h1>
        <p class="text-xl text-gray-600">Ini adalah website Admin, silakan mengelola sistem Anda disini.</p>
      </section>

      <?php if (!empty($alert_message)): ?>
        <div id="successAlert"
          style="position: fixed; top: 10px; right: 20px; z-index: 1000; background-color: #d1fae5; color: #065f46; padding: 15px 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid #10b981; transition: opacity 0.5s ease-out;">
          <?php echo $alert_message; ?>
        </div>
      <?php endif; ?>

    </main>
  </div>
  <script>
    const successAlert = document.getElementById('successAlert');

    if (successAlert) {
      setTimeout(() => {
        successAlert.style.opacity = '0';
      }, 4000);

      setTimeout(() => {
        successAlert.style.display = 'none';
      }, 4500);
    }

    // logout
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