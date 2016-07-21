<?php
class getAllMoney {
    function totalMoney($getMoney,$passMoney) {
        // 現有金額
        $passMoney = str_replace("總金額：","",$passMoney);
        // 新增的金額
        $getMoney = str_replace(",","",$getMoney);
        $money = explode("-",$getMoney);
        
        // 總計
        $total = str_replace(",","",$passMoney);
        
        // 將中文字轉換成數字進行總計
        foreach ($money as $value) {
            $change = $value;
            $change = str_replace("萬","0000",$change);
            $change = str_replace("千","000",$change);
            $change = str_replace("百","00",$change);
            
            $total = $total + $change;
        }
        
        // 將數字轉換成中文字
        return $this->getChineseNumber($total);
    }
    
    function getChineseNumber($total) {
        $allMoney = ""; // 轉換完的中文字
    
        // 大於1000
        if ($total >= 1000) {
            while ($total>1000) {
                $allMoney = "," . substr($total,-3) . $allMoney;
                $total = floor($total/1000);
            }
            $allMoney = $total . $allMoney;
        }
        // if (($total/10000) >= 1) {
        //     $allMoney = floor($total/10000) . "萬";
        //     $total = $total % 10000;
        // }
        // if (($total/1000) >= 1) {
        //     $allMoney .= floor($total/1000) . "千";
        //     $total = $total % 1000;
        // }
        // if (($total/100) >= 1) {
        //     $allMoney .= floor($total/100) . "百";
        // }
        
        return $allMoney;
    }
    
}
?>