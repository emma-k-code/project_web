<?php

header("content-type: text/html; charset=utf-8");

class cGetMemberEmail {
    
    private $userEmail;
    
    function checkMemberEmail(){
        
        // 取得資料庫設定
        require "config.php";
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        $userName = $_COOKIE['userName'];
        $member = $_COOKIE['member'];
    
        // 搜尋members資料庫中的資料
        $result = $db->query("select memberEmail,memberPW from members where memberName = '$userName'");
        
        // 處理查詢結果
        while ($row = $result->fetch()) {
            
            $check = MD5($row['memberEmail']).MD5($row['memberPW']);
            
            if ($member == $check) {
                $userEmail = $row['memberEmail'];
                $db = null;
                return $userEmail;
            }
        }
        $db = null;
        
    }
}
    
    
    
    
?>