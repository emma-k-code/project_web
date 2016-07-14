<?php

    header("content-type: text/html; charset=utf-8");
    
    // 判斷是否已登入
    if (isset($_COOKIE['userName']) & isset($_COOKIE['member'])) {
        
        // 取得會員的Email
        require "getMemberEmail.php";
        
        // 檢查帳密 取得email
        $checkResult = new cGetMemberEmail();
        $userEmail = $checkResult->checkMemberEmail();
        if (isset($userEmail)) {
            saveNumber($userEmail);
        }
        
    }
    
    function saveNumber($userEmail) {
        
        $date = $_POST['numDate'];
        $number = $_POST['number'];
        $prize = $_POST['prize'];
        // 取得資料庫設定
        require "config.php";
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
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