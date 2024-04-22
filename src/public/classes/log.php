<?php
if(!isset($_SESSION["id"])){
    header("location: ../login.php");
    exit;
}

require_once("connection.php");
class Log{
    private $categoryId;
    // private $templateId;
    private $ticketNumber;
    private $title;
    private $sqlStatement;
    
    public function __construct($categoryId, $ticketNumber, $title, $sqlStatement){
        $this->categoryId = $categoryId;
        // $this->templateId = $templateId;
        $this->ticketNumber = $ticketNumber;
        $this->title = $title;
        $this->sqlStatement = $sqlStatement;
    }

    // コンストラクタ実行したくない場合に　静的ファクトリメソッド
    public static function createLog(){
        return new self(1, 1, "sample", "sample");
    }

    public function store(){
        $categoryId = $this->categoryId;
        $ticketNumber = $this->ticketNumber;
        $title = $this->title;
        $sqlStatement = $this->sqlStatement;
        // $templateId = $this->templateId;

        $dbh = connect();
        $sql = "INSERT INTO 
                `logs`(`category_id`, `ticket_number`, `title`, `sql_statement`) 
                VALUES (:category_id, :ticket_number, :title, :sql_statement)";
        $dbh->beginTransaction();
        try {
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(":category_id", $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(":ticket_number", $ticketNumber, PDO::PARAM_INT);
            $stmt->bindValue(":title", $title, PDO::PARAM_STR);
            $stmt->bindValue(":sql_statement", $sqlStatement, PDO::PARAM_STR);
            $stmt->execute();
            $dbh->commit();

            // header("location: ../index.php");
            // exit;
        }catch(PDOException $e){
            $dbh->rollBack();
            exit($e);
        }
    }

    public function index(){
        $dbh = connect();
        $sql = "SELECT * FROM `logs`
                    INNER JOIN categories
                    ON logs.category_id = categories.id ORDER BY logs.id DESC";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

    public function search($request){
        $dbh = connect();
        $sql = "SELECT * FROM `logs`
                    INNER JOIN categories
                    ON logs.category_id = categories.id where ticket_number like '%{$request}%' or title like '%{$request}%' or categories.name like '%{$request}%' ORDER BY logs.id DESC";
        $stmt = $dbh->query($sql);
        $result = $stmt->fetchall(PDO::FETCH_ASSOC);

        return $result;
    }

    public function distinguishSql($sqlStatement){
        $patternSelect = "/^select/i";
        $patternInsert = "/^insert/i";
        $patternUpdate = "/^update/i";
        $patternDelete = "/^delete/i";

        // select文か
        // $whichSql = strstr($sqlStatement, 'SELECT');
        // $position = strpos($sqlStatement, 'SELECT');
        if (preg_match($patternSelect, $sqlStatement)){
            $result = "select";

            return $result;
        }
        // insert文か
        if (preg_match($patternInsert, $sqlStatement)){
            $result = "insert";

            return $result;
        }
        // update文か
        if (preg_match($patternUpdate, $sqlStatement)){
            $result = "update";

            return $result;
        }
        // delete文か
        if (preg_match($patternDelete, $sqlStatement)){
            $result = "delete";

            return $result;
        }
    }

    // goodsテーブルは開発段階で試験的に使ったテーブル、本番とは関係ない
    public function getGoods($id){
        $dbh = connect();
        $sql = "SELECT * FROM goods WHERE id = $id";
        try{
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchall(PDO::FETCH_ASSOC);

            return $result;
        }catch(PDOException $e){
            exit($e);
        }
        
    }

    public function executeSelect($sqlStatement){
        $dbh = connect();
        $stmt = $dbh->prepare($sqlStatement);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $id = $result["id"];
    
        header("location: ../index.php?id=$id");
        exit();
    }

    public function executeInsert($sqlStatement){
        $dbh = connect();
        $stmt = $dbh->prepare($sqlStatement);
        $stmt->execute();
        $result = $this->getLatest();
        $id = $result["id"];
        $successMesssage = "データの保存に成功しました";
    
        header("location: ../index.php?id=$id&successMessage=$successMesssage");
        exit();
    }

    public function executeUpdate($sqlStatement){
        $dbh = connect();
        $stmt = $dbh->prepare($sqlStatement);
        $stmt->execute();
        $successMesssage = "データの更新に成功しました";
    
        header("location: ../index.php?id=$id&successMessage=$successMesssage");
        exit();
    }

    public function executeDelete($sqlStatement){
        $dbh = connect();
        $stmt = $dbh->prepare($sqlStatement);
        $stmt->execute();
        $successMesssage = "データの削除に成功しました";
    
        header("location: ../index.php?successMessage=$successMesssage");
        exit();
    }

    private function getLatest(){
        $dbh = connect();
        $sql = "SELECT * FROM goods ORDER BY id DESC LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }

    public function getLatestTicletNumber(){
        $dbh = connect();
        $sql = "SELECT * FROM logs ORDER BY ticket_number DESC LIMIT 1";
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}
?>