<?php
    header("content-type: text/html; charset=utf-8");

    class cWinNumber {
      public $showData;
      
      function searchData($dateSelect){
        // 取得資料庫設定
        require "config.php";
        // 取得獎別設定
        require "prizeItems.php";
    
        // 2. 執行 SQL 敘述
        $result = $db->query("select winPrize,winNumber from winningNumbers where winDate = '$dateSelect'");
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return $showData=null;
        }
        
        $showData = $aPrizeItems; // 準備輸出的資料

        // 3. 處理查詢結果
        while ($row = $result->fetch())
        {
          $showData[$row['winPrize']][] = $row['winNumber'];
        }
        
        // 4. 結束連線
        $db = null;
        
        return $showData;
      }
      
    }
    
?>
