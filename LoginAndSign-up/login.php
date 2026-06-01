<?php
session_start();
include __DIR__ . "/../Connect/connecDB.php";

$error_message = '';

if (isset($_POST["login"])) {

  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    $error_message = 'Vui lòng nhập tài khoản và mật khẩu';
  } else {
    $stmt = $conn->prepare('SELECT * FROM adminn WHERE ten_dang_nhap = ? AND mat_khau = ?');
    if ($stmt) {
      $stmt->bind_param('ss', $username, $password);
      $stmt->execute();
      $adminResult = $stmt->get_result();
      if ($adminResult && $adminResult->num_rows > 0) {
        $admin = $adminResult->fetch_assoc();
        $_SESSION['user_id'] = $admin['id_admin'];
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';
        header('Location: ../admin/admin.php');
        exit();
      }
      $stmt->close();
    }

    $stmtUser = $conn->prepare('SELECT * FROM nguoidung WHERE ten = ? AND mat_khau = ?');
    if ($stmtUser) {
      $stmtUser->bind_param('ss', $username, $password);
      $stmtUser->execute();
      $userResult = $stmtUser->get_result();
      if ($userResult && $userResult->num_rows > 0) {
        $row = $userResult->fetch_assoc();
        $_SESSION['user_id'] = $row['id_user'];
        $_SESSION['username'] = $row['ten'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['role'] = 'user';
        header('Location: ../Pages/trangChu.php');
        exit();
      }
      $stmtUser->close();
    }

    $error_message = 'Sai tài khoản hoặc mật khẩu';
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <style>
    .notification {
      position: fixed;
      top: 20px;
      right: 20px;
      padding: 15px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      display: flex;
      align-items: center;
      gap: 10px;
      z-index: 10000;
      animation: slideIn 0.3s ease-out;
      max-width: 400px;
    }

    .notification.error {
      background: #f44336;
      color: white;
    }

    .notification.success {
      background: #4CAF50;
      color: white;
    }

    .notification i {
      font-size: 20px;
    }

    @keyframes slideIn {
      from {
        transform: translateX(400px);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }

      to {
        transform: translateX(400px);
        opacity: 0;
      }
    }
  </style>
</head>

<body>
  <?php if ($error_message): ?>
    <div class="notification error" id="notification">
      <i class="fas fa-exclamation-circle"></i>
      <span><?php echo $error_message; ?></span>
    </div>
  <?php endif; ?>

  <div class="kkk">
    <div class="form-login">
      <h1 class="login">Login</h1>
      <form action="" method="post">
        <div class="form-input">
          <label for="User"></label>
          <input name="username" type="text" id="Username" placeholder="Username" />
          <span class="error"></span>
        </div>
        <div class="form-input">
          <label for="Password"></label>
          <input name="password" type="password" id="password" placeholder="Password" />
          <span class="error"></span>
        </div>
        <div class="rememberL" style="color: rgb(192, 185, 185)">
          <label><input type="checkbox" />Remember me </label>
          <a id="abc" href="#" style="color: rgb(192, 185, 185)">Forget password</a>
        </div>
        <button name="login" type="submit" class="button">Login</button>
        <h2>- - - - - - - - -or- - - - - - - - -</h2>
        <div class="login_app">
          <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" target="_blank"><i class="fa-brands fa-google"></i></a>
        </div>
        <p style="color: rgb(192, 185, 185)">
          Don't have an account ? <br /><a
            id="Forget"
            href="Sign-up.php"
            style="color: rgb(192, 185, 185)">Create account.</a>
        </p>
      </form>
    </div>
  </div>

  <script>
    // Auto hide notification after 4 seconds
    const notification = document.getElementById('notification');
    if (notification) {
      setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => {
          notification.remove();
        }, 300);
      }, 4000);
    }
  </script>
</body>

</html>