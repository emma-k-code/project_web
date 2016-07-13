<?php
header("content-type: text/html; charset=utf-8");

class cCheckNumber {
    function check($enterNumber,$dateSelect) {
        // 取得資料庫設定
        require "config.php";
        // 取得獎金設定
        require 'getPrizeMoney.php';
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        // 2. 執行 SQL 敘述
        $result = $db->query("select winPrize,winNumber from winningNumbers where winDate = '$dateSelect'");
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          exit;
        }
        
        global $showData;
        
        // 將資料寫入$showData陣列 全預設為未中獎
        foreach ($enterNumber as $num) {
            if (strlen($num)>=3) {
                $showData[$num] = array('date'=>$dateSelect,'prize'=>"未中獎",'money'=>"0");
            }
        }
        
        // 3. 處理查詢結果
        while ($row = $result->fetch()) {
            
            foreach ($showData as $key=>$value) {
                if ($key == $row['winNumber']) {
                    $showData[$key]['prize'] = $row['winPrize'];
                    $showData[$key]['money'] = $money[$row['winPrize']];
                }
            }
        }
          
        // 4. 結束連線
        $db = null;
        
        return json_encode($showData);
        
    }
}






?>