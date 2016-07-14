<?php

    header("content-type: text/html; charset=utf-8");
    
    // 判斷是否已登入
    if (isset($_COOKIE['userName']) & isset($_COOKIE['member'])) {
        
        // 取得資料庫設定
        require_once "config.php";
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        // 檢查帳密 
        checkMemberEmail($_COOKIE['userName'],$_COOKIE['member'],$db);
    }
    
    function checkMemberEmail($userName,$member,$db){
        
        
        // 搜尋members資料庫中的資料
        $result = $db->query("select memberEmail,memberPW from members where memberName = '$userName'");
        
        // 處理查詢結果
        while ($row = $result->fetch()) {
            
            $check = MD5($row['memberEmail']).MD5($row['memberPW']);
            
            if ($member == $check) {
                $userEmail = $row['memberEmail'];
                saveNumber($userEmail,$db);
                
                $db = null;
                return;
            }
        }
        
        $db = null;
    }
    
    function saveNumber($userEmail,$db) {
        
        $date = $_POST['numDate'];
        $number = $_POST['number'];
        $prize = $_POST['prize'];
        
        // 將資料寫入membersNumbers資料庫
        $sql = "INSERT INTO membersNumbers(mDate,mNumber,mResult,memberEmail) VALUES (:date,:number,:prize,:mail)";
        $sth = $db->prepare($sql);
        
        $sth->bindParam(':date',$date);
        $sth->bindParam(':number',$number);
        $sth->bindParam(':prize',$prize);
        $sth->bindParam(':mail',$userEmail);
        $sth->execute();
        
        // 4. 結束連線
        $db = null;
    }
    
    
    
    
    
    
    
?>