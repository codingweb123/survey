<?php
namespace Database;
class Database {
    private static $db;
    public function __construct()
    {
        global $config;
        $this->__init($config["DB_HOST"],$config["DB_USER"],$config["DB_PASS"],$config["DB_NAME"]);
    }
    public function __init($host,$user,$password,$name){
        $conn = mysqli_connect($host,$user,$password,$name);
        if ($conn){
            mysqli_set_charset($conn, 'utf8');
            self::$db = $conn;
            return $conn;
        }else
            die("DB: Error!(Connect)");
    }
    public static function query($ql, $assoc = false){
        $query = mysqli_query(self::$db, $ql);
        if ($query)
            return $assoc ? mysqli_fetch_assoc($query) : $query;
        else
            die("DB: Error!(Query)Query: $ql;Error: ".self::$db->error);
    }
}