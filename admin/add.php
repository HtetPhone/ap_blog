<?php
  session_start();

  require "../config/config.php";

  if($_SESSION['user']['role'] != 1) {
    echo "<script> alert('You cant have access to this page!');window.location.href='login.php'; </script>
    ";
  }

  
  $titleErr = $contentErr = $imgErr = "";

  if(isset($_POST['submit'])){
    // print_r($_FILES['img']['name']); exit();
    if(empty($_POST['title'])) {
        $titleErr = "<p class='text-danger'>Title is empty </p>";
    }else {
      $title = filter_input(INPUT_POST,'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if(empty($_POST['content'])) {
        $contentErr = "<p class='text-danger'>Content is empty </p>";
    }else {
      $content = filter_input(INPUT_POST,'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }

    if(empty($_FILES['img']['name'])) {
        $imgErr = "<p class='text-danger'>Image is empty </p>";
    }

    if(empty($titleErr) && empty($contentErr) && empty($imgErr)) {
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
                        <input type="text" class="form-control <?php echo $titleErr ? 'is-invalid' : null; ?>" placeholder="Title" name="title">
                        <?php echo $titleErr ?>
                    </div>
                    <div class="form-group">
                        <label for="">Content</label>
                        <textarea name="content" id="" cols="30" rows="10" class="form-control <?php echo $contentErr ? 'is-invalid' : null; ?>"></textarea>
                        <?php echo $contentErr ?>
                    </div>
                    <div class="form-group">
                        <label for="">Image</label>
                        <input type="file" name="img" id="" accept="image/*" class="form-control <?php echo $imgErr ? 'is-invalid' : null; ?>">
                        <?php echo $imgErr ?>
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
  