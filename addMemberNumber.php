<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

    header("content-type: text/html; charset=utf-8");
    
    // 判斷是否已登入
    if (isset($_SESSION['userName']) & isset($_SESSION['member'])) {
        
        // 取得會員的Email
        require "getMemberEmail.php";
        
        // 檢查帳密 取得email
        $checkResult = new cGetMemberEmail();
        $userEmail = $checkResult->checkMemberEmail();
        
        if (isset($userEmail)) {
            saveNumber($userEmail,$_POST['numDate'],$_POST['number'],$_POST['prize']);
        }
        
    }
    
    function saveNumber($userEmail,$date,$number,$prize) {
        // 取得資料庫設定
        require "config.php";
        
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