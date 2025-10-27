<?php
include "config/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usn = mysqli_real_escape_string($konek, $_POST['usn']);
  $email = mysqli_real_escape_string($konek, $_POST['email']);
  $pass = $_POST['pass'];
  $repass = $_POST['repass'];

  // 1. Cek kecocokan password (Logic ini sudah Anda kerjakan sebelumnya)
  if ($pass !== $repass) {
    $error = "Password tidak cocok.";
    goto end_script;
  }

  // 2. Hash Password (Wajib)
  $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

  // 3. Set Role Default
  $default_role = 'pengunjung'; // Semua registrasi baru adalah 'pengunjung'

  // --- PERBAIKAN UTAMA DI SINI (Baris 23 yang bermasalah) ---
  // Menyebutkan nama kolom secara eksplisit dan menyediakan 5 nilai
  $query = "INSERT INTO users (name, email, password_hash, role) 
              VALUES ('$usn', '$email', '$hashed_password', '$default_role')";

  $regis = mysqli_query($konek, $query);

  if ($regis) {
    // Jika registrasi berhasil
    header("location: login.php?pesan=registrasi_sukses");
    exit();
  } else {
    // Jika gagal (misal: duplikasi email/username)
    $error = "Registrasi gagal: " . mysqli_error($konek);
  }
}
end_script: // Label untuk goto jika password tidak cocok
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      background: white;
      width: 1100px;
      height: 600px;
      max-width: 90%;
      display: flex;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(66, 65, 65, 0.3);
      overflow: hidden;
    }

    .form-section {
      flex: 1;
      padding: 20x 30px;
    }

    .form-section h1 {
      text-align: center;
      font-size: 30px;
      margin-top: 5px;
      position: relative;
      padding: 25px;
    }

    .form-group {
      margin-left: 30px;
    }

    .form-group label {
      display: block;
      font-size: 16px;
      margin-bottom: 10px;
    }

    .form-group input {
      width: 90%;
      padding: 15px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 16px;
    }

    input:focus {
      border-color: #8A70D6;
      outline: none;
      box-shadow: 0 0 0 0.25rem rgba(138, 112, 214, 0.25);
    }

    .form-footer {
      font-size: 14px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-footer a {
      color: #c084fc;
      text-decoration: none;
      font-weight: bold;
    }

    .btn {
      width: 90%;
      padding: 12px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      color: white;
      background-color: #c084fc;
      cursor: pointer;
      margin-bottom: 12px;
      margin-left: 30px;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #a855f7;
    }

    .image-section {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f8f8f8;
      padding: 20px;
    }

    .image-section img {
      width: 100%;
      max-width: 400px;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="form-section">
      <form action="" method="post">
        <h1>REGISTER</h1> <br>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="usn">
        </div>
        <div class="form-group">
          <label for="">Email</label>
          <input type="text" name="email">
        </div>
        <div class="form-group">
          <label for="repass">Repassword</label>
          <input type="password" name="repass">
        </div>
        <div class="form-group">
          <label for="pass">Password</label>
          <input type="password" name="pass">
        </div>
        <div class="form-footer">
          Sudah punya akun? <a href="login.php">Login</a>
        </div>
        <button class="btn" type="submit">Registrasi</button>
      </form>
    </div>
    <div class="image-section">
      <img src="img/logo.png">
    </div>
  </div>
</body>

</html>