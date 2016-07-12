<?php
    header("content-type: text/html; charset=utf-8");
    
    $date[] = date("Y-m",strtotime($today."-5 month"));
    $date[] = date("Y-m",strtotime($today."-3 month"));
    $date[] = date("Y-m",strtotime($today."-1 month"));
    $date[] = date("Y-m");
    
    $dateYear = Array();
    $dateMonth = Array();
    
    foreach ($date as $value) {
        $dateYM = explode("-",$value);
        $dateYear = ($dateYM[0]-1911)."年";
        
        if ($dateYM[1]%2 == 0) {
            $dateMonth = substr($dateYM[1]-1+100,1,2) . "-" . $dateYM[1] . "月";
        }else {
            $dateMonth = $dateYM[1] . "-" . substr($dateYM[1]+1+100,1,2) . "月";
        }
        
        $setDate[] = $dateYear . $dateMonth;
        
    }
    
?>