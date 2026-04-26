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
      <form action="">
        <div class="form-input">
          <input type="text" id="Username" placeholder="Username" />
          <span class="error"></span>
        </div>
        <div class="form-input">
          <input type="email" placeholder="Email" />
        </div>
        <div class="form-input">
          <input type="text" placeholder="CCCD" />
          <span style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <input type="text" placeholder="Numberphone" />
          <span style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <input type="date" placeholder="Date" />
          <span style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <input type="password" id="password" placeholder="Password" />
          <span class="error" style="color: red">(*)</span>
        </div>
        <div class="form-input">
          <label for=""></label>
        </div>
        <div class="remember">
          <label><input type="checkbox" />Remember me </label>
        </div>
        <button type="submit" class="button"><strong>Sign-up</strong></button>
      </form>
      <h2>- - - - - - - - -or- - - - - - - - -</h2>
      <div class="sign_up_app">
        <a href="#" target="_blank"><i class="fa-brands fa-facebook"></i></a>
        <a href="#" target="_blank"><i class="fa-brands fa-google"></i></a>
      </div>
      <h3 style="color: rgb(192, 185, 185)">
        Do you already have an account?
        <a href="Login.php">Login now</a>
      </h3>
    </div>
  </div>
</body>

</html>