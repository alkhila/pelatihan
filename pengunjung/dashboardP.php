<?php
session_start();

$is_logged_in = isset($_SESSION['status']) && $_SESSION['status'] == "login";

$username = $_SESSION['username'] ?? '';

$user_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'pengunjung';

if ($is_logged_in && $user_role == 'admin') {
  header("location: ../admin/dashboard.php");
  exit();
}
$alert_message = '';
if (isset($_SESSION['alert_message'])) {
  $alert_message = $_SESSION['alert_message'];
  unset($_SESSION['alert_message']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dolhareubang Cafe</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <style>
    html,
    body {
      margin: 0;
      padding: 0;
      height: 100%;
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

    .nav-profile-img {
      width: 32px;
      height: 32px;
      border-radius: 50%;
    }

    .navbar-brand-wrapper {
      display: flex;
      align-items: center;
      gap: 32px;
    }

    .h-screen {
      height: 100vh;
    }

    .bg-cover {
      background-size: cover;
    }

    .bg-center {
      background-position: center;
    }

    .text-6xl {
      font-size: 3rem;
    }
  </style>
</head>

<body>

  <div id="sidebar" class="bg-navy-900 text-white w-64 h-screen flex flex-col fixed z-20">
    <div class="flex items-center justify-between p-4">
      <button id="toggleSidebar"
        style="display: flex; align-items: center; gap: 8px; border: none; background: none; color: inherit;">
        <img src="../img/logo.png" alt="Logo" style="width: 32px; height: 32px; border-radius: 50%;" />
        <span id="logoText" style="font-weight: bold; font-size: 20px;">Dolhareubang</span>
      </button>
    </div>

    <nav class="flex-1 mt-4 space-y-2 pl-3">
      <a href="artikelP.php" class="flex items-center p-3 hover:bg-blue-800 rounded-l-full">
        <span class="material-icons">article</span>
        <span class="ml-3 sidebar-text">Artikel</span>
      </a>
      <a href="event_galeri.php" class="flex items-center p-3 hover:bg-blue-800 rounded-l-full">
        <span class="material-icons">photo_library</span>
        <span class="ml-3 sidebar-text">Event Gallery</span>
      </a>
    </nav>
  </div>

  <div class="flex-1 flex flex-col min-h-screen" id="main-content-container">
    <nav class="navbar">
      <div style="display: flex; gap: 8px;">
        <a href="#home" class="nav-link">Home</a>
        <a href="#visi-misi" class="nav-link">Visi Misi</a>
        <a href="#produk-kami" class="nav-link">Produk Kami</a>
        <a href="#kontak" class="nav-link">Kontak</a>
        <a href="#about-us" class="nav-link">About Us</a>
        <a href="#klien" class="nav-link">Klien</a>
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
      <section id="home" class="h-screen bg-cover bg-center flex flex-col items-center justify-center text-white p-10"
        style="background-image: url('../img/background.jpg'); background-repeat: no-repeat; background-size: cover;">
        <div class="text-center bg-black bg-opacity-60 p-12 rounded-xl backdrop-blur-sm">
          <h2 class="text-6xl font-extrabold tracking-wider">Dolhareubang Cafe, Jeju</h2>
          <p class="mt-4 text-2xl font-light italic">"Kopi terbaik di bawah tatapan sang Kakek Batu."</p>
          <a href="#produk-kami"
            class="mt-8 inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-full hover:bg-blue-700 transition duration-300">
            Cicipi Rasa Khas Jeju
          </a>
        </div>
      </section>

      <section id="visi-misi" class="h-screen p-16 bg-gray-100 flex items-center justify-center">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12">
          <div>
            <h2 class="text-4xl font-bold text-blue-900 mb-6 border-b-2 border-orange-500 pb-2">Visi Kami 🌟</h2>
            <p class="text-xl leading-relaxed">
              Menjadi tujuan utama penikmat kopi di Jeju yang menggabungkan cita rasa global dengan keunikan budaya
              dan
              bahan baku lokal Pulau Tiga Harta Karun.
            </p>
          </div>
          <div>
            <h2 class="text-4xl font-bold text-blue-900 mb-6 border-b-2 border-orange-500 pb-2">Misi Kami 🎯</h2>
            <ul class="list-disc list-inside text-xl space-y-3 pl-4">
              <li>Menyajikan menu dengan bahan baku hasil bumi Jeju (Jeruk Hallabong, Teh Hijau Udo).</li>
              <li>Menciptakan ruang yang menghormati tradisi dan keramahan khas Korea.</li>
              <li>Menjadi pusat informasi wisata lokal di sekitar *cafe*.</li>
              <li>Mempromosikan kopi yang ramah lingkungan dan etis.</li>
            </ul>
          </div>
        </div>
      </section>

      <section id="produk-kami" class="h-screen p-16 bg-white">
        <h2
          class="text-5xl font-bold text-center mb-16 text-blue-900 border-b-4 border-orange-500 inline-block mx-auto pb-2">
          Signature Jeju: Produk Unggulan 🍊
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 max-w-6xl mx-auto">
          <div class="bg-gray-50 p-8 rounded-xl shadow-lg border-t-4 border-blue-900">
            <h3 class="text-3xl font-bold mb-6 text-blue-900">Spesial Kopi</h3>
            <ul class="space-y-4">
              <li class="flex justify-between border-b pb-2"><span>Hallabong Cold Brew</span><span
                  class="font-semibold text-orange-600">₩ 7.500</span></li>
              <li class="flex justify-between border-b pb-2"><span>Udo Peanut Latte</span><span
                  class="font-semibold text-orange-600">₩ 8.000</span></li>
              <li class="flex justify-between border-b pb-2"><span>Jeju Green Tea Espresso</span><span
                  class="font-semibold text-orange-600">₩ 7.000</span></li>
            </ul>
          </div>
          <div class="bg-gray-50 p-8 rounded-xl shadow-lg border-t-4 border-orange-500">
            <h3 class="text-3xl font-bold mb-6 text-blue-900">Minuman Lokal</h3>
            <ul class="space-y-4">
              <li class="flex justify-between border-b pb-2"><span>Fresh Hallabong Ade</span><span
                  class="font-semibold text-orange-600">₩ 6.500</span></li>
              <li class="flex justify-between border-b pb-2"><span>Pure Jeju Matcha</span><span
                  class="font-semibold text-orange-600">₩ 7.000</span></li>
              <li class="flex justify-between border-b pb-2"><span>Omija Berry Tea (Hot)</span><span
                  class="font-semibold text-orange-600">₩ 6.000</span></li>
            </ul>
          </div>
          <div class="bg-gray-50 p-8 rounded-xl shadow-lg border-t-4 border-blue-900">
            <h3 class="text-3xl font-bold mb-6 text-blue-900">Wisata Lokal Terdekat</h3>
            <ul class="space-y-4">
              <li class="flex justify-between border-b pb-2"><span>Seongsan Ilchulbong</span><span
                  class="font-semibold text-blue-600">30 Menit</span></li>
              <li class="flex justify-between border-b pb-2"><span>Gimnyeong Maze Park</span><span
                  class="font-semibold text-blue-600">15 Menit</span></li>
              <li class="flex justify-between border-b pb-2"><span>Manjanggul Lava Tube</span><span
                  class="font-semibold text-blue-600">10 Menit</span></li>
            </ul>
          </div>
        </div>
        <br> <br>
        \
      </section>

      <section id="kontak" class="h-screen p-16 bg-gray-100">
        <h2
          class="text-5xl font-bold text-center mb-16 text-blue-900 border-b-4 border-orange-500 inline-block mx-auto pb-2">
          Lokasi Kami di Pulau Jeju ⛰️</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-7xl mx-auto">
          <div class="space-y-8 p-6 bg-white rounded-xl shadow-lg">
            <h3 class="text-3xl font-semibold text-blue-900">Hubungi Kami</h3>
            <p class="text-xl">📍 **Alamat:** 12-4 Dolhareubang-ro, Gujwa-eup, Kota Jeju, Jeju-do, Korea Selatan.</p>
            <p class="text-xl">📞 **Telepon:** <span class="font-bold text-orange-600">+82-64-1234-5678</span></p>
            <p class="text-xl">📧 **Email:** <span class="font-bold text-orange-600">hello@dolhareubang.kr</span></p>
            <h3 class="text-3xl font-semibold text-blue-900 mt-8">Jam Operasional</h3>
            <ul class="text-xl space-y-2">
              <li>Setiap Hari: **09.00 - 21.00 KST**</li>
              <li>Tutup setiap hari Selasa pertama setiap bulan.</li>
            </ul>
          </div>
          <div class="shadow-xl rounded-xl overflow-hidden h-96">
            <iframe src="https://maps.google.com/maps?q=Manjanggul+Lava+Tube&t=&z=13&ie=UTF8&iwloc=&output=embed"
              width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
          </div>
        </div>
      </section>

      <section id="about-us" class="h-screen p-16 bg-white flex items-center">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 max-w-7xl mx-auto">
          <div class="lg:col-span-1">
            <img src="../img/logo.png" alt="Patung Dolhareubang" class="rounded-xl shadow-lg w-full h-auto">
          </div>
          <div class="lg:col-span-2 space-y-6">
            <h2 class="text-5xl font-bold text-blue-900 mb-6">Kisah Kami & Jeju</h2>
            <p class="text-xl leading-relaxed">
              Dolhareubang Cafe didirikan sebagai penghormatan kepada patung batu legendaris Jeju, **Dolhareubang**
              (*Kakek Batu*), yang melambangkan perlindungan dan kesuburan. Kami membawa filosofi kehangatan,
              keramahan,
              dan perlindungan tersebut ke dalam setiap layanan kami.
            </p>
            <p class="text-xl leading-relaxed font-semibold text-orange-600">
              Kami adalah persinggahan sempurna setelah Anda menjelajahi keindahan UNESCO Global Geopark seperti Gua
              Manjanggul. Mampirlah untuk secangkir kopi yang akan mengisi kembali energi Anda!
            </p>
          </div>
        </div>
      </section>

      <section id="klien" class="h-screen p-16 bg-gray-50">
        <h2
          class="text-5xl font-bold text-center mb-16 text-blue-900 border-b-4 border-orange-500 inline-block mx-auto pb-2">
          Mitra & Klien Kami 🤝
        </h2>
        <p class="text-center max-w-4xl mx-auto text-xl text-gray-700 mb-12">
          Kami bangga bekerja sama dengan berbagai entitas di Pulau Jeju untuk mempromosikan pariwisata dan kualitas
          lokal.
        </p>

        <div
          class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-10 max-w-6xl mx-auto items-center justify-items-center">
          <div class="p-4 bg-white rounded-lg shadow-md w-full h-full flex items-center justify-center">
            <img src="../img/jejuair.jpg" alt="Jeju Air" style="max-width: 100%; height: auto;">
          </div>
          <div class="p-4 bg-white rounded-lg shadow-md w-full h-full flex items-center justify-center">
            <img src="../img/jejutravel.jpg" alt="Jeju Travel" style="max-width: 100%; height: auto;">
          </div>
          <div class="p-4 bg-white rounded-lg shadow-md w-full h-full flex items-center justify-center">
            <img src="../img/jejuuniv.png" alt="Jeju University" style="max-width: 100%; height: auto;">
          </div>
          <div class="p-4 bg-white rounded-lg shadow-md w-full h-full flex items-center justify-center">
            <img src="../img/hallabongfarm.jpg" alt="Hallabong Farm" style="max-width: 100%; height: auto;">
          </div>
          <div class="p-4 bg-white rounded-lg shadow-md w-full h-full flex items-center justify-center">
            <img src="../img/jejubnb.jpg" alt="Jeju BNB" style="max-width: 100%; height: auto;">
          </div>
        </div>
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
    const toggleSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const logoText = document.getElementById("logoText");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");
    const mainContentContainer = document.getElementById("main-content-container");

    const lebarSidebarTerbuka = '256px';
    const lebarSidebarTertutup = '80px';

    mainContentContainer.style.marginLeft = lebarSidebarTerbuka;
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

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        const targetElement = document.querySelector(targetId);

        if (targetElement) {
          targetElement.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

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

    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
      setTimeout(() => {
        successAlert.style.opacity = '0';
      }, 4000);

      setTimeout(() => {
        successAlert.style.display = 'none';
      }, 4500);
    }
  </script>
</body>

</html>