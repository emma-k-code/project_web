<?php
    header("content-type: text/html; charset=utf-8");
    
    // 取得會員的Email
    require "getMemberEmail.php";
    
    // 選擇的期別
    $dateSelect =  trim($_GET['date']); 
    
    // 檢查帳密 取得email
    $checkResult = new cGetMemberEmail();
    $userEmail = $checkResult->checkMemberEmail();
    
    // 如果有email
    if (isset($userEmail)) {
        searchMemberNumberCount($dateSelect,$userEmail);
    }

      
    function searchMemberNumberCount($dateSelect,$userEmail){
        // 取得資料庫設定
        require "config.php";
        
        // 搜尋membersNumbers中的資料筆數
        if ($dateSelect=="全部") {
          $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail'");
        }else {
          $result = $db->query("select * from membersNumbers where memberEmail = '$userEmail' AND mDate = '$dateSelect' ");
        }
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          echo 0;
        }
        
        // 4. 結束連線
        $db = null;
        
        $page = floor($result->rowCount() / 10) + 1;
        
        echo $page;
    }
      
    
    
?>
