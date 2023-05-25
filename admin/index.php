<?php
  session_start();
  require "../config/config.php";

  // print_r($_SESSION['user']['role']);
  if($_SESSION['user']['role'] != 1) {
    echo "<script> alert('You cant have access to this page!');window.location.href='login.php'; </script>
    ";
  }

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

?>

<?php include "template/header.php"; ?>



      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Admin Panel</h1>
        </div>  
        <div class="col-12 my-4">
          <a href="add.php" class="btn btn-primary">Add New Post</a>
        </div>
        <!-- table here  -->
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Blog Posts</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <?php
                  //retrieving data 
                  $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                  $stmt->execute();
                  $posts = $stmt->fetchAll();

                  //search
                  if(empty($_POST['search']) && empty($_COOKIE['search'])) {
                    // for pagination 
                    if(!empty($_GET['pageno'])) {
                      $pageNo = $_GET['pageno'];
                    }else {
                      $pageNo = 1;
                    }
                    $numberOrecs = 5; //to show
                    $offset = ($pageNo - 1) * $numberOrecs;

                    $totalPage = ceil(count($posts) / $numberOrecs); //chopping down the posts into pages

                    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $numberOrecs");
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
                    $numberOrecs = 5; //two show
                    $offset = ($pageNo - 1) * $numberOrecs;

                    $totalPage = ceil(count($posts) / $numberOrecs); //chopping down the posts into pages

                    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset, $numberOrecs");                                  //right here % is very important
                    $stmt->execute();
                    $pages = $stmt->fetchAll();
                  // for pagination  
                }
                ?>
              <div class="table-responsive">
                <table class="table table-striped table-bordered">
                  <thead>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">Title</th>
                      <th scope="col">Content</th>
                      <th scope="col" width="20%">Operations</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($pages as $key => $post): ?>
                    <tr>
                      <th scope="row"><?php echo $key + 1 ?></th>
                      <td><?php echo $post['title'] ?></td>
                      <td><?php echo substr($post['content'], 0, 80); ?></td>
                      <td class="d-flex align-items-center">
                        <div class="mr-2"><a href="edit.php?id=<?php echo $post['id'] ?>" class="btn btn-warning">Edit</a></div>
                        <div><a href="delete.php?id=<?php echo $post['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</a></div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  </tbody>
                </table>
              </div>

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
        </div>
      </div>


<?php include "template/footer.php"; ?>
  