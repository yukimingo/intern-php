<?php
session_start();
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}

session_unset();
session_destroy();

header("location: ../login.php");
exit;
?>