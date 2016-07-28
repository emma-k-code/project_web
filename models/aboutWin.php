<?php

class aboutWin {
    
    function getData() {
        // 期別固定設為四個
        $date[] = date("Y-m",strtotime($today."-5 month"));
        $date[] = date("Y-m",strtotime($today."-3 month"));
        $date[] = date("Y-m",strtotime($today."-1 month"));
        $date[] = date("Y-m");
        
        $dateYear = Array();
        $dateMonth = Array();
        
        foreach ($date as $value) {
            $dateYM = explode("-",$value);
            $dateYear = ($dateYM[0]-1911)."年";
            
            if ($dateYM[1]%2 == 0) {
                $dateMonth = substr($dateYM[1]-1+100,1,2) . "-" . $dateYM[1] . "月";
            }else {
                $dateMonth = $dateYM[1] . "-" . substr($dateYM[1]+1+100,1,2) . "月";
            }
            
            $setDate[] = array("dateYM"=>$dateYear . $dateMonth);
            
        }
        
        return $setDate;
    }
    
    function searchWinNumber($dateSelect,$db){
    
    // 依期別查詢winningNumbers表中的中獎號碼
    $sql = "select winPrize,winNumber from winningNumbers where winDate = :date ";
    $result = $db->prepare($sql);
    $result->bindParam("date",$dateSelect);
    $result->execute();
    
    if ( $result->rowCount() == 0) {
      // 結束連線
      $db = null;
      return $showData=null;
    }
    
    // 處理查詢結果
    while ($row = $result->fetch())
    {
      $showData[$row['winPrize']][] = $row['winNumber'];
    }
    
    // 結束連線
    $db = null;
    
    return $showData;
    }
  
    function outputWinTable($showData,$aPrizeMoney) {
        
        if (!isset($showData)){
            return "尚無資料";
        }
        
        $winTable = ""; // 以html格式輸出
        
        foreach ($showData as $value) {
            $winTable .= "<tr>";
            $winTable .= "<th>" . array_search($value,$showData) . "</th>";
            $winTable .= "<td>";
        foreach ($value as $num) {
            $winTable .= $num . "<br>";
        }
            $winTable .= "</td>";
            $winTable .= "<td>" . $aPrizeMoney[array_search($value,$showData)] . "</td>";
            $winTable .= "</tr>";
        }
        
        return $winTable;
        
    }
  
    function searchWinPeriod($db,$dateSelect) {
        
        // 依期別查詢winningPeriod表中的領獎期間
        $sql = "select winPs from winningPeriod where winDate = :date ";
        $result = $db->prepare($sql);
        $result->bindParam("date",$dateSelect);
        $result->execute();
        
        // 處理查詢結果
        while ($row = $result->fetch())
        {
          $showPeriod = $row['winPs'];
        }
        
        // 結束連線
        $db = null;
        
        return $showPeriod;
    }
    
    function processNumberFile($objFile) {
		if ($objFile["error"] != 0) {
			return;
		}
		if($objFile ["tmp_name"] == "") {
			return "上傳失敗";
		}
		if($objFile ["type"] != "text/plain") {
			return "檔案格式錯誤";
		}
		
		$f = fopen($objFile ["tmp_name"], "r"); // 開啟檔案
		while (!feof($f)) // 判斷文件是否已達結尾
		{
			$line = fgets($f); // 讀取一行
			$text .= Trim($line);
		}
		fclose($f); // 關閉檔案
		
		return $text;
	}
	
	// 回傳比對結果(array)
    /* $dateSelect->選擇的期別 $number->輸入的號碼 $prizeMoney->獎金設定 $db->資料庫連線 */
    function checkNumber($db,$number,$dateSelect,$aPrizeMoney) {
        
        // 將號碼依,分開
        $enterNumber = explode(",",$number);
        
        // 搜尋winningNumbers中期別為$dateSelect的資料
        $sql = "select winPrize,winNumber from winningNumbers where winDate = :date ";
        $result = $db->prepare($sql);
        $result->bindParam("date",$dateSelect);
        $result->execute();
        
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
                        // 如果號碼完全相等 需將兩個號碼定為字串
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
    
    function totalMoney($getMoney,$passMoney) {
        // 現有金額
        $passMoney = str_replace("總金額：","",$passMoney);
        // 新增的金額
        $getMoney = str_replace(",","",$getMoney);
        $money = explode("-",$getMoney);
        
        // 總計
        $total = str_replace(",","",$passMoney);
        
        // 將中文字轉換成數字進行總計
        foreach ($money as $value) {
            $change = $value;
            $change = str_replace("萬","0000",$change);
            $change = str_replace("千","000",$change);
            $change = str_replace("百","00",$change);
            
            $total = $total + $change;
        }
        
        return $total;
    }
    
    function changeNumberFormat($total) {
        $allMoney = ""; // 轉換完的字串
    
        // 大於1000
        if ($total >= 1000) {
            while ($total>1000) {
                $allMoney = "," . substr($total,-3) . $allMoney;
                $total = floor($total/1000);
            }
            $allMoney = $total . $allMoney;
        }else {
            $allMoney = $total;
        }
        
        return $allMoney;
    }
}
?>