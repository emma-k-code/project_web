<?php

    header("content-type: text/html; charset=utf-8");
    
    class updateMemberNumber {
        function update($db,$email,$checkedData) {
            
            // 更新membersNumbers資料庫的資料
            $sql = "UPDATE membersNumbers SET mResult=? WHERE memberEmail=? AND mDate=? AND mNumber=?";
            $sth = $db->prepare($sql);
            
            foreach ($checkedData as $value)
            {
                $sth->bindValue(1,$value["prize"]);
    	        $sth->bindValue(2,$email);
                $sth->bindValue(3,$value["numDate"]);
                $sth->bindValue(4,$value["number"]);
                $sth->execute();
            }
            
            // 4. 結束連線
            $db = null;
            
        }
    }

?>