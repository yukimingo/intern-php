<?php 
session_start();
if(!isset($_SESSION["id"])){
    header("location: ./login.php");
}
require_once("./classes/log.php");

if(isset($_POST["btn_set"])){
    $log = Log::createLog();
    $latestTicketNumber = $log->getLatestTicletNumber();

    $category = $_POST["category_id"];
    // $ticketNumber = $_POST["ticket_number"];
    $ticketNumber = $latestTicketNumber["ticket_number"] + 1;
    $title = $_POST["title"];
    $sqlStatement = $_POST["sql_statement"];
    // $template = $_POST["template"];
    if($category === ""){
        $errorCategory = "運用対応カテゴリーを選択してください";
    }
    if($ticketNumber === ""){
        $errorTicketNumber = "チケット番号を入力してください";
    }
    if($title === ""){
        $errorTitle = "実行理由を入力してください";
    }
    // if($sqlStatement === ""){
    //     $errorSql = "SQL文を入力してください";
    // }
}else{
    $category = null;
    $ticketNumber = null;
    $title = null;
    $sqlStatement = null;
    // $template = null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>実行履歴ページ</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="./css/log.css">
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <?php
        include "main-nav.php";
    ?>
    <div class="wrapper">
        <div class="form-wrapper">
            <form action="" method="get">
                <div class="input-group w-50">
                    <input type="text" name="search_title" placeholder="検索" class="form-control ">
                    <button type="submit" name="btn_search" class="btn btn-outline-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
            </form>
            <form action="./actions/log-store.php" method="post">
                <!-- <label for="category" class="form-label">運用対応カテゴリー</label> -->
                <input type="hidden" name="category" class="form-control w-50" value="<?= $category ?>" placeholder="運用対応カテゴリーを入力">
                <label for="ticketNumber" class="form-label">チケット番号</label>
                <input type="number" id="ticketNumber" name="ticketNumber" value="<?= $ticketNumber ?>" class="form-control w-50" placeholder="チケット番号を入力">
                <label for="title" class="form-label">タイトル</label>
                <input type="text" id="title" name="title" value="<?= $title ?>" class="form-control w-50" placeholder="タイトルを入力">
                <label for="sql" class="form-label">SQL</label>
                <input type="text" id="sql" name="sqlStatement" value="<?= $sqlStatement ?>" class="form-control w-50" placeholder="SQL文を入力">

                <?php if(!empty($category || $ticketNumber || $title || $sqlStatement)){?>
                    <input type="submit" name="btn_execution" class="btn btn-primary px-4 mt-3" value="実行">
                <?php }?>
            </form>
        </div>

        <div class="index-log">
            <h3>実行履歴</h3>
            <div class="card-wrapper">
                <?php 
                if(isset($_GET["btn_search"])){
                    $log = Log::createLog();
                    $logs = $log->search($_GET["search_title"]);
                }else{
                    $log = Log::createLog();
                    $logs = $log->index();
                }
                if($logs){
                    foreach($logs as $log){
                ?>
                <div class="card">
                    <p>運用対応カテゴリー: <?= $log["name"]?></p>
                    <p>チケット番号: <?= $log["ticket_number"]?></p>
                    <p>実行理由: <?= $log["title"]?></p>
                    <p>SQl: <?= $log["sql_statement"]?></p>
                    
                    <div class="btn-wrapper">
                        <form action="" method="post" class="form1">
                            <input type="hidden" name="category_id" value="<?= $log["category_id"]?>">
                            <input type="hidden" name="ticket_number" value="<?= $log["ticket_number"]?>">
                            <input type="hidden" name="title" value="<?= $log["title"]?>">
                            <input type="hidden" name="sql_statement" value="<?= $log["sql_statement"]?>">

                            <input type="submit" name="btn_set" class="btn btn-outline-primary w-100" value="再利用">
                        </form>

                        <form action="./actions/template-store.php" method="post" class="form2">
                            <input type="hidden" name="title" value="<?= $log["title"]?>">
                            <input type="hidden" name="sql_statement" value="<?= $log["sql_statement"]?>">

                            <input type="submit" name="btn_store" class="btn btn-outline-success w-100" value="追加">
                        </form>
                    </div>
                </div>
                <?php }}else{ ?>
                    <h1>実行履歴はありません</h1>
                <?php } ?>
            </div>
        </div>
    </div>
</body>
</html>
