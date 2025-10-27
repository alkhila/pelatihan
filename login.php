<?php
include "config/config.php";
session_start();

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usn_input = mysqli_real_escape_string($konek, $_POST['usn']);
  $pass_input = $_POST['pass'];

  // --- PERUBAHAN: SELECT kolom 'role' juga ---
  $query = "SELECT id, name, password_hash, role FROM users WHERE name='$usn_input'";
  $login_result = mysqli_query($konek, $query) or die(mysqli_error($konek));

  $cek = mysqli_num_rows($login_result);

  if ($cek > 0) {
    $data = mysqli_fetch_assoc($login_result);
    $hashed_password_dari_db = $data['password_hash'];

    if (password_verify($pass_input, $hashed_password_dari_db)) {
      // Login Berhasil

      // --- SIMPAN ROLE KE SESSION ---
      $_SESSION['id'] = $data['id'];
      $_SESSION['username'] = $data['name'];
      $_SESSION['role'] = $data['role']; // <-- INI YANG BARU
      $_SESSION['status'] = "login";

      // --- LOGIKA REDIRECT BERDASARKAN ROLE ---
      if ($data['role'] == 'admin') {
        header("location: admin/dashboard.php"); // Arahkan ke folder admin
      } else {
        header("location: pengunjung/dashboardP.php"); // Arahkan ke halaman pengunjung
      }
      exit();
    } else {
      $error = "Username atau password salah!";
    }
  } else {
    $error = "Username atau password salah!";
  }

  $_SESSION['role'] = $data['role']; // Nilai: 'admin' atau 'pengunjung'
  $_SESSION['status'] = "login";

  if ($data['role'] == 'admin') {
    header("location: admin/dashboard.php"); // Redirect ke folder Admin
  } else {
    header("location: pengunjung/dashboardP.php"); // Redirect ke halaman Pengunjung
  }
}
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
      width: 450px;
      padding: 20px 30px;
    }

    .form-section h1 {
      text-align: center;
      font-size: 30px;
      margin-top: 5px;
      position: relative;
      padding: 25px;
    }

    .form-group {
      margin-bottom: 20px;
      margin-left: 30px;
    }

    .form-group label {
      display: block;
      font-size: 16px;
      margin-bottom: 5px;
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
        <h1>LOGIN</h1> <br>
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" name="usn">
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="pass">
        </div>
        <div class="form-footer">
          Belum punya akun? <a href="registrasi.php">Daftar</a>
        </div>
        <button class="btn" type="submit">Log In</button>
      </form>
    </div>
    <div class="image-section">
      <img src="img/logo.png">
    </div>
  </div>
</body>

</html>