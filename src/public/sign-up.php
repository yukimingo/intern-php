<?php
session_start();
if(isset($_SESSION["id"])){
    header("location: ./index.php");
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
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        include "main-nav.php";
    ?>
    <div class="form-wrapper">
        <div class="card">
            <h2>新規登録</h2>
            <form action="./actions/sign-up.php" method="post">
                <label for="username" class="form-label">ユーザーネーム</label>
                <input type="text" name="username" id="username" class="form-control mb-2" placeholder="ユーザーネームを入力してください">
                <?php
                    if(isset($_SESSION["errors"]["name_error"])){
                        echo "<p class='text-danger'>".$_SESSION["errors"]["name_error"]."</p>";
                        unset($_SESSION["errors"]["name_error"]);
                    }
                ?>
                <label for="password" class="form-label">パスワード</label>
                <input type="password" name="password" id="password" class="form-control mb-2">
                <?php
                    if(isset($_SESSION["errors"]["pass_strlen_error"])){
                        echo "<p class='text-danger'>".$_SESSION["errors"]["pass_strlen_error"]."</p>";
                        unset($_SESSION["errors"]["pass_strlen_error"]);
                    }
                    if(isset($_SESSION["errors"]["pass_error"])){
                        echo "<p class='text-danger'>".$_SESSION["errors"]["pass_error"]."</p>";
                        unset($_SESSION["errors"]["pass_error"]);
                    }
                ?>
                <label for="conf-pass" class="form-label">パスワード確認</label>
                <input type="password" name="conf-pass" id="conf-pass" class="form-control mb-3">
                <?php
                    if(isset($_SESSION["errors"]["conf_pass_error"])){
                        echo "<p class='text-danger'>".$_SESSION["errors"]["conf_pass_error"]."</p>";
                        unset($_SESSION["errors"]["conf_pass_error"]);
                    }
                ?>

                <input type="submit" name="btn_sign_up" class="btn btn-primary" value="登録">
            </form>
        </div>
     </div>
</body>
</html>