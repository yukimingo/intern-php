<?php
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}

require_once("connection.php");
class Template{
    private $title;
    private $sqlStatement;

    public function __construct($title, $sqlStatement){
        $this->title = $title;
        $this->sqlStatement = $sqlStatement;
    }

    public function store(){
        $title = $this->title;
        $sqlStatement = $this->sqlStatement;


        $dbh = connect();
        $sql = "INSERT INTO 
                `templates`(`title`, `sql_statement`) 
                VALUES (:title, :sql_statement)";

        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":title", $title, PDO::PARAM_STR);
            $stmt->bindValue(":sql_statement", $sqlStatement, PDO::PARAM_STR);
            $stmt->execute();

            header("location: ../index.php");
            // exit;
        }catch(PDOException $e){
            exit($e);
        }
    }

    public function index(){
        $dbh = connect();
        $sql = "SELECT * FROM `templates`";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>