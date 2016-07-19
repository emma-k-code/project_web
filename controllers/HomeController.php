<?php

class HomeController extends Controller {
    
    function index() {
        $this->view("index");
    }
    
    function getDate() {
        $data = $this->model("setDate");
        echo $data->getData();
    }
    
    function setWinNumber() {
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        // 資料庫設定
        $config = $this->model("config");
        $db = $config->getDB();
        
        // 取得發票號碼
        $getNumber = $this->model("getWinNumber");
        $showData = $getNumber->searchData("105年01-02月",$db,$prizeItems->aprizeItems);
        
        // 獎別設定
        $prizeItems = $this->model("prizeItems");
        
        $data = $this->model("setWinNumber");
        echo $data->output($showData,$prizeItems);
    }
}

?>