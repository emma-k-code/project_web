<?php

class CatchWebController extends Controller {
    
    function index() {
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 抓取財政部資料
        $catch = $this->model("catchWeb");
        $catch->toCatch($db);
    }
    
    function getDatabaseConfig() {
        // 資料庫設定
        $config = $this->model("config");
        return $config->getDB();
    }
    
}

?>