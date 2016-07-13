<?php
header("content-type: text/html; charset=utf-8");

// 選擇的期別
$dateSelect =  trim($_GET['date']); 
// 取得獎金設定
require 'getPrizeMoney.php';
// 取得開獎號碼
require 'getWinNumber.php';

// 取得搜尋結果
$getWinNum = new cWinNumber();
$showData = $getWinNum->searchData($dateSelect);

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
    echo "<td>" . $money[array_search($value,$showData)] . "</td>";
    echo "</tr>";
}

?>
