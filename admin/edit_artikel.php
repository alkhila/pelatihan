<?php
include '../config/config.php';
session_start();

// --- PENGAMANAN & INISIALISASI ---
if (!isset($_SESSION['status']) || $_SESSION['status'] != "login") {
  header("location:login.php?pesan=belum_login");
  exit();
}

$admin_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0;
$pesan = "";

// Cek apakah ID ada di URL untuk mode EDIT
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("location: artikel.php?pesan=ID artikel tidak valid.");
  exit();
}
$id_artikel = mysqli_real_escape_string($konek, $_GET['id']);


// --- AMBIL DATA DARI DATABASE (Untuk mengisi form) ---
$query_select = mysqli_query($konek, "SELECT * FROM articles WHERE id='$id_artikel'");
$data_edit = mysqli_fetch_assoc($query_select);

if (!$data_edit) {
  header("location: artikel.php?pesan=Artikel tidak ditemukan.");
  exit();
}

// Jika ada POST, prioritaskan data POST agar data yang baru diketik tidak hilang
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Gabungkan data POST dengan data lama, agar data DB lama tidak hilang
  $data_edit = array_merge($data_edit, $_POST);
}


// --- LOGIKA PROSES UPDATE ---
if (isset($_POST['update'])) {

  // Amankan input
  $judul = mysqli_real_escape_string($konek, $_POST['title']);
  $konten = mysqli_real_escape_string($konek, $_POST['content']);
  $kategori = mysqli_real_escape_string($konek, $_POST['category']);

  $gambar_lama = $_POST['photo_old'];
  $path_foto = $gambar_lama;

  // --- Penanganan Upload Foto ---
  if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
    $target_dir = "uploads/artikel/";
    if (!is_dir($target_dir))
      mkdir($target_dir, 0777, true);

    $nama_file = time() . "_" . basename($_FILES['photo']['name']);
    $target_file = $target_dir . $nama_file;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
      $pesan = "Maaf, hanya file JPG, JPEG, & PNG yang diperbolehkan.";
      goto end_script_update;
    } elseif (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
      $path_foto = $target_file;

      // Hapus gambar lama
      if (!empty($gambar_lama) && file_exists($gambar_lama)) {
        unlink($gambar_lama);
      }
    } else {
      $pesan = "Maaf, terjadi kesalahan saat mengunggah file.";
      goto end_script_update;
    }
  }

  // UPDATE QUERY
  $query = "UPDATE articles SET 
                title='$judul', 
                content='$konten', 
                category='$kategori', 
                photo='$path_foto' 
              WHERE id='$id_artikel'";

  if (mysqli_query($konek, $query)) {
    $_SESSION['notifikasi_pesan'] = "Artikel berhasil diubah.";
    header("location: artikel.php");
    exit();
  } else {
    $pesan = "Gagal mengubah data: " . mysqli_error($konek);
  }
}

end_script_update:
// --- LANJUT KE HTML ---
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Artikel</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <style>
    body {
      background-color: #f3f4f6;
      padding: 32px;
      font-family: sans-serif;
    }

    .container {
      max-width: 800px;
      margin: auto;
      padding: 24px;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .page-title {
      font-size: 24px;
      font-weight: bold;
      color: #1f2937;
      margin-bottom: 24px;
      border-bottom: 2px solid #e5e7eb;
      padding-bottom: 8px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      color: #1f2937;
      font-weight: bold;
      margin-bottom: 8px;
    }

    .form-group input,
    .form-group textarea,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #d1d5db;
      border-radius: 6px;
      box-sizing: border-box;
    }

    .form-group textarea {
      resize: vertical;
    }

    .btn-submit {
      background-color: #2563eb;
      color: white;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      transition: background-color 0.15s;
    }

    .btn-submit:hover {
      background-color: #1d4ed8;
    }

    .btn-batal {
      background-color: #6b7280;
      color: white;
      font-weight: bold;
      padding: 10px 20px;
      border-radius: 6px;
      text-decoration: none;
      margin-right: 12px;
    }

    .alert-error {
      background-color: #fee2e2;
      border-left: 4px solid #ef4444;
      color: #b91c1c;
      padding: 12px;
      margin-bottom: 20px;
      border-radius: 4px;
    }
  </style>
</head>

<body>

  <div class="container">
    <h1 class="page-title">Ubah Artikel</h1>

    <?php if (!empty($pesan)): ?>
      <div class="alert-error">
        <p><?php echo $pesan; ?></p>
      </div>
    <?php endif; ?>

    <form method="POST" action="edit_artikel.php?id=<?php echo $id_artikel; ?>" enctype="multipart/form-data">

      <div class="form-group">
        <label for="title">Judul Artikel</label>
        <input type="text" id="title" name="title" value="<?php echo $data_edit['title']; ?>" required>
      </div>

      <div class="form-group">
        <label for="content">Konten</label>
        <textarea id="content" name="content" rows="10" required><?php echo $data_edit['content']; ?></textarea>
      </div>

      <div class="form-group">
        <label for="category">Kategori</label>
        <select id="category" name="category" required>
          <option value="">-- Pilih Kategori --</option>
          <?php
          $categories = ['Kuliner', 'Event & Promosi', 'Tips & Trik', 'Komunitas', 'Filosofi Kopi', 'Behind The Scenes', 'Gaya Hidup & Suasana'];
          $current_cat = $data_edit['category'];
          foreach ($categories as $cat):
            $selected = ($current_cat == $cat) ? 'selected' : '';
            echo "<option value='{$cat}' {$selected}>{$cat}</option>";
          endforeach;
          ?>
        </select>
      </div>

      <div class="form-group">
        <label for="photo">Foto Unggulan</label>
        <input type="file" id="photo" name="photo" accept=".jpg, .jpeg, .png">

        <?php
        $current_photo_path = $data_edit['photo'];
        if (!empty($current_photo_path)): ?>
          <p style="margin-top: 8px; font-size: 14px; color: #6b7280;">Gambar saat ini:</p>
          <img src="<?php echo $current_photo_path; ?>" alt="Gambar Lama"
            style="width: 120px; height: 80px; object-fit: cover; border-radius: 4px; margin-top: 4px;">
          <input type="hidden" name="photo_old" value="<?php echo $current_photo_path; ?>">
        <?php endif; ?>
      </div>

      <div style="text-align: right;">
        <a href="artikel.php" class="btn-batal">Batal</a>
        <button type="submit" name="update" class="btn-submit">
          Ubah Artikel
        </button>
      </div>
    </form>
  </div>

</body>

</html>