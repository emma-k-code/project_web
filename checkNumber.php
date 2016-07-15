<?php
header("content-type: text/html; charset=utf-8");

class cCheckNumber {
    function check($enterNumber,$dateSelect) {
        // 取得資料庫設定
        require "config.php";
        // 取得獎金設定
        require "getPrizeMoney.php";
        
        // 1. 連接資料庫伺服器
        $db = new PDO($dbConnect, $dbUser, $dbPw);
        $db->exec("set names utf8");
        
        // 2. 執行 SQL 敘述
        $result = $db->query("select winPrize,winNumber from winningNumbers where winDate = '$dateSelect'");
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return;
        }
        
        // 將資料寫入$showData陣列 
        foreach ($enterNumber as $num) {
            if (is_numeric($num) & strlen($num)>=3 & strlen($num)<=8) {
                $showData[] = array("number"=>$num,"numDate"=>$dateSelect,"prize"=>"未中獎","money"=>"0");
            }
        }
        
        // 3. 處理查詢結果
        while ($row = $result->fetch()) {
            
            foreach ($showData as $key=>$value) {
                if ($showData[$key]["number"] == $row["winNumber"]) {
                    $showData[$key]["prize"] = $row["winPrize"];
                    $showData[$key]["money"] = $money[$row["winPrize"]];
                }
            }
        }
          
        // 4. 結束連線
        $db = null;
        
        return $showData;
        
    }
}

?>