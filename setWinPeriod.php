<?php
header("content-type: text/html; charset=utf-8");

// 選擇的期別
$dateSelect = trim($_GET['date']); 

// 取得資料庫設定
require "config.php";

// 2. 執行 SQL 敘述
$result = $db->query("select winPs from winningPeriod where winDate = '$dateSelect'");

// 3. 處理查詢結果
while ($row = $result->fetch())
{
  $showPeriod = $row['winPs'];
}

// 4. 結束連線
$db = null;

echo $showPeriod;

?>
