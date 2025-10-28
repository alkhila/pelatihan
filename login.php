<?php
include "config/config.php";
session_start();

$error = "";
$success_message = "";

if (isset($_GET['pesan']) && $_GET['pesan'] == 'registrasi_sukses') {
  $success_message = "🎉 Registrasi berhasil! Silakan masuk dengan akun Anda.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $usn_input = mysqli_real_escape_string($konek, $_POST['usn']);
  $pass_input = $_POST['pass'];

  $query = "SELECT id, name, password_hash, role FROM users WHERE name='$usn_input'";
  $login_result = mysqli_query($konek, $query) or die(mysqli_error($konek));

  $cek = mysqli_num_rows($login_result);

  if ($cek > 0) {
    $data = mysqli_fetch_assoc($login_result);
    $hashed_password_dari_db = $data['password_hash'];

    if (password_verify($pass_input, $hashed_password_dari_db)) {
      $_SESSION['alert_message'] = "Selamat datang, " . $data['name'] . "! Anda berhasil login.";
      $_SESSION['id'] = $data['id'];
      $_SESSION['username'] = $data['name'];
      $_SESSION['role'] = $data['role'];
      $_SESSION['status'] = "login";

      if ($data['role'] == 'admin') {
        header("location: admin/dashboard.php");
      } else {
        header("location: pengunjung/dashboardP.php");
      }
      exit();

    } else {
      $error = "Username atau password salah!";
    }
  } else {
    $error = "Username atau password salah!";
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
      background: #EEEEEE;
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
        <h1>LOGIN</h1> <br>
        <?php if (!empty($success_message)): ?>
          <div id="successAlert"
            style="margin-left: 30px; background-color: #ecfccb; border: 1px solid #bbf7d0; color: #14532d; padding: 12px; border-radius: 6px; max-width:88%;">
            <strong>Berhasil:</strong>
            <span> <?php echo htmlspecialchars($success_message); ?></span>
          </div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
          <div id="errorAlert" style="margin-left: 30px;">
            <p class="error-message">⚠️ <?php echo $error; ?></p>
          </div>
        <?php endif; ?>
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
    </div>
    <div class="image-section">
      <img src="img/logo.png">
    </div>
  </div>

  <script>
    const errorAlert = document.getElementById('errorAlert');
    const successAlert = document.getElementById('successAlert');

    if (errorAlert) {
      setTimeout(() => {
        errorAlert.style.opacity = '0';
        errorAlert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
          errorAlert.style.display = 'none';
        }, 500);
      }, 4000);
    }

    if (successAlert) {
      setTimeout(() => {
        successAlert.style.opacity = '0';
        successAlert.style.transition = 'opacity 0.5s ease';
        setTimeout(() => {
          successAlert.style.display = 'none';
        }, 500);
      }, 5000);
    }
  </script>
</body>

</html>