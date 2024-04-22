<?php
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}

require_once("connection.php");
class Category{
    private $name;

    public function __construct($name){
        $this->name = $name;
    }

    public static function createCategory(){
        return new self("sample");
    }

    public function store(){
        $name = $this->name;


        $dbh = connect();
        $sql = "INSERT INTO 
                `categories`(`name`) 
                VALUES (:category_name)";

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":category_name", $name, PDO::PARAM_STR);
            $stmt->execute();

            header("location: ../index.php");
            // exit;
        }catch(PDOException $e){
            exit($e);
        }
    }

    public function index(){
        $dbh = connect();
        $sql = "SELECT * FROM `categories`";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

    public function delete($id){
        $dbh = connect();
        $sql = "DELETE FROM `categories` WHERE id = $id";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        // $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        header("location: ../category.php");
    }

    public function getCategory($id){
        $dbh = connect();
        $sql = "SELECT * FROM `categories` where id = $id";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>