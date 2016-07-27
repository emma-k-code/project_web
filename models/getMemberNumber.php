<?php
class getMemberNumber {
  public $showData;
  // 回傳會員發票號碼查詢結果 (json)
  /* $dateSelect->選擇的期別 $pageSelect->選擇的頁次 $prizeMoney->獎金設定
    $userEmail->會員的email $db->資料庫連線 */
  function searchData($db,$dateSelect,$userEmail,$pageSelect,$aPrizeMoney){
    
      // 一頁10筆
      $limit = 10;
      $start = ($pageSelect * 10)  - $limit ;
      
      // 搜尋membersNumbers中的資料
      if ($dateSelect=="全部") {
        $sql = "select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = :email ORDER BY substring(mDate,1,3) DESC,substring(mDate,5,2) DESC LIMIT $start, $limit";
        $result = $db->prepare($sql);
      }elseif ($dateSelect=="中獎發票") {
        $sql = "select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = :email AND (mResult = '特別獎' OR mResult = '特獎' OR mResult = '頭獎' OR mResult = '二獎' OR mResult = '三獎' OR mResult = '四獎' OR mResult = '五獎' OR mResult = '六獎' OR mResult = '增開六獎') ORDER BY substring(mDate,1,3) DESC,substring(mDate,5,2) DESC LIMIT $start, $limit";
        $result = $db->prepare($sql);
      }else{
        $sql = "select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = :email AND mDate = :date LIMIT $start, $limit";
        $result = $db->prepare($sql);
        $result->bindParam("date",$dateSelect);
      }
      
      $result->bindParam("email",$userEmail);
      $result->execute();
      
      // 搜尋結果為0
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
      return $showData;
  }
  
}

?>
