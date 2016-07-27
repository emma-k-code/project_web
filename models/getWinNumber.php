<?php
header("content-type: text/html; charset=utf-8");

class getWinNumber {
  function searchData($dateSelect,$db,$aPrizeItems){
    
    // 依期別查詢winningNumbers表中的中獎號碼
    $sql = "select winPrize,winNumber from winningNumbers where winDate = :date ";
    $result = $db->prepare($sql);
    $result->bindParam("date",$dateSelect);
    $result->execute();
  
    if ( $result->rowCount() == 0) {
      // 結束連線
      $db = null;
      return $showData=null;
    }
    
    $showData = $aPrizeItems; // 準備輸出的資料
    
    // 處理查詢結果
    while ($row = $result->fetch())
    {
      $showData[$row['winPrize']][] = $row['winNumber'];
    }
    
    // 結束連線
    $db = null;
    
    return $showData;
  }
  
  function output($showData,$aPrizeMoney) {
        
      if (!isset($showData)){
          return "尚無資料";
      }

      $winTable = ""; // 以html格式輸出
      
      foreach ($showData as $value) {
          $winTable .= "<tr>";
          $winTable .= "<th>" . array_search($value,$showData) . "</th>";
          $winTable .= "<td>";
          foreach ($value as $num) {
            $winTable .= $num . "<br>";
          }
          $winTable .= "</td>";
          $winTable .= "<td>" . $aPrizeMoney[array_search($value,$showData)] . "</td>";
          $winTable .= "</tr>";
      }
      
      return $winTable;
        
  }

}
    
?>
