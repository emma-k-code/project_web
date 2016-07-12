<?php
header("content-type: text/html; charset=utf-8");

$enterNumber = explode(",",$_GET['number']); 
$dateSelect = $_GET['date']; 

// 取得資料庫設定
require "config.php";
// 取得獎金設定
require 'getPrizeMoney.php';

// 1. 連接資料庫伺服器
$db = new PDO($dbConnect, $dbUser, $dbPw);
$db->exec("set names utf8");

// 2. 執行 SQL 敘述
$result = $db->query("select winPrize,winNumber from winningNumbers where winDate = '$dateSelect'"); // 依中獎號碼數量排序

if ( $result->rowCount() == 0) {
  // 結束連線
  $db = null;
  exit;
}

// 將資料寫入$showPeriod陣列 全預設為未中獎
foreach ($enterNumber as $num) {
    if (strlen($num)>=3) {
        $showPeriod[$num] = array('prize'=>"未中獎",'money'=>"0");
    }
}

// 3. 處理查詢結果
while ($row = $result->fetch()) {
    
    foreach ($showPeriod as $key=>$value) {
        if ($key == $row['winNumber']) {
            $showPeriod[$key]['prize'] = $row['winPrize'];
            $showPeriod[$key]['money'] = $money[$row['winPrize']];
        }
    }
}
  
// 4. 結束連線
$db = null;
foreach ($showPeriod as $key=>$value) {
    echo "<tr>";
    echo "<th>" . $dateSelect . "</th>";
    echo "<td>" . $key . "</td>";
    echo "<td>" . $value['prize'] . "</td>";
    echo "<td>" . $value['money'] . "</td>";
    echo "</tr>";
}
?>