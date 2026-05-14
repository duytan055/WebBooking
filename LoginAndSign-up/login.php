<?php
session_start();
include __DIR__ . "/../Connect/connecDB.php";

if (isset($_POST["login"])) {

  $username = $_POST['username'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM nguoidung 
            WHERE ten = '$username' 
            AND mat_khau = '$password'";

  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id_user'];
    $_SESSION['username'] = $row['ten'];
    $_SESSION['email'] = $row['email'];
    header("Location: ../Pages/trangChu.php");
    exit();
  } else {
    echo "<script>
                alert('Sai tài khoản hoặc mật khẩu');
              </script>";
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