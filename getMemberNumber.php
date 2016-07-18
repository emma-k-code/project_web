<?php
    header("content-type: text/html; charset=utf-8");

    class cMemberNumber {
      public $showData;
      
      function searchData($dateSelect,$userEmail,$pageSelect){
        // 取得資料庫設定
        require "config.php";
        // 取得獎金設定
        require "prizeMoney.php";
        
        $limit = $pageSelect * 10;
        
        $start = $limit - 9 ;
        
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
          $result = $db->query("select mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' ORDER BY mDate LIMIT $start, $limit");
        }else {
          $result = $db->query("select mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' AND mDate = '$dateSelect' LIMIT $start, $limit");
        }
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return $showData=null;
        }

        // 3. 處理查詢結果
        while ($row = $result->fetch())
        {
          if ($row['mResult']=="未中獎") {
              $mMoney = "0";
          }elseif($row['mResult']=="未開獎") {
              $mMoney = "";
          }else{
              $mMoney = $aPrizeMoney[$row['mResult']];
          }
          $showData[] = array("mDate"=>$row['mDate'],"mNumber"=>$row['mNumber'],"mResult"=>$row['mResult'],"money"=>$mMoney);
        }
        
        // 4. 結束連線
        $db = null;
        
        return $showData;
      }
      
    }
    
?>
