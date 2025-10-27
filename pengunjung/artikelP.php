<?php
// artikelP.php (Halaman Artikel Pengunjung)
include '../config/config.php'; // Ganti dengan path yang benar ke config.php Anda
session_start();

// Cek status login (untuk kondisional di HTML)
$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";

// PENTING: Ambil username dan role secara aman menggunakan null coalescing
// Jika session tidak ada/kosong, nilainya akan menjadi '' atau 'pengunjung'.
$username = $_SESSION['username'] ?? '';
$user_role = $_SESSION['role'] ?? 'pengunjung';

// --- LOGIKA REDIRECT ADMIN ---
// Jika user sudah login dan dia adalah ADMIN, redirect ke folder admin.
if ($is_logged_in && $user_role == 'admin') {
  header("location: ../admin/dashboard.php");
  exit();
}
// Tidak ada pengecekan wajib login di sini. Pengunjung Anonim boleh melihat sisa halaman.

// --- LOGIKA MENGAMBIL DATA ARTIKEL DARI DATABASE (TIDAK BERUBAH) ---
$query = "SELECT a.id, a.title, a.content, a.category, a.photo, a.published_at, u.name AS author_name 
          FROM articles a
          JOIN users u ON a.admin_id = u.id 
          ORDER BY a.published_at DESC";

$artikel_result = mysqli_query($konek, $query);
$articles = [];
if ($artikel_result) {
  while ($row = mysqli_fetch_assoc($artikel_result)) {
    $articles[] = $row;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog DolceVita Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    /* CSS DARI DASHBOARDP.PHP */
    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: sans-serif;
      scroll-behavior: smooth;
    }

    #sidebar {
      background-color: #1e3a8a;
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
      background-color: #1e3a8a;
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

    /* CSS KHUSUS ARTIKEL */
    body {
      background-color: #0d1117;
      padding: 0;
    }

    main {
      padding: 40px 20px;
    }

    /* Tambahkan padding di main content */
    .page-title {
      text-align: center;
      font-size: 3rem;
      font-weight: bold;
      color: white;
      margin-bottom: 50px;
    }

    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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
      transform: translateY(-5px);
    }

    .card-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-bottom: 1px solid #30363d;
    }

    .card-content {
      padding: 20px;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }

    .metadata {
      font-size: 0.8rem;
      color: #8b949e;
      margin-bottom: 10px;
      display: flex;
      justify-content: space-between;
    }

    .metadata span {
      padding: 4px 8px;
      border-radius: 4px;
      font-weight: bold;
    }

    .metadata .category {
      background-color: #384251;
      color: #58a6ff;
    }

    .card-title {
      font-size: 1.5rem;
      font-weight: 600;
      color: white;
      margin-bottom: 15px;
      line-height: 1.3;
    }

    .card-excerpt {
      font-size: 0.9rem;
      color: #8b949e;
      line-height: 1.6;
      margin-bottom: 20px;
      flex-grow: 1;
    }

    .card-author {
      display: flex;
      align-items: center;
      margin-top: auto;
      padding-top: 15px;
      border-top: 1px solid #30363d;
    }

    .author-avatar {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      object-fit: cover;
      margin-right: 10px;
    }

    .author-info strong {
      display: block;
      font-weight: 500;
      color: #c9d1d9;
      font-size: 0.95rem;
    }

    .author-info span {
      font-size: 0.8rem;
      color: #8b949e;
    }
  </style>
</head>

<body>

  <div id="sidebar" class="bg-blue-900 text-white w-64 h-screen flex flex-col fixed z-20">
    <div class="flex items-center justify-between p-4 border-b border-blue-800">
      <button id="toggleSidebar"
        style="display: flex; align-items: center; gap: 8px; border: none; background: none; color: inherit;">
        <img src="../img/logo.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%;" />
        <span id="logoText" style="font-weight: bold; font-size: 20px;">Dolhareubang</span>
      </button>
    </div>

    <nav class="flex-1 mt-4 space-y-2 pl-3">
      <a href="artikelP.php" class="flex items-center p-3 hover:bg-blue-800 rounded-l-full active"><span
          class="material-icons">article</span><span class="ml-3 sidebar-text">Artikel</span></a>
      <a href="event_galeri.php" class="flex items-center p-3 hover:bg-blue-800 rounded-l-full"><span
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

    <main class="flex-1 overflow-y-auto">
      <h1 class="page-title">Artikel Terbaru Dolhareubang Cafe</h1>
      <div class="card-grid">
        <?php if (empty($articles)): ?>
          <p style="text-align: center; grid-column: 1 / -1; color: #8b949e;">Belum ada artikel yang dipublikasikan.</p>
        <?php endif; ?>

        <?php foreach ($articles as $article):
          $full_content = strip_tags($article['content']);
          $excerpt = substr($full_content, 0, 150) . (strlen($full_content) > 150 ? '...' : '');
          $date = date('M d, Y', strtotime($article['published_at']));

          // Path Gambar: Asumsi uploads/artikel ada di admin/
          $image_path = '../admin/' . $article['photo'];
          ?>

          <a href="detail_artikelP.php?id=<?php echo $article['id']; ?>" style="text-decoration: none; color: inherit;">
            <div class="card">
              <img src="<?php echo $image_path ?: 'https://via.placeholder.com/600x400?text=DolceVita+Cafe'; ?>"
                alt="<?php echo htmlspecialchars($article['title']); ?>" class="card-image">

              <div class="card-content">
                <div class="metadata">
                  <span><?php echo $date; ?></span>
                  <span class="category"><?php echo htmlspecialchars($article['category']); ?></span>
                </div>

                <h3 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                <p class="card-excerpt"><?php echo htmlspecialchars($excerpt); ?></p>

                <div class="card-author">
                  <div class="author-info">
                    <strong><?php echo htmlspecialchars($article['author_name']); ?></strong>
                    <span>Penulis</span>
                  </div>
                </div>
              </div>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </main>
  </div>

  <script>
    // Ambil elemen untuk manipulasi
    const toggleSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const logoText = document.getElementById("logoText");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    const mainContentContainer = document.getElementById("main-content-container");

    // Tentukan nilai lebar (w-64 = 256px, w-20 = 80px)
    const lebarSidebarTerbuka = '256px';
    const lebarSidebarTertutup = '80px';

    // Atur margin awal main content agar tidak tertutup sidebar
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

    // 2. Logic Scroll Navigation (Link di navbar horizontal diarahkan ke dashboardP.php)
    // Karena ini artikelP.php, link scroll navbar horizontal harus diarahkan ke dashboardP.php
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        // Biarkan link yang menuju ke dashboardP.php/klien, dll. berfungsi normal
        // Tapi untuk halaman ini, kita nonaktifkan scroll lokal karena artikelP tidak punya section
        // Pengguna harus kembali ke dashboardP.php untuk scroll.
        // KODE INI TIDAK MEMBUTUHKAN SMOOTH SCROLL KARENA TIDAK ADA ANCHOR LOKAL

        // Hapus e.preventDefault(); di sini, biarkan browser mengarahkan ke dashboardP.php
      });
    });

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