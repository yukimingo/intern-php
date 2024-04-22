<?php
session_start();
if(isset($_SESSION["id"])){
    header("location: ../index.php");
    exit;
}

require_once("connection.php");
class User{
    private $name;
    private $password;
    // private $confPass;

    public function __construct($name, $password){
        $this->name = $name;
        $this->password = $password;
        // $this->confPass = $confPass;f
    }

    public function signup(){
        $name = $this->name;
        $password = password_hash($this->password, PASSWORD_DEFAULT);
        
        $dbh = connect();
        $sql = "INSERT INTO 
                `users`(`name`, `pass`) 
                VALUES (:username, :pass)";
        $dbh->beginTransaction();
        try{
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":username", $name, PDO::PARAM_STR);
            $stmt->bindValue(":pass", $password, PDO::PARAM_STR);
            $stmt->execute();
            $dbh->commit();

            $sql = "SELECT * FROM users ORDER BY id DESC LIMIT 1";
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user;

            // header("location: ../index.php");
            // exit;
        }catch(PDOExceptipn $e){
            $dbh->rollBack();
            die("ユーザー情報の登録に失敗しました ".$e->error);
        }
    }

    public function login(){
        $name = $this->name;
        // $password = $this->password;

        $dbh = connect();
        $sql = "SELECT * FROM `users` where name = :username";
        try{
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':username', $name, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            return $user;
        }catch(PDO_Exceptipn $e){
            $dbh->rollBack();
            die("ユーザー情報の登録に失敗しました ".$e->error);
        }
    }
}
?>