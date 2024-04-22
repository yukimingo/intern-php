<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: ./login.php");
}
require_once("./classes/category.php");

if(isset($_POST["btn_delete"])){
    $category = Category::createCategory();
    $category->delete($_POST["category_id"]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/category.css">
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        include "main-nav.php";
    ?>
    <div class="wrapper">
        <div class="form-wrapper">
            <form action="./actions/category-store.php" method="post">
                <label for="category" class="form-label">運用対応カテゴリー</label>
                <input type="text" id="category" name="category" class="form-control w-50">
                        
                <input type="submit" value="追加" name="btn_submit" class="btn btn-outline-primary px-4 mt-3">
            </form>
        </div>
        <div class="index">
            <h2>カテゴリー一覧</h2>
            <?php
            $category = Category::createCategory();
            $categories = $category->index();
            if($categories){
                foreach($categories as $category){
            ?>
            <div class="card">
                <h3><?= $category["name"]?></h3>

                <form action="" method='post'>
                    <input type="hidden" name="category_id" value=<?= $category["id"] ?>>

                    <input type="submit" name="btn_delete" value="削除">
                </form>
            </div>
            <?php }}else{?>
            <h3>カテゴリーを追加してください</h3>
            <?php }?>
        </div>
    </div>
</body>
</html>