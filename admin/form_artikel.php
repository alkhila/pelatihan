<?php
include '../config/config.php';
session_start();

$admin_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;

if (isset($_POST['submit'])) {
  $judul = mysqli_real_escape_string($konek, $_POST['title']);
  $konten = mysqli_real_escape_string($konek, $_POST['content']);
  $kategori = mysqli_real_escape_string($konek, $_POST['category']);

  $path_foto = NULL;
  $pesan = "";

  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $target_dir = "uploads/artikel/";

    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $nama_file = time() . "_" . basename($_FILES['photo']['name']);
    $target_file = $target_dir . $nama_file;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
      $pesan = "Maaf, hanya file JPG, JPEG, & PNG yang diperbolehkan.";
    } elseif (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
      $path_foto = $target_file;
    } else {
      $pesan = "Maaf, terjadi kesalahan saat mengunggah file.";
    }
  }

  if (empty($pesan)) {
    $query = "INSERT INTO articles (title, content, category, photo, admin_id) 
                  VALUES ('$judul', '$konten', '$kategori', '$path_foto', '$admin_id')";

    $input = mysqli_query($konek, $query);

    if ($input) {
      $_SESSION['notifikasi_pesan'] = "Artikel berhasil ditambahkan!";
      header("location: artikel.php");
      exit();
    } else {
      $pesan = "Gagal menyimpan artikel ke database: " . mysqli_error($konek);
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Artikel Baru | Dolhareubang</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
</head>

<body class="bg-gray-100 p-8">

  <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-2">Tambah Artikel Baru</h1>

    <?php if (!empty($pesan)): ?>
      <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
        <p><?php echo $pesan; ?></p>
      </div>
    <?php endif; ?>

    <form action="" method="post" enctype="multipart/form-data">

      <div class="mb-5">
        <label for="title" class="block text-gray-700 font-bold mb-2">Title</label>
        <input type="text" id="title" name="title"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
      </div>

      <div class="mb-5">
        <label for="content" class="block text-gray-700 font-bold mb-2">Content</label>
        <textarea id="content" name="content" rows="8"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500"
          required>Masukkan konten artikel disini</textarea>
      </div>

      <div class="mb-5">
        <label for="category" class="block text-gray-700 font-bold mb-2">Kategori</label>
        <select id="category" name="category"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-blue-500" required>
          <option value="">-- Pilih Kategori --</option>
          <option value="Kuliner">Kuliner</option>
          <option value="Event">Tips & Trik</option>
          <option value="Event">Filosofi Kopi</option>
          <option value="Tips">Komunitas</option>
          <option value="Tips">Gaya Hidup & Suasana</option>
          <option value="Tips">Event & Promosi</option>
          <option value="Tips">Behind The Scene</option>
        </select>
      </div>

      <div class="mb-5">
        <label for="photo" class="block text-gray-700 font-bold mb-2">Foto Unggulan</label>
        <input type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png"
          class="w-full border p-2 rounded-lg bg-gray-50">
      </div>

      <div class="flex justify-end space-x-4">
        <a href="artikel.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">Batal</a>
        <button type="submit" name="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg flex items-center">
          <span class="material-icons mr-2">save</span> Submit Artikel
        </button>
      </div>

    </form>
  </div>

</body>

</html>