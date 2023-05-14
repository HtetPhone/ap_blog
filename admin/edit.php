<?php
  session_start();

  require "../config/config.php";

  if(!$_SESSION['user']) {
    header('Location: login.php');
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

  if(isset($_POST['submit'])) {
    $title = $_POST['title'];
    $content = $_POST['title'];
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
                    <div class="form-group">
                        <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
                        <label for="">Title</label>
                        <input type="text" class="form-control" value="<?php echo $result['title']?>" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="">Content</label>
                        <textarea name="content" id="" cols="30" rows="10" class="form-control" required><?php echo $result['content'];?></textarea>
                    </div>
                    <div class="form-group">
                        <img src="img/<?php echo $result['img'] ?>" width="200px" height="150px" alt=""> <br> <br>
                        <label for="">Image</label>
                        <input type="file" name="img" id="" accept="image/*">
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
  