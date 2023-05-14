<?php
    if(isset($_FILES['img'])) {
        print_r($_FILES['img']);

        $dir = 'img/';
        $tmp_name = $_FILES['img']['tmp_name'];
        $img_name = $_FILES['img']['name'];

        move_uploaded_file($tmp_name, $dir.$img_name);
    }
?>

<form action="" method="post" enctype="multipart/form-data">

    <input type="file" name="img" id="">
    <input type="submit" value="submit" name="submit">
</form>