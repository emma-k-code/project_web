<?php
header("content-type: text/html; charset=utf-8");

// 選擇的期別
$dateSelect =  trim($_GET['date']); 
// 取得會員儲存之號碼
require 'getMemberNumber.php';
// 取得會員的Email
require "getMemberEmail.php";

// 檢查帳密 取得email
$checkResult = new cGetMemberEmail();
$userEmail = $checkResult->checkMemberEmail();
if (isset($userEmail)) {
    searchMemberNumber($dateSelect,$userEmail);
}

function searchMemberNumber($dateSelect,$userEmail) {
    // 取得搜尋結果
    $getMemberNum = new cMemberNumber();
    $showData = $getMemberNum->searchData($dateSelect,$userEmail);
    
    if (!isset($showData)){
        echo "尚無資料";
        return;
    }
    
    echo json_encode($showData);
}

?>
