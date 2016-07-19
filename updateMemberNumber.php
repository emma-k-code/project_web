<?php

    header("content-type: text/html; charset=utf-8");
    
    class updateMemberNumber {
        function update($db,$email,$checkedData) {
            
            // 更新membersNumbers資料庫的資料
            $sql = "UPDATE membersNumbers SET mResult=(:prize) WHERE memberEmail = '$email' AND mDate=(':date') AND mNumber=(:number);";
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