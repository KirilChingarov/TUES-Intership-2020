<?php
    namespace Model\Repository;

    use PDO, PDOException;
    require("config.php");

    class DBManager{
        private $conn;
        private static $instance = null;

        private function __construct(){
            try{
                $this->conn = new PDO("mysql:host=" . DB_HOST . 
                ";dbname=" . DB_NAME, DB_USER, DB_PASS,
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            } catch (PDOException $e){
                echo $e->getMessage() . "<br>";
            }
        }

        public static function getInstance(){
            if(!self::$instance){
                self::$instance = new self();
            }
            return self::$instance;
        }

        private function __clone() {}

        public function getConnection(){
            return $this->conn;
        }
    }
?>