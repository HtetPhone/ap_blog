<?php
  session_start();

  require "../config/config.php";
  require "../config/common.php";

  if($_SESSION['user']['role'] != 1) {
    echo "<script> alert('You cant have access to this page!');window.location.href='login.php'; </script>
    ";
  }

  $nameErr = $emailErr = $passwordErr = "";
  
  if(isset($_POST['submit'])){
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
     //retrieving data 
     $stmt = $pdo->prepare("SELECT * FROM users WHERE email=?");
     $stmt->execute([$email]);
     $user = $stmt->fetch();
     if($user) {
         echo "<script> alert('User exist!!');</script>";
     }else {
         if(isset($_POST['role'])) {
             $role = 1; 
             $sql = "INSERT INTO users(name, email, password, role) VALUES (?,?,?,?)";
             $stmt = $pdo->prepare($sql);
             $result = $stmt->execute([$name,$email,$sPass,$role]);
             if($result) {
                 echo "<script> alert('Successfully added!'); window.location.href='user_list.php'; </script>";
               }
             
         } else {
             $role = 0;
             $sql = "INSERT INTO users(name, email, password, role) VALUES (?,?,?,?)";
             $stmt = $pdo->prepare($sql);
             $result = $stmt->execute([$name,$email,$sPass,$role]);
             if($result) {
                 echo "<script> alert('Successfully added!');window.location.href='user_list.php';    </script>";
               }
         
         }
     }
  }


    

   
  
}


?>


<?php include "template/header.php"; ?>



      <div class="row mb-2">
        <!-- table here  -->
        
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Add A New User</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="add_user.php" method="post">
                    <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : null; ?>" placeholder="Name" name="name">
                        <?php echo $nameErr; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Emai</label>
                        <input type="email" class="form-control <?php echo $emailErr ? 'is-invalid' : null; ?>" placeholder="Email" name="email">
                        <?php echo $emailErr; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control <?php echo $passwordErr ? 'is-invalid' : null; ?>" placeholder="Password" name="password">
                        <?php echo $passwordErr; ?>
                    </div>
                    <div class="form-group">
                        <label for="">Admin</label> <br>
                        <input type="checkbox" name="role" id="" class="">
                    </div> <br>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success mr-3">Create</button>
                        <a href="user_list.php" class="btn btn-warning">Back</a>
                    </div>
                    
                </form>
            </div>
          </div>
        </div>
      </div>


<?php include "template/footer.php"; ?>
  