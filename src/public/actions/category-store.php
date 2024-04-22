<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}
require_once("../classes/category.php");

$category = new Category($_POST["category"]);

$category->store();
?>