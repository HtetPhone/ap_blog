<?php
    session_start();
    require "config/config.php";

    if(!$_SESSION['user']) {
    header('Location: log_in.php');
    }
    //cookie
    if(isset($_POST['search'])){
      setcookie('search', $_POST['search'], time() + (86400 * 3), "/");
      // echo $_COOKIE['search'];
      // exit();
    }else {
      if(empty($_GET['pageno'])){
        unset($_COOKIE['search']); 
        setcookie('search', null, -1, '/'); 
      }
    }

    // retrieving data
    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();


    if(empty($_POST['search']) && empty($_COOKIE['search'])) {
      // for pagination 
      if(!empty($_GET['pageno'])) {
        $pageNo = $_GET['pageno'];
      }else {
        $pageNo = 1;
      }
      $numberOfrecs = 6; //two show
      $offset = ($pageNo - 1) * $numberOfrecs;

      $totalPage = ceil(count($posts) / $numberOfrecs); //chopping down the posts into pages

      $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $numberOfrecs");
      $stmt->execute();
      $pages = $stmt->fetchAll();
      // for pagination 
    }else {
      // when u search 
      $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
      $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC");
      // print_r($stmt); exit();
      $stmt->execute();
      $posts = $stmt->fetchAll();

      // for pagination 
      if(!empty($_GET['pageno'])) {
        $pageNo = $_GET['pageno'];
      }else {
        $pageNo = 1;
      }
      $numberOfrecs = 6; //two show
      $offset = ($pageNo - 1) * $numberOfrecs;

      $totalPage = ceil(count($posts) / $numberOfrecs); //chopping down the posts into pages

      $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset, $numberOfrecs");                                  //right here % is very important
      $stmt->execute();
      $pages = $stmt->fetchAll();
    // for pagination  
  }


    
    // echo "<pre>";
    // print_r($result);

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
  <style>
    .card:hover {
      box-shadow: 3px 3px 8px lightblue,
  				-3px -3px 8px lightblue;
    }
  </style>
</head>
<body class="">
<div class="container">
    <div class="row">
        <div class="col-12 my-3 d-flex justify-content-between align-items-center">
            <h1 class="text-"> <a href="index.php">Blogs</a> </h1>
            <div class="d-flex">
              <form class="form-inline" method="post">
                <div class="input-group">
                  <input class="form-control" name="search" type="search" placeholder="Search" aria-label="Search">
                </div>
              </form>
              
            </div>
            
        </div>
    </div>
    <div class="row p-2">
        <!-- looping here  -->
        <?php foreach($pages as $post): ?>
            <div class="col-12 col-md-6  col-lg-4">
                <div class="card" style="min-height: 350px!important;">
                    <div class="card-header">
                        <h5 class="text-center mb-0"><?php echo $post['title'] ?></h5>
                    </div>
                    <div class="card-body text-center"> 
                        <a href="blog_details.php?id=<?php echo $post['id']; ?>">
                            <img class="img-fluid pad mx-auto" src="admin/img/<?php echo $post['img'] ?>" alt="Photo" style="height:200px!important">
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <div class="col-12">
           <!-- pagination  -->
           <nav aria-label="Page navigation example" class="float-right">
                <ul class="pagination">
                  <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
                  <li class="page-item <?php if($pageNo <=1){ echo "disabled";} ?>">
                    <a class="page-link" href="<?php if($pageNo <= 1) { echo "#";} else { echo "?pageno=".($pageNo - 1);} ?>">Prev</a>
                  </li>
                  <li class="page-item"><a class="page-link" href=""><?php echo $pageNo; ?></a>
                  </li>
                  <li class="page-item <?php if($pageNo >= $totalPage) { echo "disabled";}?>">
                    <a class="page-link" href="<?php if($pageNo >= $totalPage) { echo "#";} else { echo "?pageno=".($pageNo + 1);} ?>">Next</a>
                  </li>
                  <li class="page-item"><a class="page-link" href="?pageno=<?php echo $totalPage?>">Last</a></li>
                </ul>
              </nav>
        </div>
        
    </div>

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>

  <footer class="container py-4 pb-5 d-flex justify-content-between align-items-center">
    <div>
      <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>.</strong> All rights reserved.
    </div>
    <div class="">
        <a href="log_out.php" class="btn btn-danger float-right btn-block">Log Out</a>
    </div>
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
