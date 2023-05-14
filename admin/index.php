<?php
  session_start();

  require "../config/config.php";
  if(!$_SESSION['user']) {
    header('Location: login.php');
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
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Title</th>
                    <th width="50%">Content</th>
                    <th width="18%">Operations</th>
                  </tr>
                </thead>
                <tbody>
                <?php
                  if(empty($_POST['search'])) {
                    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC");
                    $stmt->execute();
                    $posts = $stmt->fetchAll();

                    // for pagination 
                    if(!empty($_GET['pageno'])) {
                      $pageNo = $_GET['pageno'];
                    }else {
                      $pageNo = 1;
                    }
                    $numberOrecs = 2; //two show
                    $offset = ($pageNo - 1) * $numberOrecs;

                    $totalPage = ceil(count($posts) / $numberOrecs); //chopping down the posts into pages

                    $stmt = $pdo->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $numberOrecs");
                    $stmt->execute();
                    $pages = $stmt->fetchAll();
                    // for pagination 
                  }else {
                    // when u search 
                    $searchKey = $_POST['search'];
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
                    $numberOrecs = 2; //two show
                    $offset = ($pageNo - 1) * $numberOrecs;

                    $totalPage = ceil(count($posts) / $numberOrecs); //chopping down the posts into pages

                    $stmt = $pdo->prepare("SELECT * FROM posts WHERE title LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset, $numberOrecs");                                  //right here % is very important
                    $stmt->execute();
                    $pages = $stmt->fetchAll();
                  // for pagination  
                }


                ?>
                <?php foreach($pages as $key => $post): ?>
                  <tr>
                    <td><?php echo $key + 1 ?></td>
                    <td><?php echo $post['title'] ?></td>
                    <td>
                      <?php echo $post['content'] ?>
                    </td>
                    <td>
                      <a href="edit.php?id=<?php echo $post['id'] ?>" class="btn btn-warning">Edit</a>
                      <a href="delete.php?id=<?php echo $post['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</a>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table> <br>

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
  