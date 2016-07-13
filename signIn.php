<?php
header("content-type: text/html; charset=utf-8");

$email = $_POST['email'];
$password = $_POST["password"];

// 取得資料庫設定
require "config.php";

// 1. 連接資料庫伺服器
$db = new PDO($dbConnect, $dbUser, $dbPw);
$db->exec("set names utf8");

// 2. 執行 SQL 敘述
$result = $db->query("select * from members where memberEmail = '$email' AND memberPW = '$password'");

if ( $result->rowCount() == 0) {
  // 結束連線
  $db = null;
  echo "notFound";
  exit;
}

// 3. 處理查詢結果
while ($row = $result->fetch())
{
  $userName = $row['memberName'];
}

setcookie("userName",$userName);

// 4. 結束連線
$db = null;

exit;

?>