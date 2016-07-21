<?php
    header("content-type: text/html; charset=utf-8");
    class getMemberNumberCount {
        function searchCount($db,$dateSelect,$userEmail){
          
          // 搜尋membersNumbers中的資料筆數
          if ($dateSelect=="全部") {
            $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail'");
          }elseif ($dateSelect=="中獎發票") {
            $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail' AND (mResult = '特別獎' OR mResult = '特獎' OR mResult = '頭獎' OR mResult = '二獎' OR mResult = '三獎' OR mResult = '四獎' OR mResult = '五獎' OR mResult = '六獎' OR mResult = '增開六獎')");
          }else {
            $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail' AND mDate = '$dateSelect' ");
          }
          
          echo $result->rowCount();
          
          // 結束連線
          $db = null;
          
      }
    }
    
      
    
    
?>
