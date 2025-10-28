<?php
include "config/config.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usn = mysqli_real_escape_string($konek, $_POST['usn']);
  $email = mysqli_real_escape_string($konek, $_POST['email']);
  $pass = $_POST['pass'];
  $repass = $_POST['repass'];

  if ($pass !== $repass) {
    $error = "Password tidak cocok.";
    goto end_script;
  }

  $query_check = "SELECT email FROM users WHERE email = '$email'";
  $result_check = mysqli_query($konek, $query_check);

  if (mysqli_num_rows($result_check) > 0) {
    $error = "Email ini sudah terdaftar. Silakan gunakan email lain atau Login.";
    goto end_script;
  }

  $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

  $default_role = 'pengunjung';

  $query = "INSERT INTO users (name, email, password_hash, role) 
              VALUES ('$usn', '$email', '$hashed_password', '$default_role')";

  $regis = mysqli_query($konek, $query);

  if ($regis) {
    header("location: login.php?pesan=registrasi_sukses");
    exit();
  } else {
    $error = "Registrasi gagal: " . mysqli_error($konek);
  }
}
end_script:
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
      border-color: #11224E;
      outline: none;
      box-shadow: 0 0 0 0.25rem rgba(138, 112, 214, 0.25);
    }

    .form-footer {
      font-size: 14px;
      margin-bottom: 20px;
      text-align: center;
    }

    .form-footer a {
      color: #11224E;
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
      background-color: #11224E;
      cursor: pointer;
      margin-bottom: 12px;
      margin-left: 30px;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #0f1f48ff;
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

    h1 {
      color: #11224E;
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