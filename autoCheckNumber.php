<?php
header("content-type: text/html; charset=utf-8");

// 取得會員的Email
require "getMemberEmail.php";

// 檢查帳密 取得email
$checkResult = new cGetMemberEmail();
$userEmail = $checkResult->checkMemberEmail();
if (isset($userEmail)) {
    $showData = auotCheck($userEmail);
}

function auotCheck($userEmail) {
    // 取得未對獎的發票號碼與期別
    require "getNoCheckNumber.php";
    // 進行對獎
    require "checkNumber.php";
    // 將對獎結果更新置資料庫
    require "updateMemberNumber.php";

    $result = new cGetNoCheckNumber();
    $noCheckNumber = $result->check($userEmail); // 尚未對獎的發票
    $checkResult = new cCheckNumber();
    $updateData = new cUpdateMemberNumber();
    
    foreach ($noCheckNumber as $key=>$value) {
        $checkedData = $checkResult->check(explode(",",$value),$key); // 取得對獎結果
        if ($checkedData!="") {
            // 進行資料庫資料更新
            $updateData->update($userEmail,$checkedData);
            $showData[] = $checkedData;
        }
        
    }
    
    return $showData;
}

?>