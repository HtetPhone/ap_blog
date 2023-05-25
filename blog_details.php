<?php
    session_start();
    require "config/config.php";

    require "config/config.php";
    if(!$_SESSION['user']) {
    header('Location: log_in.php');
    }

    //retrieving data
    if($_GET) {
        $post_id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id=$post_id");
        $stmt->execute();
        $result = $stmt->fetch();
    }else {
        header('Location: index.php');   
        echo "<script> alert('Successfully added!'); window.location.href='index.php'; </script>";

    }
    // echo "<pre>";
    // print_r($result);


    // uploading comments 
    $author_id = $_SESSION['user']['id'];
    $post_id = $_GET['id'];
    if($_POST) {
        $comment = $_POST['comment'];
        $sql = "INSERT INTO comments(content, author_id, post_id) VALUES (?,?,?)";
        $stmt = $pdo->prepare($sql);
        $cmt_result = $stmt->execute([$comment,$author_id,$post_id]);

        if($cmt_result) {
             echo "<script> alert('Your Comment is added!');</script>";
        }
    }   

    //retrieving comments
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE post_id=$post_id");
    $stmt->execute();
    $comments = $stmt->fetchAll();



?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>A Programmer | Blog</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="">
<div class="container">
    <div class="row">
        <div class="col-12 my-3 d-flex justify-content-between align-items-center">
            <h1 class="text-">A Programmer | Blog</h1> 
            <div>
                <a href="log_out.php" class="btn btn-danger btn-block">Log Out</a>
            </div>
        </div>
    </div>
    <div class="row p-2">
        <!-- looping here  -->
        <div class="col-12 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center mb-0"><?php echo $result['title'] ?></h3>
                </div>
                <div class="card-body text-center"> 
                    <a href="blog_details.php?id=<?php echo $result['id']; ?>">
                        <img class="img-fluid pad mx-auto" src="admin/img/<?php echo $result['img'] ?>" alt="Photo" style="max-height:350px">
                    </a>
                    <p class="text-black-50 text-justify" style="text-indent: 35px;">
                        <?php echo $result['content']; ?>
                    </p>
                    <h4>Comments</h4>
                    <hr>
                    <!-- comments -->
                    <div class="card-footer">
                        <?php foreach($comments as $comment): ?>
                            <!-- getting author names  -->
                            <?php
                                $auth_id = $comment['author_id'];
                               $stmt = $pdo->prepare("SELECT * FROM users WHERE id=$auth_id");
                               $stmt->execute();
                               $commentor = $stmt->fetch();
                            ?>

                            <div>
                                <div class="d-flex justify-content-start align-items-start text-start">
                                    <img src="dist/img/user4-128x128.jpg" alt="" class="img-circle mr-3" style="width: 40px">
                                    <div class="">
                                        <p class="commentor font-weight-bold mb-0" style="text-align: start!important;"><?php echo $commentor['name'] ?></p>
                                        <p class="content text-black-50"><?php echo $comment['content']; ?></p>
                                    </div>
                                </div>
                            </div>
                            <hr>   
                        <?php endforeach; ?>
                    </div>

                    <!-- comment box  -->
                    <div class="card-footer">
                        <form action="blog_details.php?id=<?php echo $_GET['id'];?>" method="post">
                            <input type="text" name="comment" class="form-control form-control-sm" placeholder="Press enter to post comment">
                            </div>
                        </form>
                    </div>

                </div>

                <div>
                    <a href="index.php" class="btn btn-warning">Go Back</a>
                </div>
            </div>
        </div>
    </div>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <footer class="container py-4 pb-5">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 3.2.0
    </div>
    <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
