<?php
header("content-type: text/html; charset=utf-8");

// 選擇的期別
$dateSelect = $_GET['date']; 

// 取得獎金設定
require 'getPrizeMoney.php';
// 取得開獎號碼
require 'getWinNumber.php';
// 取得資料庫設定
require "config.php";
// 取得獎別設定
require "prizeItems.php";

// 1. 連接資料庫伺服器
$db = new PDO($dbConnect, $dbUser, $dbPw);
$db->exec("set names utf8");

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
