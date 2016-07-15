<?php
header("content-type: text/html; charset=utf-8");

// 取得會員的Email
require "getMemberEmail.php";

// 檢查帳密 取得email
$checkResult = new cGetMemberEmail();
$userEmail = $checkResult->checkMemberEmail();
if (isset($userEmail)) {
    // 取得未對獎的發票號碼與期別
    require "getNoCheckNumber.php";
    // 進行對獎
    require "checkNumber.php";
    
    $result = new cGetNoCheckNumber();
    $noCheckNumber = $result->check($userEmail); // 尚未對獎的發票
    
    $checkResult = new cCheckNumber();
    foreach ($noCheckNumber as $key=>$value) {
        $checkedData = $checkResult->check(explode(",",$value),$key);
        
        if ($checkedData!="") {
            // 進行資料庫資料更新
        }
    }

}

?>