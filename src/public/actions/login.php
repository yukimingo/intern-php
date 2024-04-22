<?php
session_start();
if(isset($_SESSION["id"])){
    header("location: ../index.php");
    exit;
}
require_once("../classes/user.php");

$name = $_POST["username"];
$password = $_POST["password"];
$user = new User($name, $password);
$user = $user->login();

if($user && password_verify($password, $user["pass"])){
    $_SESSION['id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['roll'] = $user['roll'];

    header("location: ../index.php");
    exit;
}else{
    header("location: ../login.php");
    exit;
}
?>