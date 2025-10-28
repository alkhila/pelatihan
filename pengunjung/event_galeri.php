<?php
session_start();

$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";

$username = $_SESSION['username'] ?? '';

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'pengunjung';

if ($is_logged_in && $user_role == 'admin') {
  header("location: ../admin/dashboard.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event Gallery | Dolhareubang Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: sans-serif;
      scroll-behavior: smooth;
    }

    #sidebar {
      background-color: #11224E;
      color: white;
      height: 100vh;
      min-height: 100vh;
      width: 256px;
      position: fixed;
      z-index: 20;
      transition: width 0.3s;
      display: flex;
      flex-direction: column;
      top: 0;
    }

    #main-content-container {
      margin-left: 256px;
      transition: margin-left 0.3s;
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    .navbar {
      background-color: #11224E;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 10;
      box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
      width: 100%;
      padding: 12px 24px;
      margin: 0;
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

    .navbar-brand-wrapper {
      display: flex;
      align-items: center;
      gap: 32px;
    }

    .page-title {
      text-align: center;
      font-size: 3rem;
      font-weight: bold;
      color: #11224E;
      margin-bottom: 50px;
    }

    main {
      background-color: white;
      padding: 40px 20px;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      max-width: 1400px;
      margin: 0 auto;
    }

    .card {
      background-color: #161b22;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
      display: flex;
      flex-direction: column;
      transition: transform 0.3s ease;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
    }

    .card-image {
      width: 100%;
      height: 300px;
      object-fit: cover;
      border-bottom: 1px solid #30363d;
    }

    .card-content {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .card-metadata {
      font-size: 0.9rem;
      color: #8b949e;
      margin-bottom: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .card-title {
      font-size: 1.6rem;
      font-weight: 700;
      color: white;
      margin-top: 0;
      margin-bottom: 5px;
      line-height: 1.3;
    }

    .card-caption {
      font-size: 0.9rem;
      color: #8b949e;
      margin-bottom: 10px;
      line-height: 1.4;
    }

    .card-footer {
      border-top: 1px solid #30363d;
      padding-top: 15px;
      font-size: 0.9rem;
      color: #58a6ff;
      font-weight: 500;
    }
  </style>
</head>

<body class="bg-gray-100 flex">

  <div id="sidebar" class="bg-blue-900 text-white w-64 h-screen flex flex-col fixed z-20">
    <div class="flex items-center justify-between p-4">
      <button id="toggleSidebar"
        style="display: flex; align-items: center; gap: 8px; border: none; background: none; color: inherit;">
        <img src="../img/logo.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%;" />
        <span id="logoText" style="font-weight: bold; font-size: 20px;">Dolhareubang</span>
      </button>
    </div>

    <nav class="flex-1 mt-4 space-y-2 pl-3">

      <a href="artikelP.php" class="flex items-center p-3 hover:bg-blue-800 rounded-l-full"><span
          class="material-icons">article</span><span class="ml-3 sidebar-text">Artikel</span></a>
      <a href="event_gallery.php" class="flex items-center p-3 active hover:bg-blue-800 rounded-l-full"><span
          class="material-icons">photo_library</span><span class="ml-3 sidebar-text">Event Gallery</span></a>

    </nav>
  </div>

  <div class="flex-1 flex flex-col min-h-screen" id="main-content-container">

    <nav class="navbar">
      <div style="display: flex; gap: 8px;">
        <a href="dashboardP.php#home" class="nav-link">Home</a>
        <a href="dashboardP.php#visi-misi" class="nav-link">Visi Misi</a>
        <a href="dashboardP.php#produk-kami" class="nav-link">Produk Kami</a>
        <a href="dashboardP.php#kontak" class="nav-link">Kontak</a>
        <a href="dashboardP.php#about-us" class="nav-link">About Us</a>
        <a href="dashboardP.php#klien" class="nav-link">Klien</a>
      </div>

      <div class="flex items-center space-x-4">
        <?php if ($is_logged_in): ?>
          <span style="font-size: 14px; font-weight: 500;">Halo,
            <strong><?php echo htmlspecialchars($username); ?></strong></span>
          <button id="logoutButtonNavbar"
            style="background: none; border: none; color: white; cursor: pointer; padding: 0 8px;" title="Logout">
            <span class="material-icons" style="vertical-align: middle;">logout</span>
          </button>
        <?php else: ?>
          <a href="../login.php" title="Login Admin"
            style="display: flex; align-items: center; gap: 4px; color: white; text-decoration: none; transition: color 0.15s; font-size: 14px; font-weight: 500;">
            <i class="fas fa-sign-in-alt"></i>
            <span>Login</span>
          </a>
        <?php endif; ?>
      </div>
    </nav>

    <main class="flex-1 overflow-y-scroll">
      <h1 class="page-title">Galeri Momen DolceVita Cafe Jeju</h1>

      <div class="card-grid">

        <div class="card">
          <img src="../img/event.jpg" alt="Event Coffee & Culture" class="card-image">
          <div class="card-content">
            <div class="card-metadata">
              <span><i class="fas fa-calendar-alt"></i> 10 Jan 2026</span>
              <span><i class="fas fa-map-marker-alt"></i> Gujwa-eup, Jeju</span>
            </div>
            <h3 class="card-title">Jeju Coffee & Culture Day</h3>
            <p class="card-caption">
              Merayakan biji kopi lokal terbaru dari petani Jeju dan sesi *sharing* tentang kerajinan tangan tradisional
              Korea.
            </p>
          </div>
        </div>

        <div class="card">
          <img src="../img/event.jpg" alt="Hallabong Citrus Festival" class="card-image">
          <div class="card-content">
            <div class="card-metadata">
              <span><i class="fas fa-calendar-alt"></i> 25 Feb 2026</span>
              <span><i class="fas fa-users"></i> 45 Peserta</span>
            </div>
            <h3 class="card-title">Hallabong Citrus Tasting & Workshop</h3>
            <p class="card-caption">
              Acara *tasting* menu spesial berbasis jeruk Hallabong yang segar, dari *ade* hingga *dessert* musiman.
            </p>
          </div>
        </div>

        <div class="card">
          <img src="../img/event.jpg" alt="Barista Showcase Latte Art" class="card-image">
          <div class="card-content">
            <div class="card-metadata">
              <span><i class="fas fa-calendar-alt"></i> 12 Mar 2026</span>
              <span><i class="fas fa-star"></i> Barista Boboboy</span>
            </div>
            <h3 class="card-title">Latte Art Showcase: Spring Bloom Edition</h3>
            <p class="card-caption">
              Tunjukkan keahlian *latte art* kami dengan tema musim semi Jeju. Hadir dan vote kreasi favorit Anda!
            </p>
          </div>
        </div>

        <div class="card">
          <img src="../img/event.jpg" alt="Event 4" class="card-image">
          <div class="card-content">
            <div class="card-metadata">
              <span><i class="fas fa-calendar-alt"></i> 20 Apr 2026</span>
              <span><i class="fas fa-map-marker-alt"></i> Cafe Terrace</span>
            </div>
            <h3 class="card-title">Acoustic Night: Jeju Sunset</h3>
            <p class="card-caption">
              Nikmati musik akustik lokal sambil menyaksikan matahari terbenam di balik patung Dolhareubang.
            </p>
          </div>
        </div>

        <div class="card">
          <img src="../img/event.jpg" alt="Event 5" class="card-image">
          <div class="card-content">
            <div class="card-metadata">
              <span><i class="fas fa-calendar-alt"></i> 05 Mei 2026</span>
              <span><i class="fas fa-users"></i> Keluarga</span>
            </div>
            <h3 class="card-title">Kids Day Out: Latte Class</h3>
            <p class="card-caption">
              Kelas membuat *steamed milk* dan cokelat panas untuk anak-anak, didampingi orang tua.
            </p>
          </div>
        </div>

        <div class="card">
          <img src="../img/event.jpg" alt="Event 6" class="card-image">
          <div class="card-content">
            <div class="card-metadata">
              <span><i class="fas fa-calendar-alt"></i> 01 Jun 2026</span>
              <span><i class="fas fa-star"></i> Menu Baru</span>
            </div>
            <h3 class="card-title">Summer Menu Launch Party</h3>
            <p class="card-caption">
              Peluncuran menu musim panas, termasuk *Hallasan Snow Shave Ice* dan *Iced Matcha Mint*.
            </p>
          </div>
        </div>

      </div>

    </main>
  </div>

  <script>
    const toggleSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const logoText = document.getElementById("logoText");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    const mainContentContainer = document.getElementById("main-content-container");

    const lebarSidebarTerbuka = '256px';
    const lebarSidebarTertutup = '80px';

    mainContentContainer.style.marginLeft = lebarSidebarTerbuka;

    // 1. Logic Toggle Sidebar (Diikat ke tombol di sidebar)
    if (toggleSidebar) {
      toggleSidebar.addEventListener("click", () => {

        if (sidebar.style.width === lebarSidebarTerbuka || sidebar.style.width === '') {
          sidebar.style.width = lebarSidebarTertutup;
          mainContentContainer.style.marginLeft = lebarSidebarTertutup;
        } else {
          sidebar.style.width = lebarSidebarTerbuka;
          mainContentContainer.style.marginLeft = lebarSidebarTerbuka;
        }

        logoText.classList.toggle("hidden");
        sidebarTexts.forEach((text) => text.classList.toggle("hidden"));
      });
    }

    // 3. Logic Logout Confirmation (untuk tombol di Sidebar dan Navbar)
    const handleLogout = () => {
      const konfirmasi = confirm("Anda yakin ingin keluar (Logout)?");
      if (konfirmasi) {
        window.location.href = '../logout.php';
      }
    };

    const logoutBtnNavbar = document.getElementById('logoutButtonNavbar');
    const logoutBtnSidebar = document.getElementById('logoutButtonSidebar');

    if (logoutBtnNavbar) {
      logoutBtnNavbar.addEventListener('click', handleLogout);
    }
    if (logoutBtnSidebar) {
      logoutBtnSidebar.addEventListener('click', handleLogout);
    }
  </script>
</body>

</html>