<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class DataController extends Controller {
    
    // 取得資料庫連線
    function getDatabaseConfig() {
        // 資料庫設定
        $config = $this->model("config");
        return $config->getDB();
    }
    
    // 取得會員的email
    function getMemberEmail() {
        // 會員資料
        $userName = $_SESSION['userName'];
        $member = $_SESSION['member'];
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $getEmail = $this->model("getMemberEmail");
        return $getEmail->checkMemberEmail($db,$userName,$member);
    }
    
    // 當月的前三個期別+當期 總共四期
    function getDate() {
        $data = $this->model("setDate");
        echo $data->getData();
    }
    
    // 查詢資料庫中的開獎號碼
    function setWinNumber() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 獎別設定
        $prizeItems = $this->model("prizeItems");
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 取得查詢結果
        $getNumber = $this->model("getWinNumber");
        $showData = $getNumber->searchData($dateSelect,$db,$prizeItems->aprizeItems);
        
        // 將查詢結果輸出成表格樣式
        $data = $this->model("setWinNumber");
        echo $data->output($showData,$prizeMoney->aPrizeMoney);
    }
    
    // 查詢資料庫中期別的領獎期限
    function setWinPeriod() {
        // 選擇的期別
        $dateSelect = trim($_GET['date']); 
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 輸出查詢結果
        $data = $this->model("setWinPeriod");
        echo $data->searchData($db,$dateSelect);
        
    }
    
    function uploadNumberFile() {
        // 選擇的檔案
		$file = $_FILES["file"];
        
        // 輸出檔案內容
        $data = $this->model("uploadNumberFile");
        echo $data->processFile($file);
    }
    
    function checkNumber() {
        // 選擇的期別
        $dateSelect = trim($_POST['date']); 
        // 送出的號碼
		$number = $_POST["number"];
		// 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
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
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 輸出檔案內容
        $addNumber = $this->model("addMemberNumber");
        $addNumber->saveNumber($db,$email,$date,$number,$prize);
    }
    
    function checkMember() {
        // 按下按鈕的值
		$button = $_POST['bLog'];
        
        // 登出或登入
        $data = $this->model("checkMember");
        // 前往首頁或登入頁
        header("location: ../{$data->$button()}");
    }
    
    function signUp() {
        // 接收的註冊資料
        $userName = $_POST['userName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 寫入註冊資料
        $signUp = $this->model("signUp");
        $signUp->insertMember($db,$userName,$email,$password);
    }
    
    function signIn() {
        // 接收的登入資料
        $email = $_POST['username'];
        $password = $_POST["password"];
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 比對會員資料
        $signIn = $this->model("signIn");
        // 登入成功進入首頁 否則前往登入頁
        $toPage = $signIn->check($db,$email,$password);
        
        header("location: ../$toPage");
    }
   
    function autoCheckNumber() {
        // 資料庫設定
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
    
    function setMemberNumber() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 選擇的頁次
        $pageSelect =  trim($_GET['page']); 
        
		// 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 取得資料庫中會員的發票
        $getNumber = $this->model("getMemberNumber");
        $showData = $getNumber->searchData($db,$dateSelect,$email,$pageSelect,$prizeMoney->aPrizeMoney);
        
        echo json_encode($showData);
    }
    
    function getMemberNumberCount() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 取得資料庫中的會員發票的數量
        $getCount = $this->model("getMemberNumberCount");
        $count = $getCount->searchCount($db,$dateSelect,$email);
        
        echo $count;
    }
    
    function getAllNumber() {
        // 要統計的金額
        $money = $_POST['money']; 
        $passMoney = $_POST['passMoney']; 
        
        // 統計金額
        $getTotal = $this->model("getAllMoney");
        echo $getTotal->totalMoney($money,$passMoney);
    }
    
    function getMemberMoney() {
        // 選擇的期別
        $dateSelect =  trim($_GET['date']); 
        
        // 獎金設定
        $prizeMoney = $this->model("prizeMoney");
        
        // 資料庫設定
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
    
    function deleteMemberNumber() {
        $id = $_GET['id'];
        
        // 資料庫設定
        $db = $this->getDatabaseConfig();
        // 取得資料庫中的email
        $email = $this->getMemberEmail();
        
        // 刪除資料庫中會員的發票
        $deleteNumber = $this->model("deleteMemberNumber");
        echo $deleteNumber->deleteNumber($db,$email,$id);
    }
}

?>