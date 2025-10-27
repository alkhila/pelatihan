<?php
// detail_artikel.php
// Asumsi: File ini ada di folder 'pengunjung/'
// TIDAK ADA session_start() di sini karena ini adalah halaman publik
include '../config/config.php'; // Sesuaikan path ke config.php Anda

// --- 1. AMBIL ID DARI URL ---
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  // Alihkan jika ID tidak ada atau tidak valid
  header("Location: artikelP.php?pesan=Artikel_tidak_ditemukan");
  exit();
}

$article_id = mysqli_real_escape_string($konek, $_GET['id']);

// --- 2. QUERY MENGAMBIL DATA LENGKAP ARTIKEL ---
$query = "SELECT a.*, u.name AS author_name, u.email AS author_email 
          FROM articles a
          JOIN users u ON a.admin_id = u.id 
          WHERE a.id = '$article_id'";

$result = mysqli_query($konek, $query);
$article = mysqli_fetch_assoc($result);

// Jika artikel tidak ditemukan di DB
if (!$article) {
  header("Location: artikelP.php?pesan=Artikel_tidak_ditemukan");
  exit();
}

// Format Tanggal
$published_date = date('d M Y, H:i', strtotime($article['published_at']));
$image_path = '../admin/' . $article['photo']; // Sesuaikan path gambar dari folder admin
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($article['title']); ?> | DolceVita Blog</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    body {
      font-family: sans-serif;
      background-color: #f3f4f6;
      color: #1f2937;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 900px;
      margin: 40px auto;
      background-color: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .article-image {
      width: 100%;
      height: 400px;
      object-fit: cover;
      border-radius: 8px;
      margin-bottom: 30px;
    }

    .article-title {
      font-size: 2.5rem;
      font-weight: 800;
      margin-bottom: 15px;
      color: #1e3a8a;
    }

    .article-metadata {
      display: flex;
      justify-content: space-between;
      font-size: 0.9rem;
      color: #6b7280;
      border-bottom: 1px solid #e5e7eb;
      padding-bottom: 10px;
      margin-bottom: 30px;
    }

    .article-body {
      line-height: 1.8;
      font-size: 1.1rem;
      color: #374151;
      /* Mempertahankan format baris baru dari database */
      white-space: pre-wrap;
    }

    .back-link {
      display: inline-block;
      margin-bottom: 20px;
      color: #2563eb;
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <div class="container">

    <a href="artikelP.php" class="back-link">
      &larr; Kembali ke Daftar Artikel
    </a>

    <?php if (!empty($article['photo']) && file_exists($image_path)): ?>
      <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>"
        class="article-image">
    <?php else: ?>
      <div
        style="height: 400px; background-color: #ccc; border-radius: 8px; margin-bottom: 30px; display: flex; align-items: center; justify-content: center; color: #6b7280;">
        Gambar Tidak Tersedia
      </div>
    <?php endif; ?>

    <h1 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h1>

    <div class="article-metadata">
      <span>
        <i class="fas fa-user-circle"></i> Oleh:
        <strong><?php echo htmlspecialchars($article['author_name']); ?></strong>
      </span>
      <span>
        <i class="fas fa-calendar-alt"></i> Dipublikasikan: <?php echo $published_date; ?>
      </span>
      <span>
        <i class="fas fa-tag"></i> Kategori: <strong><?php echo htmlspecialchars($article['category']); ?></strong>
      </span>
    </div>

    <div class="article-body">
      <?php echo nl2br(htmlspecialchars($article['content'])); ?>
    </div>

  </div>

</body>

</html>