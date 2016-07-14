<?php
header("content-type: text/html; charset=utf-8");

$email = $_POST['email'];
$password = $_POST["password"];

// 取得資料庫設定
require "config.php";

// 1. 連接資料庫伺服器
$db = new PDO($dbConnect, $dbUser, $dbPw);
$db->exec("set names utf8");

// 搜尋並比對資料庫中的會員資料
$result = $db->query("select * from members where memberEmail = '$email' AND memberPW = MD5('$password')");

// 如果搜尋結果為0
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
  $userPw = $row['memberPW'];
}

// 會員名稱
setcookie("userName",$userName);

// 會員資料 進行加密
$member = MD5($email).MD5($userPw);
setcookie("member",$member);

// 4. 結束連線
$db = null;

exit;

?>