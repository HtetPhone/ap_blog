<?php
  session_start();

  require "../config/config.php";

  if(!$_SESSION['user']) {
    header('Location: login.php');
  }

  if(isset($_POST['submit'])){
    $title = $_POST['title'];
    $content = $_POST['content'];

    // files 
    $dir = "img/";
    $img_name = $_FILES['img']['name'];
    $tmp_name = $_FILES['img']['tmp_name'];
    
    // print_r($_POST);
    move_uploaded_file($tmp_name, $dir.$img_name);

    $sql = "INSERT INTO posts(title, content, img, author_id) VALUES (?,?,?,?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([$title,$content,$img_name,$_SESSION['user_id']]);

    if($result) {
      echo "<script> alert('Successfully added!'); window.location.href='index.php'; </script>";
    }
  
}


?>


<?php include "template/header.php"; ?>



      <div class="row mb-2">
        <!-- table here  -->
        
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Add A New Post</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form action="" method="post" enctype="multipart/form-data" >
                    <div class="form-group">
                        <label for="">Title</label>
                        <input type="text" class="form-control" placeholder="Title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="">Content</label>
                        <textarea name="content" id="" cols="30" rows="10" class="form-control" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="">Image</label>
                        <input type="file" name="img" id="" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="submit" class="btn btn-success">Create</button>
                        <a href="index.php" class="btn btn-warning">Back</a>
                    </div>
                </form>
            </div>
          </div>
        </div>
      </div>


<?php include "template/footer.php"; ?>
  