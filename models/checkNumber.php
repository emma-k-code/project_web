<?php
header("content-type: text/html; charset=utf-8");

class checkNumber {
    function check($db,$number,$dateSelect,$aPrizeMoney) {
        
        $enterNumber = explode(",",$number);
        
        // 執行 SQL 敘述
        $result = $db->query("select winPrize,winNumber from winningNumbers where winDate = '$dateSelect'");
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
          return;
        }
        
        // 將資料寫入$showData陣列 
        foreach ($enterNumber as $num) {
            // 判斷格式是否為數字 & 大於等於3碼 & 小於等於8碼
            if (is_numeric($num) & strlen($num)>=3 & strlen($num)<=8) {
                $showData[] = array("number"=>$num,"numDate"=>$dateSelect,"prize"=>"未中獎","money"=>"0");
            }
        }
        
        // 3. 處理查詢結果
        while ($row = $result->fetch()) {
            
            // 要比對的資料
            foreach ($showData as $key=>$value) {
                // 將號碼取號碼長度~3的長度依序比對
                for ($i = strlen($showData[$key]["number"]); $i >=3 ; $i--){
                    if ($showData[$key]["prize"]=="未中獎") {
                        if (substr($showData[$key]["number"],-$i) == $row["winNumber"]) {
                            $showData[$key]["prize"] = $row["winPrize"];
                            $showData[$key]["money"] = $aPrizeMoney[$row["winPrize"]];
                        }
                    }
                }
            }
        }
          
        // 4. 結束連線
        $db = null;
        
        return $showData;
        
    }
}

?>