<?php
class aboutMember {
    
    function getMemberEmail($db,$userName,$member){
    
        // 搜尋members資料庫中的資料
        $sql = "select memberEmail,memberPW from members where memberName = :username";
        $result = $db->prepare($sql);
        $result->bindParam("username",$userName);
        $result->execute();
        
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