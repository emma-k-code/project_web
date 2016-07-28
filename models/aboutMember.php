<?php
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();

class aboutMember {
    // 取得會員資料庫中的email
    function getMemberEmail($db){
        // 會員資料
        $userName = $_SESSION['userName'];
        $member = $_SESSION['member'];
    
        // 搜尋members資料庫中的資料
        $sql = "select memberEmail,memberPW from members where memberName = :username";
        $result = $db->prepare($sql);
        $result->bindParam("username",$userName);
        $result->execute();
        
        // 處理查詢結果
        while ($row = $result->fetch()) {
            
            $check = MD5($row['memberEmail']).MD5($row['memberPW']);
            
            if ($member == $check) {
                $userEmail = $row['memberEmail'];
                $db = null;
                return $userEmail;
            }
        }
        $db = null;
        
    }
    // 新增會員的發票號碼
    function addMemberNumber($db,$userEmail,$date,$number,$prize) {
            
        // 將資料寫入membersNumbers資料庫
        $sql = "INSERT INTO membersNumbers(mDate,mNumber,mResult,memberEmail) VALUES (:date,:number,:prize,:mail)";
        $sth = $db->prepare($sql);
        
        $sth->bindParam(':date',$date);
        $sth->bindParam(':number',$number);
        $sth->bindParam(':prize',$prize);
        $sth->bindParam(':mail',$userEmail);
        return $sth->execute();
        
        // 結束連線
        $db = null;
    }
    
    function logout() {
        // 刪除session
        session_destroy();
        
        return "登出成功";
    }
    
    function login($db,$email,$password) {
        // 搜尋並比對資料庫中的會員資料
        $result = $db->query("select * from members where memberEmail = '$email' AND memberPW = MD5('$password')");
        
        // 如果搜尋結果為0
        if ( $result->rowCount() == 0) {
            $db = null;
            return "輸入帳號或密碼錯誤";
        }
        
        // 處理查詢結果
        while ($row = $result->fetch())
        {
            $user = array("username"=>$row['memberName'],"password"=>$row['memberPW']);
        }
        
        // 結束連線
        $db = null;
        
        // 將 會員資料 進行加密
        $member = MD5($email).MD5($user["password"]);
        
        // 將會員資料存成SESSION
        $_SESSION['userName'] = $user["username"];
        $_SESSION['member'] = $member;
        
        return "OK";
    }
    
    function signUp($db,$userName,$email,$password) {
        // 搜尋資料庫中email是否已經存在
        $sql = "select * from members where memberEmail = :email ";
        $result = $db->prepare($sql);
        $result->bindParam("email",$email);
        $result->execute();
        
        // 如果email已存在
        if ( $result->rowCount() != 0) {
            // 結束連線
            $db = null;
            return "exist";
        }
        
        // 將資料寫入members資料庫
        $sql = "INSERT INTO members(memberName,memberPW,memberEmail) VALUES (:username,MD5(:password),:email);";
        $sth = $db->prepare($sql);
        $sth->bindParam("username",$userName);
        $sth->bindParam("password",$password);
        $sth->bindParam("email",$email);
        
        // 回傳是否成功
        return $sth->execute();
        
        // 結束連線
        $db = null;
    }
    
    // 回傳會員發票號碼查詢結果 (array)
    /* $dateSelect->選擇的期別 $pageSelect->選擇的頁次 $prizeMoney->獎金設定
        $userEmail->會員的email $db->資料庫連線 */
    function getMemberNumber($db,$dateSelect,$userEmail,$pageSelect,$aPrizeMoney){
        $limit = 10; // 一頁10筆
        $start = ($pageSelect * 10)  - $limit ; 
        
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
            $sql = "select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = :email ORDER BY substring(mDate,1,3) DESC,substring(mDate,5,2) DESC LIMIT $start, $limit";
            $result = $db->prepare($sql);
        }elseif ($dateSelect=="中獎發票") {
            $sql = "select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = :email AND (mResult = '特別獎' OR mResult = '特獎' OR mResult = '頭獎' OR mResult = '二獎' OR mResult = '三獎' OR mResult = '四獎' OR mResult = '五獎' OR mResult = '六獎' OR mResult = '增開六獎') ORDER BY substring(mDate,1,3) DESC,substring(mDate,5,2) DESC LIMIT $start, $limit";
            $result = $db->prepare($sql);
        }else{
            $sql = "select mNumID,mDate,mNumber,mResult from membersNumbers where memberEmail = :email AND mDate = :date LIMIT $start, $limit";
            $result = $db->prepare($sql);
            $result->bindParam("date",$dateSelect);
        }
        
        $result->bindParam("email",$userEmail);
        $result->execute();
        
        // 搜尋結果為0
        if ( $result->rowCount() == 0) {
            // 結束連線
            $db = null;
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
            $mMoney = $aPrizeMoney[$row['mResult']];
        }
            $showData[] = array("id"=>$row['mNumID'],"mDate"=>$row['mDate'],"mNumber"=>$row['mNumber'],"mResult"=>$row['mResult'],"money"=>$mMoney);
        }
        
        // 結束連線
        $db = null;
        return $showData;
    }
    // 搜尋會員發票
    function searchNumber($db,$dateSelect,$userEmail){
        // 搜尋membersNumbers中的資料
        if ($dateSelect=="全部") {
            $sql = "select * from membersNumbers where memberEmail = :email";
            $result = $db->prepare($sql);
        }elseif ($dateSelect=="中獎發票") {
            $sql = "select * from membersNumbers where memberEmail = :email AND (mResult = '特別獎' OR mResult = '特獎' OR mResult = '頭獎' OR mResult = '二獎' OR mResult = '三獎' OR mResult = '四獎' OR mResult = '五獎' OR mResult = '六獎' OR mResult = '增開六獎')";
            $result = $db->prepare($sql);
        }else {
            $sql = "select * from membersNumbers where memberEmail = :email AND mDate = :date ";
            $result = $db->prepare($sql);
            $result->bindParam("date",$dateSelect);
        }
        
        $result->bindParam("email",$userEmail);
        $result->execute();
        
        return $result;
    
    }
    // 回傳資料庫中的會員發票筆數
    function searchNumberCount($db,$dateSelect,$userEmail){
        $result = $this->searchNumber($db,$dateSelect,$userEmail);
        return $result->rowCount();
        
        // 結束連線
        $db = null;
    
    }
    
    // 回傳資料庫中會員中獎號碼金額 (string)
    function getMemberMoney($db,$dateSelect,$userEmail,$aPrizeMoney){
        $result = $this->searchNumber($db,$dateSelect,$userEmail);
        
        if ( $result->rowCount() == 0) {
            // 結束連線
            $db = null;
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
                $mMoney = $aPrizeMoney[$row['mResult']];
            }
            $moneys .= "-".$mMoney;
        }
        // 結束連線
        $db = null;
        
        return $moneys;
    }
    // 刪除會員的發票號碼
    function deleteMemberNumber($db,$email,$id) {
        $sql = "DELETE FROM membersNumbers where memberEmail = :email AND mNumID = :id ";
        $sth = $db->prepare($sql);
        $sth->bindParam("email",$email);
        $sth->bindParam("id",$id);
        
        return $sth->execute();
    }
    // 回傳自動對獎結果
    function autoCheck($db,$email,$check,$aPrizeMoney) {
        // 取得資料庫中尚未對獎的發票
        $noCheckNumber = $this->getNoCheckNumber($db,$email);
        
        // 如果有尚未對獎的號碼
        if (isset($noCheckNumber)) {
            foreach ($noCheckNumber as $key=>$value) {
                $checkedData = $check->checkNumber($db,$value["number"],$key,$aPrizeMoney); // 取得對獎結果
                if ($checkedData!="") {
                    // 進行資料庫資料更新
                    $this->updateMemberNumber($db,$email,$value["id"],$checkedData);
                    $showData[] = $checkedData;
                }
            }
            
        }
        
        return $showData;
        
    }
    // 回傳資料庫中尚未對獎的發票號碼與id (array)
    function getNoCheckNumber($db,$email) {
        
        // 查詢membersNumbers表中會員的未開獎號碼
        $sql = "select mNumID,mDate,mNumber from membersNumbers where mResult = '未開獎' AND memberEmail = :email ";
        $result = $db->prepare($sql);
        $result->bindParam("email",$email);
        $result->execute();
        
        if ( $result->rowCount() == 0) {
          // 結束連線
          $db = null;
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
        // 結束連線
        $db = null;
        
        return $noCheckNumber;
        
    }
    // 更新資料庫中的會員發票
    function updateMemberNumber($db,$email,$id,$checkedData) {
            
        // 更新membersNumbers資料庫的資料
        $sql = "UPDATE membersNumbers SET mResult= :prize WHERE memberEmail= :email AND mNumID= :id";
        $sth = $db->prepare($sql);
        
        foreach ($checkedData as $value)
        {
            $sth->bindParam("prize",$value["prize"]);
            $sth->bindParam("email",$email);
            $sth->bindParam("id",$id);
            $sth->execute();
        }
        
        // 結束連線
        $db = null;
        
    }
    // 將自動對獎結果轉回為字串
    function printResult($showData){
        
        foreach ($showData as $value) {
            foreach ($value as $data) {
                $showText .= $data['numDate']."-".$data['number']."-".$data['prize']."<br>";
            }
        }
        
        return $showText;
    }

}
?>