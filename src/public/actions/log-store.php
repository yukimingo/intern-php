<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}
require_once("../classes/log.php");
// require_once("../classes/category.php");

$log = new Log($_POST["category"], $_POST["ticketNumber"], $_POST["title"], $_POST["sqlStatement"]);

$log->store();
$result = $log->distinguishSql($_POST["sqlStatement"]);
if($result === "select"){
    $log->executeSelect($_POST["sqlStatement"]);
}elseif($result === "insert"){
    $log->executeInsert($_POST["sqlStatement"]);
}elseif($result === "update"){
    $log->executeUpdate($_POST["sqlStatement"]);
}elseif($result === "delete"){
    $log->executeDelete($_POST["sqlStatement"]);
}
?>
