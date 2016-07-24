<?php

class config {
    public $db;
    function getDB() {
        $dbConnect = "mysql:host=localhost;dbname=project;port=443";
        $dbUser = "root";
        $dbPw = "";
        
        // 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        return $db;
    }
    
    function __destruct() {
        // 關閉資料庫
        $db = null;
    }
}

?>