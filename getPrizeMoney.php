<?php
    // 取得獎金設定
    require_once "prizeMoney.php";
    
    $pMoney = new cPrizeMoney();
    $money = $pMoney->aPrizeMoney;
?>