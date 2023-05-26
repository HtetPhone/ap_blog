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
            <a href="add_user.php" class="btn btn-primary">Add New User</a>
          </div>
          <!-- table here  -->
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Users</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table class="table table-bordered w-100">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Title</th>
                      <th>Content</th>
                      <th>Role</th>
                      <th width="18%">Operations</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php
                    //retrieving data 
                    $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                    $stmt->execute();
                    $rawUsers = $stmt->fetchAll();
  
                    //search
                    if(empty($_POST['search']) && empty($_COOKIE['search'])) {
                      // for pagination 
                      if(!empty($_GET['pageno'])) {
                        $pageNo = $_GET['pageno'];
                      }else {
                        $pageNo = 1;
                      }
                      $numberOfrecs = 5; //to show
                      $offset = ($pageNo - 1) * $numberOfrecs;
  
                      $totalPage = ceil(count($rawUsers) / $numberOfrecs); //chopping down the posts into pages
  
                      $stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offset, $numberOfrecs");
                      $stmt->execute();
                      $pages = $stmt->fetchAll();
                      // for pagination 
                    }else {
                      // when u search 
                      $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                      $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                      // print_r($stmt); exit();
                      $stmt->execute();
                      $rawUsers = $stmt->fetchAll();
  
                      // for pagination 
                      if(!empty($_GET['pageno'])) {
                        $pageNo = $_GET['pageno'];
                      }else {
                        $pageNo = 1;
                      }
                      $numberOfrecs = 4; //two show
                      $offset = ($pageNo - 1) * $numberOfrecs;
  
                      $totalPage = ceil(count($rawUsers) / $numberOfrecs); //chopping down the users into pages
  
                      $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offset, $numberOfrecs");                                  //right here % is very important
                      $stmt->execute();
                      $pages = $stmt->fetchAll();
                    // for pagination  
                  }
  
  
                  ?>
                  <?php foreach($pages as $key => $user): ?>
                    <tr>
                      <td><?php echo $key + 1 ?></td>
                      <td><?php echo escape($user['name']) ?></td>
                      <td><?php echo escape($user['email']) ?></td>
                      <td><?php echo $user['role'] ?></td>
                      
                      <td>
                        <a href="edit_user.php?id=<?php echo $user['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_user.php?id=<?php echo $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item')">Delete</a>
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
    