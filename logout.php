<?php
session_start();

$user_role = $_SESSION['role'] ?? 'pengunjung';

$_SESSION = array();
if (ini_get("session.use_cookies")) {
  $params = session_get_cookie_params();
  setcookie(
    session_name(),
    '',
    time() - 42000,
    $params["path"],
    $params["domain"],
    $params["secure"],
    $params["httponly"]
  );
}
session_destroy();

if ($user_role == 'admin') {
  header("Location: login.php");
} else {
  header("Location: pengunjung/dashboardP.php");
}
exit();
?>