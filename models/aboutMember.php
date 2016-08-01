<?php
require_once "../InvoiceWeb/models/Database.php";

class aboutMember extends Database {
    public $aPrizeMoney = array("特別獎"=>"1,000萬","特獎"=>"200萬",
                    "頭獎"=>"20萬","二獎"=>"4萬",
                    "三獎"=>"1萬","四獎"=>"4千",
                    "五獎"=>"1千","六獎"=>"2百","增開六獎"=>"2百");
                    
    /* @return string */                
    function getUserName() {
        return $userName = (isset($_SESSION['userName']))? $_SESSION['userName']:"guset";
    }
    /* @return string */  
    function getLoginButton() {
        return $bLog = (isset($_SESSION['userName']))? "Logout":"Login";
    }
    /* @return bool */  
    function checkLogin() {
        if (isset($_SESSION['userName'])&&isset($_SESSION['member'])) {
            return true;
        }else {
            return false;
        }
    }
    /* @return string */  
    function getMemberEmail(){
        // 會員資料
        $userName = $_SESSION['userName'];
        $member = $_SESSION['member'];
    
        // 搜尋members資料庫中的資料
        $sql = "SELECT `memberEmail`,`memberPW` FROM `members` WHERE `memberName` = :username";
        $result = $this->prepare($sql);
        $result->bindParam("username",$userName);
        $result->execute();
        
        // 處理查詢結果
        while ($row = $result->fetch()) {
            
            $check = MD5($row['memberEmail']).MD5($row['memberPW']);
            
            if ($member == $check) {
                $userEmail = $row['memberEmail'];
                return $userEmail;
            }
        }
        
    }
    /* @return bool */  
    function addMemberNumber($userEmail,$date,$number,$prize) {
            
        // 將資料寫入membersNumbers資料庫
        $sql = "INSERT INTO `membersNumbers`(`mDate`,`mNumber`,`mResult`,`memberEmail`) VALUES (:date,:number,:prize,:mail)";
        $sth = $this->prepare($sql);
        
        $sth->bindParam('date',$date);
        $sth->bindParam('number',$number);
        $sth->bindParam('prize',$prize);
        $sth->bindParam('mail',$userEmail);
        return $sth->execute();
        
    }
    /* @return string */  
    function logout() {
        // 刪除session
        session_destroy();
        
        return "登出成功";
    }
    /* @return string */  
    function login($email,$password) {
        // 搜尋並比對資料庫中的會員資料
        $sql = "SELECT * FROM `members` WHERE `memberEmail` = :email AND `memberPW` = MD5(:password)";
        $result = $this->prepare($sql);
        
        $result->bindParam('email',$email);
        $result->bindParam('password',$password);
        $result->execute();
        
        // 如果搜尋結果為0
        if ( $result->rowCount() == 0) {
            return "輸入帳號或密碼錯誤";
        }
        
        // 處理查詢結果
        while ($row = $result->fetch())
        {
            $user = array("username"=>$row['memberName'],"password"=>$row['memberPW']);
        }
        
        // 將 會員資料 進行加密
        $member = MD5($email).MD5($user["password"]);
        
        // 將會員資料存成SESSION
        $_SESSION['userName'] = $user["username"];
        $_SESSION['member'] = $member;
        
        return "OK";
    }
    /* @return bool */  
    function signUp($userName,$email,$password) {
        // 搜尋資料庫中email是否已經存在
        $sql = "SELECT * FROM `members` WHERE `memberEmail` = :email ";
        $result = $this->prepare($sql);
        $result->bindParam("email",$email);
        $result->execute();
        
        // 如果email已存在
        if ( $result->rowCount() != 0) {
            return "exist";
        }
        
        // 將資料寫入members資料庫
        $sql = "INSERT INTO `members`(`memberName`,`memberPW`,`memberEmail`) VALUES (:username,MD5(:password),:email);";
        $sth = $this->prepare($sql);
        $sth->bindParam("username",$userName);
        $sth->bindParam("password",$password);
        $sth->bindParam("email",$email);
        
        // 回傳是否成功
        return $sth->execute();
        
    }
    
    /* @return array */  
    function getMemberNumber($dateSelect,$userEmail,$pageSelect){
        $limit = 10; // 一頁10筆
        $start = ($pageSelect * 10)  - $limit ; 
        
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
            $sql = "SELECT `mNumID`,`mDate`,`mNumber`,`mResult` FROM `membersNumbers` WHERE `memberEmail` = :email ORDER BY substring(`mDate`,1,3) DESC,substring(`mDate`,5,2) DESC LIMIT $start, $limit";
            $result = $this->prepare($sql);
        }elseif ($dateSelect=="中獎發票") {
            $sql = "SELECT `mNumID`,`mDate`,`mNumber`,`mResult` FROM `membersNumbers` WHERE `memberEmail` = :email AND (`mResult` = '特別獎' OR `mResult` = '特獎' OR `mResult` = '頭獎' OR `mResult` = '二獎' OR `mResult` = '三獎' OR `mResult` = '四獎' OR `mResult` = '五獎' OR `mResult` = '六獎' OR `mResult` = '增開六獎') ORDER BY substring(`mDate`,1,3) DESC,substring(`mDate`,5,2) DESC LIMIT $start, $limit";
            $result = $this->prepare($sql);
        }else{
            $sql = "SELECT `mNumID`,`mDate`,`mNumber`,`mResult` FROM `membersNumbers` WHERE `memberEmail` = :email AND `mDate` = :date LIMIT $start, $limit";
            $result = $this->prepare($sql);
            $result->bindParam("date",$dateSelect);
        }
        
        $result->bindParam("email",$userEmail);
        $result->execute();
        
        // 搜尋結果為0
        if ( $result->rowCount() == 0) {
            return;
        }
        
        // 處理查詢結果
        while ($row = $result->fetch())
        {
        if ($row['mResult']=="未中獎") {
            $mMoney = "0";
        }elseif($row['mResult']=="未開獎") {
            $mMoney = "";
        }else{
            $mMoney = $this->aPrizeMoney[$row['mResult']];
        }
            $showData[] = array("id"=>$row['mNumID'],"mDate"=>$row['mDate'],"mNumber"=>$row['mNumber'],"mResult"=>$row['mResult'],"money"=>$mMoney);
        }
        
        return $showData;
    }
    /* @return object */  
    function searchNumber($dateSelect,$userEmail){
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
            $sql = "SELECT * FROM `membersNumbers` WHERE `memberEmail` = :email";
            $result = $this->prepare($sql);
        }elseif ($dateSelect=="中獎發票") {
            $sql = "SELECT * FROM `membersNumbers` WHERE `memberEmail` = :email AND (`mResult` = '特別獎' OR `mResult` = '特獎' OR `mResult` = '頭獎' OR `mResult` = '二獎' OR `mResult` = '三獎' OR `mResult` = '四獎' OR `mResult` = '五獎' OR `mResult` = '六獎' OR `mResult` = '增開六獎')";
            $result = $this->prepare($sql);
        }else {
            $sql = "SELECT * FROM `membersNumbers` WHERE `memberEmail` = :email AND `mDate` = :date ";
            $result = $this->prepare($sql);
            $result->bindParam("date",$dateSelect);
        }
        
        $result->bindParam("email",$userEmail);
        $result->execute();
        
        return $result;
    
    }
    /* @return int */  
    function searchNumberCount($dateSelect,$userEmail){
        $result = $this->searchNumber($dateSelect,$userEmail);
        return $result->rowCount();
    }
    /* @return string */  
    function getMemberMoney($dateSelect,$userEmail){
        $result = $this->searchNumber($dateSelect,$userEmail);
        
        if ( $result->rowCount() == 0) {
            return "0";
        }
        
        $moneys = "";
        
        // 處理查詢結果
        while ($row = $result->fetch()) {
            if ($row['mResult']=="未中獎") {
                $mMoney = "0";
            }elseif($row['mResult']=="未開獎") {
                $mMoney = "";
            }else{
                $mMoney = $this->aPrizeMoney[$row['mResult']];
            }
            $moneys .= "-".$mMoney;
        }
        
        return $moneys;
    }
    /* @return bool */  
    function deleteMemberNumber($email,$id) {
        $sql = "DELETE FROM `membersNumbers` WHERE `memberEmail` = :email AND `mNumID` = :id ";
        $sth = $this->prepare($sql);
        $sth->bindParam("email",$email);
        $sth->bindParam("id",$id);
        
        return $sth->execute();
    }
    /* @return array */  
    function autoCheck($email,$check) {
        // 取得資料庫中尚未對獎的發票
        $noCheckNumber = $this->getNoCheckNumber($email);
        
        // 如果有尚未對獎的號碼
        if (isset($noCheckNumber)) {
            foreach ($noCheckNumber as $key=>$value) {
                $checkedData = $check->checkNumber($value["number"],$key); // 取得對獎結果
                if ($checkedData!="") {
                    // 進行資料庫資料更新
                    $this->updateMemberNumber($email,$value["id"],$checkedData);
                    $showData[] = $checkedData;
                }
            }
            
        }
        
        return $showData;
        
    }
    /* @return array */  
    function getNoCheckNumber($email) {
        
        // 查詢membersNumbers表中會員的未開獎號碼
        $sql = "SELECT `mNumID`,`mDate`,`mNumber` FROM `membersNumbers` WHERE `mResult` = '未開獎' AND `memberEmail` = :email ";
        $result = $this->prepare($sql);
        $result->bindParam("email",$email);
        $result->execute();
        
        if ( $result->rowCount() == 0) {
          return;
        }
      
        // 處理查詢結果
        while ($row = $result->fetch()) {
            if (!isset($noCheckNumber[$row["mDate"]])) {
                $noCheckNumber[$row["mDate"]]["number"] = $row["mNumber"];
                $noCheckNumber[$row["mDate"]]["id"] = $row["mNumID"];
            }else {
                $noCheckNumber[$row["mDate"]]["number"] .=  "," .$row["mNumber"];
                $noCheckNumber[$row["mDate"]]["id"] .=  "," .$row["mNumID"];
            }
        }
        
        return $noCheckNumber;
        
    }
    // 更新資料庫中的會員發票
    function updateMemberNumber($email,$id,$checkedData) {
            
        // 更新membersNumbers資料庫的資料
        $sql = "UPDATE `membersNumbers` SET `mResult` = :prize WHERE `memberEmail` = :email AND `mNumID` = :id";
        $sth = $this->prepare($sql);
        
        foreach ($checkedData as $value)
        {
            $sth->bindParam("prize",$value["prize"]);
            $sth->bindParam("email",$email);
            $sth->bindParam("id",$id);
            $sth->execute();
        }
        
    }
    // 將自動對獎結果轉為字串
    /* @return string */  
    function printResult($showData){
        if (!isset($showData)) {
            return;
        }
        
        foreach ($showData as $value) {
            foreach ($value as $data) {
                $showText .= $data['numDate']."-".$data['number']."-".$data['prize']."<br>";
            }
        }
        
        return $showText;
    }

}
?>