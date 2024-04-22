<?php
session_start();
if(isset($_SESSION["id"])){
    header("location: ../index.php");
    exit;
}
require_once("../classes/user.php");

$errors = [];
$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$username = trim($username);
$password = $_POST["password"];
$confPass = $_POST["conf-pass"];

if(empty($username)){
    $errors['name_error'] = 'ユーザーネームを入力してください';
}

if(strlen($password) < 8){
    $errors['pass_strlen_error'] = 'パスワードは8文字以上に設定してください';
}

if(!preg_match('/\d/', $password)){
    $errors['pass_error'] = 'パスワードには数字を含めてください';
}

if(!$password === $confPass){
    $errors['conf_pass_error'] = 'パスワードと確認用パスワードが一致しません';
}

if(count($errors) === 0){
    $user = new User($username, $password);
    $user = $user->signup();

    $_SESSION['id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['roll'] = $user['roll'];

    header("location: ../index.php");
    exit;
}else{
    $_SESSION["errors"] = $errors;
    header("location: ../sign-up.php");
    exit;
}
?>