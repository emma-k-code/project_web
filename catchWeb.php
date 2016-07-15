<?php

    header("content-type: text/html; charset=utf-8");
    
    // 取得獎別設定
    require_once "prizeItems.php";

    // 1. 初始設定
    $ch = curl_init();
    
    // 2. 設定 / 調整參數
    $url = "http://invoice.etax.nat.gov.tw/";
    
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    
    // 3. 執行，取回 response 結果
    $output = curl_exec($ch);
    
    // 4. 關閉與釋放資源
    curl_close($ch);

    // 解析資料
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($output);
    
    $xpath = new DOMXPath($doc);
    
    $pItems = new prizeItems();
    
    // 要insert置資料庫的資料
    $insertData = $pItems->aPrizeItems;
    
    $entries = $xpath->query("//*[@id='area1']/table/tr");
    foreach ($entries as $entry) 
    {
        // 獎別
        $prize = $xpath->query("./td[1]", $entry)->item(0)->nodeValue;
        // 中獎號碼
        $winNumber = $xpath->query("./td[2]/span", $entry)->item(0)->nodeValue;
        
        if (isset($prize)) {
            if (isset($winNumber)) {
                $number = explode("、",$winNumber);
                $insertData[$prize] = $number;
            }
        }
    }
    
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

    // 取得資料庫設定
    require_once "config.php";
    
    // 1. 連接資料庫伺服器
    $db = new PDO($dbConnect, $dbUser, $dbPw);
    $db->exec("set names utf8");
    
    // 將資料寫入winningPeriod資料庫
    $sql = "INSERT INTO winningPeriod(winDate,winPs) VALUES ('$invoiceDate','$invoicePs');";
    $sth = $db->prepare($sql);
    $sth->execute();
    
    // 將資料寫入winningNumbers資料庫
    $sql = 'INSERT INTO winningNumbers(winDate,winPrize,winNumber) VALUES (:data,:prize,:number);';
    $sth = $db->prepare($sql);
    
    foreach ($insertData as $key=>$pNumbers)
    {
        foreach ($pNumbers as $num)
        {
	        $sth->bindParam(':data',$pNumbers[]);
            $sth->bindParam(':prize',$pNumbers[]);
            $sth->bindParam(':number',$pNumbers[]);
            $sth->execute();
        }
    }
    
    // 4. 結束連線
    $db = null;
?>