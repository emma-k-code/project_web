<?php
    header("content-type: text/html; charset=utf-8");
    class getMemberNumberCount {
        function searchCount($db,$dateSelect,$userEmail){
          
          // 搜尋membersNumbers中的資料筆數
          if ($dateSelect=="全部") {
            $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail'");
          }else {
            $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail' AND mDate = '$dateSelect' ");
          }
          
          echo $result->rowCount();
          
          // 4. 結束連線
          $db = null;
          
      }
    }
    
      
    
    
?>
