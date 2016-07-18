<?php
header("content-type: text/html; charset=utf-8");


function signIn($email,$password) {
  // 取得資料庫設定
  require "config.php";
  
  // 搜尋並比對資料庫中的會員資料
  $result = $db->query("select * from members where memberEmail = '$email' AND memberPW = MD5('$password')");
  
  // 如果搜尋結果為0
  if ( $result->rowCount() == 0) {
    $db = null;
    return;
  }
  
  // 3. 處理查詢結果
  while ($row = $result->fetch())
  {
    $user = array("username"=>$row['memberName'],"password"=>$row['memberPW']);
  }
  
  // 4. 結束連線
  $db = null;
  
  return $user;
  
}




?>