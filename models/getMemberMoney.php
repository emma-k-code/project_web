<?php
    header("content-type: text/html; charset=utf-8");

    class getMemberMoney {
      
      function searchData($db,$dateSelect,$userEmail,$aPrizeMoney){
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
          $result = $db->query("select mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail'");
        }elseif ($dateSelect=="中獎發票") {
          $result = $db->query("select mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' AND (mResult = '特別獎' OR mResult = '特獎' OR mResult = '頭獎' OR mResult = '二獎' OR mResult = '三獎' OR mResult = '四獎' OR mResult = '五獎' OR mResult = '六獎' OR mResult = '增開六獎')");
        }else{
          $result = $db->query("select mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' AND mDate = '$dateSelect'");
        }
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return "0";
        }
        
        $moneys = "";
        
        // 處理查詢結果
        while ($row = $result->fetch())
        {
          if ($row['mResult']=="未中獎") {
              $mMoney = "0";
          }elseif($row['mResult']=="未開獎") {
              $mMoney = "";
          }else{
              $mMoney = $aPrizeMoney[$row['mResult']];
          }
          
          $moneys .= "-".$mMoney;
        }
        // 結束連線
        $db = null;
        
        return $moneys;
      }
      
    }
    
?>
