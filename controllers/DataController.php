<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

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
    
    function checkNumber() {
        // 選擇的期別
        $dateSelect = trim($_GET['date']); 
        // 送出的號碼
		$number = $_GET["number"];
		// 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫設定
        $config = $this->model("config");
        $db = $config->getDB();
        
        // 輸出比對結果
        $data = $this->model("checkNumber");
        $show = $data->check($db,$number,$dateSelect,$prizeMoney->aPrizeMoney);
        echo json_encode($show);
    }
    
    function addMemberNumber() {
        // 接收的資料
        $date = $_POST['numDate'];
        $number = $_POST['number'];
        $prize = $_POST['prize'];
        // 會員資料
        $userName = $_SESSION['userName'];
        $member = $_SESSION['member'];
        
        // 資料庫設定
        $config = $this->model("config");
        $db = $config->getDB();
        
        // 取得資料庫中的email
        $getEmail = $this->model("getMemberEmail");
        $email = $getEmail->checkMemberEmail($db,$userName,$member);
        
        // 輸出檔案內容
        $addNumber = $this->model("addMemberNumber");
        $addNumber->saveNumber($db,$email,$date,$number,$prize);
    }
    
    function checkMember() {
        // 按下按鈕的值
		$button = $_POST['bLog'];
        
        // 輸出檔案內容
        $data = $this->model("checkMember");
        echo $data->$button();
    }
}

?>