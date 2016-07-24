<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
header("content-type: text/html; charset=utf-8");

class signIn {
  function check($db,$email,$password) {
    // 搜尋並比對資料庫中的會員資料
    $result = $db->query("select * from members where memberEmail = '$email' AND memberPW = MD5('$password')");
    
    // 如果搜尋結果為0
    if ( $result->rowCount() == 0) {
      $db = null;
      return "輸入帳號或密碼錯誤";
    }
    
    // 處理查詢結果
    while ($row = $result->fetch())
    {
      $user = array("username"=>$row['memberName'],"password"=>$row['memberPW']);
    }
    
    // 結束連線
    $db = null;
    
    // 會員資料 進行加密
    $member = MD5($email).MD5($user["password"]);
    
    // 存SESSION
    $_SESSION['userName'] = $user["username"];
    $_SESSION['member'] = $member;
    
    return "OK";
  }
}






?>