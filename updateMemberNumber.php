<?php

    header("content-type: text/html; charset=utf-8");
    
    class cUpdateMemberNumber {
        function update($email,$checkedData) {
            // 取得資料庫設定
            require "config.php";
            
            // 更新membersNumbers資料庫的資料
            $sql = "UPDATE membersNumbers SET mDate=(:date),mNumber=(:number),mResult=(:prize) WHERE memberEmail = '$email' AND mDate=(:date) AND mNumber=(:number);";
            $sth = $db->prepare($sql);
            
            foreach ($checkedData as $value)
            {
    	        $sth->bindParam(':date',$value["numDate"]);
                $sth->bindParam(':prize',$value["prize"]);
                $sth->bindParam(':number',$value["number"]);
                $sth->execute();
            }
            
            // 4. 結束連線
            $db = null;
            
        }
    }

?>