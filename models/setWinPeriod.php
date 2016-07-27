<?php
header("content-type: text/html; charset=utf-8");

class setWinPeriod {
    function searchData($db,$dateSelect) {
        
        // 依期別查詢winningPeriod表中的領獎期間
        $sql = "select winPs from winningPeriod where winDate = :date ";
        $result = $db->prepare($sql);
        $result->bindParam("date",$dateSelect);
        $result->execute();
        
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
