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

    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = $id");
    $stmt->execute();
    $result = $stmt->fetch();
    // print_r($result);
}


  /*  Updating the dat */
  $titleErr = $contentErr = "";
  if(isset($_POST['submit'])) {

    if(empty($_POST['title'])) {
        $titleErr = '<p class="text-danger"> Title is empty </p>';
    }else {
      $title = filter_input(INPUT_POST,'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if(empty($_POST['content'])) {
        $contentErr = '<p class="text-danger"> Content is empty </p>';
    }else {
      $content = filter_input(INPUT_POST,'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if(empty($titleErr) && empty($contentErr)) {
      $id = $_POST['id'];
      $dir = "img/";

      if($_FILES['img']['name'] != "") {
        $img_name = $_FILES['img']['name'];
        $tmp_name = $_FILES['img']['tmp_name'];

        move_uploaded_file($tmp_name, $dir.$img_name);

        $stmt = $pdo->prepare("UPDATE posts SET title='$title', content='$content', img='$img_name' WHERE id='$id' ");
        $result = $stmt->execute();
        if($result) {
          echo "<script> alert('Successfully Updated!'); window.location.href='index.php'; </script>";
        }
        // echo "img change tal";
      }else {
        // echo "img ma change tal";
        $stmt = $pdo->prepare("UPDATE posts SET title='$title', content='$content' WHERE id='$id' ");
        $result = $stmt->execute();
        if($result) {
          echo "<script> alert('Successfully Updated!'); window.location.href='index.php'; </script>";
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
              <h3 class="card-title">Upate Your Post</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data" >
                  <input name="_token" type="hidden" value="<?php echo $_SESSION['_token']; ?>">
                  <div class="form-group">
                      <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                      <label for="">Title</label>
                      <input type="text" class="form-control <?php echo $titleErr ? 'is-invalid' : null; ?>" value="<?php echo $result['title']?>" name="title">
                      <?php echo $titleErr; ?>
                  </div>
                  <div class="form-group">
                      <label for="">Content</label>
                      <textarea name="content" id="" cols="30" rows="10" class="form-control <?php echo $contentErr ? 'is-invalid' : null; ?>"><?php echo $result['content'];?></textarea>
                      <?php echo $contentErr; ?>
                  </div>
                  <div class="form-group">
                      <img src="img/<?php echo $result['img'] ?>" width="200px" height="150px" alt=""> <br> <br>
                      <label for="">Image</label>
                      <input type="file" name="img" id="" accept="image/*" class="form-control">
                  </div>
                  <div class="form-group">
                      <button type="submit" name="submit" class="btn btn-success">Update</button>
                      <a href="index.php" class="btn btn-warning">Back</a>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>


<?php include "template/footer.php"; ?>
  