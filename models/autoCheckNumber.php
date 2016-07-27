<?php
class autoCheckNumber {
    
    function autoCheck($db,$email,$check,$aPrizeMoney) {
        // 取得資料庫中尚未對獎的發票
        $noCheckNumber = $this->getNoCheckNumber($db,$email);
        
        // 如果有尚未對獎的號碼
        if (isset($noCheckNumber)) {
            foreach ($noCheckNumber as $key=>$value) {
                $checkedData = $check->check($db,$value,$key,$aPrizeMoney); // 取得對獎結果
                if ($checkedData!="") {
                    // 進行資料庫資料更新
                    $this->updateMemberNumber($db,$email,$checkedData);
                    $showData[] = $checkedData;
                }
            }
            
        }
        
        // 如果有自動比對的結果 將結果轉為字串
        if (isset($showData)) {
            return $this->printResult($showData);
        }
    }
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
                $noCheckNumber[$row["mDate"]] = $row["mNumber"];
            }else {
                $noCheckNumber[$row["mDate"]] .=  "," .$row["mNumber"];
            }
        }
        // 結束連線
        $db = null;
        
        return $noCheckNumber;
        
    }
    
    function updateMemberNumber($db,$email,$checkedData) {
            
        // // 更新membersNumbers資料庫的資料
        $sql = "UPDATE membersNumbers SET mResult= :prize WHERE memberEmail= :email AND mDate= :date AND mNumber= :number";
        $sth = $db->prepare($sql);
        
        foreach ($checkedData as $value)
        {
            $sth->bindParam("prize",$value["prize"]);
            $sth->bindParam("email",$email);
	        $sth->bindParam("date",$value["numDate"]);
            $sth->bindParam("number",$value["number"]);
            $sth->execute();
        }
        
        // 結束連線
        $db = null;
        
    }
    
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