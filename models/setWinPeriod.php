<?php
header("content-type: text/html; charset=utf-8");

class setWinPeriod {
    function searchData($db,$dateSelect) {
        // 執行 SQL 敘述
        $result = $db->query("select winPs from winningPeriod where winDate = '$dateSelect'");
        
        // 處理查詢結果
        while ($row = $result->fetch())
        {
          $showPeriod = $row['winPs'];
        }
        
        // 結束連線
        $db = null;
        
        return $showPeriod;
    }
}

?>
