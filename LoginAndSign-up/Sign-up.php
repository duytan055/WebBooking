<?php
include __DIR__ . "/../Connect/connecDB.php";
if (isset($_POST['register'])) {
  $username = $_POST['username'];
  $email = $_POST['email'];
  $cccd = $_POST['cccd'];
  $phone = $_POST['phone'];
  $birthdate = $_POST['birthdate'];
  $password = $_POST['password'];
  $checkEmail = "SELECT * FROM nguoidung
                 WHERE email = '$email'";
  $resultEmail = $conn->query($checkEmail);
  if ($resultEmail->num_rows > 0) {
    echo "<script>
                alert('Email đã tồn tại');
              </script>";
    exit();
  } else {
    $sql = "INSERT INTO nguoidung
    (
      ten,
      email,
      cccd,
      sdt,
      ngay_sinh,
      mat_khau
    )
    VALUES
    (
      '$username',
      '$email',
      '$cccd',
      '$phone',
      '$birthdate',
      '$password'
    )";

    if ($conn->query($sql) === TRUE) {

      echo "
        <script>
          alert('Đăng ký thành công');
          window.location.href='login.php';
        </script>
      ";
    } else {

      echo "
        <script>
          alert('Đăng ký thất bại');
        </script>
      ";

      echo $conn->error;
    }
  }
}
?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <link rel="stylesheet" href="style.css" />
  <title>Document</title>
</head>

<body>
  <div class="kkk">
    <div class="form-sign-up">
      <h1 class="Sign-up">Sign-up</h1>
      <form action="" method="POST">
        <div class="form-input">
          <input name="username" type="text" id="Username" placeholder="Username" />
          <span class="error"></span>
        </div>
        <div class="form-input">
          <input name="email" type="email" placeholder="Email" />
        </div>
        <div class="form-input">
          <input name="cccd" type="text" placeholder="CCCD" />
          <span style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <input name="phone" type="text" placeholder="Numberphone" />
          <span style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <input name="birthdate" type="date" placeholder="Date" />
          <span style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <input name="password" type="password" id="password" placeholder="Password" />
          <span class="error" style="color: red">(*)</span>
        </div>
        <div class="remember">
          <label><input name="remember" type="checkbox" />Remember me </label>
        </div>
        <button type="submit" name="register" class="button"><strong>Sign-up</strong></button>
      </form>
      <h2>- - - - - - - - -or- - - - - - - - -</h2>
      <div class="sign_up_app">
        <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
        <a href="#" target="_blank"><i class="fa-brands fa-google"></i></a>
      </div>
      <h3 style="color: rgb(192, 185, 185)">
        Do you already have an account?
        <a href="login.php">Login now</a>
      </h3>
    </div>
  </div>
</body>

</html>