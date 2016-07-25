<?php
    header("content-type: text/html; charset=utf-8");

    class getMemberNumber {
      public $showData;
      
      function searchData($db,$dateSelect,$userEmail,$pageSelect,$aPrizeMoney){
        
        $limit = 10;
        $start = ($pageSelect * 10)  - $limit ;
        
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
          $result = $db->query("select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' ORDER BY substring(mDate,1,3) DESC,substring(mDate,5,2) DESC LIMIT $start, $limit");
        }elseif ($dateSelect=="中獎發票") {
          $result = $db->query("select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' AND (mResult = '特別獎' OR mResult = '特獎' OR mResult = '頭獎' OR mResult = '二獎' OR mResult = '三獎' OR mResult = '四獎' OR mResult = '五獎' OR mResult = '六獎' OR mResult = '增開六獎') ORDER BY substring(mDate,1,3) DESC,substring(mDate,5,2) DESC LIMIT $start, $limit");
        }else{
          $result = $db->query("select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = '$userEmail' AND mDate = '$dateSelect' LIMIT $start, $limit");
        }
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return "尚無資料";
        }

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
          $showData[] = array("id"=>$row['mNumID'],"mDate"=>$row['mDate'],"mNumber"=>$row['mNumber'],"mResult"=>$row['mResult'],"money"=>$mMoney);
        }
        
        // 結束連線
        $db = null;
        return json_encode($showData);
      }
      
    }
    
?>
