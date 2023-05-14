<?php 
    $user = 'root';
    $password = '';

    $pdo = new PDO('mysql:host=localhost;dbname=ap_blog;',$user, $password);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    if(!$pdo) {
        echo "failed";
    }
?>