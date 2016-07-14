<?php

    header("content-type: text/html; charset=utf-8");
    
    // 判斷是否已登入
    if (isset($_COOKIE['userName']) & isset($_COOKIE['member'])) {
        // 檢查帳密 
        checkMemberEmail($_COOKIE['userName'],$_COOKIE['member']);
    }
    
    function checkMemberEmail($userName,$member){
        // 取得資料庫設定
        require_once "config.php";
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        // 搜尋members資料庫中的資料
        $result = $db->query("select memberEmail,memberPW from members where memberName = '$userName'");
        
        // 處理查詢結果
        while ($row = $result->fetch()) {
            
            $check = MD5($row['memberEmail']).MD5($row['memberPW']);
            
            if ($member == $check) {
                $userEmail = $row['memberEmail'];
                saveNumber($userEmail);
                
                $db = null;
                return;
            }
        }
        
        $db = null;
    }
    
    function saveNumber($userEmail) {
        
        $addData = json_decode($_GET['data']);
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        // 將資料寫入membersNumbers資料庫
        $sql = 'INSERT INTO winningNumbers(winDate,winPrize,winNumber) VALUES (:data,:prize,:number);';
        $sth = $db->prepare($sql);
        
        foreach ($insertData as $key=>$pNumbers)
        {
            foreach ($pNumbers as $num)
            {
    	        $sth->bindParam(':data',$invoiceDate);
                $sth->bindParam(':prize',$key);
                $sth->bindParam(':number',$num);
                $sth->execute();
            }
        }
        
        // 4. 結束連線
        $db = null;
        
        echo var_dump($addNumber);
    }
    
    
    
    
    
    
    
?>