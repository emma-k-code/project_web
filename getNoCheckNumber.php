<?php
header("content-type: text/html; charset=utf-8");

class cGetNoCheckNumber {
    function check($email) {
        // 取得資料庫設定
        require "config.php";
        
        // 2. 執行 SQL 敘述
        $result = $db->query("select mDate,mNumber from membersNumbers where mResult = '未開獎' AND memberEmail = '$email' ");
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return;
        }
      
        // 3. 處理查詢結果
        while ($row = $result->fetch()) {
            if (!isset($noCheckNumber[$row["mDate"]])) {
                $noCheckNumber[$row["mDate"]] = $row["mNumber"];
            }else {
                $noCheckNumber[$row["mDate"]] .=  "," .$row["mNumber"];
            }
        }
        // 4. 結束連線
        $db = null;
        
        return $noCheckNumber;
        
    }
}
?>