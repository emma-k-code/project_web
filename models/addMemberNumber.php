<?php
    header("content-type: text/html; charset=utf-8");
    
    class addMemberNumber {
        
        function saveNumber($db,$userEmail,$date,$number,$prize) {
            
            // 將資料寫入membersNumbers資料庫
            $sql = "INSERT INTO membersNumbers(mDate,mNumber,mResult,memberEmail) VALUES (:date,:number,:prize,:mail)";
            $sth = $db->prepare($sql);
            
            $sth->bindParam(':date',$date);
            $sth->bindParam(':number',$number);
            $sth->bindParam(':prize',$prize);
            $sth->bindParam(':mail',$userEmail);
            $sth->execute();
            
            // 結束連線
            $db = null;
        }
        
    }

    
?>