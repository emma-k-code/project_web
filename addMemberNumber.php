<?php

    header("content-type: text/html; charset=utf-8");
 
    
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
                global $userEmail;
                $userEmail = $row['memberEmail'];
            }
        }
    }
    
    function saveNumber() {
        // 取得資料庫設定
        require_once "config.php";
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        // 將資料寫入winningPeriod資料庫
        $sql = "INSERT INTO winningPeriod(winDate,winPs) VALUES ('$invoiceDate','$invoicePs');";
        $sth = $db->prepare($sql);
        $sth->execute();
        
        // 將資料寫入winningNumbers資料庫
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
    }
    
    
    
    
    
    
    
?>