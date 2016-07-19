<?php
header("content-type: text/html; charset=utf-8");

class setWinNumber {
    function output($showData,$prizeItems) {
        
        if (!isset($showData)){
            echo "尚無資料";
            return;
        }
        
        // 輸出資料
        foreach ($showData as $value) {
            echo "<tr>";
            echo "<th>" . array_search($value,$showData) . "</th>";
            echo "<td>";
            foreach ($value as $num) {
              echo $num . "<br>";
            }
            echo "</td>";
            echo "<td>" . $aPrizeMoney[array_search($value,$showData)] . "</td>";
            echo "</tr>";
        }
    }
}
?>
