<?php
header("content-type: text/html; charset=utf-8");

// 取得會員的Email
require "getMemberEmail.php";
// 取得未對獎的發票號碼與期別
require "getNoCheckNumber.php";
// 進行對獎
require "checkNumber.php";
// 將對獎結果更新置資料庫
require "updateMemberNumber.php";

// 檢查帳密 取得email
$checkResult = new cGetMemberEmail();
$userEmail = $checkResult->checkMemberEmail();

// 取得尚未對獎的發票
$result = new cGetNoCheckNumber();
$noCheckNumber = $result->check($userEmail); // 尚未對獎的發票

// 如果有未對獎的發票
if (isset($noCheckNumber)){
    $showData = auotCheck($noCheckNumber,$userEmail); // 要顯示的對獎結果
}

function auotCheck($noCheckNumber,$userEmail) {
    
    $checkResult = new cCheckNumber();
    $updateData = new cUpdateMemberNumber();
    
    foreach ($noCheckNumber as $key=>$value) {
        $checkedData = $checkResult->check(explode(",",$value),$key); // 取得對獎結果
        if ($checkedData!="") {
            // 進行資料庫資料更新
            $updateData->update($userEmail,$checkedData);
            // 要顯示的對獎結果
            $showData[] = $checkedData;
        }
        
    }
    
    return $showData;
}

?>