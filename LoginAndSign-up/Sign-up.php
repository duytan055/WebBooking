<?php
include __DIR__ . "/../Connect/connecDB.php";

$error_message = '';
$success_message = '';
$password_error = '';

if (isset($_POST['register'])) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $cccd = trim($_POST['cccd']);
  $phone = trim($_POST['phone']);
  $birthdate = trim($_POST['birthdate']);
  $password = $_POST['password'];

  $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/';

  if ($username === '' || $email === '' || $cccd === '' || $phone === '' || $birthdate === '' || $password === '') {
    $error_message = 'Vui lòng nhập đầy đủ thông tin.';
  } elseif (!preg_match($passwordPattern, $password)) {
    $password_error = 'Mật khẩu phải tối thiểu 8 ký tự, có ít nhất 1 chữ hoa, 1 chữ thường và 1 ký tự đặc biệt.';
    $error_message = $password_error;
  } else {
    $stmt = $conn->prepare('SELECT id_user FROM nguoidung WHERE email = ?');
    if ($stmt) {
      $stmt->bind_param('s', $email);
      $stmt->execute();
      $resultEmail = $stmt->get_result();
      if ($resultEmail && $resultEmail->num_rows > 0) {
        $error_message = 'Email đã tồn tại';
      } else {
        $stmtInsert = $conn->prepare('INSERT INTO nguoidung (ten, email, cccd, sdt, ngay_sinh, mat_khau) VALUES (?, ?, ?, ?, ?, ?)');
        if ($stmtInsert) {
          $stmtInsert->bind_param('ssssss', $username, $email, $cccd, $phone, $birthdate, $password);
          if ($stmtInsert->execute()) {
            $success_message = 'Đăng ký thành công! Đang chuyển đến trang đăng nhập...';
          } else {
            $error_message = 'Đăng ký thất bại: ' . $stmtInsert->error;
          }
          $stmtInsert->close();
        } else {
          $error_message = 'Lỗi hệ thống, vui lòng thử lại sau.';
        }
      }
      $stmt->close();
    } else {
      $error_message = 'Lỗi hệ thống, vui lòng thử lại sau.';
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

  <?php if ($success_message): ?>
    <div class="notification success" id="notification">
      <i class="fas fa-check-circle"></i>
      <span><?php echo $success_message; ?></span>
    </div>
  <?php endif; ?>

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
          <input name="password" type="password" id="password" placeholder="Password" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}" title="Mật khẩu tối thiểu 8 ký tự, gồm ít nhất 1 chữ hoa, 1 chữ thường và 1 ký tự đặc biệt." />
          <span class="error" style="color: red">(*)</span>
          <?php if ($password_error): ?>
            <small style="color: #ffbaba; display: block; margin-top: 4px; font-size: 0.9rem;"><?php echo $password_error; ?></small>
          <?php endif; ?>
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

  <script>
    // Auto hide notification after 4 seconds
    const notification = document.getElementById('notification');
    if (notification) {
      <?php if ($success_message): ?>
        // Redirect to login page after 2 seconds on success
        setTimeout(() => {
          window.location.href = 'login.php';
        }, 2000);
      <?php else: ?>
        // Just hide notification on error
        setTimeout(() => {
          notification.style.animation = 'slideOut 0.3s ease-out';
          setTimeout(() => {
            notification.remove();
          }, 300);
        }, 4000);
      <?php endif; ?>
    }
  </script>
</body>

</html>