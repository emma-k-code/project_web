<?php
header("content-type: text/html; charset=utf-8");

class catchWeb {
    
    /* $db->資料庫連線 $aPrizeItems->獎別設定 */
    function toCatch($db,$aPrizeItems) {
        // 取得網站資料
        $catchData = $this->catchWeb();
        // 取得中獎號碼 期別 領獎期間 (array)
        $resolveData = $this->resolveData($catchData,$aPrizeItems); 
        // 將資料寫入資料庫
        $this->insertDatabase($db,$resolveData);
    }
    
    // 回傳抓取到的網站資料
    function catchWeb() {
        // 1. 初始設定
        $ch = curl_init();
        
        // 2. 設定 財政部稅務入口網
        $url = "http://invoice.etax.nat.gov.tw/";
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        
        // 3. 執行，取回 response 結果
        $output = curl_exec($ch);
        
        // 4. 關閉與釋放資源
        curl_close($ch);
        
        return $output;
    }
    
    // 解析網站資料 回傳中獎的號碼 期別 領獎期間 (array)
    /* $data->要解析的資料 $insertData->獎別設定 */
    function resolveData($data,$insertData) {
        // 解析資料
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($data);
        
        $xpath = new DOMXPath($doc);
        
        $entries = $xpath->query("//*[@id='area1']/table/tr");
        foreach ($entries as $entry) 
        {
            // 獎別
            $prize = $xpath->query("./td[1]", $entry)->item(0)->nodeValue;
            // 中獎號碼
            $winNumber = $xpath->query("./td[2]/span", $entry)->item(0)->nodeValue;
            
            // 如果一次抓取多筆號碼
            if (isset($prize)) {
                if (isset($winNumber)) {
                    $number = explode("、",$winNumber);
                    $insertData[$prize] = $number;
                }
            }
        }
        
        // 取得二到六獎的號碼
        foreach ($insertData["頭獎"] as $value)
        {
            $insertData["二獎"][] = substr($value,1,7);
            $insertData["三獎"][] = substr($value,2,6);
            $insertData["四獎"][] = substr($value,3,5);
            $insertData["五獎"][] = substr($value,4,4);
            $insertData["六獎"][] = substr($value,5,3);
        }
        
        // 發票期別
        $invoiceDate = $xpath->query("//*[@id='area1']/h2[2]")->item(0)->nodeValue;
        // 領獎期間
        $invoicePs = $xpath->query("//*[@id='area1']/p")->item(0)->nodeValue;
        
        // 回傳中獎的號碼 期別 領獎期間
        return array($insertData,$invoiceDate,$invoicePs);
        
    }
    
    // 將資料寫入資料庫 
    /* $db->資料庫連線 $data->要寫入的資料 */
    function insertDatabase($db,$data=array()) {
        $insertData = $data[0]; // 中獎資料
        $invoiceDate = $data[1];// 發票期別
        $invoicePs = $data[2]; // 領獎期間
        
        // 檢查資料庫中是否已有資料 true->return;離開function
        $sql = "select count(*) from winningPeriod where winDate ='$invoiceDate';";
        $sth = $db->prepare($sql);
        $sth->execute();
        // 如果已有資料就跳出
        if (($sth->fetchAll())>1) {
            return;
        }
        
        $sth = null;
        
        // 將資料寫入winningPeriod資料庫
        $sql = "INSERT INTO winningPeriod(winDate,winPs) VALUES ('$invoiceDate','$invoicePs');";
        $sth = $db->prepare($sql);
        
        // 將資料寫入winningNumbers資料庫
        $sql = 'INSERT INTO winningNumbers(winDate,winPrize,winNumber) VALUES (:data,:prize,:number);';
        $sth = $db->prepare($sql);
        
        foreach ($insertData as $key=>$pNumbers)
        {
            foreach ($pNumbers as $num)
            {
    	        $sth->bindParam(':data',$invoiceDate); // 期別
                $sth->bindParam(':prize',$key); // 獎別
                $sth->bindParam(':number',$num); // 號碼
                $sth->execute();
            }
        }
        
        // 結束連線
        $db = null;
    }
}
?>