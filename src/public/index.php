<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: ./login.php");
}
require_once("./classes/category.php");
require_once("./classes/log.php");
require_once("./classes/template.php");
// バリデーション
if(isset($_POST["btn_confirmation"])){
    $log = Log::createLog();
    $latestTicketNumber = $log->getLatestTicletNumber();

    $categoryId = $_POST["category"];
    $ticketNumber = $latestTicketNumber["ticket_number"] + 1;
    $title = $_POST["title"];
    $sqlStatement = $_POST["sql"];
    // $template = $_POST["template"];
    if($categoryId === "" || trim($categoryId) === ""){
        $_SESSION["category_error_message"] = "運用対応カテゴリーを入力してください";

        header("location: index.php");
        exit();
    }
    // if($ticketNumber === "" || trim($ticketNumber) === ""){
    //     $_SESSION["ticket_number_error_message"] = "チケット番号を入力してください";

    //     header("location: index.php");
    //     exit();
    // }
    if($title === "" || trim($title) === ""){
        $_SESSION["title_error_message"] = "実行理由を入力してください";

        header("location: index.php");
        exit();
    }
    if($sqlStatement === "" || trim($sqlStatement) === ""){
        $_SESSION["sql_error_message"] = "SQLを入力してください";

        header("location: index.php");
        exit();
    }
}else{
    $categoryId = null;
    $ticketNumber = null;
    $title = null;
    $sqlStatement = null;
    $template = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>実行ページ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        include "main-nav.php";
    ?>
    <div class="form-wrapper">
        <div class="left-side">
            <div class="card">
                <form action="index.php" method="post">
                    <label for="category" class="form-label">運用対応カテゴリー</label>
                    <select name="category" id="category" class="form-select w-50">
                        <option value="" hidden>運用対応カテゴリー選択</option>
                        <?php
                        $category = new Category(1);
                        $categories = $category->index();
                        foreach($categories as $category){
                        ?>
                        <option value="<?= $category["id"]?>"><?= $category["name"]?></option>
                        <?php }?>
                    </select>
                    <?php
                        if(isset($_SESSION["category_error_message"])){
                            echo "<p class='text-danger'>".$_SESSION["category_error_message"]."</p>";
                            unset($_SESSION["category_error_message"]);
                        }
                    ?>
                    <!-- <label for="ticketNumber" class="form-label">チケット番号</label>
                    <input type="number" id="ticketNumber" name="ticketNumber" class="form-control w-50" placeholder="チケット番号を入力" >
                    <?php
                        // if(isset($_SESSION["ticket_number_error_message"])){
                        //     echo "<p class='text-danger'>".$_SESSION["ticket_number_error_message"]."</p>";
                        //     unset($_SESSION["ticket_number_error_message"]);
                        // }
                    ?> -->
                    <label for="title" class="form-label">実行理由</label>
                    <input type="text" id="title" name="title" class="form-control w-50" placeholder="実行理由を入力">
                    <?php
                        if(isset($_SESSION["title_error_message"])){
                            echo "<p class='text-danger'>".$_SESSION["title_error_message"]."</p>";
                            unset($_SESSION["title_error_message"]);
                        }
                    ?>
                    <label for="sql" class="form-label">SQL</label>
                    <input type="text" id="sql" name="sql" class="form-control w-50" placeholder="SQL文を入力">
                    <?php
                        if(isset($_SESSION["sql_error_message"])){
                            echo "<p class='text-danger'>".$_SESSION["sql_error_message"]."</p>";
                            unset($_SESSION["sql_error_message"]);
                        }
                    ?>
                    <label for="template" class="form-label">実行パターンテンプレート</label>
                    <select name="template" id="category" class="form-select w-50" onchange=updateInputField(this.value)>
                        <option value="" hidden>実行パターンテンプレート選択</option>
                        <?php 
                        $template = new Template(1, 1);
                        $templates = $template->index();
                        foreach($templates as $template){
                        ?>
                        <option value="<?= $template["sql_statement"]?>"><?= $template["sql_statement"]?></option>
                        <?php }?>
                    </select>
                    <script>
                        function updateInputField(selectedValue){
                            document.getElementById("sql").value = selectedValue;
                        }
                    </script>

                    <a href="./log.php" class="btn btn-outline-success  px-4 mt-3">履歴</a>
                    <input type="submit" value="確認" name="btn_confirmation" class="btn btn-outline-primary  px-4 mt-3">
                </form>
            </div>
        </div>
        <div class="right-side">
            <form action="./actions/log-store.php" method="post">
                <!-- 確認結果出力画面 -->
                <div class="confirmation">
                    <p>運用対応カテゴリー: <?= $categoryId?></p>
                    <p>チケット番号: <?= $ticketNumber ?></p>
                    <p>実行理由: <?= $title ?></p>
                    <p>SQl: <?= $sqlStatement ?></p>

                    <input type="hidden" name="category" class="form-control w-50" value="<?= $categoryId ?>">
                    <input type="hidden" name="ticketNumber" class="form-control w-50" value="<?= $ticketNumber ?>">
                    <input type="hidden" name="title" class="form-control w-50" value="<?= $title ?>">
                    <input type="hidden" name="sqlStatement" class="form-control" value="<?= $sqlStatement ?>">
                </div>
                <?php if(!empty($categoryId || $ticketNumber || $title || $sqlStatement)){?>
                    <input type="submit" value="実行" name="btn_execution" class="btn btn-primary px-4 mt-3">
                <?php }?>
            </form>
            <!-- 実行結果出力画面 -->
            <h1>実行結果出力</h1>
            <?php
            $log = Log::createLog();
            if(isset($_GET["id"])){
                $results = $log->getGoods($_GET["id"]);
            }
            if(isset($results)){
                foreach($results as $result){
            ?>
            <h3><span>商品名:  </span><?= $result["name"]?></h3>
            <h3><span>価格:  </span><?= $result["price"]?>円</h3>
            <h3><span>詳細:  </span><?= $result["description"]?></h3>
            <?php }}?>
            <?php if(isset($_GET["successMessage"])){?>
            <p><?= $_GET["successMessage"]?></p>
            <?php }?>
        </div>
    </div>
</body>
</html>