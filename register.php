<?php 
  require "config/config.php";

  $nameErr = $emailErr = $passwordErr = "";

  if(isset($_POST['submit'])) {
    if(empty($_POST['name'])) {
      $nameErr = "<p class='text-danger'>Name is empty </p>";
    }else {
      $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if(empty($_POST['email'])) {
        $emailErr = "<p class='text-danger'>email is empty </p>";
    }else {
      $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_EMAIL);
    }

    if(empty($_POST['password'])) {
      $passwordErr = "<p class='text-danger'>password is empty </p>";
    }else {
      $password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
      $sPass = password_hash($password, PASSWORD_DEFAULT);
    }

    if(empty($nameErr) && empty($emailErr) && empty($passwordErr)) {
      //checking if user exist
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?") ;
      $stmt->execute([$email]);
      $result = $stmt->fetch();
      if($result) {
        echo "<script> alert('User exists!!'); </script>";
      }else {
        $stmt=$pdo->prepare("INSERT INTO users(name, email, password) VALUES (?,?,?)");
        $result = $stmt->execute([$name, $email, $sPass]);
        if($result) {
            echo "<script> alert('Registeration Succeeded! Now you can log in!');window.location.href='log_in.php'; </script>";
          }
        echo "<script> alert('Registeration Failed!!') </script>";
      }
    }
    

    
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>A Programmer | Registeration</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="word-nowrap "><b>A Programmer |</b>Blog</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Register A New Account</p>

      <form action="register.php" method="post">
        <div class="input-group mb-3">
            <input type="text" name='name' class="form-control <?php echo $nameErr ? 'is-invalid' : null; ?>" placeholder="Name">
            <div class="input-group-append">
                <div class="input-group-text">
                <span class="fas fa-user"></span>
                </div>
            </div>
          </div>
          <?php echo $nameErr; ?>
        <div class="input-group mb-3">
          <input type="email" name='email' class="form-control <?php echo $emailErr ? 'is-invalid' : null; ?>" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <?php echo $emailErr; ?>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : null; ?>" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <?php echo $passwordErr; ?>
        <div class="row">
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-success btn-block"> Register </button>
          </div>
          <div class="col-4">
            <button class="btn btn-outline-primary btn-block">
                <a href="log_in.php" class="text-dark">Sign In</a>
            </button>
          </div>
          
          <!-- /.col -->
        </div>
      </form>
      <!-- /.social-auth-links -->
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
