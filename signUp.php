<?php
header("content-type: text/html; charset=utf-8");

$userName = $_POST['userName'];
$email = $_POST["email"];
$password = $_POST["password"];

// 取得資料庫設定
require "config.php";

// 1. 連接資料庫伺服器
$db = new PDO($dbConnect, $dbUser, $dbPw);
$db->exec("set names utf8");

// 將資料寫入members資料庫
$sql = "INSERT INTO members(memberName,memberPW,memberEmail) VALUES ('$userName','$password','$email');";
$sth = $db->prepare($sql);
$sth->execute();

// 4. 結束連線
$db = null;

exit;

?>