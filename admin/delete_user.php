<?php
  session_start();

  require "../config/config.php";

  if(!$_SESSION['user']) {
    header('Location: login.php');
  }

  if($_GET['id']) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id=?");
    $result = $stmt->execute([$id]);
    if($result) {
        header('Location: user_list.php');
    }
}
  



?>

  