<?php
    // if(isset($_FILES['img'])) {
    //     print_r($_FILES['img']);

    //     $dir = 'img/';
    //     $tmp_name = $_FILES['img']['tmp_name'];
    //     $img_name = $_FILES['img']['name'];

    //     move_uploaded_file($tmp_name, $dir.$img_name);
    if(isset($_POST['submit'])) {
        print_r($_POST);
        }
    // }
?>

<form action="" method="post">
    <input type="checkbox" name="role" id="">
    <input type="submit" value="submit" name="submit">
</form>

