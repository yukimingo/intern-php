<?php

function connect(){
    $dsn = "mysql:host=intern_dev_mysql;dbname=intern_develops;charset=utf8";
    $user = "intern_develops_user";
    $pass = "password";

    try {
        $dbh = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]);
        // echo "接続成功";
        return $dbh;
    } catch(PDoException $e) {
        echo "接続失敗". $e->getMessage();
        exit();
    };
}
?>