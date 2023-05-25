<?php
  session_start();

  require "../config/config.php";
  require "../config/common.php";

  if($_SESSION['user']['role'] != 1) {
    echo "<script> alert('You cant have access to this page!');window.location.href='login.php'; </script>
    ";
  }
   
  /* retrieving data from database */
  if($_GET['id']) {
    $id = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = $id");
    $stmt->execute();
    $result = $stmt->fetch();
  }

  $nameErr = $emailErr = "";
  /*  Updating the dat */
  if(isset($_POST['submit'])) {
    $id = $_POST['id'];

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

    if(empty($nameErr) && empty($emailErr)) {
         //retrieving data to check if new email exists alredy
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id !=? AND email=? ");
        $stmt->execute([$id, $email]);
        $result = $stmt->fetch();
        if($result){
              echo "<script> alert('User with such email alredy exists!'); window.location.href='edit_user.php?id=$id';   </script>";
          }else {
              if(isset($_POST['role'])) {
                  $role = 1;
                  $sql = "UPDATE users SET name=?, email=?, role=? WHERE id=$id";
                  $stmt = $pdo->prepare($sql);
                  $result = $stmt->execute([$name,$email,$role]);
                  if($result) {
                      echo "<script> alert('Successfully updated!'); window.location.href='user_list.php'; </script>";
                  }
                  
              }else {
                  $role = 0;
                  $sql = "UPDATE users SET name=?, email=?, role=? WHERE id=$id";
                  $stmt = $pdo->prepare($sql);
                  $result = $stmt->execute([$name,$email,$role]);
                  if($result) {
                      echo "<script> alert('Successfully updated!');window.location.href='user_list.php';    </script>";
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
              <h3 class="card-title">Upate User</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="edit_user.php?id=<?php echo $_GET['id'];?>" method="post" enctype="multipart/form-data" >
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                      <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                      <label for="">Name</label>
                      <input type="text" class="form-control <?php echo $nameErr ? 'is-invalid' : null; ?>" value="<?php echo $result['name']?>" name="name">
                      <?php echo $nameErr; ?>
                  </div>
                  <div class="form-group">
                      <label for="">Email</label>
                      <input type="email" class="form-control <?php echo $emailErr ? 'is-invalid' : null; ?>" value="<?php echo $result['email']?>" name="email">
                      <?php echo $emailErr; ?>
                  </div>
                  <!-- <div class="form-group">
                    <label for="">Password</label>
                    <?php echo $result['password'] ?'<p class="text-warning">user has password</p>' : null ?>
                    <input type="password" class="form-control" value="" name="password">
                  </div> -->
                  <div class="form-group">
                      <label for="">Admin</label> <br>
                      <input type="checkbox" name="role" id="" <?php echo $result['role'] == 1 ? 'checked' : null ?>>
                  </div>
                
                  <div class="form-group">
                      <button type="submit" name="submit" class="btn btn-success">Update</button>
                      <a href="user_list.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>


<?php include "template/footer.php"; ?>
  