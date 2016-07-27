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
}
?>