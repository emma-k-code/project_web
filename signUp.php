<?php
header("content-type: text/html; charset=utf-8");

class signUp {
  function insertMember($db,$userName,$email,$password) {
    
    // 搜尋資料庫中email是否已經存在
    $result = $db->query("select * from members where memberEmail = '$email'");
    
    // 如果email已存在
    if ( $result->rowCount() != 0) {
      // 結束連線
      $db = null;
      echo "exist";
      exit;
    }
    
    // 將資料寫入members資料庫
    $sql = "INSERT INTO members(memberName,memberPW,memberEmail) VALUES ('$userName',MD5('$password'),'$email');";
    $sth = $db->prepare($sql);
    $sth->execute();
    
    // 4. 結束連線
    $db = null;
  }
}

?>