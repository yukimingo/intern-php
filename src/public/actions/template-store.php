<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}
require_once("../classes/log.php");
require_once("../classes/template.php");

$template = new Template($_POST["title"], $_POST["sql_statement"]);

$template->store();
?>