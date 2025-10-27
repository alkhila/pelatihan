<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard Flowbite</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-gray-100 flex">
  <!-- Sidebar -->
  <div id="sidebar" class="bg-blue-900 text-white w-64 min-h-screen flex flex-col">
    <div class="flex items-center justify-between p-4 border-b border-blue-800">
      <button id="toggleSidebar" class="flex items-center space-x-2">
        <img src="../img/logo.png" alt="Logo" class="w-8 h-8" />
        <span id="logoText" class="font-bold text-xl">Dolhareubang</span>
      </button>
    </div>

    <!-- Menu -->
    <nav class="flex-1 mt-4 space-y-2 pl-3">
      <a href="#" class="flex items-center p-3 hover:bg-blue-800">
        <span class="material-icons">article</span>
        <span class="ml-3 sidebar-text">Artikel</span>
      </a>
      <a href="#" class="flex items-center p-3 hover:bg-blue-800">
        <span class="material-icons">photo_library</span>
        <span class="ml-3 sidebar-text">Event Gallery</span>
      </a>
      <a href="#" class="flex items-center p-3 hover:bg-blue-800">
        <span class="material-icons">handshake</span>
        <span class="ml-3 sidebar-text">Klien</span>
      </a>
      <a href="#" class="flex items-center p-3 hover:bg-blue-800">
        <span class="material-icons">login</span>
        <span class="ml-3 sidebar-text">Login</span>
      </a>
    </nav>
  </div>

  <!-- Main content -->
  <div class="flex-1 flex flex-col">
    <!-- Navbar -->
    <nav class="bg-blue-900 text-white flex justify-between items-center px-6 py-3">
      <div class="hidden sm:ml-6 sm:block">
        <div class="flex space-x-4">
          <a href="#home"          
            class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Home</a>
          <a href="#visi-misi"          
            class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Visi
            Misi</a>
          <a href="#produk-kami"          
            class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Produk
            Kami</a>
          <a href="#kontak"          
            class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">Kontak</a>
          <a href="#about-us"          
            class="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white">About
            Us</a>
        </div>
      </div>
      <div class="flex items-center space-x-4">
        <i class="fas fa-bell"></i>
        <img src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" class="w-8 h-8 rounded-full"
          alt="Profile" />
      </div>
    </nav>

  </div>

  <!-- Toggle Sidebar Script -->
  <script>
    const toggleSidebar = document.getElementById("toggleSidebar");
    const sidebar = document.getElementById("sidebar");
    const logoText = document.getElementById("logoText");
    const sidebarTexts = document.querySelectorAll(".sidebar-text");

    toggleSidebar.addEventListener("click", () => {
      sidebar.classList.toggle("w-64");
      sidebar.classList.toggle("w-20");

      logoText.classList.toggle("hidden");
      sidebarTexts.forEach((text) => text.classList.toggle("hidden"));
    });
  </script>