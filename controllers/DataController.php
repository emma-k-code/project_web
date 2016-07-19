<?php

class DataController extends Controller {
    
    function getDate() {
        $data = $this->model("setDate");
        echo $data->getData();
    }
    
    function setWinNumber() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 獎別設定
        $prizeItems = $this->model("prizeItems");
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        // 資料庫設定
        $config = $this->model("config");
        $db = $config->getDB();
        
        // 取得發票號碼
        $getNumber = $this->model("getWinNumber");
        $showData = $getNumber->searchData($dateSelect,$db,$prizeItems->aprizeItems);
        
        // 輸出查詢結果
        $data = $this->model("setWinNumber");
        echo $data->output($showData,$prizeMoney->aPrizeMoney);
    }
    
    function setWinPeriod() {
        // 選擇的期別
        $dateSelect = trim($_GET['date']); 
        // 資料庫設定
        $config = $this->model("config");
        $db = $config->getDB();
        
        // 輸出查詢結果
        $data = $this->model("setWinPeriod");
        echo $data->searchData($db,$dateSelect);
        
    }
    
    function uploadNumberFile() {
        // 選擇的檔案
		$file = $_FILES ["file"];
        
        // 輸出檔案內容
        $data = $this->model("uploadNumberFile");
        echo $data->processFile($file);
    }
    
}

?>