<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class DataController extends Controller {
    
    // 取得資料庫連線 (PDO)
    function getDatabaseConfig() {
        // 資料庫設定
        $config = $this->model("config");
        // 回傳資料庫連線
        return $config->getDB();
    }
    
    // 回傳資料庫中會員的email (string)
    /* $userName->SESSION $member->SESSION $db->資料庫連線 */
    function getMemberEmail() {
        // 會員資料
        $userName = $_SESSION['userName'];
        $member = $_SESSION['member'];
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $getEmail = $this->model("getMemberEmail");
        return $getEmail->checkMemberEmail($db,$userName,$member);
    }
    
    // 抓取財政部網頁資料存進資料庫
    /* $prizeMoney->獎別設定 $db->資料庫連線 */
    function catchWeb() {
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 獎別設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 抓取財政部網頁資料
        $catch = $this->model("catchWeb");
        $catch->toCatch($db,$prizeMoney->aPrizeItems);
    }
    
    // 顯示當月的前三個期別+當期 總共四期 (json)
    function getDate() {
        $data = $this->model("setDate");
        echo $data->getData();
    }
    
    // 顯示資料庫中的開獎號碼 (string)
    /* $dateSelect->選擇的期別 $prizeItems->獎別設定 
        $prizeMoney->獎金設定 $db->資料庫連線 */
    function setWinNumber() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 獎別設定
        $prizeItems = $this->model("prizeItems");
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 取得查詢結果 (array)
        $getNumber = $this->model("getWinNumber");
        $showData = $getNumber->searchData($dateSelect,$db,$prizeItems->aprizeItems);
        
        // 將查詢結果輸出成表格樣式
        $data = $this->model("setWinNumber");
        $data->output($showData,$prizeMoney->aPrizeMoney);
    }
    
    // 顯示資料庫中期別的領獎期限 (string)
    /* $dateSelect->選擇的期別 $db->資料庫連線 */
    function setWinPeriod() {
        // 選擇的期別
        $dateSelect = trim($_GET['date']); 
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 回傳查詢結果
        $data = $this->model("setWinPeriod");
        echo $data->searchData($db,$dateSelect);
        
    }
    
    // 顯示上傳的檔案內容 (string)
    /* $file->選擇的檔案 */
    function uploadNumberFile() {
        // 選擇的檔案
		$file = $_FILES["file"];
        
        // 回傳檔案內容
        $getFileContent = $this->model("uploadNumberFile");
        echo $getFileContent->processFile($file);
    }
    
    // 顯示比對結果 (json)
    /* $dateSelect->選擇的期別 $number->輸入的號碼 
        $prizeMoney->獎金設定 $db->資料庫連線 */
    function checkNumber() {
        // 選擇的期別
        $dateSelect = trim($_POST['date']); 
        // 輸入的號碼
		$number = $_POST["number"];
		// 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 輸出比對結果
        $data = $this->model("checkNumber");
        $show = $data->check($db,$number,$dateSelect,$prizeMoney->aPrizeMoney);
        echo json_encode($show);
    }
    
    // 新增會員的發票號碼至資料庫
    /* $date->期別 $number->號碼 $prize->中獎結果 
        $email->資料庫中的email $db->資料庫連線 */
    function addMemberNumber() {
        // 接收的資料
        $date = $_POST['numDate'];
        $number = $_POST['number'];
        $prize = $_POST['prize'];
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 新增至資料庫中
        $addNumber = $this->model("addMemberNumber");
        $addNumber->saveNumber($db,$email,$date,$number,$prize);
    }
    
    // 依接收值判斷登出或登入 並前往指定頁面
    /* $button->按下按鈕的值 */
    function checkMember() {
        // 按下按鈕的值
		$button = $_POST['bLog'];
        
        // 登出或登入 回傳要前往的網址 (string)
        $data = $this->model("checkMember");
        // 前往首頁或登入頁
        header("location: ../{$data->$button()}");
    }
    
    // 寫入註冊資料
    /* $userName->輸入的名稱 $email->輸入的Email
        $password->輸入的密碼 $db->資料庫連線 */
    function signUp() {
        // 接收的註冊資料
        $userName = $_POST['userName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 寫入註冊資料
        $signUp = $this->model("signUp");
        $signUp->insertMember($db,$userName,$email,$password);
    }
    
    // 顯示自動對獎結果並更新資料庫中的資料 (string)
    /* $prizeMoney->獎金設定 $email->資料庫中的email $db->資料庫連線 */
    function autoCheckNumber() {
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 取得資料庫中尚未對獎的發票
        $getNoCheckNumber = $this->model("getNoCheckNumber");
        $noCheckNumber = $getNoCheckNumber->check($db,$email);
        
        if (isset($noCheckNumber)) {
            // 比對發票
            $check = $this->model("checkNumber");
            // 更新資料庫中的會員發票
            $update = $this->model("updateMemberNumber");
            
            foreach ($noCheckNumber as $key=>$value) {
                $checkedData = $check->check($db,$value,$key,$prizeMoney->aPrizeMoney); // 取得對獎結果
                if ($checkedData!="") {
                    // 進行資料庫資料更新
                    $update->update($db,$email,$checkedData);
                    $showData[] = $checkedData;
                }
            }
            
        }
        
        if (isset($showData)) {
            $setShow = $this->model("setAutoCheck");
            $showText = $setShow->printResult($showData);
        }
        
        echo $showText;
        
    }
    
    // 顯示資料庫中會員的發票號碼 (json)
    /* $dateSelect->選擇的期別 $pageSelect->選擇的頁次 $prizeMoney->獎金設定
        $email->資料庫中的email $db->資料庫連線 */
    function setMemberNumber() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 選擇的頁次
        $pageSelect =  trim($_GET['page']); 
        
		// 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 取得資料庫中會員的發票
        $getNumber = $this->model("getMemberNumber");
        $showData = $getNumber->searchData($db,$dateSelect,$email,$pageSelect,$prizeMoney->aPrizeMoney);
        
        echo $showData;
    }
    
    // 顯示資料庫中會員的發票號碼數量 (string)
    /* $dateSelect->選擇的期別 $email->資料庫中的email $db->資料庫連線 */
    function getMemberNumberCount() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 取得資料庫中的會員發票的數量
        $getCount = $this->model("getMemberNumberCount");
        $count = $getCount->searchCount($db,$dateSelect,$email);
        
        echo $count;
    }
    
    // 顯示統計金額-首頁 (string)
    /* $money->增加的金額 $passMoney->已統計的金額 */
    function getAllNumber() {
        // 要統計的金額
        $money = $_POST['money']; 
        $passMoney = $_POST['passMoney']; 
        
        // 統計金額
        $getTotal = $this->model("getAllMoney");
        echo $getTotal->totalMoney($money,$passMoney);
    }
    
    // 顯示統計金額-會員頁 (string)
    /* $dateSelect->選擇的期別 $prizeMoney->獎金設定 
        $email->資料庫中的email $db->資料庫連線 */
    function getMemberMoney() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 取得資料庫中會員的發票並計算總金額
        $getMoney = $this->model("getMemberMoney");
        $moneys = $getMoney->searchData($db,$dateSelect,$email,$prizeMoney->aPrizeMoney);
        
        // 統計金額
        $getTotal = $this->model("getAllMoney");
        echo $getTotal->totalMoney($moneys,"0");
    }
    
    // 刪除會員發票號碼並顯示成功與否 (string)
    /* $id->要刪除的發票號碼id $email->資料庫中的email $db->資料庫連線 */
    function deleteMemberNumber() {
        // 要刪除的發票號碼id
        $id = $_GET['id'];
        
        // 資料庫連線
        $db = $this->getDatabaseConfig();
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 刪除資料庫中會員的發票
        $deleteNumber = $this->model("deleteMemberNumber");
        echo $deleteNumber->deleteNumber($db,$email,$id);
    }
}

?>