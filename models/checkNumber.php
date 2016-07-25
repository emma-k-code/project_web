<?php
header("content-type: text/html; charset=utf-8");

class checkNumber {
     /* $dateSelect->選擇的期別 $number->輸入的號碼
        $prizeMoney->獎金設定 $db->資料庫連線 */
    function check($db,$number,$dateSelect,$aPrizeMoney) {
        
        // 將號碼依,分開
        $enterNumber = explode(",",$number);
        
        // 搜尋winningNumbers中期別為$dateSelect的資料
        $result = $db->query("select winPrize,winNumber from winningNumbers where winDate = '$dateSelect'");
        
        // 搜尋結果為0直接結束function
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return;
        }
        
        // 將資料寫入$showData陣列 預設為未中獎
        foreach ($enterNumber as $num) {
            // 判斷格式是否為數字 & 大於等於3碼 & 小於等於8碼
            if (is_numeric($num) & strlen($num)>=3 & strlen($num)<=8) {
                $showData[] = array("prize"=>"未中獎","number"=>$num,"numDate"=>$dateSelect,"money"=>"0");
            }
        }
        
        // 處理查詢結果 從大獎比到小獎
        while ($row = $result->fetch()) {
            
            // 要比對的資料
            foreach ($showData as $key=>$value) {
                // 將號碼取號碼長度~3的長度依序比對
                for ($i = strlen($showData[$key]["number"]); $i >=3 ; $i--){
                    if ($showData[$key]["prize"]=="未中獎") {
                        // 如果號碼完全相等
                        if (strcmp(substr($showData[$key]["number"],-$i),"{$row["winNumber"]}") == 0) {
                            $showData[$key]["prize"] = $row["winPrize"];
                            $showData[$key]["money"] = $aPrizeMoney[$row["winPrize"]];
                        }
                    }
                }
                
            }
        }
          
        // 結束連線
        $db = null;
        return $showData;
        
    }
}

?>