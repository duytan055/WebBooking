<?php
session_start();
include __DIR__ . "/../Connect/connecDB.php";

if (isset($_POST["login"])) {

  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  if ($username === '' || $password === '') {
    echo "<script>alert('Vui lòng nhập tài khoản và mật khẩu');</script>";
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

    echo "<script>alert('Sai tài khoản hoặc mật khẩu');</script>";
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
</head>

<body>
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
</body>

</html>